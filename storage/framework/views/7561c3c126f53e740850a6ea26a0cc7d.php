<style>
            tr {
        page-break-inside: avoid;
    }
</style>
<center>
    <h4>PENGOLAHAN DATA SURVEI KEPUASAN MASYARAKAT PER RESPONDEN<br>DAN PERUNSUR LAYANAN</h4>
</center>
<table border="0" style="width:700px">
    <tr>
        <td>Unit Pelayanan </td>
        <td>: <?php echo e($skpd->nama_skpd); ?></td>
    </tr>
    <tr>
        <td>Periode </td>
        <td>: <?php echo e($periode); ?></td>
    </tr>
    <tr>
        <td>Alamat </td>
        <td>: <?php echo e($skpd->alamat); ?></td>
    </tr>
    <tr>
        <td>Telp. Fax </td>
        <td>: <?php echo e($skpd->telp); ?></td>
    </tr>
</table>


<br>
<?php
$respon = count($resp);
$u1 = 0;
$u2 = 0;
$u3 = 0;
$u4 = 0;
$u5 = 0;
$u6 = 0;
$u7 = 0;
$u8 = 0;
$u9 = 0; ?>

<table border="1" style="border-collapse:collapse;width:100%;border-style:solid;border-color:#000;font-size:10px"
    class="table table-bordered">
    <tr>
        <th rowspan="2" style="width:150px">No Res</th>
        <th colspan="9 style="text-align:center">Nilai Unsur Pelayanan</th>
        <th align="center" rowspan="<?php echo e($respon + 4); ?>"></th>
    </tr>
    <tr>
        <?php for($i = 1; $i <= 9; $i++): ?>
            <th style="text-align:center">U<?php echo e($i); ?></th>
        <?php endfor; ?>
    </tr>
    <?php if(count($resp) > 0): ?>
        <?php
foreach($resp as $k=>$r):
?>
        <tr>
            <td align="center"><?php echo e($k + 1); ?></td>
            <?php
            $u1 += $r->u1;
            $u2 += $r->u2;
            $u3 += $r->u3;
            $u4 += $r->u4;
            $u5 += $r->u5;
            $u6 += $r->u6;
            $u7 += $r->u7;
            $u8 += $r->u8;
            $u9 += $r->u9;
            ?>
            <td align="center"><?php echo e($r->u1); ?></td>
            <td align="center"><?php echo e($r->u2); ?></td>
            <td align="center"><?php echo e($r->u3); ?></td>
            <td align="center"><?php echo e($r->u4); ?></td>
            <td align="center"><?php echo e($r->u5); ?></td>
            <td align="center"><?php echo e($r->u6); ?></td>
            <td align="center"><?php echo e($r->u7); ?></td>
            <td align="center"><?php echo e($r->u8); ?></td>
            <td align="center"><?php echo e($r->u9); ?></td>
        </tr>
        <?php
endforeach;
?>
        <tr>
            <td>Nilai/Unsur</td>
            <td align="center"><?php echo e($u1); ?></td>
            <td align="center"><?php echo e($u2); ?></td>
            <td align="center"><?php echo e($u3); ?></td>
            <td align="center"><?php echo e($u4); ?></td>
            <td align="center"><?php echo e($u5); ?></td>
            <td align="center"><?php echo e($u6); ?></td>
            <td align="center"><?php echo e($u7); ?></td>
            <td align="center"><?php echo e($u8); ?></td>
            <td align="center"><?php echo e($u9); ?></td>
        </tr>
        <tr>
            <td>NRR/Unsur</td>
            <td align="center"><?php echo e(round($u1 / $respon, 2)); ?></td>
            <td align="center"><?php echo e(round($u2 / $respon, 2)); ?></td>
            <td align="center"><?php echo e(round($u3 / $respon, 2)); ?></td>
            <td align="center"><?php echo e(round($u4 / $respon, 2)); ?></td>
            <td align="center"><?php echo e(round($u5 / $respon, 2)); ?></td>
            <td align="center"><?php echo e(round($u6 / $respon, 2)); ?></td>
            <td align="center"><?php echo e(round($u7 / $respon, 2)); ?></td>
            <td align="center"><?php echo e(round($u8 / $respon, 2)); ?></td>
            <td align="center"><?php echo e(round($u9 / $respon, 2)); ?></td>
        </tr>
        <tr>
            <td>NRR Tertimbang/Unsur</td>
            <td align="center"><?php echo e(round((($u1 / $respon) * 1) / 9, 2)); ?></td>
            <td align="center"><?php echo e(round((($u2 / $respon) * 1) / 9, 2)); ?></td>
            <td align="center"><?php echo e(round((($u3 / $respon) * 1) / 9, 2)); ?></td>
            <td align="center"><?php echo e(round((($u4 / $respon) * 1) / 9, 2)); ?></td>
            <td align="center"><?php echo e(round((($u5 / $respon) * 1) / 9, 2)); ?></td>
            <td align="center"><?php echo e(round((($u6 / $respon) * 1) / 9, 2)); ?></td>
            <td align="center"><?php echo e(round((($u7 / $respon) * 1) / 9, 2)); ?></td>
            <td align="center"><?php echo e(round((($u8 / $respon) * 1) / 9, 2)); ?></td>
            <td align="center"><?php echo e(round((($u9 / $respon) * 1) / 9, 2)); ?></td>
            <td align="center">*)
                <?php echo e(round((($u1 / $respon) * 1) / 9 + (($u9 / $respon) * 1) / 9 + (($u2 / $respon) * 1) / 9 + (($u3 / $respon) * 1) / 9 + (($u4 / $respon) * 1) / 9 + (($u5 / $respon) * 1) / 9 + (($u6 / $respon) * 1) / 9 + (($u7 / $respon) * 1) / 9 + (($u8 / $respon) * 1) / 9, 2)); ?>

            </td>
        </tr>

        <tr>
            <td colspan="10">Ikm Pelayanan</td>
            <?php $ikm =((($u1 / $respon)*1/9)+(($u9 / $respon)*1/9)+(($u2 / $respon)*1/9)+(($u3 / $respon)*1/9)+(($u4 / $respon)*1/9)+(($u5 / $respon)*1/9)+(($u6 / $respon)*1/9)+(($u7 / $respon)*1/9)+(($u8 / $respon)*1/9))*25;
            ?>
            <td align="center">**) <b><?php echo e($ikm > 0? round($ikm,2) : 0); ?></b></td>
        </tr>
        <?php else: ?>
        <tr>
            <td colspan='10' align="center">
                <h1>Belum ada data</h1>
            </td>
        </tr>
        <?php endif; ?>
