<?= view('layout/header.php'); ?>

<div class="auth-container">
 <div class="auth-card">
  <div class="auth-header">
   <span class="hint">Forget Your Passord?</span>
   <h4>Please enter your email</h4>
  </div>

  <?php if (session()->getFlashdata('error')): ?>
   <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('success')): ?>
   <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>

  <form method="post" action="<?= base_url('/forgot') ?>" class="auth-form">
   <label for="email">Email</label>
   <input type="email" name="email" class="form-control mb-3" placeholder="email@example.com" required>
   <button class="btn-auth w-100">Send Reset Link</button>
  </form>
  <div class="auth-footer">
   <p>Remember Your Password? <a href="<?= base_url('/login') ?>">Login</a></p>
  </div>
 </div>
</div>
<?= view('layout/footer.php'); ?>