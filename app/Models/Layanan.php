<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $table = 'layanan';
    public $timestamps = false;


    function respon(){
        return $this->hasMany(Respon::class,'id_layanan','id_layanan');

    }
}
