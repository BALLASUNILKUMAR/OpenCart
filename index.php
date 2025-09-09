<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sealed Units</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="/Sealed Units/assets/images/logo.jpeg">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      line-height: 1.7;
      color: #333;
    }
    .section-title {
      text-align: center;
      margin-bottom: 30px;
    }
    .section-title h2 {
      font-weight: bold;
      color: #007BFF;
    }
    .highlight-title {
      color: #007BFF;
      font-weight: 600;
    }
    .service-box img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 10px;
      margin-bottom: 10px;
    }
    .service-box h5 {
      text-align: center;
      color: #007BFF;
      font-weight: bold;
    }
    .content-section {
      padding: 60px 0;
    }
    .process-section {
      background: #f9f9f9;
      padding: 60px 0;
    }
    .enquiry-section {
      background: #eef5ff;
      padding: 60px 0;
    }
    .btn-primary {
      background: #007BFF;
      border: none;
    }
    .btn-primary:hover {
      background: #0056b3;
    }
    .carousel-inner .carousel-item img {
      height: 400px;
      object-fit: cover;
      width: 100%;
    }
    @media (max-width: 767.98px) {
      .carousel-inner .carousel-item img {
        height: 200px;
      }
      .content-section,
      .process-section,
      .enquiry-section {
        padding: 30px 0;
      }
      .service-box img {
        height: 120px;
      }
    }
  </style>
</head>
<body>
<?php include 'includes/navbar.php';?>
<!-- Sliders Section -->
<div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img class="d-block w-100" src="assets/images/slider 1.jpg" alt="First slide">
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="assets/images/slider 2.jpg" alt="Second slide">
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="assets/images/slider 3.jpg" alt="Third slide">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>  

  <!-- About Us Section -->
  <section class="content-section">
    <div class="container">
      <div class="section-title">
        <h6>ABOUT US</h6>
        <h2>WHO WE ARE?</h2>
        <p class="text-muted">What we Do?</p>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <p>
            Made to Measure Glass UK; a one-stop shop for all your glass requirements. We are a family owned business and our simple philosophy today is the same as it has always been. To provide our clients with a comprehensive range of contemporary architectural and decorative glass products. Our goal is to ensure highly practical yet aesthetically pleasing design solutions to meet each individual brief.
          </p>
          <p>
            We are increasingly gaining the reputation for providing great customer service, value for money and excellent after service care. Our mantra is to achieve excellence in customer service, thus maintaining good relations with all clients involved in each project and retaining their goodwill for the future. Thereby growing the profit of the company year by year to benefit our customers with a service and product range they value.
          </p>
          <h5 class="highlight-title text-center mt-4">Specialists in Bespoke Glass Products</h5>
          <p>
            We specialise in high quality bespoke glass products using only the best toughened glass. We design, manufacture, supply and install a range of high quality glass applications such as Kitchen and Bathroom Splashbacks, Shower Enclosures, Shelves, Glass Doors, Balustrade Systems, Table-tops & Protectors, Toughened Mirrors and Wall Art for the corporate, commercial and domestic market. With the ability to deliver glass nationwide for one-off projects or daily deliveries we have the capabilities to supply a premium service. For all that you need in glass we can supply it.
          </p>
          <p>
            Whatever you’re looking for, our design team is there to offer support and advice on your journey from product choice to installation. Our friendly, professional team is on hand to help you with design decisions, offer feedback on colours, textures and quality, discuss technical issues and installation pros and cons.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Manufacturing & Glass Processing Section -->
  <section class="process-section">
    <div class="container">
      <div class="section-title">
        <h2>Manufacturing & Glass Processing</h2>
      </div>
      <div class="row text-center">
        <div class="col-md-2 col-6 mb-4">
          <div class="service-box">
            <img src="prototype/4mm.png" alt="Cutting">
            <h5>CUTTING</h5>
          </div>
        </div>
        <div class="col-md-2 col-6 mb-4">
          <div class="service-box">
            <img src="prototype/6mm.png" alt="Polishing">
            <h5>POLISHING</h5>
          </div>
        </div>
        <div class="col-md-2 col-6 mb-4">
          <div class="service-box">
            <img src="prototype/8mm.png" alt="Drilling">
            <h5>DRILLING</h5>
          </div>
        </div>
        <div class="col-md-2 col-6 mb-4">
          <div class="service-box">
            <img src="prototype/10mm.png" alt="CNC">
            <h5>CNC</h5>
          </div>
        </div>
        <div class="col-md-2 col-6 mb-4">
          <div class="service-box">
            <img src="prototype/12mm.png" alt="Toughening">
            <h5>TOUGHENING</h5>
          </div>
        </div>
        <div class="col-md-2 col-6 mb-4">
          <div class="service-box">
            <img src="prototype/4mm.png" alt="Painting">
            <h5>PAINTING</h5>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <p>
            We can supply glass from 4mm – 19mm thicknesses in a range of different glass types. We have glass polishing machines for that perfect straight-line finish, any glass can be given a highly bright polished edge. We also have two drilling machines for any holes you require in glass. With the use of state of the art CNC (computer numerical cutting) machines we can produce and polish bespoke pieces in bespoke shapes and sizes that wouldn’t be possible using traditional glass cutting and polishing methods. Whether it be a circle, oval or specialist template you require us to match we can produce this for you. We can also create a bevelled glass finish to simple shapes, such as circles and ovals, as well as more complex shapes in widths ranging from 6mm to 40mm.
          </p>
          <p>
            We also boast our very own paint booth for painting glass for splashbacks, cladding, breakfast bars etc... We can match any Dulux, RAL, Pantone or British Standard colours. We can use low iron glass from 4mm – 19mm for extra clarity of colour. We can make socket cut outs and notches for any intricate shaping you require.
          </p>
        </div>
      </div>
    </div>
  </section>

  <?php include 'enquiry.php'; ?>
<!-- Footer -->
 <?php include 'includes/footer.php'; ?>
</body>
</html>