<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use App\Models\Login;
use App\Models\Skpd;
use App\Models\Respon;
use App\Models\Layanan;
use App\IkmManager;
use View;
use PDF;
use Str;
use DB;

class DashboardController extends Controller
{
  //Memeriksa apakah ada session dan ambil level sessions
  public function __construct()
  {
    $this->middleware(function ($request, $next) {
       if(!session()->has('id_user'))
       {
        return redirect('login')->with('danger','Silahkan Login Terlebih Dahulu');
       }
         return $next($request);
     });
     $this->menuside();

  }

  function dashboard(Request $request, \App\IkmManager $ikm){
    $data = json_decode(json_encode($ikm->ikm_kabupaten($request->year??date('Y'))));
    $per = $request->all() ?? null;
return view('admin.dashboard',compact('data','per'));
  }
  function data_skpd(Request $request,Skpd $skpd,\App\IkmManager $ikm){
    $edit = null;
    $per = $request->all() ?? null;
    $ikm->periode();
      if($request->act){
      if($request->act=='delete'){
      $cek = $skpd->where('id_skpd',dec64($request->id));
      if(empty($cek->first()))
      return back()->with('danger','Data Tidak Ditemukan');
      $cek->delete();
      return redirect('admin/data-skpd')->with('success','Berhasil dihapus');

      }

      if($request->act=='edit'){

      $edit = $skpd->where('id_skpd',dec64($request->id));
      if(empty($edit->first()))
      return redirect('admin/data-skpd')->with('danger','Data Tidak Ditemukan');

      if($request->simpan){
        $edit->update(['nama_skpd'=>$request->nama_skpd,'status_sample'=>$request->status_sample,'alamat'=>$request->alamat]);
        \App\Models\Periode::whereSkpdId(dec64($request->id))->delete();
        foreach($request->tahun_sample as $r){
        \App\Models\Periode::updateOrCreate(['skpd_id' => dec64($request->id),'tahun'=>$r], ['tahun'=>$r]);
        }

        return back()->with('success','Berhasil diedit');
       }
      $edit = $edit->first();
     }

      if($request->act=='add'){
        if($request->simpan){
          $skpd->insert(['nama_skpd'=>$request->nama_skpd,'alamat'=>$request->alamat]);
          return redirect('admin/data-skpd')->with('success','Berhasil Ditambah');

        }
      }
    }
    return view('admin.data_skpd',['edit'=>$edit,'per'=>$per]);

      }


  function hasil_survei(Request $request,Respon $res){
    return view('admin.hasilsurvei.hasilsurvei');
  }
  function respon_layanan(Request $request,Respon $res,$id=null){
      $l = Layanan::join('skpd','skpd.id_skpd','layanan.id_skpd')->where('skpd.id_skpd',dec64($id))->first();
      if(empty($l)){
        return redirect('admin/layanan')->with('danger','Data tidak ditemukan');
    }
      $resp = $res->join('layanan','layanan.id_layanan','respon.id_layanan')->where('layanan.id_skpd',dec64($id));
      if(request('tahun')){
        $resp = $resp->whereYear('created',request('tahun'));
        if(request('bulan')){
          $resp = $resp->whereMonth('created',request('bulan'));
        }
      }

      if(request('cetak')){
        $pdf = PDF::loadview('admin.hasilsurvei.hasil_skm',['resp'=>$resp->get(),'layanan'=>$l,'id'=>$id]);
        return $pdf->download(Str::slug($l->nama_layanan).'.pdf');

      }
    return view('admin.hasilsurvei.hasilsurvei',['resp'=>$resp->get(),'layanan'=>$l,'id'=>$id]);
  }
}
