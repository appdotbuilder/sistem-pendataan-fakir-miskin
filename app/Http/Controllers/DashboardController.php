<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Household;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Basic statistics
        $stats = [
            'total_households' => Household::count(),
            'miskin' => Household::miskin()->count(),
            'tidak_miskin' => Household::tidakMiskin()->count(),
            'belum_diverifikasi' => Household::belumDiverifikasi()->count(),
            'disetujui' => Household::where('verification_status', 'disetujui')->count(),
            'perlu_revisi' => Household::where('verification_status', 'perlu_revisi')->count(),
        ];
        
        // Role-specific data
        $roleData = [];
        
        if ($user->role === 'pengisi_data') {
            $roleData = [
                'my_households' => Household::where('created_by', $user->id)->count(),
                'my_pending' => Household::where('created_by', $user->id)
                    ->where('verification_status', 'belum_diverifikasi')->count(),
                'my_approved' => Household::where('created_by', $user->id)
                    ->where('verification_status', 'disetujui')->count(),
            ];
        }
        
        if ($user->role === 'verifikator') {
            $roleData = [
                'pending_verification' => Household::belumDiverifikasi()->count(),
                'my_verifications' => Household::where('verified_by', $user->id)->count(),
            ];
        }
        
        if ($user->role === 'kepala_dinas') {
            $roleData = [
                'total_users' => User::count(),
                'pengisi_data_count' => User::pengisiData()->count(),
                'verifikator_count' => User::verifikator()->count(),
                'districts' => Household::distinct('district')->count(),
                'villages' => Household::distinct('village')->count(),
            ];
        }
        
        // Recent households for quick access
        $recentHouseholds = Household::with(['creator', 'verifier'])
            ->when($user->role === 'pengisi_data', function($query) use ($user) {
                return $query->where('created_by', $user->id);
            })
            ->latest()
            ->limit(5)
            ->get();
        
        // Geographic distribution for map
        $geoData = Household::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select('id', 'head_of_household_name', 'latitude', 'longitude', 'recommendation_status', 'verification_status')
            ->get();

        return Inertia::render('dashboard', [
            'stats' => $stats,
            'roleData' => $roleData,
            'recentHouseholds' => $recentHouseholds,
            'geoData' => $geoData,
            'userRole' => $user->role,
        ]);
    }


}