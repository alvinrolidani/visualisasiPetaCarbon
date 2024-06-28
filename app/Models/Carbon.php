<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carbon extends Model
{
    use HasFactory;

    protected $table = 'carbon';


    public function desa()
    {
        return $this->belongsTo(Geojson::class, 'desa_id', 'id');
    }
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id');
    }
}
