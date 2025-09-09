<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
}

require_once '../includes/db.php';

// Pagination setup
$limit = 20;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch total number of unique enquiries (by name, email, phone, message, created_at)
$total_enquiries = $conn->query("
    SELECT COUNT(*) FROM (
        SELECT MIN(id) FROM enquiries
        GROUP BY name, email, phone, subject, message, created_at
    ) as unique_enquiries
")->fetch_row()[0];
$total_pages = ceil($total_enquiries / $limit);

// Fetch unique enquiries for current page, latest first
$stmt = $conn->prepare("
    SELECT name, email, phone, subject, message, created_at
    FROM enquiries
    WHERE id IN (
        SELECT min_id FROM (
            SELECT MIN(id) as min_id
            FROM enquiries
            GROUP BY name, email, phone, subject, message, created_at
            ORDER BY min_id DESC
            LIMIT ? OFFSET ?
        ) as t
    )
    ORDER BY created_at DESC
");
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$enquiries = $stmt->get_result();

if (isset($_POST['export_excel'])) {
    // Fetch all unique enquiries (no pagination)
    $result = $conn->query("
        SELECT name, email, phone, subject, message, created_at
        FROM enquiries
        WHERE id IN (
            SELECT min_id FROM (
                SELECT MIN(id) as min_id
                FROM enquiries
                GROUP BY name, email, phone, subject, message, created_at
            ) as t
        )
        ORDER BY created_at DESC
    ");

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=enquiries_export_' . date('Ymd_His') . '.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Date', 'Name', 'Email', 'Mobile', 'Subject', 'Message']);
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            date('d M Y, H:i', strtotime($row['created_at'])),
            $row['name'],
            $row['email'],
            $row['phone'],
            $row['subject'],
            $row['message']
        ]);
    }
    fclose($output);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Customer Enquiries</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
  <div class="d-flex align-items-center mb-3">
    <span class="badge bg-primary fs-5 me-3">Total Enquiries: <?php echo $total_enquiries; ?></span>
    <form method="post" action="" class="mb-0">
        <button type="submit" name="export_excel" class="btn btn-success">Export as Excel</button>
    </form>
</div>
  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped mb-0">
          <thead class="table-dark">
            <tr>
              <th>Date</th>
              <th>Name</th>
              <th>Email</th>
              <th>Mobile</th>
              <th>Subject</th>
              <th>Message</th>
              
            </tr>
          </thead>
          <tbody>
            <?php if ($enquiries->num_rows > 0): ?>
              <?php while ($row = $enquiries->fetch_assoc()): ?>
                <tr>
                  <td><?php echo date('d M Y, H:i', strtotime($row['created_at'])); ?></td>
                  <td><?php echo htmlspecialchars($row['name']); ?></td>
                  <td><?php echo htmlspecialchars($row['email']); ?></td>
                  <td><?php echo htmlspecialchars($row['phone']); ?></td>
                  <td><?php echo nl2br(htmlspecialchars($row['subject'])); ?></td>
                  <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                  
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-center">No enquiries found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Pagination -->
  <?php if ($total_pages > 1): ?>
    <nav class="mt-3">
      <ul class="pagination justify-content-center">
        <li class="page-item<?php if ($page <= 1) echo ' disabled'; ?>">
          <a class="page-link" href="?page=<?php echo $page-1; ?>">Previous</a>
        </li>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <li class="page-item<?php if ($i == $page) echo ' active'; ?>">
            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
          </li>
        <?php endfor; ?>
        <li class="page-item<?php if ($page >= $total_pages) echo ' disabled'; ?>">
          <a class="page-link" href="?page=<?php echo $page+1; ?>">Next</a>
        </li>
      </ul>
    </nav>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>