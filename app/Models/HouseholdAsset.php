<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\HouseholdAsset
 *
 * @property int $id
 * @property int $household_id
 * @property string $asset_type
 * @property bool $owned
 * @property int $quantity
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\Household $household
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|HouseholdAsset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HouseholdAsset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HouseholdAsset query()
 * @method static \Illuminate\Database\Eloquent\Builder|HouseholdAsset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HouseholdAsset whereHouseholdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HouseholdAsset whereAssetType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HouseholdAsset whereOwned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HouseholdAsset whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HouseholdAsset whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HouseholdAsset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HouseholdAsset whereUpdatedAt($value)
 * @method static \Database\Factories\HouseholdAssetFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class HouseholdAsset extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'household_id',
        'asset_type',
        'owned',
        'quantity',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'owned' => 'boolean',
        'quantity' => 'integer',
    ];

    /**
     * Get the household that owns this asset.
     */
    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }

    /**
     * Get asset type label in Indonesian.
     *
     * @return string
     */
    public function getAssetTypeLabel(): string
    {
        $labels = [
            'lemari_es' => 'Lemari Es',
            'ac' => 'AC',
            'tv' => 'TV',
            'laptop' => 'Laptop',
            'komputer' => 'Komputer',
            'hp_android' => 'HP Android',
            'hp_biasa' => 'HP Biasa',
            'sepeda_motor' => 'Sepeda Motor',
            'mobil' => 'Mobil',
            'emas' => 'Emas',
            'tabungan' => 'Tabungan',
            'tanah_bangunan' => 'Tanah Bangunan',
            'sawah' => 'Sawah',
            'kebun' => 'Kebun',
            'kolam' => 'Kolam',
            'ternak_sapi' => 'Ternak Sapi',
            'ternak_kerbau' => 'Ternak Kerbau',
            'ternak_kuda' => 'Ternak Kuda',
            'ternak_babi' => 'Ternak Babi',
            'ternak_kambing' => 'Ternak Kambing',
            'ternak_ayam' => 'Ternak Ayam',
        ];

        return $labels[$this->asset_type] ?? $this->asset_type;
    }
}