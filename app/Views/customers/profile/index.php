<?php
$session = session();
$role = $session->get('role');

// Dynamically pick layout based on role
if ($role === 'admin') {
 $this->extend('admin/layouts/main');
} else {
 $this->extend('customers/layouts/main');
}
?>
<?= $this->section('content') ?>

<div class="container-fluid">
 <div class="row">
  <div class="col-12">
   <!-- Page Header -->
   <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
     <h2 class="h4 fw-bold text-dark mb-1">Account Settings</h2>
     <p class="text-muted small mb-0">Manage your profile and security settings</p>
    </div>

    <!-- Toggle Switch -->
    <div class="toggle-container">
     <input class="toggle-input" type="checkbox" id="toggleMode">
     <label class="toggle-label" for="toggleMode">
      <span class="toggle-text-left">Profile</span>
      <span class="toggle-handle"></span>
      <span class="toggle-text-right">Password</span>
     </label>
    </div>
   </div>

   <!-- CHANGE PASSWORD FORM -->
   <div id="passwordSection" class="settings-section" style="display:none;">
    <div class="card border-0 shadow-sm">
     <div class="card-header py-3 border-bottom">
      <h5 class="mb-0 d-flex align-items-center">
       <span class="icon-wrapper me-2">
        <i class="fas fa-lock"></i>
       </span>
       Change Password
      </h5>
     </div>
     <div class="card-body p-4">



      <form method="post" class="compact-form" action="<?= base_url('/change-password') ?>">
       <div class="row g-3">
        <div class="col-md-4">
         <label for="current_password" class="form-label small fw-semibold">Current Password</label>
         <div class="input-group input-group-sm">
          <span class="input-group-text bg-light border-end-0">
           <i class="fas fa-key"></i>
          </span>
          <input type="password" id="current_password" name="current_password" class="form-control form-control-sm border-start-0" placeholder="Current password" required>
          <button type="button" class="btn btn-outline-secondary border-start-0 toggle-password" data-target="current_password">
           <i class="fas fa-eye"></i>
          </button>
         </div>
        </div>

        <div class="col-md-4">
         <label for="new_password" class="form-label small fw-semibold">New Password</label>
         <div class="input-group input-group-sm">
          <span class="input-group-text bg-light border-end-0">
           <i class="fas fa-key"></i>
          </span>
          <input type="password" id="new_password" name="new_password" class="form-control form-control-sm border-start-0" placeholder="New password" required>
          <button type="button" class="btn btn-outline-secondary border-start-0 toggle-password" data-target="new_password">
           <i class="fas fa-eye"></i>
          </button>
         </div>
        </div>

        <div class="col-md-4">
         <label for="confirm_password" class="form-label small fw-semibold">Confirm Password</label>
         <div class="input-group input-group-sm">
          <span class="input-group-text bg-light border-end-0">
           <i class="fas fa-key"></i>
          </span>
          <input type="password" id="confirm_password" name="confirm_password" class="form-control form-control-sm border-start-0" placeholder="Confirm new password" required>
          <button type="button" class="btn btn-outline-secondary border-start-0 toggle-password" data-target="confirm_password">
           <i class="fas fa-eye"></i>
          </button>
         </div>
        </div>
       </div>

       <div class="mt-4">
        <button class="btn btn-primary btn-sm px-4 py-2">
         <span>Update Password</span>
         <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="ms-2">
          <path d="M20 6L9 17L4 12" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
         </svg>
        </button>
       </div>
      </form>
     </div>
    </div>
   </div>

   <!-- UPDATE PROFILE FORM -->
   <div id="profileSection" class="settings-section">
    <div class="card border-0 shadow-sm">
     <div class="card-header py-3 border-bottom">
      <h5 class="mb-0 d-flex align-items-center">
       <span class="icon-wrapper me-2">
        <i class="fas fa-user"></i>
       </span>
       Personal Details
      </h5>
     </div>
     <div class="card-body p-4">
      <form method="POST" class="compact-form" action="<?= base_url('update-profile') ?>">
       <div class="row g-3">

        <div class="col-md-6">
         <label class="form-label small fw-semibold">First Name</label>
         <input name="firstname" type="text" class="form-control form-control-sm profile-field" placeholder="First Name" value="<?= $profile['firstname'] ?>">
        </div>

        <div class="col-md-6">
         <label class="form-label small fw-semibold">Last Name</label>
         <input name="lastname" type="text" class="form-control form-control-sm profile-field" placeholder="Last Name" value="<?= $profile['lastname'] ?>">
        </div>
        <div class="col-md-6">
         <label class="form-label small fw-semibold">Phone Number</label>
         <input type="text" name='phone_number' class="form-control form-control-sm profile-field" value="<?= $profile['phone_number'] ?>">
        </div>

        <div class="col-md-6">
         <label class="form-label small fw-semibold">Email</label>
         <input type="text" name="email" class="form-control form-control-sm profile-field" value="<?= $profile['email'] ?>">
        </div>
       </div>

       <div class="mt-4">
        <button type="submit" class="btn btn-primary btn-sm px-4 py-2">
         <span>Update Profile</span>
         <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="ms-2">
          <path d="M20 6L9 17L4 12" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
         </svg>
        </button>
       </div>
      </form>
     </div>
    </div>
   </div>
  </div>
 </div>
</div>

