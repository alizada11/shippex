<?= view('layout/header.php'); ?>

<div class="auth-container">
 <div class="auth-card register">
  <div class="auth-header">
   <h2>Create Account</h2>
   <p>Join us today!</p>
  </div>

  <?php if (session()->getFlashdata('errors')): ?>
   <div class="alert alert-danger">
    <ul style="margin: 0;">
     <?php foreach (session()->getFlashdata('errors') as $error): ?>
      <li><?= esc($error) ?></li>
     <?php endforeach ?>
    </ul>
   </div>
  <?php endif; ?>

  <?php if (session()->getFlashdata('error')): ?>
   <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <form method="post" action="<?= base_url('/register') ?>" class="auth-form">
   <div class="row">
    <div class="form-group col-lg-6">
     <label for="firstname">First Name</label>
     <input type="text" id="firstname" name="firstname" required class="form-control" placeholder="First Name">
    </div>

    <div class="form-group col-lg-6">
     <label for="lastname">Last Name</label>
     <input type="text" id="lastname" name="lastname" required class="form-control" placeholder="Last Name">
    </div>

    <div class="form-group col-lg-6">
     <label for="username">Username</label>
     <input type="text" id="username" name="username" required class="form-control" placeholder="username">
    </div>

    <div class="form-group col-lg-6">
     <label for="email">Email</label>
     <input type="email" id="email" name="email" required class="form-control" placeholder="email@example.com">
    </div>

    <div class="form-group col-lg-6">
     <label for="password">Password</label>
     <input type="password" id="password" name="password" required class="form-control" placeholder="••••••••">
    </div>
    <div class="form-group col-lg-6">
     <label for="password">Confirm Password</label>
     <input type="password" id="password" name="confirm_password" required class="form-control" placeholder="••••••••">
    </div>
   </div>

   <button type="submit" class="btn-auth">Register</button>
  </form>

  <div class="auth-footer">
   <p>Already have an account? <a href="<?= base_url('/login') ?>">Sign in</a></p>
  </div>
 </div>
</div>

<?= view('layout/footer.php'); ?>