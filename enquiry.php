<?php
include 'includes/db.php';

$success_msg = '';
$error_msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $phone   = mysqli_real_escape_string($conn, $_POST['phone']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $sql = "INSERT INTO enquiries (name, email, phone, subject, message) 
            VALUES ('$name', '$email', '$phone', '$subject', '$message')";

    if ($conn->query($sql) === TRUE) {
        $success_msg = "Thank you! Your enquiry has been submitted successfully.";
    } else {
        $error_msg = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Enquiry Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!--<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card p-4 shadow-sm">
        <h4 class="mb-3">Enquiry Form</h4>
        <?php if ($success_msg): ?>
          <div class="alert alert-success"><?php echo $success_msg; ?></div>
        <?php elseif ($error_msg): ?>
          <div class="alert alert-danger"><?php echo $error_msg; ?></div>
        <?php endif; ?>
        <form method="post" action="">
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="tel" name="phone" class="form-control" required pattern="[0-9]{7,15}" inputmode="numeric" title="Please enter numbers only">
          </div>
          <div class="mb-3">
            <label class="form-label">Subject</label>
            <input type="text" name="subject" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Message</label>
            <textarea name="message" class="form-control" rows="4" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Submit Enquiry</button>
        </form>
      </div>
    </div>
  </div>
</div>-->
<section class="enquiry-section">
  <div class="container">
    <div class="section-title">
      <h2>Enquiry Form</h2>
      <p class="text-muted">Get in touch with us for your glass requirements</p>
    </div>
    <?php if ($success_msg): ?>
          <div class="alert alert-success"><?php echo $success_msg; ?></div>
        <?php elseif ($error_msg): ?>
          <div class="alert alert-danger"><?php echo $error_msg; ?></div>
        <?php endif; ?>
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <form action="" method="POST">
          <div class="row g-3">
            <div class="col-md-6">
              <input type="text" name="name" class="form-control" placeholder="Your Name" required>
            </div>
            <div class="col-md-6">
              <input type="email" name="email" class="form-control" placeholder="Your Email" required>
            </div>
            <div class="col-md-6">
              <input type="tel" name="phone" class="form-control" placeholder="Your Contact Eg:9876543210" required pattern="[0-9]{7,15}" inputmode="numeric">
            </div>
            <div class="col-md-6">
              <input type="text" name="subject" class="form-control" placeholder="Subject">
            </div>
            <div class="col-12">
              <textarea name="message" class="form-control" rows="5" placeholder="Your Message" required></textarea>
            </div>
            <div class="col-12 text-center">
              <button type="submit" class="btn btn-primary px-5">Send Enquiry</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>