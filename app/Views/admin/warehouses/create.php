<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-4">
 <div class="card border-0 shadow-sm">
  <div class="card-header bg-shippex-purple text-white">
   <h3 class="mb-0"><i class="fas fa-warehouse me-2"></i>Add Warehouse Address</h3>
  </div>

  <div class="card-body">
   <form action="/warehouse/store" method="post" class="simple-form">
    <div class="row g-3">


     <!-- Country -->
     <div class="col-md-6">
      <label class="form-label text-shippex-purple fw-bold"> Country</label>

      <?php
      $countries = json_decode(file_get_contents(dirname(__DIR__, 3) . '/views/partials/countries.json'), true);
      $selectedCountry = isset($address['country']) && !empty($address['country']) ? $address['country'] : '';
      ?>

      <select id="country" name="country" class="form-control form-control-lg" required>
       <option value="">-- Select Country --</option>
       <?php foreach ($countries as $ct): ?>
        <option value="<?= esc($ct['name']) ?>"
         data-code="<?= esc($ct['code']) ?>"
         <?= $ct['name'] === $selectedCountry ? 'selected' : '' ?>>
         <?= esc($ct['name']) ?>
        </option>
       <?php endforeach; ?>
      </select>

      <!-- Hidden field for country code -->
      <input type="hidden" name="code" id="country_code">
     </div>
     <!-- City -->
     <div class="col-md-6">
      <label class="form-label text-shippex-purple fw-bold"> City</label>
      <input type="text" name="city" class="form-control form-control-lg" required>
     </div>

     <!-- Address Line -->
     <div class="col-12">
      <label class="form-label text-shippex-purple fw-bold">Address Line</label>
      <textarea name="address_line" class="form-control form-control-lg" rows="3" required></textarea>
     </div>

     <!-- postl code -->
     <div class="col-md-6">
      <label class="form-label text-shippex-purple fw-bold">Postal Code</label>
      <input type="text" name="postal_code" class="form-control form-control-lg" required>
     </div>
     <!-- Phone -->
     <div class="col-md-6">
      <label class="form-label text-shippex-purple fw-bold">Phone</label>
      <input type="text" name="phone" class="form-control form-control-lg" required>
     </div>

     <!-- Default Checkbox -->
     <div class="col-12 mt-2">
      <div class="form-check">
       <input type="checkbox" name="is_active" id="isDefault" value="1" class="form-check-input" checked>
       <label class="form-check-label text-shippex-purple fw-bold" for="isDefault">Is Active</label>
      </div>
     </div>

     <!-- Submit Button -->
     <div class="col-12 mt-4">
      <button type="submit" class="btn btn-shippex-orange btn-lg w-100">
       <i class="fas fa-save me-2"></i>Save Warehouse Address
      </button>
     </div>
    </div>
   </form>
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

 .simple-form .form-control-lg {
  padding: 0.75rem 1rem;
  font-size: 1rem;
  border: 1px solid #dee2e6;
  border-radius: 0.375rem;
 }

 .simple-form .form-control-lg:focus {
  border-color: #4E148C;
  box-shadow: 0 0 0 0.25rem rgba(78, 20, 140, 0.25);
 }

 .form-check-input:checked {
  background-color: #FF6600;
  border-color: #FF6600;
 }

 .card {
  border-radius: 0.5rem;
 }
</style>

<script>
 // Simple phone number formatting
 document.querySelector('input[name="phone"]').addEventListener('input', function(e) {
  this.value = this.value.replace(/[^0-9+]/g, '');
 });
</script>
<script>
 document.addEventListener('DOMContentLoaded', function() {
  const countrySelect = document.getElementById('country');
  const countryCodeInput = document.getElementById('country_code');

  function updateCountryCode() {
   const selected = countrySelect.options[countrySelect.selectedIndex];
   countryCodeInput.value = selected.getAttribute('data-code') || '';
  }

  // Update on change
  countrySelect.addEventListener('change', updateCountryCode);

  // Set default if already selected
  updateCountryCode();
 });
</script>
<?= $this->endSection() ?>