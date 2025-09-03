<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Household
 *
 * @property int $id
 * @property string $respondent_status
 * @property \Illuminate\Support\Carbon $respondent_status_date
 * @property string $head_of_household_name
 * @property string $family_card_number
 * @property string $nik
 * @property string $address
 * @property string $rt
 * @property string $rw
 * @property string $village
 * @property string $district
 * @property string $house_ownership_status
 * @property string $floor_type
 * @property string $wall_type
 * @property string $roof_type
 * @property string $water_source
 * @property string $lighting_source
 * @property string|null $pln_id
 * @property string|null $electricity_power
 * @property string $toilet_facility
 * @property string $toilet_type
 * @property string $waste_disposal
 * @property string $cooking_fuel
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $recommendation_status
 * @property string $verification_status
 * @property string|null $verification_notes
 * @property int|null $verified_by
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property int $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\User|null $updater
 * @property-read \App\Models\User|null $verifier
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HouseholdAsset> $assets
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HouseholdPhoto> $photos
 * @property-read int|null $assets_count
 * @property-read int|null $photos_count
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Household newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Household newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Household query()
 * @method static \Illuminate\Database\Eloquent\Builder|Household whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household whereRespondentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household whereRespondentStatusDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household whereHeadOfHouseholdName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household whereFamilyCardNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household whereRt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household whereRw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household whereVillage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household whereDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household whereVerificationStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household whereRecommendationStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household whereVerifiedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Household miskin()
 * @method static \Illuminate\Database\Eloquent\Builder|Household tidakMiskin()
 * @method static \Illuminate\Database\Eloquent\Builder|Household belumDiverifikasi()
 * @method static \Database\Factories\HouseholdFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Household extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'respondent_status',
        'respondent_status_date',
        'head_of_household_name',
        'family_card_number',
        'nik',
        'address',
        'rt',
        'rw',
        'village',
        'district',
        'house_ownership_status',
        'floor_type',
        'wall_type',
        'roof_type',
        'water_source',
        'lighting_source',
        'pln_id',
        'electricity_power',
        'toilet_facility',
        'toilet_type',
        'waste_disposal',
        'cooking_fuel',
        'latitude',
        'longitude',
        'recommendation_status',
        'verification_status',
        'verification_notes',
        'verified_by',
        'verified_at',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'respondent_status_date' => 'date',
        'verified_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Get the user who created this household record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this household record.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the user who verified this household record.
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get the assets for this household.
     */
    public function assets(): HasMany
    {
        return $this->hasMany(HouseholdAsset::class);
    }

    /**
     * Get the photos for this household.
     */
    public function photos(): HasMany
    {
        return $this->hasMany(HouseholdPhoto::class)->orderBy('order');
    }

    /**
     * Scope a query to only include poor households.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMiskin($query)
    {
        return $query->where('recommendation_status', 'miskin');
    }

    /**
     * Scope a query to only include non-poor households.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTidakMiskin($query)
    {
        return $query->where('recommendation_status', 'tidak_miskin');
    }

    /**
     * Scope a query to only include unverified households.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBelumDiverifikasi($query)
    {
        return $query->where('verification_status', 'belum_diverifikasi');
    }

    /**
     * Calculate poverty recommendation based on indicators.
     *
     * @return string
     */
    public function calculatePovertyRecommendation(): string
    {
        $povertyScore = 0;
        
        // Floor type scoring
        if (in_array($this->floor_type, ['tanah', 'bambu'])) {
            $povertyScore += 2;
        } elseif (in_array($this->floor_type, ['semen', 'kayu'])) {
            $povertyScore += 1;
        }
        
        // Wall type scoring
        if (in_array($this->wall_type, ['bambu', 'seng'])) {
            $povertyScore += 2;
        } elseif ($this->wall_type === 'kayu') {
            $povertyScore += 1;
        }
        
        // Water source scoring
        if (in_array($this->water_source, ['sungai', 'mata_air', 'air_hujan'])) {
            $povertyScore += 2;
        } elseif (in_array($this->water_source, ['sumur_gali', 'sumur_bor'])) {
            $povertyScore += 1;
        }
        
        // Lighting source scoring
        if (in_array($this->lighting_source, ['minyak_tanah', 'petromaks', 'lilin'])) {
            $povertyScore += 2;
        } elseif ($this->lighting_source === 'listrik_non_pln') {
            $povertyScore += 1;
        }
        
        // Toilet facility scoring
        if (in_array($this->toilet_facility, ['tidak_ada', 'umum'])) {
            $povertyScore += 2;
        } elseif ($this->toilet_facility === 'bersama') {
            $povertyScore += 1;
        }
        
        return $povertyScore >= 5 ? 'miskin' : 'tidak_miskin';
    }
}