<?= view('layout/header.php'); ?>
<div class="container py-4 m-5">
 <div class="row justify-content-center">
  <div class="shadow p-4 rounded col-md-4">
   <h3 class="mb-4 text-center">Login</h3>

   <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
   <?php endif; ?>

   <form method="post" action="<?= base_url('/register') ?>">
    <input type="text" name="username" required placeholder="Username" class="form-control mb-2">
    <input type="email" name="email" required placeholder="Email" class="form-control mb-2">
    <input type="password" name="password" required placeholder="Password" class="form-control mb-2">
    <button type="submit" class="btn btn-primary w-100">Register</button>
   </form>

  </div>
 </div>
</div>
<?= view('layout/footer.php'); ?>