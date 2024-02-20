<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Session;
use View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


  function menuside(){
    if(session()->has('id_skpd')){
      View::share('sidebarmenu',[
        ['ikon'=>'desktop','nama'=>'Layanan','target'=>url('adminskpd/layanan')],
        ['ikon'=>'camera','nama'=>'Gallery','target'=>url('adminskpd/gallery')],
      ['ikon'=>'gear','nama'=>'Pengaturan','target'=>url('adminskpd/pengaturan')],

        ]);
    }else{
    View::share('sidebarmenu',[
      ['ikon'=>'building','nama'=>'Perangkat Daerah','target'=>url('admin/data-skpd')],
      ['ikon'=>'list','nama'=>'Unsur','target'=>url('admin/unsur')],
      ['ikon'=>'desktop','nama'=>'Layanan','target'=>url('admin/layanan')],
      ['ikon'=>'users','nama'=>'User Perangkat Daerah','target'=>url('admin/userskpd')],
      ['ikon'=>'globe','nama'=>'Lihat Web','target'=>url('/')]
      ]);
  }
}
}