<style>
 :root {
  --primary-color: #4d148c;
  --secondary-color: #ff6600;
  --shippex-light: #f0e6ff;
  --shippex-accent: #e74c3c;
  --shippex-light: #ecf0f1;
  --shippex-success: #2ecc71;
 }

 .toggle-password {
  border: 1px solid #e2e8f0;
  border-left: none;
  background-color: white;
  padding: 0.375rem 0.75rem;
  transition: all 0.2s ease;
 }

 .toggle-password:hover {
  background-color: var(--shippex-light);
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

 /* Compact Toggle Switch */
 .toggle-container {
  display: inline-block;
 }

 .toggle-input {
  display: none;
 }

 .toggle-label {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 180px;
  height: 36px;
  background: var(--shippex-light);
  border-radius: 40px;
  padding: 3px;
  cursor: pointer;
  position: relative;
  transition: all 0.3s ease;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
 }

 .toggle-handle {
  position: absolute;
  top: 3px;
  left: 3px;
  width: 84px;
  height: 30px;
  background: var(--primary-color);
  border-radius: 30px;
  transition: all 0.3s ease;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
 }

 .toggle-text-left,
 .toggle-text-right {
  z-index: 1;
  font-weight: 600;
  font-size: 0.8rem;
  padding: 0 12px;
  transition: color 0.3s ease;
 }

 .toggle-text-left {
  color: white;
 }

 .toggle-text-right {
  color: var(--primary-color);
 }

 .toggle-input:checked+.toggle-label .toggle-handle {
  transform: translateX(90px);
 }

 .toggle-input:checked+.toggle-label .toggle-text-left {
  color: var(--primary-color);
 }

 .toggle-input:checked+.toggle-label .toggle-text-right {
  color: white;
 }

 /* Card and Form Styles */

 .compact-form .form-label {
  margin-bottom: 0.25rem;
 }

 .form-control-sm {
  padding: 0.375rem 0.75rem;
  font-size: 0.875rem;
  border-radius: 4px;
  border: 1px solid #e2e8f0;
 }

 .form-control:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 0.1rem rgba(77, 20, 140, 0.1);
 }

 .input-group-sm>.form-control {
  padding: 0.375rem 0.75rem;
 }

 .input-group-text {
  background-color: var(--shippex-light);
  border: 1px solid #e2e8f0;
  padding: 0.375rem 0.75rem;
 }

 /* Button Styles */
 .btn-primary {
  background: linear-gradient(135deg, var(--primary-color), #6a1b9a);
  border: none;
  border-radius: 4px;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  transition: all 0.2s ease;
 }

 .btn-primary:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 8px rgba(77, 20, 140, 0.3);
 }

 .btn-sm {
  padding: 0.375rem 1rem;
  font-size: 0.875rem;
 }

 /* Icon Styles */
 .icon-wrapper {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  border-radius: 6px;
 }

 /* Section Animation */
 .settings-section {
  transition: opacity 0.3s ease, transform 0.3s ease;
 }

 /* Alert Styles */
 .alert {
  border-radius: 4px;
  font-size: 0.875rem;
 }

 /* Responsive adjustments */
 @media (max-width: 768px) {
  .toggle-label {
   width: 160px;
   height: 32px;
  }

  .toggle-handle {
   width: 74px;
   height: 26px;
  }

  .toggle-input:checked+.toggle-label .toggle-handle {
   transform: translateX(80px);
  }

  .toggle-text-left,
  .toggle-text-right {
   font-size: 0.75rem;
   padding: 0 10px;
  }

  .card-body {
   padding: 1rem !important;
  }
 }
</style>

<script>
 document.addEventListener('DOMContentLoaded', function() {
  const toggle = document.getElementById('toggleMode');
  const passwordSection = document.getElementById('passwordSection');
  const profileSection = document.getElementById('profileSection');
  const fields = document.querySelectorAll('.profile-field');

  // Function to switch sections with animation
  function switchSections(showProfile) {
   if (showProfile) {
    // Show profile update mode
    passwordSection.style.opacity = '0';
    passwordSection.style.transform = 'translateX(-10px)';
    setTimeout(() => {
     passwordSection.style.display = 'none';
     profileSection.style.display = 'block';
     setTimeout(() => {
      profileSection.style.opacity = '1';
      profileSection.style.transform = 'translateX(0)';
     }, 50);
    }, 300);

   } else {
    // Show password change mode
    profileSection.style.opacity = '1';
    profileSection.style.transform = 'translateX(10px)';
    setTimeout(() => {
     profileSection.style.display = 'none';
     passwordSection.style.display = 'block';
     setTimeout(() => {
      passwordSection.style.opacity = '1';
      passwordSection.style.transform = 'translateX(0)';
     }, 50);
    }, 300);

   }
  }

  // Initialize sections
  passwordSection.style.opacity = '0';
  passwordSection.style.transform = 'translateX(0)';
  profileSection.style.opacity = '1';
  profileSection.style.transform = 'translateX(10px)';

  // Add transition styles
  passwordSection.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
  profileSection.style.transition = 'opacity 0.3s ease, transform 0.3s ease';

  toggle.addEventListener('change', function() {
   switchSections(!this.checked);
  });
 });
</script>

<script>
 document.addEventListener('DOMContentLoaded', function() {
  const toggleButtons = document.querySelectorAll('.toggle-password');

  toggleButtons.forEach(button => {
   button.addEventListener('click', function() {
    const targetId = this.getAttribute('data-target');
    const passwordInput = document.getElementById(targetId);

    if (passwordInput.type === 'password') {
     passwordInput.type = 'text';
     this.classList.add('active');
     this.querySelector('i').classList.remove('fa-eye');
     this.querySelector('i').classList.add('fa-eye-slash');
    } else {
     passwordInput.type = 'password';
     this.classList.remove('active');
     this.querySelector('i').classList.remove('fa-eye-slash');
     this.querySelector('i').classList.add('fa-eye');
    }

    // Focus back on the input for better UX
    passwordInput.focus();
   });
  });
 });
</script>

<?= $this->endSection() ?>