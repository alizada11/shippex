<?= $this->extend('customers/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container py-4">
 <div class="card border-0 shadow-sm">
  <div class="card-header bg-shippex-purple text-white">
   <h3 class="mb-0">
    <i class="fas fa-<?= isset($address) ? 'edit' : 'plus-circle' ?> me-2"></i>
    <?= isset($address) ? 'Edit Address' : 'Add New Address' ?>
   </h3>
  </div>

  <div class="card-body">
   <?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
     <i class="fas fa-exclamation-triangle me-2"></i>
     <h5 class="alert-heading">Please fix these errors:</h5>
     <ul class="mb-0">
      <?php foreach (session()->getFlashdata('errors') as $error): ?>
       <li><?= esc($error) ?></li>
      <?php endforeach; ?>
     </ul>
     <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
   <?php endif; ?>

   <?= form_open(isset($address) ? "/addresses/update/{$address['id']}" : '/addresses/store', ['class' => 'needs-validation', 'novalidate' => '']) ?>

   <div class="row mb-4">
    <div class="col-md-6">
     <div class="form-floating mb-3">
      <select name="type" class="form-select" id="addressType" required>
       <option value="">Select address type</option>
       <option value="shipping" <?= (isset($address) && $address['type'] == 'shipping') ? 'selected' : '' ?>>Shipping Address</option>
       <option value="billing" <?= (isset($address) && $address['type'] == 'billing') ? 'selected' : '' ?>>Billing Address</option>
      </select>
      <label for="addressType">Address Type <span class="text-danger">*</span></label>
      <div class="invalid-feedback">Please select an address type</div>
     </div>
    </div>
   </div>

   <h5 class="text-shippex-purple mb-3"><i class="fas fa-user me-2"></i>Contact Information</h5>
   <div class="row g-3 mb-4">
    <div class="col-md-6">
     <div class="form-floating">
      <input type="text" name="first_name" class="form-control" id="firstName"
       value="<?= isset($address) ? esc($address['first_name']) : '' ?>"
       placeholder="First Name" required maxlength="50">
      <label for="firstName">First Name <span class="text-danger">*</span></label>
      <div class="invalid-feedback">Please provide a first name</div>
     </div>
    </div>
    <div class="col-md-6">
     <div class="form-floating">
      <input type="text" name="last_name" class="form-control" id="lastName"
       value="<?= isset($address) ? esc($address['last_name']) : '' ?>"
       placeholder="Last Name" required maxlength="50">
      <label for="lastName">Last Name <span class="text-danger">*</span></label>
      <div class="invalid-feedback">Please provide a last name</div>
     </div>
    </div>
   </div>

   <h5 class="text-shippex-purple mb-3"><i class="fas fa-map-marked-alt me-2"></i>Address Details</h5>
   <div class="row g-3 mb-4">
    <div class="col-12">
     <div class="form-floating">
      <input type="text" name="street_address1" class="form-control" id="streetAddress1"
       value="<?= isset($address) ? esc($address['street_address1']) : '' ?>"
       placeholder="Street Address 1" required maxlength="150">
      <label for="streetAddress1">Street Address 1 <span class="text-danger">*</span></label>
      <div class="invalid-feedback">Please provide a street address</div>
     </div>
    </div>
    <div class="col-12">
     <div class="form-floating">
      <input type="text" name="street_address2" class="form-control" id="streetAddress2"
       value="<?= isset($address) ? esc($address['street_address2']) : '' ?>"
       placeholder="Street Address 2" maxlength="150">
      <label for="streetAddress2">Street Address 2 (Optional)</label>
     </div>
    </div>
    <div class="col-md-4">
     <div class="form-floating">
      <input type="text" name="city" class="form-control" id="city"
       value="<?= isset($address) ? esc($address['city']) : '' ?>"
       placeholder="City" required maxlength="50">
      <label for="city">City <span class="text-danger">*</span></label>
      <div class="invalid-feedback">Please provide a city</div>
     </div>
    </div>
    <div class="col-md-4">
     <div class="form-floating">
      <input type="text" name="state" class="form-control" id="state"
       value="<?= isset($address) ? esc($address['state']) : '' ?>"
       placeholder="State" maxlength="50">
      <label for="state">State/Province</label>
     </div>
    </div>
    <div class="col-md-4">
     <div class="form-floating">
      <input type="text" name="zip_code" class="form-control" id="zipCode"
       value="<?= isset($address) ? esc($address['zip_code']) : '' ?>"
       placeholder="Zip Code" maxlength="20">
      <label for="zipCode">Zip/Postal Code</label>
     </div>
    </div>
    <div class="col-12">
     <div class="form-floating">
      <?php
      // Load countries list
      $countries = json_decode(file_get_contents(dirname(__DIR__, 3) . '/views/partials/countries.json'), true);

      // Determine selected country (if available in $address)
      $selectedCountry = isset($address['country']) && !empty($address['country']) ? $address['country'] : '';
      ?>

      <select id="country" name="country" class="form-select" required maxlength="50">
       <option value="">-- Select Country --</option>
       <?php foreach ($countries as $ct): ?>
        <option value="<?= esc($ct['name']) ?>"
         <?= $ct['name'] === $selectedCountry ? 'selected' : '' ?>>
         <?= esc($ct['name']) ?>
        </option>
       <?php endforeach; ?>
      </select>

      <label for="country">Country <span class="text-danger">*</span></label>
      <div class="invalid-feedback">Please provide a country</div>
     </div>
    </div>

   </div>

   <h5 class="text-shippex-purple mb-3"><i class="fas fa-phone-alt me-2"></i>Contact Numbers</h5>
   <div class="row g-3 mb-4">
    <div class="col-md-6">
     <div class="form-floating">
      <input type="tel" name="phone_primary" class="form-control" id="primaryPhone"
       value="<?= isset($address) ? esc($address['phone_primary']) : '' ?>"
       placeholder="Primary Phone" required maxlength="25">
      <label for="primaryPhone">Primary Phone <span class="text-danger">*</span></label>
      <div class="invalid-feedback">Please provide a primary phone number</div>
     </div>
    </div>
    <div class="col-md-6">
     <div class="form-floating">
      <input type="tel" name="phone_secondary" class="form-control" id="secondaryPhone"
       value="<?= isset($address) ? esc($address['phone_secondary']) : '' ?>"
       placeholder="Secondary Phone" maxlength="25">
      <label for="secondaryPhone">Secondary Phone (Optional)</label>
     </div>
    </div>
   </div>

   <div class="form-floating mb-4">
    <input type="text" name="tax_id" class="form-control" id="taxId"
     value="<?= isset($address) ? esc($address['tax_id']) : '' ?>"
     placeholder="Tax ID" maxlength="30">
    <label for="taxId">Tax ID (Optional)</label>
   </div>

   <div class="form-check form-switch mb-4">
    <input class="form-check-input" type="checkbox" name="show_shipping_price"
     id="showShippingPrice" value="1" <?= isset($address) && $address['show_shipping_price'] ? 'checked' : '' ?>>
    <label class="form-check-label" for="showShippingPrice">Show Shipping Price</label>
   </div>

   <div class="d-flex justify-content-between">
    <a href="/addresses" class="btn btn-outline-shippex-purple">
     <i class="fas fa-arrow-left me-2"></i>Cancel
    </a>
    <button type="submit" class="btn btn-shippex-orange">
     <i class="fas fa-<?= isset($address) ? 'save' : 'plus-circle' ?> me-2"></i>
     <?= isset($address) ? 'Update Address' : 'Add Address' ?>
    </button>
   </div>

   <?= form_close() ?>
  </div>
 </div>
</div>

<style>
 .bg-shippex-purple {
  background-color: #4E148C !important;
 }

 .text-shippex-purple {
  color: #4E148C !important;
 }

 .btn-shippex-orange {
  background-color: #FF6600;
  color: white;
  border-color: #FF6600;
 }

 .btn-shippex-orange:hover {
  background-color: #e05c00;
  border-color: #e05c00;
  color: white;
 }

 .btn-outline-shippex-purple {
  color: #4E148C;
  border-color: #4E148C;
 }

 .btn-outline-shippex-purple:hover {
  background-color: #4E148C;
  color: white;
 }

 .form-check-input:checked {
  background-color: #FF6600;
  border-color: #FF6600;
 }

 .form-floating label {
  padding-left: 2.5rem;
 }

 .form-floating .form-control {
  padding-left: 2.5rem;
 }

 .form-floating>.form-control:focus~label,
 .form-floating>.form-control:not(:placeholder-shown)~label,
 .form-floating>.form-select~label {
  transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
 }
</style>

<script>
 // Client-side validation
 (function() {
  'use strict';
  window.addEventListener('load', function() {
   var forms = document.getElementsByClassName('needs-validation');
   var validation = Array.prototype.filter.call(forms, function(form) {
    form.addEventListener('submit', function(event) {
     if (form.checkValidity() === false) {
      event.preventDefault();
      event.stopPropagation();
     }
     form.classList.add('was-validated');
    }, false);
   });
  }, false);
 })();

 // Phone number formatting
 document.getElementById('primaryPhone').addEventListener('input', function(e) {
  this.value = this.value.replace(/[^0-9+]/g, '');
 });
 document.getElementById('secondaryPhone').addEventListener('input', function(e) {
  this.value = this.value.replace(/[^0-9+]/g, '');
 });
</script>

<?= $this->endSection() ?>