<?php include('layout/header.php'); ?>
<?php
$countries = json_decode(file_get_contents(__DIR__ . '/partials/countries.json'), true);

?>
<!-- Hero Section -->
<section class="hero-section" style="background-image: url('<?= base_url($hero['background_image'] ?? 'images/default-bg.jpg') ?>');">
  <div class="overlay"></div>
  <div class="container text-white hero-content">
    <div class=" row">
      <div class="col-md-8">
        <h1 class="display-4 fw-bold"><?= esc($hero['title']) ?></h1>
        <h4 class="mt-3"><?= esc($hero['subtitle']) ?></h4>
        <p class="lead mt-3">
          <?= esc($hero['description']) ?>
        </p>
        <a href="<?= esc($hero['button_link']) ?>" class="btn btn-start text-white mt-4">
          <?= esc($hero['button_text']) ?>
        </a>
      </div>
    </div>

    <div class="row  mt-sm-5 mt-md-5 mt-lg-3 mt-xl-5 mb-xl-0">
      <!-- Left column -->
      <div class="col-5 col-sm-5 col-lg-5 col-xl-5 shipment-col">
        <div>
          <p class="mb-0 text-white fw-bold fs-6">WE SHIP WORLDWIDE</p>
        </div>
        <div class="container-fluid px-0">
          <div class="row">
            <div class="col-12">
              <!-- Show on md+ -->
              <img alt="Image" class="img-fluid d-none d-lg-inline-block"
                src="<?= base_url('images/Header-logos-horizontal-ship-white.webp') ?>">

              <!-- Show on lg and smaller (hide xl) -->
              <img alt="Image" class="img-fluid d-xl-none d-lg-none"
                src="<?= base_url('images/Header-logos-vertical-ship-white.webp') ?>">
            </div>
          </div>
        </div>
      </div>

      <!-- Middle column (button) -->
      <div class="col-3 col-sm-3 col-lg-2 col-xl-1 text-center">
        <a href="#how-it-works" class="btn animated-arrow mt-lg-3 mt-xl-5">
          <svg class="mt-1 svg-inline--fa fa-chevron-down fa-w-14 icon-arrow-jump" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-down" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
            <path fill="#ff6600" d="M207.029 381.476L12.686 187.132c-9.373-9.373-9.373-24.569 0-33.941l22.667-22.667c9.357-9.357 24.522-9.375 33.901-.04L224 284.505l154.745-154.021c9.379-9.335 24.544-9.317 33.901.04l22.667 22.667c9.373 9.373 9.373 24.569 0 33.941L240.971 381.476c-9.373 9.372-24.569 9.372-33.942 0z"></path>
          </svg>
        </a>
      </div>

      <!-- Right column -->
      <div class="col-4 col-md-4 col-lg-5 col-xl-6 payment-col">
        <div>
          <p class="mb-0 text-end text-white fw-bold fs-6">PAY WITH CONFIDENCE</p>
        </div>
        <div class="container-fluid px-0">
          <div class="row">
            <div class="col-12 text-end">
              <!-- Show on md+ -->
              <img alt="Image" class="img-fluid d-none d-lg-inline-block"
                src="<?= base_url('images/Header-logos-horizontal-pay-white-2.webp') ?>">

              <!-- Show on lg and smaller (hide xl) -->
              <img alt="Image" class="img-fluid d-xl-none d-lg-none"
                src="<?= base_url('images/Header-logos-vertical-pay-white-2.webp') ?>">
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  </div>
</section>
<!-- calculator -->
<section class="hero" id="top">
  <div class="hero-wrap wrap">
    <div>
      <h1>Shop Worldwide. <br />Ship Anywhere.</h1>
      <p>Parcel forwarding & Personal Shopper with an <strong>Instant Shipping Calculator</strong>. We buy, receive, <strong>consolidate</strong>, and ship from USA • UK • UAE • China • Oman to 190+ countries.</p>
      <div class="badges">
        <span class="pill">30‑day Free Storage</span>
        <span class="pill">Consolidation & Repack</span>
        <span class="pill">Tracked Delivery</span>
        <span class="pill">VIP Discounts (Beta)</span>
      </div>
      <div class="cta-row">
        <a href="<?= base_url('/shopper') ?>" class="btn btn-outline">Use Personal Shopper</a>
        <a href="#warehouses" class="btn btn-outline">Get Warehouse Address</a>
      </div>
    </div>

    <!-- Calculator Card -->
    <?= view('partials/calculator/html-sm') ?>
  </div>
