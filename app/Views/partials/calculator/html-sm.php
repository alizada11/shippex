<?php
$countries = json_decode(
 file_get_contents(dirname(__DIR__) . '/countries.json'),
 true
);
?>

<?php
$countries = json_decode(
 file_get_contents(dirname(__DIR__) . '/countries.json'),
 true
);
?>
<div class="card border-0 shadow-sm" style="border-radius: 12px;">
 <div class="card-header py-3 bg-white border-bottom">
  <h5 class="fw-bold mb-0 text-dark d-flex align-items-center">
   <span class="material-icons-outlined text-primary fs-6 me-2">local_shipping</span>
   Shipping Quote Calculator
  </h5>
 </div>

 <div class="card-body p-3">
  <form id="shippingForm" class="needs-validation" novalidate>
   <!-- ORIGIN & DESTINATION -->
   <div class="row g-3">
    <div class="col-lg-12">
     <h6 class="fw-semibold mb-2 text-secondary d-flex align-items-center">
      <span class="material-icons-outlined fs-6 me-1">location_on</span>
      Origin Address
     </h6>
     <div class="row ">
      <div class="col-6 mb-2">
       <input type="text" class="form-control " id="origin_line_1" name="origin_line_1" placeholder="Street Address" required>
       <div class="invalid-feedback">Street address required</div>
      </div>
      <div class="col-6 mb-2">
       <input type="text" class="form-control " id="origin_city" name="origin_city" placeholder="City" required>
       <div class="invalid-feedback">City required</div>
      </div>
      <div class="col-6 mb-2">
       <input type="text" class="form-control " id="origin_state" name="origin_state" placeholder="State">
      </div>
      <div class="col-6 mb-2">
       <input type="text" class="form-control " id="origin_postal_code" name="origin_postal_code" placeholder="ZIP">
      </div>
      <div class="col-12">
       <select class="form-select " id="origin_country" name="origin_country" required>
        <option value="">Country</option>
        <?php foreach ($countries as $ct): ?>
         <option <?= $ct['code'] === 'US' ? 'selected' : '' ?> value="<?= esc($ct['code']) ?>">
          <?= esc($ct['name']) ?>
         </option>
        <?php endforeach; ?>
       </select>
       <div class="invalid-feedback">Country required</div>
      </div>
     </div>
    </div>

    <div class="col-lg-12">
     <h6 class="fw-semibold mb-2 text-secondary d-flex align-items-center">
      <span class="material-icons-outlined fs-6 me-1">place</span>
      Destination Address
     </h6>
     <div class="row ">
      <div class="col-6 mb-3">
       <input type="text" class="form-control " id="dest_line_1" name="dest_line_1" placeholder="Street Address" required>
       <div class="invalid-feedback">Street address required</div>
      </div>
      <div class="col-6 mb-2">
       <input type="text" class="form-control " id="dest_city" name="dest_city" placeholder="City" required>
       <div class="invalid-feedback">City required</div>
      </div>
      <div class="col-6 mb-2">
       <input type="text" class="form-control " id="dest_state" name="dest_state" placeholder="State">
      </div>
      <div class="col-6 mb-2">
       <input type="text" class="form-control " id="dest_postal_code" name="dest_postal_code" placeholder="ZIP">
      </div>
      <div class="col-12">
       <select class="form-select" id="dest_country" name="dest_country" required>
        <option value="">Country</option>
        <?php foreach ($countries as $ct): ?>
         <option value="<?= esc($ct['code']) ?>"><?= esc($ct['name']) ?></option>
        <?php endforeach; ?>
       </select>
       <div class="invalid-feedback">Country required</div>
      </div>
     </div>
    </div>
   </div>

   <!-- PARCEL DETAILS -->
   <div class="mb-3 mt-3">
    <h6 class="fw-semibold mb-2 text-secondary d-flex align-items-center">
     <span class="material-icons-outlined fs-6 me-1">inventory</span>
     Parcel Details
    </h6>
    <div class="row align-items-end">
     <div class="col-lg-6 mb-3">
      <select class="form-select form-select-sm" required name="category" id="category_slug">
       <option value="">Category</option>
       <?php foreach ($categories as $cat): ?>
        <option data-hs-code="<?= esc($cat['hs_code']) ?>" value="<?= esc($cat['slug']) ?>">
         <?= esc($cat['name']) ?>
        </option>
       <?php endforeach; ?>
      </select>
     </div>
     <input type="hidden" name="hs_code" id="hs_code_input">
     <div class="col-lg-6 mb-3">
      <div class="input-group input-group-sm">
       <input type="number" class="form-control" id="weight" name="weight" placeholder="W" step="0.01" required>
       <span class="input-group-text">kg</span>
      </div>
     </div>
     <div class="col">
      <div class="input-group input-group-sm">
       <input type="number" class="form-control" id="length" name="length" placeholder="L" required>
       <span class="input-group-text">cm</span>
      </div>
     </div>
     <div class="col">
      <div class="input-group input-group-sm">
       <input type="number" class="form-control" id="width" name="width" placeholder="W" required>
       <span class="input-group-text">cm</span>
      </div>
     </div>
     <div class="col">
      <div class="input-group input-group-sm">
       <input type="number" class="form-control" id="height" name="height" placeholder="H" required>
       <span class="input-group-text">cm</span>
      </div>
     </div>
    </div>
   </div>

   <!-- SHIPPING OPTIONS -->
   <div class="mb-3">
    <h6 class="fw-semibold mb-2 text-secondary d-flex align-items-center">
     <span class="material-icons-outlined fs-6 me-1">tune</span>
     Options
    </h6>
    <div class="row ">
     <div class="col-lg-6 mb-3">
      <div class="input-group input-group-sm">
       <input type="number" class="form-control" id="declared_customs_value" name="declared_customs_value" placeholder="Value" step="0.01" required>
       <select class="form-select" id="declared_currency" name="declared_currency" style="max-width: 90px;">
        <option value="USD" selected>USD</option>
        <option value="EUR">EUR</option>
       </select>
      </div>
     </div>
     <div class="col-lg-6 mb-3">
      <select class="form-select form-select-sm" name="incoterms" id="incoterms" required>
       <option value="">Taxes & Duties</option>
       <option value="DDU" selected>DDU (Receiver pays)</option>
       <option value="DDP">DDP (Sender pays)</option>
      </select>
     </div>
     <div class="col">
      <select class="form-select form-select-sm" id="is_insured" name="is_insured">
       <option value="">Insurance?</option>
       <option value="true">Yes</option>
       <option value="false" selected>No</option>
      </select>
     </div>
     <div class="col">
      <input type="number" class="form-control form-control-sm" id="insured_amount" name="insured_amount" placeholder="Insurance Amount" step="0.01">
     </div>
     <div class="col">
      <select class="form-select form-select-sm" id="set_as_residential" name="set_as_residential">
       <option value="">Residential?</option>
       <option value="true">Yes</option>
       <option value="false" selected>No</option>
      </select>
     </div>
    </div>
   </div>

   <!-- SUBMIT -->
   <div class="d-grid mt-3">
    <button type="submit" class="btn btn-primary btn-sm py-2">
     <span class="spinner-border spinner-border-sm d-none me-1"></span>
     Calculate Rates
    </button>
   </div>
  </form>
 </div>
