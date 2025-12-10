<?= view('layout/header.php'); ?>



<div class="container py-5">
  <div class="row d-none d-md-flex mb-5">
    <!-- Shipping Section -->
    <div class="col-12 col-md-6">
      <p class="mb-0 text-purple fw-bold text-uppercase" style="font-size:16px;">We ship worldwide with</p>
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
      <p class="mb-0 text-purple fw-bold text-uppercase" style="font-size:16px;">Pay with confidence</p>
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

  <div class="row justify-content-center">
    <div class="col-lg-12">
      <?= view('partials/calculator/html') ?>
    </div>


    <div class="col-lg-6">
      <div class="container my-5">
        <div class="row">
          <div class="col-lg-12">
            <h2 class="text-uppercase fw-bold mb-3 d-block" style="font-size:24px;">Your Benefits</h2>
            <img width="200px" src="https://d2z2mkwk6fkehh.cloudfront.net/f2me/common/separators/title-2.svg" alt="Separator" class=" mb-4">
          </div>
          <div class="col-12 col-md-6">

            <!-- Benefit Item -->
            <div class="d-flex align-items-center mb-3">
              <div class="me-3" style="width:40px;">
                <img src="<?= base_url('icons/inventory.svg') ?>" alt="Free Storage" class="img-fluid">
              </div>
              <p class="mb-0 fw-bold" style="font-size:12px;">30 DAYS FREE STORAGE</p>
            </div>

            <div class="d-flex align-items-center mb-3">
              <div class="me-3" style="width:40px;">
                <img src="https://d2z2mkwk6fkehh.cloudfront.net/f2me/page/Warehouses/USA/estimate/PPC_PMG_n.svg" alt="Price Match Guarantee" class="img-fluid">
              </div>
              <p class="mb-0 fw-bold" style="font-size:12px;">PRICE MATCH GUARANTEE</p>
            </div>

            <div class="d-flex align-items-center">
              <div class="me-3" style="width:40px;">
                <img src="https://d2z2mkwk6fkehh.cloudfront.net/f2me/page/Warehouses/USA/estimate/SiM Return without hex.svg" alt="Free Return To Sender" class="img-fluid">
              </div>
              <p class="mb-0 fw-bold" style="font-size:12px;">FREE RETURN TO SENDER</p>
            </div>
          </div>

          <div class="col-12 col-md-6 mt-4 mt-md-0">
            <div class="d-flex align-items-center mb-3">
              <div class="me-3" style="width:40px;">
                <img src="https://d2z2mkwk6fkehh.cloudfront.net/f2me/page/Warehouses/USA/estimate/SiM Combine and repack without hex.svg" alt="Combine and Repack" class="img-fluid">
              </div>
              <p class="mb-0 fw-bold" style="font-size:12px;">COMBINE AND REPACK</p>
            </div>

            <div class="d-flex align-items-center mb-3">
              <div class="me-3" style="width:40px;">
                <img src="https://d2z2mkwk6fkehh.cloudfront.net/f2me/page/Warehouses/USA/estimate/Hand_n.svg" alt="GST Free" class="img-fluid">
              </div>
              <p class="mb-0 fw-bold" style="font-size:12px;">GST FREE</p>
            </div>

            <div class="d-flex align-items-center">
              <div class="me-3" style="width:40px;">
                <img src="https://d2z2mkwk6fkehh.cloudfront.net/f2me/page/Warehouses/USA/estimate/Call_edit.svg" alt="Friendly Support" class="img-fluid">
              </div>
              <p class="mb-0 fw-bold" style="font-size:12px;">INDUSTRY LEADING SUPPORT</p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<?= view('partials/calculator/js') ?>
<?= view('layout/footer.php'); ?>