</section>
<!-- Results Section -->
<div class="container" style="position: relative;">
  <div id="ratesResult" class="mt-3">


    <div class="d-none" id="errorContainer">
      <div class="alert alert-danger" role="alert">
        <h5 class="alert-heading">Unable to Calculate Rates</h5>
        <p id="errorMessage"></p>

      </div>
    </div>

    <!-- Loader for booking -->
    <div id="bookingLoader" class="d-none text-center my-3" style="position: absolute; top:50%; left:50%; z-index:99999;">
      <div class="spinner-border text-info" role="status">
        <span class="visually-hidden">Booking...</span>
      </div>
      <p class="mt-2">Processing your booking...</p>
    </div>
  </div>
  <div id="rateContainer" class="mt-3"></div>
</div>
<!-- How it works -->
<section id="how-it-works" class="how-it-works py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold mb-3">How Parcel Forwarding Works</h2>
      <p class="lead text-muted">Think parcel forwarding means more steps? We make them fewer...</p>
    </div>

    <div class="row g-4">
      <?php foreach ($steps as $step): ?>
        <div class="col-md-6 col-lg-3">
          <div class="h-100 p-3">
            <div class="d-flex align-items-start mb-3">
              <?php if ($step['icon']): ?>
                <img src="<?= base_url($step['icon']) ?>" alt="Step <?= esc($step['step_number']) ?>" class="me-3" width="40">
              <?php endif; ?>
              <div>
                <p class="text-uppercase small text-muted mb-0"><?= esc($step['subtitle']) ?></p>
                <h4 class="text-shippex-primary fw-bold mb-0"><?= esc($step['title']) ?></h4>
              </div>
            </div>
            <p><?= esc($step['description']) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Where are we located -->
<section id="where-are-we" class="py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold mb-3">Where Are We Located?</h2>
      <p class="lead text-muted">
        We give you access to the world's top shopping destinations...
      </p>
    </div>

    <div class="row g-4">
      <?php foreach ($locations as $loc): ?>
        <div class="col-md-6 col-lg-4 mb-4">
          <?php if ($loc['status'] === 'coming_soon'): ?>
            <div class="coming-soon-card">
              <div class="hexagon-logo">
                <img src="<?= base_url('uploads/' . $loc['flag_image']) ?>" alt="<?= esc($loc['name']) ?>">
              </div>
              <div class="coming-soon-content">
                <h3>More<br><?= esc($loc['name']) ?></h3>
              </div>
            </div>
          <?php else: ?>
            <a href="<?= esc($loc['link']) ?>" class="location-card">
              <div class="hexagon-flag">
                <img src="<?= base_url('uploads/' . $loc['flag_image']) ?>" alt="<?= esc($loc['name']) ?>">
              </div>
              <div class="location-content">
                <span class="location-label">WAREHOUSE</span>
                <h3 class="location-name"><?= esc($loc['name']) ?>
                  <img src="<?= base_url('/icons/arrow.png') ?>" alt="arrow">
                </h3>
                <?php if ($loc['location_info']): ?>
                  <p class="location-info"><?= esc($loc['location_info']) ?></p>
                <?php endif; ?>
              </div>
              <img src="<?= base_url('uploads/' . $loc['thumbnail_image']) ?>" alt="<?= esc($loc['name']) ?>" class="location-image">
            </a>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- deliverd today -->

