<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Picture extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $fillable = [
        'album_id',
        'pic_name',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function album()
    {
        return $this->belongsTo(Album::class,'album_id');
    }

}
