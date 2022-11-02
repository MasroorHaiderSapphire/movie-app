<?php

namespace App\Models;

use App\Models\Episode;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Season extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'tmdb_id',
        'name',
        'slug',
        'poster_path',
        'tv_show_id',
        'season_number',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function Episodes()
    {
        return $this->hasMany(Episode::class);
    }
}
