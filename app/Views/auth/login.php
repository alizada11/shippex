<?= view('layout/header.php'); ?>

<div class="auth-container">
 <div class="auth-card">
  <div class="auth-header">
   <h2>Welcome Back</h2>
   <p>Please enter your credentials</p>
  </div>

  <?php if (session()->getFlashdata('error')): ?>
   <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <form method="post" action="<?= base_url('/login-post') ?>" class="auth-form">
   <div class="form-group">
    <label for="email">Email</label>
    <input type="email" id="email" name="email" required class="form-control" placeholder="john@example.com">
   </div>

   <div class="form-group">
    <label for="password">Password</label>
    <input type="password" id="password" name="password" required class="form-control" placeholder="••••••••">
   </div>

   <div class="form-options">
    <div class="form-check d-flex align-items-center gap-2">
     <input type="checkbox" id="remember" class="form-check-input">
     <label for="remember" class="form-check-label">Remember me</label>
    </div>
    <a href="<?= base_url('/forgot') ?>" class="forgot-password">Forgot password?</a>
   </div>

   <button type="submit" class="btn-auth">Login</button>
  </form>

  <div class="auth-footer">
   <p>Don't have an account? <a href="<?= base_url('/register') ?>">Register</a></p>
  </div>
 </div>
</div>

<?= view('layout/footer.php'); ?>