</div>

<!-- Results Container -->
<div class="container" style="position: relative;">
 <div id="ratesResult" class="mt-3"></div>
 <div id="bookingLoader" class="d-none">
  <div class="position-fixed top-0 left-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(255,255,255,0.9); z-index:99999;">
   <div class="text-center">
    <div class="spinner-border spinner-border-sm text-primary"></div>
    <p class="mt-2 small text-muted">Calculating...</p>
   </div>
  </div>
 </div>
</div>


<style>
 /* Minimal custom styles */
 .form-control-sm,
 .form-select-sm {
  font-size: 0.875rem;
  padding: 0.25rem 0.5rem;
  height: 32px;
  border-radius: 6px;
 }

 .form-check-input {
  width: 2.5em;
  height: 1.25em;
 }

 .form-check-input:checked {
  background-color: #0d6efd;
  border-color: #0d6efd;
 }

 .btn-sm {
  padding: 0.375rem 0.75rem;
  font-size: 0.875rem;
  border-radius: 6px;
 }

 .form-switch .form-check-input:focus {
  width: 3em;
 }

 .card {
  font-size: 0.875rem;
 }

 h6 {
  font-size: 0.875rem;
 }

 .small {
  font-size: 0.75rem;
 }

 /* Compact spacing */
 .mb-1 {
  margin-bottom: 0.25rem !important;
 }

 .mb-2 {
  margin-bottom: 0.5rem !important;
 }

 .mb-3 {
  margin-bottom: 1rem !important;
 }

 .g-2 {
  gap: 0.5rem !important;
 }

 .p-1 {
  padding: 0.25rem !important;
 }

 .p-2 {
  padding: 0.5rem !important;
 }

 .p-3 {
  padding: 1rem !important;
 }
</style>

<script>
 // Toggle insurance amount visibility
 document.getElementById('insurance_toggle').addEventListener('change', function() {
  const container = document.getElementById('insurance_amount_container');
  const amountField = document.getElementById('insured_amount');

  if (this.checked) {
   container.style.display = 'block';
   this.value = '1';
   amountField.disabled = false;
   amountField.focus();
  } else {
   container.style.display = 'none';
   this.value = '0';
   amountField.value = '';
   amountField.disabled = true;
  }
 });

 // Update residential value
 document.getElementById('residential_toggle').addEventListener('change', function() {
  this.value = this.checked ? '1' : '0';
 });
</script>