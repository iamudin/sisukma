<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use App\Models\Layanan;
use App\Models\Unsur;
use App\Models\Respon;
use App\Models\Skpd;
use App\Models\Periode;
use View;
use DB;
use PDF;
use Str;
class FrontController extends Controller
{
    public function __construct()
  {
    $this->middleware(function ($request, $next) {

         return $next($request);
     });

  }
function home(Request $req){
  // if(!session()->has('id_user'))
  // exit('Dalam Pengembangan');
    // foreach(Skpd::get() as $r){
    //     foreach(['2022','2023','2024'] as $rr){
    //             Periode::updateOrCreate(['skpd_id' => $r->id_skpd, 'tahun' => $rr], ['tahun' => $rr]);
    //     }
    // }
  if(request()->print){
    $skpd = DB::table('skpd')->where('id_skpd',dec64($req->ids))->first();
    $ikm = json_decode(dec64($req->print));
    $pdf = PDF::loadview('skpd.cetak_ikm',['data'=>$ikm,'skpd'=>$skpd,'periode'=>$req->periode]);
    return $pdf->stream('IKM '.Str::slug($req->periode.' '.$skpd->nama_skpd).'.pdf');
    // return view('skpd.cetak_ikm');
  }
  $data = new \App\IkmManager;
  $ikm = $data->ikm_kabupaten($req->year??date('Y'));
return view('front.new',compact('ikm'));
}
function jadwal(){
  return view('front.jadwal');
}
function gallery($id=null){
    if(!empty($id)){
        $data = DB::table('gallery')->wherePermalink($id)->first();
        if(empty($data))
        return redirect('gallery');

    }else{
      $data = DB::table('gallery')->get();

    }
    return view('front.gallery',compact('data'));
}

function skpd(Request $r,$id=null,$ly = null){
  $skpd = Skpd::whereIdSkpd(dec64($id))->first();
  if(empty($skpd))
  return abort('404');

  if(!empty($ly)){
    $data  = Layanan::whereIdLayanan(dec64($ly))->first();
    if($skpd->dibatasi=='Y'){
    if(!checkwaktu(now())){
      return redirect('survei/'.$id);
    }
  }
    if(empty($data))
    return abort('404');

    if($r->kirim_survei){
      $data = $r->all();
      unset($data['kirim_survei']);
      unset($data['_token']);
      unset($data['saran']);
      unset($data['tgl']);
      $data['id_layanan'] = dec64($ly);
      $data['tgl_survei'] = date('Y-m-d');
      $data['created'] = now();
      $data['reference'] = 'qr';
      $data['saran'] = nl2br($r->saran);
      // dd($data);
      Respon::insert($data);
      $r->session()->regenerateToken();
      return redirect('survei/'.$id)->with('success','Berhasil');
    }
  return view('front.survei',compact('data','skpd'));

  }

  $layanan = Layanan::join('skpd','skpd.id_skpd','layanan.id_skpd')->where('layanan.id_skpd',dec64($id))->get();
  return view('front.layanan',compact('skpd','layanan'));
}
function survei_form(Request $req, Layanan $layanan,$id=null){
  $lay = $layanan->join('skpd','skpd.id_skpd','layanan.id_skpd')->where('layanan.id_layanan',dec64($id))->first();
  if(empty($lay)){
  return redirect('/');
  }
if($req->kirim_survei){
$data = $req->all();
dd($data);
unset($data['kirim_survei']);
unset($data['_token']);
unset($data['tgl']);
$data['id_layanan'] = dec64($id);
$data['tgl_survei'] = date('Y-m-d');
$data['created'] = now();
Respon::insert($data);
Session::put('has_survei',$id);
return redirect('survei/'.$id.'?status=success');
}
  return view('front.survei_form',['layanan'=>$lay,'skpd'=>$lay]);
}
}
