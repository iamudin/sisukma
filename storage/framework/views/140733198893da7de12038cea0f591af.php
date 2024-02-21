<html>
    <body>
        <center><h3>REKAPITULASI NILAI INDEKS KEPUASAN MASYARAKAT <br>DI LINGKUNGAN PEMERINTAH KABUPATEN BENGKALIS<br> PERIODE <?php echo e(Str::upper($periode)); ?></h3></center>
        <table border="1" style="border-collapse:collapse;width:100%;border-style:solid;border-color:#000;font-size:10px">
<tr>
    <td rowspan="2" align="center">No</td>
    <td rowspan="2" align="center">Perangkat Daerah</td>
    <td colspan="9" align="center">Nilai Unsur Pelayanan</td>
    <td rowspan="2" align="center">Nilai IKM</td>
</tr>
<tr>
<?php for($i=1;$i<=9; $i++): ?>
<td align="center">U<?php echo e($i); ?></td>
<?php endfor; ?>
</tr>
<?php $__currentLoopData = $data->data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr>
    <td align="center"><?php echo e($k+1); ?></td>
    <td ><?php echo e($r->nama_skpd); ?></td>
    <?php $__currentLoopData = ['u1','u2','u3','u4','u5','u6','u7','u8','u9']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <td align="right" style="padding-right:10px"><?php echo e(round($r->$s,2)); ?></td>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <td align="right" style="padding-right:10px"><?php echo e(round($r->ikm,2)); ?></td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<tr>
    <td colspan="2" align="center">IKM Perunsur</td>
    <?php $__currentLoopData = ['u1','u2','u3','u4','u5','u6','u7','u8','u9']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <td align="right" style="padding-right:10px"><?php echo e(round($data->unsur->$s,2)); ?></td>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <td align="right" style="padding-right:10px"><?php echo e(round($data->unsur->total_ikm,2)); ?></td>
</tr>
        </table>
<br>
<br>
<br>


    </body>
</html><?php /**PATH C:\laragon\www\sisukma\resources\views/rekap/tahunan.blade.php ENDPATH**/ ?>