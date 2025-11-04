<?= view('layout/header.php'); ?>

<div class="auth-container">
 <div class="auth-card">
  <div class="auth-header">
   <h2>Please enter your email</h2>
  </div>

  <?php if (session()->getFlashdata('error')): ?>
   <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('success')): ?>
   <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>

  <form method="post" action="<?= base_url('/forgot') ?>" class="auth-form">
   <input type="email" name="email" class="form-control mb-3" placeholder="Your email" required>
   <button class="btn-auth w-100">Send Reset Link</button>
  </form>

 </div>
</div>
<?= view('layout/footer.php'); ?>