<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\UnsurController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SkpdController;
use App\Http\Controllers\SendMail;
use App\IkmManager;
Route::get('/cekpass',function (){
    return bcrypt('admiiin2024');

});
Route::get('printqr/{id?}',function($skpd=null){
    $skpd =  \App\Models\Skpd::where('id_skpd',$skpd)->first();
    if(empty($skpd)){
        return redirect('/');
    }
    if(request('print')){
    if(file_exists(public_path('photo/'.$skpd->id_skpd.'.jpg'))){
        unlink(public_path('photo/'.$skpd->id_skpd.'.jpg'));
    }
    SnappyImage::generate(url('printqr/'.$skpd->id_skpd),public_path('photo/'.$skpd->id_skpd.'.jpg'));
    if(file_exists(public_path('photo/'.$skpd->id_skpd.'.jpg'))){
        return response()->download(public_path('photo/'.$skpd->id_skpd.'.jpg'),'qr-'.Str::slug($skpd->nama_skpd).'.jpg');
    }

    }else{
        return view('front.template',compact('skpd'));
    }
    // SnappyImage::generate(url('https://google.com'),public_path('photo/tes.jpg'));
//     $data = new IkmManager;
//     $ikm = $data->ikm_kabupaten();
// return view('front.new',compact('ikm'));
});

Route::get('cekhasil', function () {
    foreach (DB::table('respon')->join('layanan','layanan.id_layanan','respon.id_layanan')->where('layanan.id_skpd',316)->get() as $item) {
        if (empty($item->pendidikan)) {
            $ed = DB::table('respon')->where('id_respon', $item->id_respon)->update([
                'pendidikan' => 'S1'
            ]);
        echo $ed.'<br>';

        }
    }

    });

    Route::get('cetakrekapskpd',function(){
        $t = new IkmManager;
        if(request('year')){
            $peri = request('year');
            if(request('month')){
                $peri = blnindo(request('month')).' '.request('year');
            }elseif(request()->from && request()->to){
                $peri = 'Triwulan '.$t->astrw(request()->from,request()->to).' '.request('year');
            }
            else{
            }
        }
        $data = json_decode(json_encode($t->nilai_unsur_rekap()));
    // return $data;
        $pdf = PDF::loadView('rekap.tahunan',['data'=>$data]);
        return $pdf->stream('rekapitulasi-kabupaten-'.Str::slug($peri).'.pdf');

        });
//route untuk registrasi dan login customer
Route::match(['get','post'],'login','App\Http\Controllers\AuthController@login');
Route::match(['get','post'],'logout','App\Http\Controllers\AuthController@logout');
Route::match(['get','post'],'account','App\Http\Controllers\AuthController@account');

Route::match(['get','post'],'janjun','App\Http\Controllers\Test@index');
// Route::get('tes',function(){
// dd(populasi(6000));
// });

Route::controller(FrontController::class)->group(function () {
Route::match(['get','post'],'/','home');
// Route::match(['get','post'],'survei/{id}','survei_form');
Route::match(['get','post'],'survei/{id}','skpd');
Route::match(['get','post'],'survei/{id}/{ly}','skpd');
Route::match(['get','post'],'skpd/{id}','skpd');
Route::match(['get','post'],'skpd/{id}/{ly}','skpd');
Route::match(['get','post'],'gallery/{id?}','gallery');
});

Route::controller(SkpdController::class)->group(function () {
    Route::match(['get','post'],'adminskpd','dashboard');
    Route::match(['get','post'],'adminskpd/dashboard','dashboard');
    Route::match(['get','post'],'adminskpd/layanan','layanan');
    Route::match(['get','post'],'adminskpd/layanan/lihatrespon/{id?}','lihatrespon');
    Route::match(['get','post'],'adminskpd/layanan/lihatikm/{data?}','lihatikm');
    Route::match(['get','post'],'adminskpd/gallery','gallery');
    Route::match(['get','post'],'adminskpd/respon-layanan/{id}','respon_layanan');
    Route::match(['get','post'],'adminskpd/pengaturan','pengaturan');
    });

Route::controller(DashboardController::class)->group(function () {
Route::match(['get','post'],'admin/dashboard','dashboard');
Route::match(['get','post'],'admin/data-skpd','data_skpd');
Route::match(['get','post'],'admin/hasil-survei','hasil_survei');
Route::match(['get','post'],'admin/respon-layanan/{id_layanan}','respon_layanan');

});

Route::controller(LayananController::class)->group(function () {
Route::match(['get','post'],'admin/layanan','index');
Route::match(['get','post'],'admin/layanan/create','create');
Route::match(['get','post'],'admin/layanan/update','update');
Route::match(['get','post'],'admin/layanan/delete/{id}','delete');
});

Route::controller(UnsurController::class)->group(function () {
Route::match(['get','post'],'admin/unsur','index');
Route::match(['get','post'],'admin/unsur/create','create');
Route::match(['get','post'],'admin/unsur/update','update');
Route::match(['get','post'],'admin/unsur/delete/{id}','delete');
});

Route::controller(UserController::class)->group(function () {
Route::match(['get','post'],'admin/userskpd','index');
Route::match(['get','post'],'admin/userskpd/create','create');
Route::match(['get','post'],'admin/userskpd/update','update');
Route::match(['get','post'],'admin/userskpd/delete/{id}','delete');
});
// Route::get('/', function () {
//     echo phpinfo();
// });