<div class="delivered-today-section py-5">
  <div class="container">
    <div class="row align-items-center">
      <!-- Text -->
      <div class="col-md-4 mb-4 mb-md-0">
        <h2 class="text-white mb-3">DELIVERED TODAY</h2>
        <p class="text-light">Recent shipments from our customers around the world.</p>

        <!-- Desktop Nav -->
        <div class="d-none d-md-flex mt-4">
          <button class="carousel-prev btn btn-link text-white mr-3">
            <i class="fas fa-chevron-left"></i>
          </button>
          <button class="carousel-next btn btn-link text-white">
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>
      </div>

      <!-- Carousel -->
      <div class="col-md-8">
        <div class="owl-carousel owl-theme delivered-carousel">
          <?php foreach ($items as $item): ?>
            <div class="item">
              <div class="card bg-white rounded-0 p-3 h-100">
                <div class="row align-items-center mb-3">
                  <div class="col-6">
                    <div class="courier-logo" style="background-image: url('<?= base_url($item['courier_logo']) ?>')"></div>
                  </div>
                  <div class="col-6 ">
                    <img src="<?= base_url($item['retailer_logo']) ?>" alt="Retailer" class="retailer-logo">
                  </div>
                </div>

                <div class="hexagon-wrapper mb-3">
                  <div class="hexagon-shape mx-auto">
                    <div class="hexagon-shape-inner">
                      <i class="<?= esc($item['icon']) ?> "></i>
                    </div>
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-6">
                    <div class="d-flex align-items-end mb-1 justify-content-start gap-2">
                      <span class="text-muted small mr-2">From</span>
                      <img src="<?= base_url($item['from_flag']) ?>" alt="<?= esc($item['from_country']) ?>" class="country-flag">
                    </div>
                    <span data-bs-toggle="tooltip" title="<?= esc($item['to_country']) ?>" class="country-name"><?= esc($item['from_country']) ?></span>
                  </div>
                  <div class="col-6 text-end">
                    <div class="d-flex align-items-end mb-1 justify-content-end gap-2">
                      <span class="text-muted small mr-2">To</span>
                      <img src="<?= base_url($item['to_flag']) ?>" alt="<?= esc($item['to_country']) ?>" class="country-flag">
                    </div>
                    <span data-bs-toggle="tooltip" title="<?= esc($item['to_country']) ?>" class="country-name"><?= esc($item['to_country']) ?></span>
                  </div>
                </div>

                <hr>

                <div class="row">
                  <div class="col-6">
                    <span class="text-muted small">Cost</span>
                    <div class="metric"><?= esc($item['cost']) ?></div>
                  </div>
                  <div class="col-6 text-end">
                    <span class="text-muted small">Weight</span>
                    <div class="metric"><?= esc($item['weight']) ?></div>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Mobile Nav -->
        <div class="d-flex d-md-none mt-3">
          <button class="carousel-prev btn btn-link text-white mr-auto">
            <i class="fas fa-chevron-left"></i>
          </button>
          <button class="carousel-next btn btn-link text-white">
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Promo section -->

<section class="promo-section py-5">
  <div class="container">
    <div class="row">

      <?php

      foreach ($promoCards as $card): ?>
        <div class="col-lg-6 mb-4">
          <div class="promo-card <?= strtolower(str_replace(' ', '-', $card['title'])) ?>-card"
            style="background-image: url('<?= base_url('uploads/promo_cards/' . $card['background']) ?>')">


            <div class="promo-content">
              <h2><?= esc($card['title']) ?></h2>
              <p><?= esc($card['description']) ?></p>
              <div class="promo-footer">
                <a href="<?= esc($card['button_url']) ?>" class="btn promo-btn"><?= esc($card['button_text']) ?></a>
                <?php if ($card['image']): ?>
                  <img src="<?= base_url('uploads/promo_cards/' . $card['image']) ?>"
                    alt="<?= esc($card['title']) ?> Logo" class="promo-logo">
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
<style>
  /* Hero */
  .heros {
    position: relative;
    overflow: hidden;
    background:
      radial-gradient(1200px 500px at 10% -20%, rgba(107, 77, 246, .16), transparent 60%),
      radial-gradient(1000px 500px at 100% 0%, rgba(255, 122, 26, .18), transparent 60%);
    padding: 2.6rem 1rem 1rem;
  }

  .hero-wrap {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 2rem;
    align-items: start
  }

  .heros h1 {
    font-size: clamp(1.9rem, 2.8vw, 3rem);
    margin: .25rem 0 .75rem;
    font-weight: 800;
    letter-spacing: -.02em
  }

  .heros p {
    font-size: clamp(1rem, 1.2vw, 1.1rem);
    color: #2b2f47;
    max-width: 60ch
  }

  .heros .badges {
    display: flex;
    flex-wrap: wrap;
    gap: .5rem;
    margin: 1rem 0 1.25rem
  }

  .pill {
    background: white;
    border: 1px solid #eceef6;
    color: #37406b;
    padding: .45rem .65rem;
    border-radius: 999px;
    font-weight: 700;
    font-size: .9rem
  }

  .cta-row {
    display: flex;
    gap: .7rem;
    flex-wrap: wrap;
    margin-top: 1rem
  }

  .cta-row .btn {
    padding: .85rem 1.05rem
  }
</style>
<?= view('partials/calculator/js') ?>


<?php include('layout/footer.php'); ?>