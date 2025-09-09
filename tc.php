<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Glass Calculator</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #fbfbfbff;
      font-family: 'Segoe UI', sans-serif;
    }
    h2 {
      font-weight: bold;
      color: #4a4a4a;
    }
    p {
      font-size: 1.1rem;
      color: #333;
    }
    .section-title {
      margin-bottom: 20px;
    }
    .highlight {
      font-weight: bold;
    }
    .delivery-section {
      padding: 50px 0;
    }
    .lead-time {
      margin-top: 40px;
    }
    .image-box {
      max-width: 100%;
      height: auto;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .delivery-box {
      background-color: #f2f5fa;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
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
  </style>
</head>
<body class="bg-light text-dark">
<?php include 'includes/navbar.php'; ?>

  <div class="container py-5">
    <h1 class="mb-4">Terms of Use of Sealed Units Direct Online Services</h1>

    <p>
      Welcome to our website. If you continue to browse and use this website, you are agreeing to comply with and be bound by the following terms and conditions of use, which together with our privacy policy govern Sealed Units Direct’s relationship with you in relation to this website. If you disagree with any part of these terms and conditions, please do not use our website.
    </p>

    <h3 class="mt-5">Website Usage Terms and Conditions</h3>
    
    <p>
      The term <strong>Sealed Units Direct</strong> or 'us' or 'we' refers to the owner of the website whose registered office is Unit 4, Charfleets Road, Canvey Island, Essex SS8 0PQ. Our company registration number is 16187030. The term 'you' refers to the user(s) or person(s) viewing our website.
    </p>

    <ul class="list-group list-group-flush mb-4">
      <li class="list-group-item">This website is subject to the following terms of use:</li>
      <li class="list-group-item">The content is for general information and use only. It is subject to change without notice.</li>
      <li class="list-group-item">This website uses cookies. Personal info (name, email, company name, etc.) may be stored if cookies are allowed.</li>
    </ul>

    <h3 class="mt-5">Online Payments Terms &amp; Conditions</h3>

    <p>
      Our online payment facilities carry their own sets of Terms &amp; Conditions:
    </p>

    <ul class="list-unstyled">
      <li><a href="#" class="text-primary text-decoration-none">› Trade Terms & Conditions</a></li>
      <li><a href="#" class="text-primary text-decoration-none">› Consumer Terms & Conditions</a></li>
    </ul>

    <div class="mt-4">
      <a href="index.php" class="btn btn-primary">← Back to Home</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Footer -->
 <?php include 'includes/footer.php'; ?>
</body>
</html>