<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHouseholdRequest;
use App\Http\Requests\UpdateHouseholdRequest;
use App\Models\Household;
use App\Models\HouseholdAsset;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HouseholdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
        $query = Household::with(['creator', 'verifier'])
            ->latest();
        
        // Filter based on user role
        if ($user->role === 'pengisi_data') {
            $query->where('created_by', $user->id);
        }
        
        $households = $query->paginate(15);
        
        return Inertia::render('households/index', [
            'households' => $households,
            'filters' => request()->only(['search', 'status']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        if (!$user->canInputData()) {
            abort(403, 'Unauthorized action.');
        }
        
        return Inertia::render('households/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHouseholdRequest $request)
    {
        $user = auth()->user();
        if (!$user->canInputData()) {
            abort(403, 'Unauthorized action.');
        }
        
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        
        // Calculate poverty recommendation
        $household = new Household($data);
        $data['recommendation_status'] = $household->calculatePovertyRecommendation();
        
        $household = Household::create($data);
        
        // Store assets if provided
        if ($request->has('assets')) {
            foreach ($request->assets as $assetData) {
                if ($assetData['owned']) {
                    HouseholdAsset::create([
                        'household_id' => $household->id,
                        'asset_type' => $assetData['asset_type'],
                        'owned' => $assetData['owned'],
                        'quantity' => $assetData['quantity'] ?? 1,
                        'notes' => $assetData['notes'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('households.show', $household)
            ->with('success', 'Data rumah tangga berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Household $household)
    {
        $user = auth()->user();
        
        // Authorization logic
        if ($user->role === 'pengisi_data' && $household->created_by !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $household->load(['creator', 'verifier', 'assets', 'photos']);
        
        return Inertia::render('households/show', [
            'household' => $household,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Household $household)
    {
        $user = auth()->user();
        
        // Authorization logic
        if (!$user->canVerifyData() && $household->created_by !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
        
        if ($user->role === 'pengisi_data' && $household->verification_status === 'disetujui') {
            abort(403, 'Cannot edit approved household data.');
        }
        
        $household->load(['assets', 'photos']);
        
        return Inertia::render('households/edit', [
            'household' => $household,
        ]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Household $household)
    {
        $user = auth()->user();
        
        // Authorization logic - only Kepala Dinas can delete, or Pengisi Data can delete their unverified records
        if ($user->role !== 'kepala_dinas') {
            if ($user->role !== 'pengisi_data' || $household->created_by !== $user->id || $household->verification_status !== 'belum_diverifikasi') {
                abort(403, 'Unauthorized action.');
            }
        }
        
        $household->delete();

        return redirect()->route('households.index')
            ->with('success', 'Data rumah tangga berhasil dihapus.');
    }

    /**
     * Update the specified resource with verification status.
     */
    public function update(UpdateHouseholdRequest $request, Household $household)
    {
        $user = auth()->user();
        
        // Authorization logic
        if (!$user->canVerifyData() && $household->created_by !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
        
        if ($user->role === 'pengisi_data' && $household->verification_status === 'disetujui') {
            abort(403, 'Cannot edit approved household data.');
        }
        
        $data = $request->validated();
        $data['updated_by'] = auth()->id();
        
        // Handle verification if user can verify and verification data is provided
        if ($user->canVerifyData() && $request->has('verification_status')) {
            $request->validate([
                'verification_status' => 'required|in:disetujui,perlu_revisi',
                'verification_notes' => 'nullable|string',
            ]);
            
            $data['verification_status'] = $request->verification_status;
            $data['verification_notes'] = $request->verification_notes;
            $data['verified_by'] = auth()->id();
            $data['verified_at'] = now();
        } else {
            // Recalculate poverty recommendation for regular updates
            $tempHousehold = new Household($data);
            $data['recommendation_status'] = $tempHousehold->calculatePovertyRecommendation();
        }
        
        $household->update($data);
        
        // Update assets
        if ($request->has('assets')) {
            // Delete existing assets and recreate
            $household->assets()->delete();
            
            foreach ($request->assets as $assetData) {
                if ($assetData['owned']) {
                    HouseholdAsset::create([
                        'household_id' => $household->id,
                        'asset_type' => $assetData['asset_type'],
                        'owned' => $assetData['owned'],
                        'quantity' => $assetData['quantity'] ?? 1,
                        'notes' => $assetData['notes'] ?? null,
                    ]);
                }
            }
        }

        $message = $request->has('verification_status') && $user->canVerifyData()
            ? ($request->verification_status === 'disetujui' 
                ? 'Data rumah tangga telah disetujui.' 
                : 'Data rumah tangga perlu direvisi.')
            : 'Data rumah tangga berhasil diperbarui.';

        return redirect()->route('households.show', $household)
            ->with('success', $message);
    }
}