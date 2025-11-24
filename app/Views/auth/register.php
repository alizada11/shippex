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
      <?= csrf_field() ?>

      <div class="row">

        <div class="form-group col-lg-6">
          <label for="firstname">First Name</label>
          <input type="text"
            id="firstname"
            name="firstname"
            required
            class="form-control"
            value="<?= old('firstname') ?>"
            placeholder="First Name">
        </div>

        <div class="form-group col-lg-6">
          <label for="lastname">Last Name</label>
          <input type="text"
            id="lastname"
            name="lastname"
            required
            class="form-control"
            value="<?= old('lastname') ?>"
            placeholder="Last Name">
        </div>

        <div class="form-group col-lg-12">
          <label for="username">Username</label>
          <input type="text"
            id="username"
            name="username"
            required
            class="form-control"
            value="<?= old('username') ?>"
            placeholder="username">
        </div>

        <div class="form-group col-lg-6">
          <label for="email">Email</label>
          <input type="email"
            id="email"
            name="email"
            required
            class="form-control"
            value="<?= old('email') ?>"
            placeholder="email@example.com">
        </div>

        <div class="form-group col-lg-6">
          <label for="phone">Phone Number</label>
          <input type="text"
            id="phone"
            name="phone_number"
            required
            class="form-control"
            value="<?= old('phone_number') ?>"
            placeholder="+1235468989">
        </div>

        <div class="form-group col-lg-6">
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

        <div class="form-group col-lg-6">
          <label for="confirm_password">Confirm Password</label>
          <div class="input-group">
            <input type="password"
              id="confirm_password"
              name="confirm_password"
              required
              class="form-control w-auto"
              placeholder="••••••••">
            <button type="button" class="btn btn-outline-secondary border-start-0 toggle-password" data-target="confirm_password">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>

      </div>

      <button type="submit" class="btn-auth">Register</button>
    </form>


    <div class="auth-footer">
      <p>Already have an account? <a href="<?= base_url('/login') ?>">Sign in</a></p>
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