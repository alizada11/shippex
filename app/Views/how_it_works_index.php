<?= view('layout/header.php'); ?>

<!-- Hero Section -->
<div class="container-fluid" style="background-image: linear-gradient(340deg, #f8f8f8 50%, #fff 50%);">
  <div class="container py-5">
    <div class="row align-items-center">
      <div class="col-md-6 ">
        <h1 class="cms-title-1" style="font-size:48px;"><?= $sections['title'] ?></h1>
        <p class="cms-paragraph">
          <?= $sections['description'] ?>
        </p>
        <a href="<?= $sections['button_link'] ?>" class="btn cms-cta-button shippex-btn">
          <i class="icon-f2me-22-fast-delivery" style="font-size:22px; vertical-align:middle; margin-right:10px;"></i>
          <?= $sections['button_text'] ?>
        </a>
      </div>
      <div class="col-md-6 text-center">
        <img src="<?= 'uploads/how_it_works/' . $sections['image'] ?>" alt="parcel forwarding"
          style="height:500px;" class=" img-fluid">
      </div>
    </div>
  </div>
</div>

<!-- Steps Section -->
<div class="container-fluid py-5" style="background-color:#f8f8f8;">
  <div class="container">
    <h2 class="cms-title-2 text-center mb-5">How to Use Shippex</h2>
    <?php foreach ($steps as $step): ?>
      <!-- Step 1 -->
      <div class="row align-items-center mb-5">
        <div class="col-md-6 text-center">
          <img src="<?= 'uploads/' . $step['image'] ?>" class="img-fluid" alt="Step 1">
        </div>
        <div class="col-md-6">
          <h2 class="cms-title-2"><?= $step['title'] ?></h2>
          <p class="cms-paragraph">
            <?= $step['description'] ?>
          </p>
        </div>
      </div>
    <?php endforeach; ?>

  </div>
</div>

<!-- Why Choose Us Section -->
<div class="container py-5">
  <h2 class="cms-title-2 text-center mb-5">Why Choose Shippex</h2>
  <div class="row text-center">
    <?php foreach ($why as $y): ?>
      <div class="col-md-4 mb-4">
        <img src="<?= 'uploads/why_choose/' . $y['icon'] ?>" alt="Storage" width="50">
        <h5 class="mt-3"><?= $y['title'] ?></h5>
        <p><?= $y['description'] ?></p>
      </div>

    <?php endforeach; ?>

  </div>

  <!-- Final CTA -->
  <div class="container-fluid py-5" style="background-color:#f8f8f8;">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6">
          <h2 class="cms-title-2">Go Global</h2>
          <p class="cms-paragraph">
            Even when stores won’t. Use a forward2me address to shop internationally and get delivery anywhere.
          </p>
          <a href="https://my.forward2me.com/register/" class="btn shippex-btn">
            <i class="icon-f2me-22-fast-delivery" style="font-size:22px; vertical-align:middle; margin-right:10px;"></i>
            GET YOUR ADDRESS
          </a>
        </div>
        <div class="col-md-6 text-center">
          <img src="https://d2z2mkwk6fkehh.cloudfront.net/f2me/page/How%20It%20Works/man-2.png" alt="Go Global" class="img-fluid" width="717" height="515">
        </div>
      </div>
    </div>
  </div>
</div>

<?= view('layout/footer.php'); ?>