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

<div class="premium-admin-container p-0">
 <div class="premium-header">
  <div class="container">
   <div class="d-flex justify-content-between align-items-center">
    <div class="page-title-section">
     <h1 class="page-title"><i class="fas fa-plus-circle me-2"></i>Add Package</h1>
     <p class="page-subtitle">Add a new package and its items/files</p>
    </div>
   </div>
  </div>
 </div>

 <div class="container py-4">
  <form action="<?= base_url('packages/store') ?>" method="post" enctype="multipart/form-data">
   <?= csrf_field() ?>

   <!-- Package Information -->
   <div class="premium-card mb-4">
    <div class="card-header">
     <h3 class="card-title"><i class="fas fa-info-circle me-2"></i>Package Information</h3>
    </div>
    <div class="card-body">
     <div class="row">
      <div class="col-md-6">
       <label class="form-label fw-semibold">Retailer <span class="text-danger">*</span></label>
       <input type="text" name="retailer" class="form-control" required>


       <label class="form-label fw-semibold mt-3">Value ($) <span class="text-danger">*</span></label>
       <input type="number" step="0.01" name="value" class="form-control">
       <label class="form-label fw-semibold mt-3">Status</label>
       <select name="status" class="form-select">
        <option value="incoming">Incoming</option>
        <option value="ready">Ready</option>
        <option value="shipped">Shipped</option>
        <option value="returned">Returned</option>
       </select>
       <label for="userSelect" class="form-label fw-semibold mt-3">Choose a user:</label>
       <select class="form-control" id="userSelect" name="user_id" style="width: 100%;">
        <option value="">Select a user</option>
        <?php foreach ($users as $user): ?>
         <option value="<?= esc($user['id']) ?>">
          <?= esc($user['firstname'] . ' ' . $user['lastname']) . ' | ' . $user['id'] ?>
         </option>
        <?php endforeach; ?>
       </select>
       <div id="userPreview" class="mt-2 text-muted">Selected user will appear here</div>

      </div>

      <div class="col-md-6">
       <label class="form-label fw-semibold mt-3">Select Warehouse</label>
       <select name="warehouse_id" class="form-select">
        <?php foreach ($warehouses as $wh): ?>
         <option value="<?= $wh['id']; ?>"><?= $wh['city'] . ', ' . $wh['country'] . ' ' . $wh['postal_code'] ?></option>

        <?php endforeach; ?>
       </select>

       <label class="form-label fw-semibold">Weight (Kg)</label>
       <input type="number" step="0.01" name="weight" class="form-control">

       <label class="form-label fw-semibold mt-3">Dimensions (L × W × H)</label>
       <div class="d-flex gap-2">
        <input type="number" name="length" class="form-control" placeholder="Length">
        <input type="number" name="width" class="form-control" placeholder="Width">
        <input type="number" name="height" class="form-control" placeholder="Height">
       </div>


      </div>
     </div>
    </div>
   </div>

   <!-- Package Items -->
   <!-- <div class="premium-card mb-4">
    <div class="card-header">
     <h3 class="card-title"><i class="fas fa-boxes me-2"></i>Package Items</h3>
    </div>
    <div class="card-body">
     <div id="itemsContainer">
      <div class="item-row mb-3 border p-3 rounded">
       <div class="row g-2">
        <div class="col-md-2">
         <input type="number" name="items[0][quantity]" class="form-control" placeholder="Qty" required>
        </div>
        <div class="col-md-2">
         <input type="text" name="items[0][description]" class="form-control" placeholder="Description">
        </div>
        <div class="col-md-2">
         <input type="text" name="items[0][hs_code]" class="form-control" placeholder="HS Code">
        </div>
        <div class="col-md-2">
         <input type="number" step="0.01" name="items[0][weight]" class="form-control" placeholder="Weight">
        </div>
        <div class="col-md-2">
         <input type="number" step="0.01" name="items[0][value]" class="form-control" placeholder="Value">
        </div>
        <div class="col-md-2 text-end">
         <button type="button" class="btn btn-danger btn-sm remove-item"><i class="fas fa-trash"></i></button>
        </div>
       </div>
      </div>
     </div>
     <button type="button" id="addItemBtn" class="btn btn-outline-shippex-purple mt-2">
      <i class="fas fa-plus me-2"></i>Add Item
     </button>
    </div>
   </div> -->

   <!-- Package Files -->
   <div class="premium-card mb-4">
    <div class="card-header">
     <h3 class="card-title"><i class="fas fa-paperclip me-2"></i>Attach Files</h3>
    </div>
    <div class="card-body">
     <div id="filesContainer">
      <div class="file-row mb-3">
       <div class="row g-2">
        <div class="col-md-4">
         <select name="files[0][file_type]" class="form-select">
          <option value="invoice">Invoice</option>
          <option value="photo">Photo</option>
          <option value="label">Label</option>
          <option value="other">Other</option>
         </select>
        </div>
        <div class="col-md-6">
         <input type="file" name="files[0][file]" class="form-control" required>
        </div>
        <div class="col-md-2 text-end">
         <button type="button" class="btn btn-danger btn-sm remove-file"><i class="fas fa-trash"></i></button>
        </div>
       </div>
      </div>
     </div>
     <button type="button" id="addFileBtn" class="btn btn-outline-shippex-purple mt-2">
      <i class="fas fa-plus me-2"></i>Add File
     </button>
    </div>
   </div>

   <div class="d-flex justify-content-end">
    <a href="<?= base_url('packages') ?>" class="btn btn-outline-secondary me-2">Cancel</a>
    <button type="submit" class="btn btn-shippex"><i class="fas fa-save me-2"></i>Save Package</button>
   </div>
  </form>
 </div>
