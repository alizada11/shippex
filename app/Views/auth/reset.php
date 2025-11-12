<?= view('layout/header.php'); ?>

<div class="auth-container">
 <div class="auth-card">
  <div class="auth-header">
   <h4>Please Enter Your New Password</h4>
  </div>

  <?php if (session()->getFlashdata('error')): ?>
   <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('success')): ?>
   <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>

  <form method="post" action="<?= base_url('/reset-password/' . $token) ?>" class="auth-form">
   <div class="form-group mb-4">
    <label for="password">New Password</label>
    <input type="password" id="password" name="password" required class="form-control" placeholder="New Password">
    <label for="confirm_password">Confirm Password</label>
    <input type="password" id="confirm_password" name="confirm_password" required class="form-control" placeholder="Confirm Password">
   </div>

   <button type="submit" class="btn-auth">Change Password</button>


  </form>
  <div class="auth-footer">
   <p>Remember Your Password? <a href="<?= base_url('/login') ?>">Login</a></p>
  </div>
 </div>
</div>
<?= view('layout/footer.php'); ?>