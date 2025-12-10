<?= view('layout/header.php'); ?>

<?php
$countries = json_decode(file_get_contents(__DIR__ . '/../partials/countries.json'), true);

?>
<div class="container-fluid py-5" style=" background-image: url(<?= base_url('uploads/warehouses/' . $warehouse['banner_image']) ?>);  background-position: 265px 25%; background-size: 100%;">
  <div class="container">
    <div class="row align-items-center">

      <!-- LEFT COLUMN -->
      <div class="col-lg-5 col-md-12 mb-4 mb-lg-0">
        <h2 class="fw-bold mb-4"><?= $warehouse['hero_title']; ?></h2>
        <p>
          <?= $warehouse['hero_description_1'] ?>
        </p>
        <p>
          <?= $warehouse['hero_description_2']; ?>
        </p>
        <a href="<?= base_url('services') ?>" class="btn btn-danger btn-lg px-4">START SAVING</a>
      </div>

      <!-- RIGHT COLUMN -->
      <div class="col-lg-7 col-md-12 text-center">
        <!-- Background map with US flag -->
        <div class="position-relative">

          <!-- Statue of Liberty overlay -->
          <img src="<?= base_url('uploads/warehouses/' . $warehouse['hero_image']) ?>"
            class="img-fluid position-absolute top-50 start-50 translate-middle"
            style="max-height: 400px;"
            alt="Statue of Liberty" />
        </div>
      </div>
    </div>
  </div>
</div>
<div class="brands container-fluid py-5">
  <div class="py-5">
    <img class="bg-element-1 bg" src="<?= base_url('images/bg-element1.png') ?>" alt="">
    <img class="bg-element-2 bg" src="<?= base_url('images/bg-element2.png') ?>" alt="">
    <!-- LEFT COLUMN -->
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 col-md-12 mb-4 mb-lg-0">
          <h3 class="fw-bold mb-4 text-center text-lg-start"><?= $warehouse['brands_title']; ?></h3>
          <p>
            <?= $warehouse['brands_text']; ?>
          </p>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="col-lg-6 col-md-12 text-center">
          <img src="<?= base_url('uploads/warehouses/' . $warehouse['brands_image']) ?>" class="img-fluid" alt="US Brands Logos" />
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container py-5">
  <div class="row d-none d-md-flex mb-5">
    <!-- Shipping Section -->
    <div class="col-12 col-md-6">
      <p class="mb-0 text-success fw-bold text-uppercase" style="font-size:16px;">We ship worldwide with</p>
      <div class="row mt-3">
        <div class="col-6 col-md-3 mb-2">
          <img src="<?= base_url('images/FedEx-color.png') ?>"
            alt="FedEx" class="img-fluid logo-img">
        </div>
        <div class="col-6 col-md-3 mb-2">
          <img src="<?= base_url('images/UPS-color.png'); ?>"
            alt="UPS" class="img-fluid logo-img">
        </div>
      </div>
    </div>

    <!-- Payment Section -->
    <div class="col-12 col-md-6 text-end">
      <p class="mb-0 text-success fw-bold text-uppercase" style="font-size:16px;">Pay with confidence</p>
      <div class="row mt-3 justify-content-end">
        <div class="col-6 col-sm-4 col-md-2 mb-2">
          <img src="<?= base_url('images/PayPal-color.png') ?>"
            alt="PayPal" class="img-fluid logo-img">
        </div>
        <!-- <div class="col-6 col-sm-4 col-md-2 mb-2">
          <img src="<?= base_url('images/visa-mastercard.png') ?>"
            alt="Visa/MasterCard" class="img-fluid logo-img">
        </div> -->

      </div>
    </div>
  </div>


  <!-- calculator -->
  <section class="heros" id="top">
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
</div>

<div class="container-fluid bg-gray py-5 mb-5">
  <div class="container ">
    <div class="row mb-4">
      <div class="col-12 text-center">
        <h2 class="fw-bold"><?= $warehouse['bottom_title']; ?></h2>
      </div>
    </div>
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10 text-center">
        <p class="mb-3" style="font-size:16px;">
          <?= $warehouse['bottom_paragraph_1']; ?>
        </p>
        <p class="mb-4" style="font-size:16px;">
          <?= $warehouse['bottom_paragraph_2']; ?>
        </p>
        <a href="<?= base_url($warehouse['bottom_cta_link']) ?>" class="btn btn-primary btn-lg">START SHOPPING NOW</a>
      </div>
    </div>
  </div>
</div>

<div class="container py-3">
  <div class="row align-items-start">

    <!-- Accordion Section -->
    <div class="col-lg-6 mb-4">
      <h2 class="mb-4">Frequently Asked Questions</h2>
      <?= render_faqs(get_faqs(12), 'shippingFaqs') ?>


      <!-- Read More Button -->
      <div class="mt-4">
        <a href="<?= base_url('faqs') ?>" target="_blank" class="btn btn-primary">Read More</a>
      </div>
    </div>

    <!-- Image Section -->
    <div class="col-lg-6 text-center sticky-top" style="top: 100px;">
      <img src="<?= base_url('images/faq.svg') ?>" alt="FAQ Banner" class="img-fluid">
    </div>

  </div>
</div>

<?= view('partials/calculator/js') ?>



<style>
  /* Make all logos grayscale by default */
  .bg-gray {
    background-color: #eee;
  }

  .logo-img {
    filter: grayscale(100%);
    transition: filter 0.3s ease;
  }

  /* On hover, show original color */
  .logo-img:hover {
    filter: grayscale(0%);
  }

  .bg-gradient-primary {
    background: linear-gradient(135deg, #2c3e50, #3498db);
  }

  .form-control,
  .form-select {
    border-radius: 8px;
    border: 1px solid #ced4da;
    transition: all 0.3s;
  }

  .form-control:focus,
  .form-select:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
  }

  .input-group-text {
    background-color: #f8f9fa;
  }

  .card {
    border-radius: 12px;
    overflow: hidden;
  }

  .table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
  }

  .table td {
    vertical-align: middle;
  }

  span.input-group-text {
    border-radius: 0 8px 8px 0 !important;
  }


  /* Hero */
  .heros {
    position: relative;
    overflow: hidden;
    background-color: #FFF;
    padding: 1.6rem 0;
  }

  .hero-wrap {
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
<?= view('layout/footer.php'); ?>