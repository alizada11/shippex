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
  <?php if (session()->getFlashdata('success')): ?>
   <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>

  <form method="post" action="<?= base_url('/login-post') ?>" class="auth-form">
   <div class="form-group">
    <label for="email">Email / Username</label>
    <input type="text" id="email" name="email" required class="form-control" value="<?= old('email') ?>" placeholder="email@example.com">
   </div>

   <div class="form-group ">
    <label for="password">Password</label>
    <div class="input-group">
     <input type="password"
      id="password"
      name="password"
      required
      class="form-control w-auto"
      placeholder="••••••••">
     <button type="button" class="btn btn-outline-secondary border-start-0 toggle-password" data-target="password">
      <i class="fas fa-eye"></i>
     </button>
    </div>
   </div>

   <div class="form-options">
    <div class=" d-flex align-items-center gap-2">
     <input type="checkbox" name="remember" value="1" id="remember" class="remember-checkbox">
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

<style>
 .toggle-password {
  border: 1px solid #e2e8f0;
  border-left: none;
  background-color: white;
  padding: 0.375rem 0.75rem;
  transition: all 0.2s ease;
 }

 .toggle-password:hover {
  background-color: #5a67d8;
  border-color: var(--primary-color);
 }

 .toggle-password.active {
  background-color: var(--shippex-light);
  border-color: var(--primary-color);
  color: var(--primary-color);
 }

 .toggle-password.active i::before {
  content: "\f070";
  /* fa-eye-slash */
 }

 .input-group .form-control:focus+.toggle-password {
  border-color: var(--primary-color);
 }

 /* Ensure the input group maintains proper styling */
 .input-group {
  display: flex;
  align-items: stretch;
 }

 .input-group .form-control {
  border-right: none;
 }

 .input-group .toggle-password {
  border: 1px solid #7d7d7d;
  border-left: none;
 }
</style>
<script>
 document.addEventListener('DOMContentLoaded', function() {
  const toggleButtons = document.querySelectorAll('.toggle-password');

  toggleButtons.forEach(button => {
   button.addEventListener('click', function() {
    const targetId = this.getAttribute('data-target');
    const passwordInput = document.getElementById(targetId);

    // For FontAwesome version
    const icon = this.querySelector('i');
    // For SVG version
    const eyeSlash = this.querySelector('.eye-slash');

    if (passwordInput.type === 'password') {
     passwordInput.type = 'text';
     this.classList.add('active');

     // FontAwesome
     if (icon) {
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
     }

     // SVG
     if (eyeSlash) {
      eyeSlash.style.display = 'block';
     }
    } else {
     passwordInput.type = 'password';
     this.classList.remove('active');

     // FontAwesome
     if (icon) {
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
     }

     // SVG
     if (eyeSlash) {
      eyeSlash.style.display = 'none';
     }
    }

    // Focus back on the input for better UX
    passwordInput.focus();
   });
  });
 });
</script>
<?= view('layout/footer.php'); ?>