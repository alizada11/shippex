<?php

use CodeIgniter\Database\BaseUtils;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>ShipPex - Home</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: '<?= esc($defaultFont) ?>', sans-serif;
      font-size: 1rem;
    }
  </style>
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/media-queries.css'); ?>">
  <!-- Add these to your head section -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>
  <!-- Loader -->
  <!-- <div id="preloader">
    <div class="loader-container">
      <div class="loader"></div>
    </div>
  </div> -->
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-2">
    <div class="container">
      <a class="navbar-brand" class="logo" href="<?= base_url('/') ?>">
        <img src="<?= base_url('images/logo.png'); ?>" alt="Logo">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
        data-bs-target="#navbarNav" aria-controls="navbarNav"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item"><a class="nav-link menu-link" href="<?= base_url('/how-it-works') ?>">How Shippex Works</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link menu-link dropdown-toggle <?= (strpos(current_url(), '/warehouses') !== false) ? 'active' : '' ?>"
              href="#"
              id="warehousesDropdown"
              role="button"
              data-bs-toggle="dropdown"
              aria-expanded="false">
              Warehouses
            </a>
            <ul class="dropdown-menu" aria-labelledby="warehousesDropdown">
              <?= warehousesMenu() ?>
            </ul>
          </li>



          <li class="nav-item"><a class="nav-link menu-link <?= (strpos(current_url(), '/shipping') !== false) ? 'active' : '' ?>" href="<?= base_url('shipping/rates') ?>">Shipping Rates</a></li>
          <li class="nav-item"><a class="nav-link menu-link <?= (strpos(current_url(), '/services') !== false) ? 'active' : '' ?>" href="<?= base_url('/services'); ?>">Shopping Services</a></li>
          <li class="nav-item"><a class="nav-link menu-link <?= (strpos(current_url(), '/blog') !== false) ? 'active' : '' ?>" href="<?= base_url('/blog'); ?>">Business Blog</a></li>
          <?php
          if (!session()->get('logged_in')):
          ?>
            <li class="nav-item">
              <a class="btn nav-link  bg-shippex-purple text-white mx-2" href="<?= base_url('register'); ?>">Sign Up</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('login'); ?>">Login</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('dashboard'); ?>"><i class="fas fa-tachometer-alt"></i> Dashboard
              </a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
  <div class="main-contet">