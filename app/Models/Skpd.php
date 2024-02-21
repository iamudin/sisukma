<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skpd extends Model
{
    protected $table = 'skpd';
    public $timestamps = false;

    public function periodeAktif(){
        return $this->hasMany(Periode::class,'skpd_id','id_skpd');
    }
}
