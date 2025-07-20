<?= view('layout/header.php'); ?>
<div class="container m-5 py-4">
 <div class=" row justify-content-center">
  <div class="shadow rounded p-4 col-md-4">
   <h3 class="mb-4 text-center">Login</h3>

   <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
   <?php endif; ?>

   <form method="post" action="<?= base_url('/login-post') ?>">
    <div class="mb-3">
     <input type="email" name="email" class="form-control" placeholder="Email" required>
    </div>

    <div class="mb-3">
     <input type="password" name="password" class="form-control" placeholder="Password" required>
    </div>

    <button type="submit" class="btn btn-primary w-100">Login</button>
   </form>
  </div>
 </div>
</div>
<?= view('layout/footer.php'); ?>