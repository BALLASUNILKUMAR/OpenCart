<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db.php';
$user_id = $_SESSION['user_id'];

// Get user's registration date
$user = $conn->query("SELECT created_at FROM users WHERE id = $user_id")->fetch_assoc();
$user_created_at = $user['created_at'];

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Count total notifications (only after user registered)
$total_notifications = $conn->query("
    SELECT COUNT(*) FROM notifications
    WHERE (user_id IS NULL AND created_at >= '$user_created_at')
       OR user_id = $user_id
")->fetch_row()[0];
$total_pages = ceil($total_notifications / $limit);

// Mark as read if requested (not used with AJAX, but kept for fallback)
if (isset($_GET['read_id'])) {
    $read_id = intval($_GET['read_id']);
    $conn->query("UPDATE notifications SET is_read = 1 WHERE id = $read_id AND ((user_id IS NULL AND created_at >= '$user_created_at') OR user_id = $user_id)");
    header("Location: notify.php");
    exit();
}

// Fetch notifications for current page (only after user registered)
$notifications = $conn->query("
    SELECT n.*,
        CASE
            WHEN n.user_id IS NULL THEN
                (SELECT COUNT(*) FROM notification_reads r WHERE r.notification_id = n.id AND r.user_id = $user_id)
            ELSE n.is_read
        END AS is_read
    FROM notifications n
    WHERE (n.user_id IS NULL AND n.created_at >= '$user_created_at')
       OR n.user_id = $user_id
    ORDER BY n.created_at DESC
    LIMIT $limit OFFSET $offset
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>Customer Notifications</title>
  <style>
    .notification-dot {
      position: absolute;
      top: 10px;
      right: 16px;
      width: 14px;
      height: 14px;
      border-radius: 50%;
      background: #90ee90;
      box-shadow: 0 0 8px 2px #90ee90, 0 0 2px 1px #fff inset;
      border: 2px solid #fff;
      z-index: 2;
      animation: shine 1s infinite alternate;
    }
    @keyframes shine {
      from { box-shadow: 0 0 8px 2px #90ee90, 0 0 2px 1px #fff inset; }
      to   { box-shadow: 0 0 16px 6px #90ee90, 0 0 2px 1px #fff inset; }
    }
  </style>
</head>
<body>
<?php include '../includes/navbar.php'; ?>
<div class="container-fluid mt-4">
  <div class="card p-4 shadow-sm mb-4 w-100">
    <div class="card-header">Notifications</div>
    <ul class="list-group list-group-flush">
      <?php while ($n = $notifications->fetch_assoc()): ?>
        <li class="list-group-item notification-item <?php echo $n['is_read'] ? '' : 'fw-bold unread'; ?>"
            data-id="<?php echo $n['id']; ?>" style="cursor:pointer; position:relative;">
          <?php if (!$n['is_read']): ?>
            <span class="notification-dot"></span>
          <?php endif; ?>
          <strong><?php echo htmlspecialchars($n['title']); ?></strong><br>
          <?php echo nl2br(htmlspecialchars($n['message'])); ?><br>
          <small class="text-muted"><?php echo $n['created_at']; ?></small>
        </li>
      <?php endwhile; ?>
    </ul>
    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
      <nav>
        <ul class="pagination justify-content-center mt-3 mb-0">
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
</div>
<?php include '../includes/footer.php'; ?>
<script>
document.querySelectorAll('.notification-item.unread').forEach(item => {
    item.addEventListener('click', function() {
        const id = this.dataset.id;
        fetch('mark_notification_read.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'id=' + encodeURIComponent(id)
        }).then(res => res.text()).then(() => {
            this.classList.remove('fw-bold', 'unread');
            const dot = this.querySelector('.notification-dot');
            if (dot) dot.style.display = 'none';
        });
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>