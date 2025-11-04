<?= view('layout/header.php'); ?>

<div class="auth-container">
 <div class="auth-card">
  <div class="auth-header">
   <h3>Please Enter Your New Passwor</h3>
  </div>

  <?php if (session()->getFlashdata('error')): ?>
   <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('success')): ?>
   <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>

  <form method="post" action="<?= base_url('/reset-password/' . $token) ?>" class="auth-form">
   <div class="form-group mb-4">
    <input type="password" id="password" name="password" required class="form-control" placeholder="New Password">
   </div>

   <button type="submit" class="btn-auth">Change Password</button>
  </form>

 </div>
</div>
<?= view('layout/footer.php'); ?>