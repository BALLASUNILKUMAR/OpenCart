<div class="modal fade" id="enquiryModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="process/enquiry.php" method="post" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Enquiry Form</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input name="name" class="form-control mb-2" placeholder="Name" required>
        <input name="email" class="form-control mb-2" placeholder="Email" required>
        <input name="phone" class="form-control mb-2" placeholder="Phone" required>
        <textarea name="message" class="form-control" rows="3" placeholder="Your message..." required></textarea>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Submit Enquiry</button>
      </div>
    </form>
  </div>
</div>
