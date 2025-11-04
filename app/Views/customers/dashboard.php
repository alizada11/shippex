<?= $this->extend('customers/layouts/main') ?>

<?= $this->section('content') ?>
<div class="row mb-5 g-4">
  <div class="col-md-4">
    <div class="card text-white bg-primary mb-3">
      <div class="card-body">
        <h5 class="card-title"><i class="fas fa-users"></i> Shipping Requests</h5>
        <p class="card-text fs-4"><?= $shipping_requests ?></p>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card text-white bg-success mb-3">
      <div class="card-body">
        <h5 class="card-title"><i class="fas fa-shopping-cart"></i> Shopper Requests</h5>
        <p class="card-text fs-4"><?= $shopper_requests ?></p>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card text-white bg-warning mb-3">
      <div class="card-body">
        <h5 class="card-title"><i class="fas fa-address-book"></i> Addresses</h5>
        <p class="card-text fs-4"><?= $addresses ?></p>
      </div>
    </div>
  </div>
</div>
<div class="warehouse-section mb-5">
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="text-shippex-primary mb-2">
        <i class="fas fa-warehouse me-2"></i>Our Warehouse Network
      </h2>
      <p class="text-muted mb-0">Global shipping facilities ready to serve you</p>
    </div>
    <div class="d-none d-md-block">
      <span class="badge bg-shippex-primary fs-6 px-3 py-2">
        <i class="fas fa-map-marker-alt me-1"></i>
        <?= count($address) ?> Locations
      </span>
    </div>
  </div>

  <?php if ($address): ?>
    <div class="row g-4">
      <?php foreach ($address as $index => $wh): ?>
        <?php
        $code = strtolower($wh['code'] ?? 'us');

        // Fix or map invalid country codes
        $map = [
          'uk' => 'gb', // show UK flag for 'uk'
          'gp' => 'gb', // treat 'gp' as UK
        ];

        $flagCode = $map[$code] ?? $code;
        ?>
        <div class="col-lg-6 col-xl-4">
          <div class="warehouse-card card h-100 border-0 shadow-hover">
            <!-- Card Header with Gradient -->
            <div class="card-header position-relative overflow-hidden bg-shippex-gradient text-white p-4">
              <div class="position-absolute top-0 end-0 m-3">
                <span class="badge bg-white text-shippex-primary fs-7">
                  <i class="fas fa-hashtag me-1"></i>WH-<?= $wh['id'] ?? $index + 1 ?>
                </span>
              </div>
              <div class="d-flex align-items-center">
                <div class="warehouse-icon me-3">
                  <i class="fi fi-<?= $flagCode; ?> fa-lg"></i>
                </div>
                <div>
                  <h5 class="mb-1 fw-bold"><?= esc($wh['city']) ?></h5>
                  <p class="mb-0 opacity-90">
                    <i class="fas fa-map-marker-alt me-1"></i>
                    <?= esc($wh['country']) ?>
                  </p>
                </div>
              </div>
            </div>

            <!-- Card Body -->
            <div class="card-body p-4">
              <!-- Address Section -->
              <div class="info-item mb-4">
                <div class="d-flex align-items-start">
                  <div class="icon-wrapper bg-shippex-light text-shippex-primary me-3">
                    <i class="fas fa-map-marked-alt"></i>
                  </div>
                  <div class="flex-grow-1">
                    <h6 class="text-shippex-primary fw-semibold mb-2">Warehouse Address</h6>
                    <div class="address-details">
                      <p class="mb-1 text-dark">
                        <i class="fas fa-building me-2 text-muted"></i>
                        <?= esc($wh['address_line']) ?>
                      </p>
                      <p class="mb-1 text-dark">
                        <i class="fas fa-city me-2 text-muted"></i>
                        <?= esc($wh['city']) ?>, <?= esc($wh['postal_code']) ?>
                      </p>
                      <p class="mb-0 text-dark">
                        <i class="fas fa-globe me-2 text-muted"></i>
                        <?= esc($wh['country']) ?>
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Contact Section -->
              <div class="info-item mb-4">
                <div class="d-flex align-items-start">
                  <div class="icon-wrapper bg-shippex-light text-shippex-primary me-3">
                    <i class="fas fa-phone-alt"></i>
                  </div>
                  <div class="flex-grow-1">
                    <h6 class="text-shippex-primary fw-semibold mb-2">Contact Information</h6>
                    <div class="contact-details">
                      <p class="mb-2">
                        <a href="tel:<?= esc($wh['phone']) ?>" class="text-decoration-none text-dark">
                          <i class="fas fa-phone me-2 text-muted"></i>
                          <?= esc($wh['phone']) ?>
                        </a>
                      </p>
                      <?php if (!empty($wh['email'])): ?>
                        <p class="mb-0">
                          <a href="mailto:<?= esc($wh['email']) ?>" class="text-decoration-none text-shippex-primary">
                            <i class="fas fa-envelope me-2"></i>
                            <?= esc($wh['email']) ?>
                          </a>
                        </p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Operating Hours -->
              <?php if (!empty($wh['operating_hours'])): ?>
                <div class="info-item mb-4">
                  <div class="d-flex align-items-start">
                    <div class="icon-wrapper bg-shippex-light text-shippex-primary me-3">
                      <i class="fas fa-clock"></i>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="text-shippex-primary fw-semibold mb-2">Operating Hours</h6>
                      <p class="mb-0 text-dark">
                        <i class="fas fa-calendar-alt me-2 text-muted"></i>
                        <?= esc($wh['operating_hours']) ?>
                      </p>
                    </div>
                  </div>
                </div>
              <?php endif; ?>

              <!-- Services Offered -->
              <?php if (!empty($wh['services'])): ?>
                <div class="info-item">
                  <div class="d-flex align-items-start">
                    <div class="icon-wrapper bg-shippex-light text-shippex-primary me-3">
                      <i class="fas fa-concierge-bell"></i>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="text-shippex-primary fw-semibold mb-2">Services Offered</h6>
                      <div class="services-tags">
                        <?php $services = explode(',', $wh['services']); ?>
                        <?php foreach ($services as $service): ?>
                          <span class="service-tag"><?= trim(esc($service)) ?></span>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            </div>

            <!-- Card Footer with Actions -->
            <div class="card-footer bg-transparent border-0 pt-0">
              <div class="d-grid gap-2">
                <a href="<?= esc($wh['map_link'] ?? '#') ?>"
                  target="_blank"
                  class="btn btn-shippex-primary btn-sm">
                  <i class="fas fa-directions me-2"></i>Get Directions
                </a>
                <?php
                $request = $requestMap[$wh['id']] ?? null;
                ?>
                <?php if ($request): ?>
                  <a class="btn btn-outline-shippex-primary btn-sm 
        ">


                    <?php if ($request['status'] == 'pending'): ?>
                      Requested <i class="fas fa-info-circle"
                        data-bs-toggle="tooltip"
                        title="Request pending. Requested on: <?= date('Y-m-d H:i', strtotime($request['created_at'])) ?>"></i>
                    <?php elseif ($request['status'] == 'accepted'): ?>
                      You own it <i class="fas fa-check-circle text-success"
                        data-bs-toggle="tooltip"
                        title="Request accepted"></i>
                    <?php elseif ($request['status'] == 'rejected'): ?>
                      Rejected <i class="fas fa-times-circle text-danger"
                        data-bs-toggle="tooltip"
                        title="Request rejected: <?= esc($request['rejectation_reason']) ?>"></i>
                  </a>
                <?php endif; ?>
              <?php else: ?>
                <a class="btn btn-outline-shippex-primary btn-sm warehouse-select-btn 
        "
                  data-warehouse-id="<?= $wh['id'] ?? '' ?>">
                  Request Warehouse
                </a>
              <?php endif; ?>
              </a>

              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <!-- Empty State -->
    <div class="empty-state text-center py-5">
      <div class="empty-icon mb-4">
        <i class="fas fa-warehouse text-muted fa-4x opacity-25"></i>
      </div>
      <h4 class="text-muted mb-3">No Warehouses Available</h4>
      <p class="text-muted mb-4">We're expanding our network. Please check back later or contact support.</p>
      <a href="<?= site_url('contact') ?>" class="btn btn-shippex-primary">
        <i class="fas fa-headset me-2"></i>Contact Support
      </a>
    </div>
  <?php endif; ?>
