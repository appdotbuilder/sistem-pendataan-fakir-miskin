<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\HouseholdPhoto
 *
 * @property int $id
 * @property int $household_id
 * @property string $file_path
 * @property string $original_name
 * @property string $file_size
 * @property string $mime_type
 * @property int $order
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\Household $household
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|HouseholdPhoto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HouseholdPhoto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HouseholdPhoto query()
 * @method static \Illuminate\Database\Eloquent\Builder|HouseholdPhoto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HouseholdPhoto whereHouseholdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HouseholdPhoto whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HouseholdPhoto whereOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HouseholdPhoto whereFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HouseholdPhoto whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HouseholdPhoto whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HouseholdPhoto whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HouseholdPhoto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HouseholdPhoto whereUpdatedAt($value)
 * @method static \Database\Factories\HouseholdPhotoFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class HouseholdPhoto extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'household_id',
        'file_path',
        'original_name',
        'file_size',
        'mime_type',
        'order',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Get the household that owns this photo.
     */
    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }

    /**
     * Get the full URL for the photo.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Get formatted file size.
     *
     * @return string
     */
    public function getFormattedSize(): string
    {
        $bytes = (int) $this->file_size;
        
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }
        
        return $bytes . ' bytes';
    }
}