</div>

<style>
 /* Premium Admin Styling - Building on existing auth_style.css */
 :root {
  --border-radius: 8px;
  --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  --transition: all 0.3s ease;
 }

 .premium-admin-container {
  background-color: var(--shippex-light);
  min-height: 100vh;
 }

 /* Header Styling */
 .premium-header {
  background: linear-gradient(135deg, var(--primary-color) 0%, #3a0d6b 100%);
  color: white;
  padding: 2rem 0;
  margin-bottom: 2rem;
  border-radius: 10px;
 }

 .page-title {
  font-size: 1.8rem;
  font-weight: 700;
  margin-bottom: 0.25rem;
 }

 .page-subtitle {
  opacity: 0.85;
  margin-bottom: 0;
 }

 /* Main Card */
 .premium-card {
  background: white;
  border-radius: var(--border-radius);
  box-shadow: var(--box-shadow);
  overflow: hidden;
  margin-bottom: 1.5rem;
 }

 .card-header {
  background-color: var(--primary-color);
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  padding: 1.25rem 1.5rem;
 }

 .card-title {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 0;
  color: white;
 }

 .card-body {
  padding: 1.5rem;
 }

 /* Form Styling */
 .form-label {
  color: var(--primary-color);
  margin-bottom: 0.5rem;
 }

 .form-control,
 .form-select {
  border-radius: 6px;
  border: 1px solid #dee2e6;
  transition: var(--transition);
 }

 .form-control:focus,
 .form-select:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 0.2rem rgba(77, 20, 140, 0.25);
 }

 .bg-shippex-purple {
  background-color: var(--primary-color) !important;
 }

 .bg-shippex-light {
  background-color: var(--shippex-light) !important;
  color: var(--primary-color) !important;
  border: 1px solid var(--primary-color);
 }

 .bg-shippex-light:hover {
  background-color: var(--primary-color) !important;
  color: white !important;
 }

 /* Table Styling */
 .table {
  margin-bottom: 0;
 }

 .table thead th {
  border-top: none;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.85rem;
  letter-spacing: 0.5px;
  color: var(--primary-color);
  padding: 1rem 0.75rem;
  background-color: rgba(77, 20, 140, 0.05);
 }

 .table tbody td {
  padding: 1rem 0.75rem;
  vertical-align: middle;
  border-color: rgba(77, 20, 140, 0.1);
 }

 .table-hover tbody tr:hover {
  background-color: rgba(77, 20, 140, 0.03);
 }

 .table tfoot {
  background-color: rgba(77, 20, 140, 0.05);
 }

 /* Item Description */
 .item-description {
  line-height: 1.3;
 }

 .quantity-badge {
  display: inline-block;
  padding: 0.25rem 0.5rem;
  background-color: rgba(77, 20, 140, 0.1);
  color: var(--primary-color);
  border-radius: 4px;
  font-weight: 600;
 }

 .value-amount {
  font-weight: 600;
  color: var(--shippex-success);
 }

 .total-amount {
  color: var(--primary-color);
 }

 .hs-code {
  font-family: monospace;
  background-color: #f8f9fa;
  padding: 0.2rem 0.4rem;
  border-radius: 4px;
  font-size: 0.85rem;
 }

 /* File Cards */
 .file-card {
  display: flex;
  align-items: center;
  padding: 1rem;
  border: 1px solid rgba(77, 20, 140, 0.1);
  border-radius: var(--border-radius);
  transition: var(--transition);
 }

 .file-card:hover {
  border-color: var(--primary-color);
  transform: translateY(-2px);
 }

 .file-icon {
  width: 40px;
  height: 40px;
  border-radius: 8px;
  background-color: rgba(77, 20, 140, 0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--primary-color);
  margin-right: 1rem;
  font-size: 1.2rem;
 }

 .file-info {
  flex-grow: 1;
 }

 .file-name {
  font-weight: 600;
  color: var(--primary-color);
 }

 .file-path {
  font-size: 0.8rem;
  color: var(--secondary-color);
  word-break: break-all;
 }

 .file-actions {
  display: flex;
  gap: 0.25rem;
 }

 /* Action Buttons */
 .action-buttons {
  display: flex;
  gap: 0.5rem;
 }

 .btn-icon {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: var(--shippex-light);
  color: var(--primary-color);
  border: none;
  transition: var(--transition);
 }

 .btn-icon:hover {
  background-color: var(--secondary-color);
  color: white;
 }

 .btn-danger {
  background-color: var(--shippex-accent);
  color: white;
 }

 .btn-danger:hover {
  background-color: #c82333;
  color: white;
 }

 /* Empty State */
 .empty-state {
  text-align: center;
  padding: 2rem 1rem;
 }

 .empty-state.small {
  padding: 1.5rem 1rem;
 }

 .empty-state.small .empty-icon {
  font-size: 2.5rem;
  margin-bottom: 1rem;
 }

 .empty-state.small h5 {
  font-size: 1.1rem;
 }

 .empty-icon {
  font-size: 3rem;
  color: rgba(77, 20, 140, 0.2);
  margin-bottom: 1.5rem;
 }

 .empty-state h4,
 .empty-state h5 {
  color: var(--primary-color);
  margin-bottom: 0.5rem;
 }

 .empty-state p {
  color: var(--secondary-color);
  max-width: 300px;
  margin: 0 auto;
 }

 /* Form Sections */
 .add-item-section,
 .upload-section {
  background-color: rgba(77, 20, 140, 0.02);
  border-radius: var(--border-radius);
  padding: 1.5rem;
 }

 /* Alert Styling */
 .alert {
  border-radius: var(--border-radius);
  border: none;
 }

 .alert-danger {
  background-color: rgba(220, 53, 69, 0.1);
  color: #721c24;
 }

 /* Responsive Adjustments */
 @media (max-width: 768px) {
  .premium-header {
   padding: 1.5rem 0;
  }

  .page-title {
   font-size: 1.5rem;
  }

  .header-actions {
   margin-top: 1rem;
   width: 100%;
  }

  .header-actions .btn {
   width: 100%;
   margin-bottom: 0.5rem;
  }

  .file-card {
   flex-direction: column;
   text-align: center;
  }

  .file-icon {
   margin-right: 0;
   margin-bottom: 0.5rem;
  }

  .file-actions {
   margin-top: 0.5rem;
   justify-content: center;
  }

  .action-buttons {
   flex-direction: column;
   gap: 0.25rem;
  }
 }
