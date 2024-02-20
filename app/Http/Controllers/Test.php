<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skpd;
use App\Models\Respon;
use App\Models\Layanan;
use PDF;
use Str;

class Test extends Controller
{

  public function diffmonth(\DateTime $date1, \DateTime $date2)
  {
    $diff = $date1->diff($date2);

    $months = $diff->y * 12 + $diff->m + $diff->d / 30;

    return (int) round($months);
  }

  function index(\App\IkmManager $ikm, Request $request, Respon $res, $id = null)
  {
    $l = Skpd::where('id_skpd', '335')->first();
    if (empty($l)) {
      return redirect('adminskpd/layanan')->with('danger', 'Data tidak ditemukan');
    }
    $resp = $res->join('layanan', 'layanan.id_layanan', 'respon.id_layanan')->where('layanan.id_skpd', $l->id_skpd);
    if (request('year')) {
      $peri = request('year');
      $resp = $resp->whereYear('tgl_survei', request('year'));

      if (request('month')) {
        $peri = blnindo(request('month')) . ' ' . request('year');
        $resp = $resp->whereMonth('tgl_survei', request('month'));
      } elseif (request('from') && request('to')) {
        if (request('sms')) {
          $peri = (Str::lower(request('sms')) == "i" ? "Januari s/d Juni" : "Juli s/d Desember ") . " " . request('year');
          $from = date('Y-m-d', strtotime(request()->year . '-' . request('from') . '-01'));
          $to_date = request()->year . "-" . request()->to . "-23";
          $between = [$from, date("Y-m-t", strtotime($to_date))];

          $respon = $resp->whereBetween('tgl_survei', $between)->get();

          $totalmonth = $this->diffmonth(new \DateTime($from), new \DateTime($to_date));
          $tot = 0;
          $data = array();
          for ($a = 1; $a <= $totalmonth; $a++){
            $rsp = getResponfilter($respon->sortByDesc('id_respon'), request('year'), numtomonth($a));
            $u = $rsp->take(takesample(count($rsp)));
            foreach ($u as $r) {
              array_push($data, $r);
            }
            $f[$a] = getIkmPd($u);
            $tot = $tot + $f[$a];
          }
          // return $tot / $totalmonth;
          // return getIkmPd(collect($data));
          return view('rekap.rekaptes', ['resp' => $data, 'skpd' => $l,'periode'=>$peri,'ikmw'=>0.0,'periode'=>'oo']);
          exit;
        } else {
          $peri = "Triwulan " . $ikm->astrw(request('from'), request('to')) . ' ' . request('year');
          $from = date('Y-m-d', strtotime(request()->year . '-' . request()->from . '-01'));
          $to_date = request()->year . "-" . request()->to . "-23";
          $between = [$from, date("Y-m-t", strtotime($to_date))];
          $resp = $resp->whereBetween('tgl_survei', $between);
        }
      } else {

      }
    }

    if (request('cetak')) {
      $pdf = PDF::loadview('skpd.pdfhasil', ['resp' => $resp->get(), 'skpd' => $l, 'periode' => $peri]);
      return $pdf->download(Str::slug('rekapitulasi ' . $l->nama_skpd . ' periode ' . $peri) . '.pdf');

    }
    return view('skpd.pdfhasil', ['resp' => $resp->get(), 'skpd' => $l]);
  }

  function data_skpd(Request $request, Skpd $skpd, \App\IkmManager $ikm)
  {
    $edit = null;
    $per = $request->all() ?? null;
    $ikm->periode();
    if ($request->act) {
      if ($request->act == 'delete') {
        $cek = $skpd->where('id_skpd', dec64($request->id));
        if (empty($cek->first()))
          return back()->with('danger', 'Data Tidak Ditemukan');
        $cek->delete();
        return redirect('admin/data-skpd')->with('success', 'Berhasil dihapus');

      }

      if ($request->act == 'edit') {
        $edit = $skpd->where('id_skpd', dec64($request->id));
        if (empty($edit->first()))
          return redirect('admin/data-skpd')->with('danger', 'Data Tidak Ditemukan');

        if ($request->simpan) {
          $edit->update(['nama_skpd' => $request->nama_skpd, 'alamat' => $request->alamat]);
          return redirect('admin/data-skpd')->with('success', 'Berhasil diedit');
        }
        $edit = $edit->first();
      }

      if ($request->act == 'add') {
        if ($request->simpan) {
          $skpd->insert(['nama_skpd' => $request->nama_skpd, 'alamat' => $request->alamat]);
          return redirect('admin/data-skpd')->with('success', 'Berhasil Ditambah');

        }
      }
    }
    return view('admin.data_skpd', ['edit' => $edit, 'per' => $per]);

  }


  function hasil_survei(Request $request, Respon $res)
  {
    return view('admin.hasilsurvei.hasilsurvei');
  }
  function respon_layanan(Request $request, Respon $res, $id = null)
  {
    $l = Layanan::join('skpd', 'skpd.id_skpd', 'layanan.id_skpd')->where('skpd.id_skpd', dec64($id))->first();
    if (empty($l)) {
      return redirect('admin/layanan')->with('danger', 'Data tidak ditemukan');
    }
    $resp = $res->join('layanan', 'layanan.id_layanan', 'respon.id_layanan')->where('layanan.id_skpd', dec64($id));
    if (request('tahun')) {
      $resp = $resp->whereYear('created', request('tahun'));
      if (request('bulan')) {
        $resp = $resp->whereMonth('created', request('bulan'));
      }
    }

    if (request('cetak')) {
      $pdf = PDF::loadview('admin.hasilsurvei.hasil_skm', ['resp' => $resp->get(), 'layanan' => $l, 'id' => $id]);
      return $pdf->download(Str::slug($l->nama_layanan) . '.pdf');

    }
    return view('admin.hasilsurvei.hasilsurvei', ['resp' => $resp->get(), 'layanan' => $l, 'id' => $id]);
  }
}