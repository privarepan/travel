<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 *
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property array $cover
 * @property string $city
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read mixed $cover_url
 * @method static \Illuminate\Database\Eloquent\Builder|Route newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Route newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Route query()
 * @method static \Illuminate\Database\Eloquent\Builder|Route whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Route whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Route whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Route whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Route whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Route whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Route whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Route whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','content','cover','city',
    ];

    protected $appends = [
        'cover_url',
    ];

    protected $casts = [
        'cover' => 'array',
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format($this->getDateFormat());
    }

    public function getCoverUrlAttribute()
    {
        $cover = $this->getOriginal('cover');
        if ($cover) {
            return Storage::disk('public')->url($cover);
        }
        return '';
    }
}