</style>
<script>
 // let itemIndex = 1;
 // document.getElementById('addItemBtn').addEventListener('click', function() {
 //  const container = document.getElementById('itemsContainer');
 //  const newRow = container.children[0].cloneNode(true);
 //  newRow.querySelectorAll('input').forEach(input => input.value = '');
 //  newRow.querySelectorAll('input').forEach((input, idx) => {
 //   const name = input.name.replace(/\d+/, itemIndex);
 //   input.name = name;
 //  });
 //  container.appendChild(newRow);
 //  itemIndex++;
 // });

 // document.getElementById('itemsContainer').addEventListener('click', function(e) {
 //  if (e.target.classList.contains('remove-item') || e.target.closest('.remove-item')) {
 //   e.target.closest('.item-row').remove();
 //  }
 // });

 let fileIndex = 1;
 document.getElementById('addFileBtn').addEventListener('click', function() {
  const container = document.getElementById('filesContainer');
  const newRow = container.children[0].cloneNode(true);
  newRow.querySelectorAll('input').forEach(input => input.value = '');
  newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
  newRow.querySelectorAll('input, select').forEach((el, idx) => {
   const name = el.name.replace(/\d+/, fileIndex);
   el.name = name;
  });
  container.appendChild(newRow);
  fileIndex++;
 });

 document.getElementById('filesContainer').addEventListener('click', function(e) {
  if (e.target.classList.contains('remove-file') || e.target.closest('.remove-file')) {
   e.target.closest('.file-row').remove();
  }
 });
</script>
<!-- Include Select2 -->

<?= $this->section('script') ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
 $(document).ready(function() {

  // Initialize Select2 with search
  $('#userSelect').select2({
   placeholder: "Search for a user...",
   allowClear: true,
   templateResult: formatUserOption,
   templateSelection: formatUserOption,
   matcher: matchCustom
  });

  // On user change
  $('#userSelect').on('change', function() {
   const userName = $('#userSelect option:selected').text();
   $('#userPreview').text(userName ? `Selected: ${userName}` : 'Selected user will appear here');
  });

  // Custom rendering for dropdown options
  function formatUserOption(state) {
   if (!state.id) return state.text; // placeholder
   const user = state.text;
   const $option = $(`<span>${user}</span>`);
   return $option;
  }

  // Optional: custom matcher for case-insensitive search
  function matchCustom(params, data) {
   if ($.trim(params.term) === '') return data;
   if (typeof data.text === 'undefined') return null;
   if (data.text.toLowerCase().includes(params.term.toLowerCase())) {
    return data;
   }
   return null;
  }

 });
</script>

<?= $this->endSection() ?>
<?= $this->endSection() ?>