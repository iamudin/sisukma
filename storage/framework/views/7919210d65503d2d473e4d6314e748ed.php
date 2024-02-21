<div class="modal periode" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
    <form action="<?php echo e(URL::full()); ?>" method="post">
      <?php echo csrf_field(); ?>
      <div class="modal-header">
        <h5 class="modal-title">Periode</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
    
      <div class="row">
        <div class="col-12">
          <div class="form-group">
            <small>Tahun</small>
            <select name="year" class="form-control" id="" onchange="if(this.value){$('#peri').show()}else{$('#peri').hide()}">
              <option value="">-pilih-</option>
              <?php for($i=2022; $i<=date('Y'); $i++): ?>
              <option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
              <?php endfor; ?>
            </select>
          </div>
          <div id="peri" style="display:none">
          <input type="radio" name="type" onchange="if(this.value){$('.month').show();$('.month select').removeAttr('disabled');$('#from').val('');$('#to').val('');}"> Bulan<br>
          <input type="radio" onchange="$('.month').hide();$('#sms').val('I');$('#from').val('01');$('#to').val('06');" name="type"> Januari - Juni<br>
          <input type="radio" onchange="$('.month').hide();$('#sms').val('II');$('#from').val('07');$('#to').val('12');" name="type"> Juli - Desember<br>
          <?php $__currentLoopData = array(
            ['name'=>'I','range'=>['01','03']],
            ['name'=>'II','range'=>['04','06']],
            ['name'=>'III','range'=>['07','09']],
            ['name'=>'IV','range'=>['10','12']]
            ); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <input type="radio" onchange="$('#sms').val('');$('.month').hide();$('#from').val('<?php echo e($row['range'][0]); ?>');$('#to').val('<?php echo e($row['range'][1]); ?>');" name="type"> Triwulan <?php echo e($row['name']); ?><br>
         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
         </div>
          <input type="hidden" name="from" id="from">
          <input type="hidden" name="to" id="to">
          <input type="hidden" name="sms" id="sms">
          <div class="form-group month" style="display:none">
            <small>Bulan</small>
            <select disabled name="month" class="form-control" onchange="if(this.value){$('#sms').val('');$('.date').show();$('.date select').removeAttr('disabled');}">
              <option value="">-pilih-</option>
              <?php $__currentLoopData = ['01','02','03','04','05','06','07','08','09','10','11','12']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($r); ?>"><?php echo e(blnindo($r)); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>
          <div class="form-group date" style="display:none">
            <small>Tanggal</small>
            <select disabled name="date" class="form-control">
              <option value="">-pilih-</option>
              <?php for($i=1; $i<=31; $i++): ?>
              <option value="<?php echo e(strlen($r)==1 ? '0'.$i : $i); ?>"><?php echo e($i); ?></option>
              <?php endfor; ?>
            </select>
          </div>
        </div>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Lihat Hasil</button>
      </div>
    </form>

    </div>
  </div>
</div><?php /**PATH C:\laragon\www\sisukma\resources\views/periode.blade.php ENDPATH**/ ?>