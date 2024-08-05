<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use App\Models\Login;
use App\Models\Skpd;
use App\Models\Respon;
use App\Models\Layanan;
use View;
use PDF;
use Str;
use DB;
use Excel;

class SkpdController extends Controller
{
  //Memeriksa apakah ada session dan ambil level sessions
  public function __construct()
  {
    $this->middleware(function ($request, $next) {
       if(!session()->has('id_user'))
       {
        return redirect('login')->with('danger','Silahkan Login Terlebih Dahulu');
       }else{
        if(session('level')!='skpd' && !request()->is('adminskpd/respon-layanan/*')){
        return redirect('admin/dashboard')->with('danger','Akses hanya untuk SKPD');
        }
       }
       $this->menuside();
         return $next($request);
     });

  }
function pengaturan(Request $req){
  $data = DB::table('skpd')->whereIdSkpd(session('id_skpd'))->first();
  if($req->simpan){
    if($req->file('banner')){
      if(file_exists(public_path($data->banner)))
      unlink(public_path($data->banner));
    }
    DB::table('skpd')->whereIdSkpd(session('id_skpd'))->update([
      'banner' => $req->file('banner')? $this->upload_img($req->file('banner')) : $data->banner,
      'alamat' => $req->alamat,
      'telp' => $req->telp,
      'dibatasi' => $req->survei,
      'email' => $req->email
    ]);

    return back()->with('success','Pengaturan berhasil disimpan');
  }
  return view('skpd.pengaturan',compact('data'));
}
  function dashboard(Request $request, \App\IkmManager $ikm){
    $data = json_decode(json_encode($ikm->nilai_ikm_skpd(session('id_skpd'))));
    // return $data;
    $per = $request->all() ?? null;
    // return $data;
    if($request->cetak_saran){
      $pdf = PDF::loadview('skpd.cetak_saran',['periode'=>$request->periode,'data'=>json_decode(dec64($request->saran))]);
      return $pdf->download('Saran-'.Str::slug($request->periode).'.pdf');
    }
return view('skpd.dashboard',compact('data','per'));
  }
  function lihatsaran($data){
    return view('skpd.lihatsaran',compact('data'));
  }
  function gallery(Request $req){
    $data =  null;
    if($req->act){
      if($req->act=='add'){
        if($req->simpan){
          $id = DB::table('gallery')->insertGetId(['nama'=>$req->nama,'permalink'=>Str::slug($req->nama),'id_skpd'=>session('id_skpd')]);
        return redirect('adminskpd/gallery/?act=edit&id='.base64_encode($id))->with('success','Berhasil Ditambah');

        }
      }

      if($req->act=='edit'){
        $data = DB::table('gallery')->whereIdSkpd(session('id_skpd'))->whereId(base64_decode(request('id')))->first();
          if(empty($data))
          return redirect('gallery');

        $foto =  DB::table('img_gallery')->whereIdGallery($data->id)->get();
        View::share('foto',$foto);
        if($req->delete_foto){
        DB::table('img_gallery')->wherePath($req->delete_foto)->delete();
        if(file_exists(public_path($req->delete_foto))){
          unlink(public_path($req->delete_foto));
        }
        return redirect('adminskpd/gallery?act=edit&id='.$req->id)->with('success','Foto berhasil dihapus');
        }
        if($req->simpan){
           DB::table('gallery')->where('id',$data->id)->update(['nama'=>$req->nama,'permalink'=>Str::slug($req->nama)]);
        return back()->with('success','Berhasil Diperbarui');

        }
        if($req->upload){
          DB::table('img_gallery')->insert(['id_gallery'=>base64_decode(request('id')),'path'=>$this->upload_img($req->file('path')),'caption'=>$req->caption]);
          return back()->with('success','Gambar berhasil diupload');
        }
      }
      if($req->act=='delete'){
        DB::table('gallery')->where('id',base64_decode($req->id))->delete();
        return back()->with('success','Berhasil Dihapus');
      }
    }else{
      $data = DB::table('gallery')->whereIdSkpd(session('id_skpd'))->get();

    }
    return view('skpd.gallery',compact('data'));
  }
  function upload_img($file){
    $ext = $file->getClientOriginalExtension();
    if(!in_array(Str::lower($ext),['jpg','png','gif']))
    return back()->with('danger','File gambar tidak dizinkan');
    $name =  Str::random(10).'.'.$ext;
    $file->move(public_path('foto/'),$name);
    return 'foto/'.$name;
  }
  function lihatrespon(Request $req,$id=null){
    $layanan = Layanan::with('respon')->whereIdLayanan(dec64($id))->first();
    if(empty($layanan))
    return redirect('adminskpd/layanan');

    if($req->delimport){
     Respon::whereIdLayanan($layanan->id_layanan)->where('created_at',dec64($req->delimport))->where('reference','xlsx')->delete();

      return back()->with('success','Data Import Berhasil Dihapus');
    }
    if($req->importbtn){
    if($xlsx = $req->file('importfile'))
    {
      if($xlsx->getClientOriginalExtension() != 'xlsx')
      return back();

      $total = 0;
      $gagal = 0;
      $data = Excel::toArray(null,$xlsx);
      foreach($data as $v){
        foreach($v as $k=>$value){
          if($k>0 && is_numeric($value[0])){

          $t['tgl_survei'] = $value[1];
          $t['jam_survei'] = $value[2];
          $t['jenis_kelamin'] = $value[3];
          $t['usia'] = $value[4];
          $t['pendidikan'] = Str::upper($value[5]);
          $t['pekerjaan'] = Str::upper($value[6]);
          $t['u1'] = $value[7];
          $t['u2'] = $value[8];
          $t['u3'] = $value[9];
          $t['u4'] = $value[10];
          $t['u5'] = $value[11];
          $t['u6'] = $value[12];
          $t['u7'] = $value[13];
          $t['u8'] = $value[14];
          $t['u9'] = $value[15];
          $t['saran'] = $value[16];
          $t['id_layanan'] = $layanan->id_layanan;
          $t['reference'] = 'xlsx';
          if(isDate($value[1])){
          Respon::insert($t);
          $total++;
          }else{
            $gagal++;

          }
          }

        }
      }
      $desc = $total + $gagal;
      return back()->with('success','Total data '.$desc.' | '.$total.' Responden berhasil diimport dan '.$gagal.' Gagal Tanggal Tidak Sesuai Format');

    }else{
      return back();

    }
  }
    return view('skpd.lihatresponlayanan',compact('layanan'));
  }
  function layanan(Request $request,Layanan $lay){
    // dd(url()->full());
    $edit = null;
    if($request->act){
    if($request->act=='delete'){
    $cek = $lay->where('id_layanan',dec64($request->id));
    if(empty($cek->first()))
    return back()->with('danger','Data Tidak Ditemukan');
    $cek->delete();
    return redirect('adminskpd/layanan')->with('success','Berhasil dihapus');

    }

    if($request->act=='edit'){
    $edit = $lay->where('id_layanan',dec64($request->id));
    if(empty($edit->first()))
    return redirect('adminskpd/layanan')->with('danger','Data Tidak Ditemukan');

    if($request->simpan){
      $edit->update(['nama_layanan'=>$request->nama_layanan]);
      return redirect('adminskpd/layanan')->with('success','Berhasil diedit');
     }
    $edit = $edit->first();
   }

    if($request->act=='add'){
      if($request->simpan){
        $lay->insert(['nama_layanan'=>$request->nama_layanan,'id_skpd'=>session('id_skpd')]);
      return redirect('adminskpd/layanan')->with('success','Berhasil Ditambah');

      }
    }
  }
    // $skpd = Skpd::all();
    if(request()->year){
      View::share('periode',request()->year);
     if(request()->month){
      View::share('periode',blnindo(request()->month).' '.request()->year);
      if(request()->date){
      View::share('periode',request()->date.' '.blnindo(request()->month).' '.request()->year);
      }
      }elseif(request()->from && request()->to){
        View::share('periode','Triwulan '.astrw(request()->from,request()->to).' '.request()->year);
    }else{

    }
  }else{
    View::share('periode',date('Y'));
}

  if($request->lihat){
    return $this->lihatikm($request);
  }
    $data = Layanan::where('id_skpd',session('id_skpd'))->get();
    return view('skpd.layanan',['data'=>$data,'edit'=>$edit]);
}
function get_response($id){
  $q = Respon::where('id_layanan',$id)->get();
  return getResponLayanan(enc64(json_encode(json_decode($q,true))));
}
function lihatikm(Request $request){
  $data = $this->get_response($request->lihat);
  if($request->layanan && $request->cetak){
    $pdf = PDF::loadview('skpd.cetakresponlayanan',['respon'=>$data]);
    return $pdf->download(Str::slug('ikm '.$request->layanan).'.pdf');
  }
  return view('skpd.tableikmlayanan',['respon'=>$data]);
}
function respon_layanan(\App\IkmManager $ikm,Request $request,Respon $res,$id=null){
  $l = Skpd::where('id_skpd',$id)->first();
  if(empty($l)){
    return redirect('adminskpd/layanan')->with('danger','Data tidak ditemukan');
}
  $resp = $res->join('layanan','layanan.id_layanan','respon.id_layanan')->where('layanan.id_skpd',$l->id_skpd);
  if(request('year')){
    $peri = request('year');
    $resp = $resp->whereYear('tgl_survei',request('year'));

    $from = date('Y-m-d',strtotime(request()->year.'-01-01'));
    $to_date = request()->year."-12-23";
    $between = [$from,date("Y-m-t", strtotime($to_date))];
    $respon = $resp->whereBetween('tgl_survei',$between)->get();

    $totalmonth = diffmonth(new \DateTime($from), new \DateTime($to_date));
    $star = intval(substr("01", 0, 1) == 0 ? substr("01", 1, 2) : "01");
    $to = intval(substr("12", 0, 1) == 0 ? substr("12", 1, 2) : "12");
    $data = array();
        for ($a = $star; $a <= $to; $a++){
      $rsp = getResponfilter($respon->sortByDesc('id_respon'), request('year'), numtomonth($a));
      $u = $rsp->take(takesample(count($rsp)));
      foreach ($u as $rd) {
        array_push($data, $rd);
      }
      // $o[] = numtomonth($a);

      // $f[$a] = getIkmPd($u);?
          // return $rsp;
    }
    if(request('month')){
    $peri = blnindo(request('month')).' '.request('year');
    $respon = $resp->whereMonth('tgl_survei',request('month'))->orderBy('id_respon','desc')->get();
    $totalmonth = 1;
        $star = 1;
        $to = 1;
    $data = array();
      $u = $respon->take(takesample(count($respon)));
      foreach ($u as $rd) {
        array_push($data, $rd);
      }
}
    elseif(request('from') && request('to')){
    if(request('sms')){
      $peri = (Str::lower(request('sms')) == "i" ? "Januari s/d Juni" : "Juli s/d Desember " )." ".request('year');
    }
    else{
    $peri = "Triwulan ".$ikm->astrw(request('from'),request('to')).' '.request('year');

    }
    $from = date('Y-m-d',strtotime(request()->year.'-'.request()->from.'-01'));
    $to_date = request()->year."-".request()->to."-23";
    $between = [$from,date("Y-m-t", strtotime($to_date))];
    $respon = $resp->whereBetween('tgl_survei',$between)->get();

    $totalmonth = diffmonth(new \DateTime($from), new \DateTime($to_date));
        $star = intval(substr(request('from'), 0, 1) == 0 ? substr(request('from'), 1, 2) : request('from'));
        $to = intval(substr(request('to'), 0, 1) == 0 ? substr(request('to'), 1, 2) : request('to'));
    $data = array();
        for ($a = $star; $a <= $to; $a++){
      $rsp = getResponfilter($respon->sortByDesc('id_respon'), request('year'), numtomonth($a));
      $u = $rsp->take(takesample(count($rsp)));
      foreach ($u as $rd) {
        array_push($data, $rd);
      }
      // $o[] = numtomonth($a);

      // $f[$a] = getIkmPd($u);?
          // return $rsp;
    }
        // return $o;
        // return $data;
    if(request('cetak')){
      $pdf = PDF::loadview('rekap.semester',['resp'=>$data,'skpd'=>$l,'periode'=>$peri]);
      return $pdf->download(Str::slug('rekapitulasi '.$l->nama_skpd.' periode '.$peri).'.pdf');

    }
    }
    else{

    }
    if(request('cetak')){
        $pdf = PDF::loadview('rekap.semester',['resp'=>$data,'skpd'=>$l,'periode'=>$peri]);
        return $pdf->download(Str::slug('rekapitulasi '.$l->nama_skpd.' periode '.$peri).'.pdf');

      }
  }

  // if(request('cetak')){
  //   $pdf = PDF::loadview('skpd.pdfhasil',['resp'=>$resp->get(),'skpd'=>$l,'periode'=>$peri]);
  //   return $pdf->download(Str::slug('rekapitulasi '.$l->nama_skpd.' periode '.$peri).'.pdf');

  // }
return view('skpd.pdfhasil',['resp'=>$resp->get(),'skpd'=>$l]);
}

}