</table>
<!-- <tr>
      <td colspan="11" align="right">prediket(((($u1 / $respon)*1/9)+(($u9 / $respon)*1/9)+(($u2 / $respon)*1/9)+(($u3 / $respon)*1/9)+(($u4 / $respon)*1/9)+(($u5 / $respon)*1/9)+(($u6 / $respon)*1/9)+(($u7 / $respon)*1/9)+(($u8 / $respon)*1/9))*25)</td>
     
    </tr> -->
<table style="border-collapse:collapse;width:100%;border:none;margin-top:10px;font-size:10px" border="0">
    <tr style="border:none">
        <td style="border:none" rowspan="12">
            Keterangan :<br>
            - U1 s.d U9 = Unsur-unsur pelayanan<br>
            - NRR = Unsur-unsur pelayanan<br>
            - IKM = Indek Kepuasan Masyarakat<br>
            - *) = Jumlah NRR IKM tertimbang<br>
            - **) = Jumlah NRR Tertimbang x 25<br>
            - NRR Per Usur = Jumlah nilai per unsur dibagi jumlah kusioner yang terisi<br>
            - NRR tertimbang = NRR per unsur x 0,111 per unsur<br><br>
            <?php $ikm =((($u1 / $respon)*1/9)+(($u9 / $respon)*1/9)+(($u2 / $respon)*1/9)+(($u3 / $respon)*1/9)+(($u4 / $respon)*1/9)+(($u5 / $respon)*1/9)+(($u6 / $respon)*1/9)+(($u7 / $respon)*1/9)+(($u8 / $respon)*1/9))*25;
            ?>
            <h5 style="border:2px solid #000;padding:10px;margin-right:50px">IKM UNIT PELAYANAN <span
                    style="float:right"><?php echo e($ikm > 0 ? round($ikm,2) : 0); ?></span></h5>

     
        <td>
    </tr>
    <tr>
        <td style="border:1px solid #000;padding-left:4px">No</td>
        <td style="border:1px solid #000;padding-left:4px">Unsur Pelayanan</td>
        <td style="border:1px solid #000;padding-left:4px">Nilai Rata-Rata</td>
    </tr>
    <?php for($i = 1; $i <= 9; $i++): ?>
        <tr>
            <td style="border:1px solid #000;padding-left:4px">U<?php echo e($i); ?></td>
            <td style="border:1px solid #000;padding-left:4px">
                <?php echo e([1 => 'Persyaratan', 'Prosedur', 'Waktu Pelayanan', 'Biaya / Tarif', 'Produk Pelayanan', 'Kompetensi Pelaksana', 'Perilaku Pelaksana', 'Penanganan Pengaduan Saran dan Masukan', 'Sarana dan Prasarana'][$i]); ?>

            </td>
            <td style="border:1px solid #000;padding-right:20px;text-align:right">
                <?php echo e([1 => round($u1 / $respon, 2), round($u2 / $respon, 2), round($u3 / $respon, 2), round($u4 / $respon, 2), round($u5 / $respon, 2), round($u6 / $respon, 2), round($u7 / $respon, 2), round($u8 / $respon, 2), round($u9 / $respon, 2)][$i]); ?>

            </td>

        </tr>
    <?php endfor; ?>
</table>
<?php if($respon > 0): ?>
<div style="font-size:10px">
Mutu Pelayanan:<br>
A (Sangat Baik) : 88,31 - 100,00<br>
B (Baik) : 76,61 - 88,30<br>
C (Kurang Baik) : 65,00 - 76,60<br>
D (Tidak Baik): 25,00 - 64,99<br>
<?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\sisukma\resources\views/rekap/semester.blade.php ENDPATH**/ ?>