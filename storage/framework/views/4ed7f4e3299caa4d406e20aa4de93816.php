<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>
    <div class="row">
        <div class="col-lg-12 text-center">
            <h4>INDEKS KEPUASAN MASYARAKAT (IKM)<br><?php echo e(Str::upper($skpd->nama_skpd)); ?><br>
        KABUPATEN BENGKALIS<br>PERIODE <?php echo e(Str::upper($periode)); ?> </h4>
        </div>
        <div class="col-lg-6 col-6" style="float:left"><center>
<h3 style="border:4px solid #000;padding:10px">NILAI IKM</h3></center>
<div style="border:4px solid #000;min-height:400px;text-align:center">
<h1 style="font-size :100px;margin-top:20%;"><?php echo e(round($data->ikm,2)); ?></h1>
<p class="text-center"><h5>Mutu Pelayanan </h5>
  <h1><?php echo e(prediket(round($data->ikm,2),true)); ?></h1> (<?php echo e(prediket(round($data->ikm,2))); ?>)
</p>
</div>
        </div>
        <div class="col-lg-6 col-6" style="float:right"><center>
<h3 style="border:4px solid #000;padding:10px">RESPONDEN</h3></center>
<div style="border:4px solid #000;min-height:400px;vertical-align:center;padding:30px">
<table style="width:100%">
<tr>
    <td width="40%" >JUMLAH <span style="float:right">:</span></td>
    <td colspan="3"> <?php echo e($data->jumlah); ?> Orang</td>
</tr>
<tr>
    <td >JUMLAH<span style="float:right">:</span></td>
    <td colspan="3">L = <?php echo e($data->l); ?> Orang / P = <?php echo e($data->p); ?> Orang</td>
</tr>

<?php $__currentLoopData = ['Non Pendidikan','SD','SMP','SMA','DIII','S1','S2','S3']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr>
    <td ><?php if($k==0): ?>PENDIDIKAN<span style="float:right">:</span><?php endif; ?></td>
    <td ><?php echo e($r); ?> </td>
    <td >= </td>
    <?php $pd = Str::lower(str_replace(' ','_',$r));?>
    <td > <?php echo e($data->pendidikan->$pd ?? 0); ?> <span style="float:right">Orang</span></td>
</tr>

      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<tr>
  
    <td colspan="4" style="padding-top:20px" align="center">
        Periode Survei  = <?php echo e($periode); ?>

    </td>
</tr>
</table>
</div>
        </div>
        <div class="col-lg-12 text-center" style="margin-top:500px;display:block;position:absolute">
         
<h5>TERIMA KASIH ATAS PENILAIAN TELAH ANDA BERIKAN<br>
MASUKAN ANDA SANGAT BERMANFAAT UNTUK KEMAJUAN UNIT KAMI AGAR TERUS MEMPERBAIKI DAN MENINGKATKAN KUALITAS PELAYANAN BAGI MASYARAKAT</h5>
        </div>
    </div>
      
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html><?php /**PATH C:\laragon\www\sisukma\resources\views/skpd/cetak_ikm.blade.php ENDPATH**/ ?>