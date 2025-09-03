<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHouseholdRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();
        $household = $this->route('household');
        
        // Allow if user can verify data or if they created the record
        return $user->canVerifyData() || $household->created_by === $user->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $household = $this->route('household');
        
        return [
            // Data Responden
            'respondent_status' => 'required|in:ditemukan,pindah,tidak_ditemukan,meninggal',
            'respondent_status_date' => 'required|date',
            'head_of_household_name' => 'required|string|max:255',
            'family_card_number' => 'required|string|size:16|unique:households,family_card_number,' . $household->id,
            'nik' => 'required|string|size:16',
            'address' => 'required|string',
            'rt' => 'required|string|max:3',
            'rw' => 'required|string|max:3',
            'village' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            
            // Survey Tempat Tinggal
            'house_ownership_status' => 'required|in:milik_sendiri,kontrak,sewa,bebas_sewa,dinas,lainnya',
            'floor_type' => 'required|in:marmer,keramik,parket,kayu,semen,bambu,tanah',
            'wall_type' => 'required|in:tembok,kayu,bambu,seng,lainnya',
            'roof_type' => 'required|in:genteng,seng,asbes,kayu,bambu,ijuk,lainnya',
            'water_source' => 'required|in:pdam,sumur_bor,sumur_gali,mata_air,air_hujan,sungai,lainnya',
            'lighting_source' => 'required|in:listrik_pln,listrik_non_pln,minyak_tanah,petromaks,lilin,lainnya',
            'pln_id' => 'nullable|string|max:255',
            'electricity_power' => 'nullable|string|max:255',
            'toilet_facility' => 'required|in:sendiri,bersama,umum,tidak_ada',
            'toilet_type' => 'required|in:leher_angsa,plengsengan,cemplung,tidak_ada',
            'waste_disposal' => 'required|in:tangki_septik,spal,kolam,sungai,pantai,tanah_lapang,lainnya',
            'cooking_fuel' => 'required|in:listrik,gas_lpg,gas_kota,minyak_tanah,kayu_bakar,arang,lainnya',
            
            // Geotagging
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            
            // Assets
            'assets' => 'nullable|array',
            'assets.*.asset_type' => 'required|string',
            'assets.*.owned' => 'required|boolean',
            'assets.*.quantity' => 'nullable|integer|min:0',
            'assets.*.notes' => 'nullable|string',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'head_of_household_name.required' => 'Nama Kepala Keluarga harus diisi.',
            'family_card_number.required' => 'Nomor Kartu Keluarga harus diisi.',
            'family_card_number.size' => 'Nomor Kartu Keluarga harus 16 digit.',
            'family_card_number.unique' => 'Nomor Kartu Keluarga sudah terdaftar.',
            'nik.required' => 'NIK harus diisi.',
            'nik.size' => 'NIK harus 16 digit.',
            'address.required' => 'Alamat harus diisi.',
            'village.required' => 'Desa/Kelurahan harus diisi.',
            'district.required' => 'Kecamatan harus diisi.',
            'respondent_status_date.required' => 'Tanggal status responden harus diisi.',
            'respondent_status_date.date' => 'Format tanggal tidak valid.',
        ];
    }
}