</div>


<style>
  .icon-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .info-item {
    padding: 0.5rem 0;
  }

  :root {
    --shippex-primary: #4E148C;
    --shippex-secondary: #FF6600;
    --shippex-light: #F0E6FF;
    --shippex-gradient: linear-gradient(135deg, #4E148C 0%, #6A3FB8 100%);
    --shippex-purple: #4E148C;
  }

  /* Card Styles */
  .warehouse-card {
    border-radius: 16px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
  }

  .warehouse-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(78, 20, 140, 0.15) !important;
  }

  .card-header.bg-shippex-gradient {
    background: var(--shippex-gradient) !important;
    border-bottom: none;
    position: relative;
  }

  .card-header.bg-shippex-gradient::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, transparent 0%, rgba(255, 255, 255, 0.1) 100%);
  }

  .warehouse-icon {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
  }

  /* Icon Wrapper */
  .icon-wrapper {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.3s ease;
  }

  .info-item:hover .icon-wrapper {
    transform: scale(1.05);
  }

  /* Services Tags */
  .services-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
  }

  .service-tag {
    background: var(--shippex-light);
    color: var(--shippex-primary);
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    border: 1px solid rgba(78, 20, 140, 0.1);
    transition: all 0.3s ease;
  }

  .service-tag:hover {
    background: var(--shippex-primary);
    color: white;
    transform: translateY(-1px);
  }

  /* Buttons */
  .btn-shippex-primary {
    background: var(--shippex-primary);
    border: none;
    color: white;
    border-radius: 10px;
    padding: 10px 16px;
    font-weight: 500;
    transition: all 0.3s ease;
  }

  .btn-shippex-primary:hover {
    background: #3A0C6E;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(78, 20, 140, 0.3);
  }

  .btn-outline-shippex-primary {
    border: 2px solid var(--shippex-primary);
    color: var(--shippex-primary);
    background: transparent;
    border-radius: 10px;
    padding: 10px 16px;
    font-weight: 500;
    transition: all 0.3s ease;
  }

  .btn-outline-shippex-primary:hover {
    background: var(--shippex-primary);
    color: white;
    transform: translateY(-2px);
  }

  /* Address Details */
  .address-details p,
  .contact-details p {
    transition: color 0.3s ease;
  }

  .address-details p:hover,
  .contact-details p:hover {
    color: var(--shippex-primary) !important;
  }

  /* Badges */
  .badge.bg-shippex-primary {
    background: var(--shippex-primary) !important;
    border-radius: 10px;
    font-weight: 500;
  }

  /* Empty State */
  .empty-state {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 20px;
    padding: 4rem 2rem;
  }

  /* Responsive Design */
  @media (max-width: 768px) {
    .warehouse-card {
      margin-bottom: 1.5rem;
    }

    .card-header.bg-shippex-gradient {
      padding: 1.5rem !important;
    }

    .icon-wrapper {
      width: 40px;
      height: 40px;
    }
  }

  /* Animation */
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .warehouse-card {
    animation: fadeInUp 0.6s ease-out;
  }

  .warehouse-card:nth-child(2) {
    animation-delay: 0.1s;
  }

  .warehouse-card:nth-child(3) {
    animation-delay: 0.2s;
  }

  /* Hover Effects */
  .shadow-hover {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  }

  .text-shippex-primary {
    color: var(--shippex-primary) !important;
  }

  .bg-shippex-light {
    background-color: var(--shippex-light) !important;
  }
</style>

<?= $this->endSection() ?>