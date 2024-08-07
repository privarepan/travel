<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class News extends Model
{
    protected $fillable = [
        'title','remark','content','cover','sort','view_count'
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

    use HasFactory;


    public function getCoverUrlAttribute()
    {
        $cover = $this->getOriginal('cover');
        if ($cover) {
            return Storage::disk('public')->url($cover);
        }
        return '';
    }
}
