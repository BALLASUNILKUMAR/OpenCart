<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Delivery Information</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="/Sealed Units/assets/images/logo.jpeg">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #fbfbfbff;
      font-family: 'Segoe UI', sans-serif;
      
    }
    h2 {
      font-weight: bold;
      color: #4a4a4a;
      padding: 10px;
    }
    p {
      font-size: 1.1rem;
      color: #333;
      padding: 10px;
    }
    .section-title {
      margin-bottom: 20px;
      padding: 10px;
    }
    .highlight {
      font-weight: bold;
    }
    .delivery-section {
      padding: 40px 0 30px 0;
    }
    .lead-time {
      margin-top: 40px;
    }
    .image-box {
      max-width: 100%;
      height: auto;
      border: 1px solid #ccc;
      border-radius: 5px;
      display: block;
      margin-left: auto;
      margin-right: auto;
    }
    .delivery-box {
      background-color: #f2f5fa;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .delivery-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: #333;
    }
    .delivery-price {
      font-size: 1.1rem;
      margin-bottom: 15px;
      color: #555;
    }
    .area-list {
      padding-left: 1rem;
    }
    .area-list li {
      list-style: disc;
      margin-bottom: 0.3rem;
      line-height: 1.6;
      color: #000;
    }
    .note {
      font-size: 0.9rem;
      color: #444;
      margin-top: 10px;
    }
    @media (max-width: 991.98px) {
      .delivery-section {
        padding-top: 24px;
        padding-bottom: 10px;
      }
      .lead-time {
        margin-top: 24px;
      }
    }
    @media (max-width: 767.98px) {
      .delivery-section {
        padding-top: 16px;
        padding-bottom: 0;
      }
      .delivery-box {
        padding: 18px;
      }
      .image-box {
        margin-top: 24px;
      }
    }
  </style>
</head>
<body>
<?php include 'includes/navbar.php'; ?>
<div class="container delivery-section">
  <div class="row gx-5 gy-4 align-items-center">
    <!-- Text Section -->
    <div class="col-lg-6 mb-4 mb-lg-0">
      <h2 class="section-title">DELIVERIES</h2>
      <p>Delivering across mainland UK to your door or directly to site <span class="highlight">WITHIN 10 DAYS.</span></p>
      <p>
        All of our vehicles carry the latest GPS navigation systems and all of our drivers can be contacted by mobile phone.
        Our vehicles are also fitted with satellite tracking systems, so that we know where our delivery vehicles are and can follow their journey <span class="highlight">24/7.</span>
      </p>
      <p style="font-size: 0.9rem;">Delivery to other areas is subject to order value. Call for details.</p>
      <div class="lead-time">
        <h2 class="section-title">LEAD TIMES</h2>
        <p>
          Please check with our Sales Dept. for current lead times. For customers who need their order extra quick we offer our
          <span class="text-primary">Fast Track Ordering</span> service.
        </p>
      </div>
    </div>
    <!-- Image Section -->
    <div class="col-lg-6 text-center">
      <img src="/Sealed Units/assets/images/delivery_map_SUBTOAMOUNT.png" alt="Delivery Areas Map" class="image-box w-100">
    </div>
  </div>
</div>
<div class="container py-5">
  <div class="row g-4">
    <!-- Delivery Area 1 -->
    <div class="col-md-6">
      <div class="delivery-box h-100">
        <h4 class="delivery-title">DELIVERY AREA 1</h4>
        <p class="delivery-price">£37.50 + VAT per delivery</p>
        <ul class="area-list">
          <li>Bedfordshire • Berkshire • Buckinghamshire</li>
          <li>Cambridgeshire • Essex • Hampshire • Hertfordshire</li>
          <li>Kent • Middlesex • Oxfordshire • Norfolk • Suffolk</li>
          <li>Surrey • Sussex • Wiltshire</li>
        </ul>
      </div>
    </div>
    <!-- Delivery Area 2 -->
    <div class="col-md-6">
      <div class="delivery-box h-100">
        <h4 class="delivery-title">DELIVERY AREA 2</h4>
        <p class="delivery-price">£67.50 + VAT per delivery</p>
        <ul class="area-list">
          <li>Derbyshire • Dorset • Gloucestershire • Herefordshire</li>
          <li>Leicestershire • Lincolnshire • Nottinghamshire</li>
          <li>Rutland • Shropshire • Somerset • Staffordshire</li>
          <li>Warwickshire • Worcestershire</li>
        </ul>
        <p class="note"><em>*Delivery to Area 2 is £37.50 + VAT for orders over £2000</em></p>
      </div>
    </div>
  </div>
</div>
<!-- Footer -->
<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>