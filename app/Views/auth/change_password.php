<?= view('layout/header.php'); ?>

<div class="auth-container">
 <div class="auth-card">
  <div class="auth-header">
   <span class="hint">Do you want to change your password?</span>
   <h4>Please enter your old and new password</h4>
  </div>

  <?php if (session()->getFlashdata('error')): ?>
   <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>
  <form method="post" class="auth-form" action="<?= base_url('/change-password') ?>">
   <input type="password" name="current_password" placeholder="Current password" class="form-control mb-2" required>
   <input type="password" name="new_password" placeholder="New password" class="form-control mb-2" required>
   <input type="password" name="confirm_password" placeholder="Confirm New password" class="form-control mb-2" required>
   <button class="btn-auth w-100">Change Password</button>
  </form>
 </div>
</div>
<?= view('layout/footer.php'); ?>