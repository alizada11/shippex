<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <title>ShipPex - Home</title>
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <!-- Bootstrap CSS -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
 <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

 <link rel="stylesheet" href="<?= base_url('assets/css/style.css'); ?>">
 <!-- Add these to your head section -->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

</head>

<body>

 <!-- Navbar -->
 <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
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
     <li class="nav-item"><a class="nav-link" href="#">How It Works</a></li>
     <li class="nav-item"><a class="nav-link" href="#">Locations</a></li>
     <li class="nav-item"><a class="nav-link" href="#">Pricing</a></li>
     <li class="nav-item"><a class="nav-link" href="<?= base_url('/services'); ?>">Services</a></li>
     <li class="nav-item"><a class="nav-link" href="#">Resources</a></li>
     <li class="nav-item">
      <a class="btn btn-danger text-white mx-2" href="<?= base_url('register'); ?>">Sign Up</a>
     </li>
     <li class="nav-item"><a class="nav-link" href="<?= base_url('login'); ?>">Login</a></li>
    </ul>
   </div>
  </div>
 </nav>
 <div class="main-contet">