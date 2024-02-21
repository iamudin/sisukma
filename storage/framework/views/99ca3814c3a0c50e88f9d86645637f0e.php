<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>S I S U K M A - Admin</title>
        <link rel="icon" href="https://bengkaliskab.go.id/favicon.png" type="image/x-icon" />
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="<?php echo e(asset('css/styles.css')); ?>" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdn.rawgit.com/ax5ui/ax5ui-picker/dist/ax5picker.css">
        <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.3.min.js"></script>
        <script type="text/javascript" src="https://cdn.rawgit.com/ax5ui/ax5core/master/dist/ax5core.min.js"></script>
        <script type="text/javascript" src="https://cdn.rawgit.com/ax5ui/ax5ui-picker/master/dist/ax5picker.min.js"></script>

    </head>
    <body class="sb-nav-fixed">
        <nav class="bg-primary sb-topnav navbar navbar-expand navbar-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="<?php echo e(url('/')); ?>"><b>S I S U K M A</b></a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
                <form class="d-none d-md-inline-block form-inline me-auto me-0 me-md-3 my-2 my-md-0" style="color:#f5f5f5">
                <?php if(session('level')=='admin'): ?>
                Selamat Datang <?php echo e(session('nama')); ?>

                <?php else: ?> 
                Selamat Datang <?php echo e(session('nama')); ?>

                <?php endif; ?>
               
                </form>
            <!-- Navbar-->
           
            <ul class="navbar-nav ms-auto ms-auto me-3 me-lg-4">
          
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(url('account')); ?>"><i class="fas fa-user fa-fw"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="navbarDropdown" href="<?php echo e(url('logout')); ?>" onclick="return confirm('Yakin untuk keluar ?')"><i class="fas fa-sign-out-alt fa-fw"></i></a>
                </li>
            </ul>
        </nav>
        <style media="screen">
          .sb-menu {border-left:4px solid #07c;background:lightblue;margin-bottom: 8px;border-radius: 5px;color:#111}

          .nav {padding:10px }
        </style>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion bg-light" style="border-right:4px dashed #ccc" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">MENU</div>

                            <a class="nav-link sb-menu" href="<?php echo e(url((session('level')=='skpd'? 'admin':'').session('level').'/dashboard')); ?>">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <?php $__currentLoopData = $sidebarmenu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a class="nav-link sb-menu" href="<?php echo e($row['target']); ?>">
                                <div class="sb-nav-link-icon"><i class="fas fa-<?php echo e($row['ikon']); ?>"></i></div>
                                <?php echo e($row['nama']); ?>

                            </a>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Login Sebagai:</div>
                      <?php echo e(session('nama')); ?>

                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                  <?php if(Session::has('success')): ?>
                  <div class="alert alert-success align-right" style="margin:10px">
                    <?php echo e(Session::get('success')); ?>

                    <span class="text-right" style="cursor:pointer;float:right" onclick="$('.alert').hide()">x</span>
                  </div>
                  <?php endif; ?>
                  <?php if(Session::has('danger')): ?>
                  <div class="alert alert-danger align-right" style="margin:10px">
                    <?php echo e(Session::get('danger')); ?>

                    <span class="text-right" style="cursor:pointer;float:right" onclick="$('.alert').hide()">x</span>
                  </div>
                  <?php endif; ?>
                  <?php echo $__env->yieldContent('content'); ?>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; <?php echo e(get_info('nama_aplikasi')); ?></div>
                            <div>

                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo e(asset('js/scripts.js')); ?>"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo e(asset('assets/demo/chart-area-demo.js')); ?>"></script>
        <script src="<?php echo e(asset('assets/demo/chart-bar-demo.js')); ?>"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script src="<?php echo e(asset('js/datatables-simple-demo.js')); ?>"></script>
    </body>
</html>
<?php /**PATH C:\laragon\www\sisukma\resources\views/layout/app.blade.php ENDPATH**/ ?>