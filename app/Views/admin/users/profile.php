<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<form method="POST" action="<?= base_url('change-password') ?>">
 <div class="col-12">
  <div class="card card-my h-100">
   <h2 class="card-header">Personal Details</h2>
   <div class="card-body">
    <p class="text-center mb-4">You can change your password when you need to.</p>

    <div class="form-group row mb-2">
     <label class="col-sm-4 col-md-3 col-form-label">Customer ID</label>
     <div class="col-sm-8 col-md-9">
      <input type="text" class="form-control" disabled="disabled" value="<?= $profile['id'] ?>">
     </div>
    </div>

    <div class="form-group row mb-2">
     <label class="col-sm-4 col-md-3 col-form-label">Email</label>
     <div class="col-sm-8 col-md-9">
      <input type="text" class="form-control" disabled="disabled" value="<?= $profile['email'] ?>">
     </div>
    </div>

    <div class="form-group row mb-2">
     <label class="col-sm-4 col-md-3 col-form-label">First Name</label>
     <div class="col-sm-8 col-md-9">
      <input data-validation="required,length" data-validation-length="2-35" name="firstname" type="text" class="form-control" placeholder="First Name" disabled="disabled" value="<?= $profile['firstname'] ?>">
     </div>
    </div>

    <div class="form-group row mb-2">
     <label class="col-sm-4 col-md-3 col-form-label">Last Name</label>
     <div class="col-sm-8 col-md-9">
      <input data-validation="required,length" data-validation-length="2-35" name="lastname" type="text" class="form-control" placeholder="Last Name" disabled="disabled" value="<?= $profile['lastname'] ?>">
     </div>
    </div>

    <div class="form-group row mb-2">
     <label class="col-sm-4 col-md-3 col-form-label">Current Password</label>
     <div class="col-sm-8 col-md-9">
      <input data-validation="strength" data-validation-optional="true" data-validation-strength="1" name="current_password" type="password" class="form-control" placeholder="Current Password">
     </div>
    </div>
    <div class="form-group row mb-2">
     <label class="col-sm-4 col-md-3 col-form-label">New Password</label>
     <div class="col-sm-8 col-md-9">
      <input data-validation="strength" data-validation-optional="true" data-validation-strength="1" name="new_password" type="password" class="form-control" placeholder="New Password">
     </div>
    </div>
    <div class="form-group row mb-2">
     <label class="col-sm-4 col-md-3 col-form-label">Confirm Password</label>
     <div class="col-sm-8 col-md-9">
      <input data-validation="strength" data-validation-optional="true" data-validation-strength="1" name="confirm_password" type="password" class="form-control" placeholder="Confirm Password">
     </div>
    </div>


    <div class="row justify-content-between">

     <div class="col-lg-4 col-xl-3 my-3 text-right">
      <button type="submit" data-submit-button="" class="btn btn-block btn-primary text-uppercase" id="profile-form-submit">Update Profile</button>
     </div>
    </div>
   </div>
  </div>
 </div>
 <?= $this->endSection() ?>