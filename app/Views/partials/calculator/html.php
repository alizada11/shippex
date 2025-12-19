<?php
$countries = json_decode(
 file_get_contents(dirname(__DIR__) . '/countries.json'),
 true
);

?>
<div class="card shadow border-0 overflow-hidden">
 <!-- Card Header with Gradient Background -->
 <div class="card-header py-3">
  <h3 class="fw-bold mb-0">Get Shipping Quote</h3>
 </div>

 <div class="card-body p-2 p-md-3">
  <form id="shippingForm" class="needs-validation" novalidate>
   <!-- Origin & Destination same as your form -->
   <div class="row">
    <div class="col-lg-6">
     <div class="mb-3">
      <h4 class="fw-bold mb-3 text-purple d-flex align-items-center">
       <span class="material-icons-outlined me-2">location_on</span>
       Origin Address
      </h4>
      <div class="row g-3">
       <div class="col-md-6">
        <input type="text" class="form-control" id="origin_line_1" name="origin_line_1" placeholder="Street Address" required>
        <div class="invalid-feedback">Please provide a street address</div>
       </div>
       <div class="col-md-6">
        <input type="text" class="form-control" id="origin_city" name="origin_city" placeholder="City" required>
        <div class="invalid-feedback">Please provide a city</div>
       </div>
       <div class="col-md-4">
        <input type="text" class="form-control" id="origin_state" name="origin_state" placeholder="State/Province">
       </div>
       <div class="col-md-4">
        <input type="text" class="form-control" id="origin_postal_code" name="origin_postal_code" placeholder="Postal Code">
       </div>
       <div class="col-md-4">
        <select class="form-select" id="origin_country" name="origin_country" required>
         <option value="">--Country--</option>
         <?php foreach ($countries as $ct): ?>
          <option <?= $ct['code'] === 'US' ? 'selected' : '' ?> value="<?= esc($ct['code']) ?>">
           <?= esc($ct['name']) ?>
          </option>
         <?php endforeach; ?>
        </select>
        <div class="invalid-feedback">Please select a country</div>
       </div>
      </div>
     </div>
    </div>

    <div class="col-lg-6">
     <div class="mb-3">
      <h4 class="fw-bold mb-3 text-purple d-flex align-items-center">
       <span class="material-icons-outlined me-2">place</span>
       Destination Address
      </h4>
      <div class="row g-3">
       <div class="col-md-6">
        <input type="text" class="form-control" id="dest_line_1" name="dest_line_1" placeholder="Street Address" required>
        <div class="invalid-feedback">Please provide a street address</div>
       </div>
       <div class="col-md-6">
        <input type="text" class="form-control" id="dest_city" name="dest_city" placeholder="City" required>
        <div class="invalid-feedback">Please provide a city</div>
       </div>
       <div class="col-md-4">
        <input type="text" class="form-control" id="dest_state" name="dest_state" placeholder="State/Province">
       </div>
       <div class="col-md-4">
        <input type="text" class="form-control" id="dest_postal_code" name="dest_postal_code" placeholder="Postal Code">
       </div>
       <div class="col-md-4">
        <select class="form-select" id="dest_country" name="dest_country" required>
         <option value="">--Country--</option>
         <?php foreach ($countries as $ct): ?>
          <option value="<?= esc($ct['code']) ?>"><?= esc($ct['name']) ?></option>
         <?php endforeach; ?>
        </select>
        <div class="invalid-feedback">Please select a country</div>
       </div>
      </div>
     </div>
    </div>
   </div>

   <!-- Parcel Details -->
   <div class="mb-4">
    <h4 class="fw-bold mb-4 text-purple d-flex align-items-center">
     <span class="material-icons-outlined me-2">inventory</span>
     Parcel Details
    </h4>
    <div class="row g-3 align-items-end">
     <div class="col-md-3">
      <label class="input-group-label" for="category_slug">Category</label>
      <select class="form-select" required name="category" id="category_slug">
       <option value="" selected>--select category--</option>
       <?php foreach ($categories as $cat): ?>
        <option data-hs-code="<?= esc($cat['hs_code']) ?>" value="<?= esc($cat['slug']) ?>">
         <?= esc($cat['name']) ?>
        </option>
       <?php endforeach; ?>
      </select>
      <div class="invalid-feedback">Please select category</div>
     </div>

     <input type="hidden" name="hs_code" id="hs_code_input">

     <div class="col-md-2">
      <label class="input-group-label" for="weight">Weight</label>
      <div class="input-group">
       <input type="number" class="form-control" id="weight" name="weight" placeholder="0.00" step="0.01" required>
       <span class="input-group-text">kg</span>
       <div class="invalid-feedback">Please enter weight</div>
      </div>
     </div>

     <div class="col-md-2">
      <label class="input-group-label" for="length">Length</label>
      <div class="input-group">
       <input type="number" class="form-control" id="length" name="length" placeholder="0" required>
       <span class="input-group-text">cm</span>
       <div class="invalid-feedback">Please enter length</div>
      </div>
     </div>

     <div class="col-md-2">
      <label class="input-group-label" for="width">Width</label>
      <div class="input-group">
       <input type="number" class="form-control" id="width" name="width" placeholder="0" required>
       <span class="input-group-text">cm</span>
       <div class="invalid-feedback">Please enter width</div>
      </div>
     </div>

     <div class="col-md-2">
      <label class="input-group-label" for="height">Height</label>
      <div class="input-group">
       <input type="number" class="form-control" id="height" name="height" placeholder="0" required>
       <span class="input-group-text">cm</span>
       <div class="invalid-feedback">Please enter height</div>
      </div>
     </div>
    </div>
   </div>

   <!-- Additional Easyship Fields -->
   <div class="mb-4">
    <h4 class="fw-bold mb-3 text-purple d-flex align-items-center">
     <span class="material-icons-outlined me-2">settings</span>
     Shipping Options
    </h4>

    <div class="row g-3">
     <div class="col-md-3">
      <label class="input-group-label" for="declared_customs_value">Declared Customs Value (unit)</label>
      <div class="input-group">
       <input type="number" class="form-control" id="declared_customs_value" name="declared_customs_value" placeholder="0.00" step="0.01" required>
       <select class="form-select" id="declared_currency" name="declared_currency" style="max-width:110px;">
        <option value="USD" selected>USD</option>
        <option value="EUR">EUR</option>
        <option value="AED">AED</option>
        <!-- add other currencies as needed -->
       </select>
      </div>
      <div class="invalid-feedback">Please enter declared customs value</div>
     </div>

     <div class="col-md-3">
      <label class="input-group-label" for="is_insured">Insurance</label>
      <select class="form-select" id="is_insured" name="is_insured" required>
       <option value="">--Select--</option>
       <option value="true">Yes</option>
       <option value="false">No</option>
      </select>
      <div class="invalid-feedback">Please select insurance</div>
     </div>

     <div class="col-md-2">
      <label class="input-group-label" for="insured_amount">Insurance Amount (optional)</label>
      <input type="number" class="form-control" id="insured_amount" name="insured_amount" placeholder="0.00" step="0.01">
     </div>

     <div class="col-md-2">
      <label class="input-group-label" for="incoterms">Taxes & Duties (Incoterms)</label>
      <select class="form-select" id="incoterms" name="incoterms" required>
       <option value="">--Select--</option>
       <option value="DDU" selected>DDU (Receiver pays)</option>
       <option value="DDP">DDP (Sender pays)</option>
      </select>
      <div class="invalid-feedback">Please select incoterm</div>
     </div>

     <div class="col-md-2">
      <label class="input-group-label" for="set_as_residential">Set as Residential?</label>
      <select class="form-select" id="set_as_residential" name="set_as_residential" required>
       <option value="">--Select--</option>
       <option value="true">Yes</option>
       <option value="false" selected>No</option>
      </select>
      <div class="invalid-feedback">Please select residential option</div>
     </div>
    </div>
   </div>

   <!-- Submit Button -->
   <div class="d-grid mt-4">
    <button type="submit" class="btn bg-shippex-purple text-white py-3">
     <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
     Calculate Shipping Rates
    </button>
   </div>
  </form>


 </div>
</div>
<!-- Results Section -->
<div class="container" style="position: relative; padding-bottom:3rem;">
 <div id="ratesResult" class="mt-3">


  <div class="d-none" id="errorContainer">
   <div class="alert alert-danger" role="alert">
    <h5 class="alert-heading">Unable to Calculate Rates</h5>
    <p id="errorMessage"></p>

   </div>
  </div>

  <!-- Loader for booking -->
  <div id="bookingLoader" class="d-none text-center my-3 " style="position: absolute; top:0; left:50%; z-index:99999; transform:translateX(-50%)">
   <div class="spinner-border text-info" role="status">
    <span class="visually-hidden">Loading...</span>
   </div>
   <p class="mt-2">Processing your request...</p>
  </div>
 </div>
 <div id="rateContainer" class="mt-3"></div>
</div>