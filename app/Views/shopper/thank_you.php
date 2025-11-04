<?= $this->extend('customers/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
 <div class="row justify-content-center">
  <div class="col-md-8 col-lg-6">
   <!-- Success Card -->
   <div class="card shadow border-0 rounded-lg success-card">
    <div class="card-body p-5 text-center">
     <!-- Success Icon -->
     <div class="mb-4">
      <div class="success-icon mx-auto">
       <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="32" cy="32" r="30" fill="#28a745" stroke="#28a745" stroke-width="2" />
        <path d="M22 32L29 39L42 25" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
       </svg>
      </div>
     </div>

     <!-- Success Message -->
     <h2 class="card-title h3 mb-3">Request Submitted Successfully!</h2>
     <p class="card-text text-muted mb-4">
      Thank you! Ysour personal shopper request was received. We'll be in touch shortly.
     </p>

     <!-- Action Buttons -->
     <div class="d-grid gap-3 d-md-flex justify-content-md-center mt-4">
      <a class="btn btn-primary  px-4" href="/shopper">
       <i class="bi bi-plus-circle me-2"></i>Create Another Request
      </a>
      <a class="btn btn-outline-primary  px-4" href="/shopper/requests">
       <i class="bi bi-eye me-2"></i>View Your Requests
      </a>
     </div>
    </div>
   </div>

   <!-- Next Steps Info -->
   <div class="card mt-4 border-0 bg-light">
    <div class="card-body p-4">
     <h5 class="card-title mb-3">What Happens Next?</h5>
     <ul class="list-unstyled mb-0">
      <li class="d-flex mb-2">
       <i class="bi bi-check-circle text-success me-2"></i>
       <span>We'll review your request and match you with a personal shopper</span>
      </li>
      <li class="d-flex mb-2">
       <i class="bi bi-clock text-primary me-2"></i>
       <span>You'll receive a confirmation email within 24 hours</span>
      </li>
      <li class="d-flex">
       <i class="bi bi-chat-dots text-info me-2"></i>
       <span>Your personal shopper will contact you to discuss your needs</span>
      </li>
     </ul>
    </div>
   </div>
  </div>
 </div>
</div>

<style>
 .success-card {
  background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
  border-top: 4px solid #28a745 !important;
 }

 .success-icon {
  width: 80px;
  height: 80px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto;
 }

 .btn-primary {
  background-color: #28a745;
  border-color: #28a745;
 }

 .btn-primary:hover {
  background-color: #218838;
  border-color: #1e7e34;
 }

 .btn-outline-primary {
  color: #28a745;
  border-color: #28a745;
 }

 .btn-outline-primary:hover {
  background-color: #28a745;
  border-color: #28a745;
  color: white;
 }

 .card {
  transition: transform 0.3s ease;
 }

 .card:hover {
  transform: translateY(-5px);
 }
</style>

<?= $this->endSection() ?>