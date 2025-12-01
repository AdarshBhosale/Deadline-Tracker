<?php
session_start();
// ✅ Redirect to correct login page if employee not logged in
if (!isset($_SESSION['employee'])) {
  header("Location: user_login.php");
  exit;
}
include("db_connection.php");

$employee = $_SESSION['employee'];
$email    = $_SESSION['employee_email'];

// ✅ Get employee ID from email
$stmt = $conn->prepare("SELECT id FROM employee WHERE email=? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($employee_id);
$stmt->fetch();
$stmt->close();

// ✅ Handle filters (only status now)
$status_filter = $_GET['status'] ?? '';

$query = "
  SELECT wa.id, wa.work, wa.work_status, wa.deadline, wa.created_date, m.name AS manager_name
  FROM work_assigned wa
  JOIN managers m ON wa.manager_id = m.id
  WHERE wa.employee_id = ?
";

$params = [$employee_id];
$types  = "i";

if (!empty($status_filter)) {
  $query .= " AND wa.work_status = ?";
  $params[] = $status_filter;
  $types   .= "s";
}

$query .= " ORDER BY wa.created_date DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Employee Dashboard</title>
  <style>
    body { font-family: "Segoe UI", Tahoma, sans-serif; background: #f8fafc; margin: 0; display: flex; flex-direction: column; min-height: 100vh; }
    .navbar { background: #fff; padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .navbar img { height: 70px; }
    .container { flex: 1; padding: 30px; max-width: 1100px; margin: auto; }
    h1, h2 { text-align: center; margin: 20px 0; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    table th, table td { padding: 12px; border: 1px solid #e5e7eb; text-align: center; }
    table th { background: #3b82f6; color: #fff; }
    .status-select { padding: 6px 10px; border-radius: 6px; border: 1px solid #ccc; font-weight: bold; }
    /* ✅ Status Colors */
    .status-pending { background: #ffedd5; color: #d97706; }
    .status-completed { background: #dcfce7; color: #15803d; }
    .status-notstarted { background: #fee2e2; color: #b91c1c; }
    footer { background: #fff; text-align: center; padding: 15px; font-size: 14px; box-shadow: 0 -2px 10px rgba(0,0,0,0.1); }

    /* ✅ Filter Sidebar */
    .filter-btn { background: #3b82f6; color: white; border: none; padding: 8px 14px; border-radius: 6px; cursor: pointer; }
    .filter-btn:hover { background: #2563eb; }
    .filter-sidebar {
      position: fixed;
      top: 0;
      right: -360px;
      width: 300px;
      height: 100%;
      background: white;
      box-shadow: -2px 0 8px rgba(0,0,0,0.2);
      padding: 20px;
      transition: right 0.3s ease-in-out;
      z-index: 2000;
    }
    .filter-sidebar.open { right: 0; }
    .filter-sidebar h3 { margin-top: 0; }
    .filter-sidebar label { display: block; margin-bottom: 8px; font-weight: bold; }
    .filter-sidebar select { width: 100%; padding: 6px; border-radius: 6px; border: 1px solid #ccc; }
    .filter-sidebar button { margin-top: 15px; width: 100%; padding: 8px; border: none; border-radius: 6px; cursor: pointer; }
    .apply-btn { background: #3b82f6; color: white; }
    .reset-btn { background: #e5e7eb; }
    .close-btn { background: transparent; border: none; font-size: 18px; float: right; cursor: pointer; }

    /* ✅ Overlay */
    .overlay {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.5);
      z-index: 1500;
    }
    .overlay.show { display: block; }

    /* ✅ Align filter button top-left */
    .filter-container {
      display: flex;
      justify-content: flex-start;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <!-- ✅ Navbar -->
  <div class="navbar">
    <img src="assets/logo.png" alt="Logo">
    <div>
      <?php echo date("l, d M Y"); ?> | Welcome, <?php echo htmlspecialchars($employee); ?> |
      <a href="logout.php">Logout</a>
    </div>
  </div>

  <div class="container">
    <h1>Employee Dashboard</h1>
    <h2>My Assigned Work</h2>

    <!-- ✅ Filter Button aligned left -->
    <div class="filter-container">
      <button class="filter-btn" onclick="toggleFilter()">☰ Filter</button>
    </div>

    <?php if ($result->num_rows > 0): ?>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Manager</th>
            <th>Work</th>
            <th>Status</th>
            <th>Deadline</th>
            <th>Assigned On</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): 
            $statusClass = "";
            if ($row['work_status'] == "Completed") $statusClass = "status-completed";
            elseif ($row['work_status'] == "Pending") $statusClass = "status-pending";
            elseif ($row['work_status'] == "Not Started") $statusClass = "status-notstarted";
          ?>
            <tr>
              <td><?php echo $row['id']; ?></td>
              <td><?php echo htmlspecialchars($row['manager_name']); ?></td>
              <td><?php echo htmlspecialchars($row['work']); ?></td>
              <td>
                <select class="status-select <?php echo $statusClass; ?>" 
                        onchange="updateStatus(<?php echo $row['id']; ?>, this.value)">
                  <option value="Pending" <?php if($row['work_status']=="Pending") echo "selected"; ?>>Pending</option>
                  <option value="Completed" <?php if($row['work_status']=="Completed") echo "selected"; ?>>Completed</option>
                  <option value="Not Started" <?php if($row['work_status']=="Not Started") echo "selected"; ?>>Not Started</option>
                </select>
              </td>
              <td><?php echo $row['deadline']; ?></td>
              <td><?php echo $row['created_date']; ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p style="text-align:center;">No work assigned yet.</p>
    <?php endif; ?>
  </div>

  <!-- ✅ Overlay -->
  <div class="overlay" id="overlay" onclick="toggleFilter(false)"></div>

  <!-- ✅ Filter Sidebar -->
  <div class="filter-sidebar" id="filterSidebar">
    <button class="close-btn" onclick="toggleFilter(false)">✖</button>
    <h3>Filter Work</h3>
    <form method="GET" action="">
      <label>Status:</label>
      <select name="status">
        <option value="">All</option>
        <option value="Pending" <?php if($status_filter=="Pending") echo "selected"; ?>>Pending</option>
        <option value="Completed" <?php if($status_filter=="Completed") echo "selected"; ?>>Completed</option>
        <option value="Not Started" <?php if($status_filter=="Not Started") echo "selected"; ?>>Not Started</option>
      </select>
      <button type="submit" class="apply-btn">Apply</button>
      <a href="employee_dashboard.php"><button type="button" class="reset-btn">Reset</button></a>
    </form>
  </div>

  <footer><p>&copy; 2025 Multi Login System. All Rights Reserved.</p></footer>

  <script>
    function updateStatus(id, status) {
      fetch("update_status.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id=" + id + "&status=" + encodeURIComponent(status)
      })
      .then(res => res.json())
      .then(data => {
        if (!data.success) {
          alert("Failed to update status");
        } else {
          location.reload();
        }
      })
      .catch(err => alert("Error: " + err));
    }

    // ✅ Toggle Filter Sidebar + Overlay
    function toggleFilter(force) {
      const sidebar = document.getElementById("filterSidebar");
      const overlay = document.getElementById("overlay");
      const isOpen = sidebar.classList.contains("open");

      if (force === false || isOpen) {
        sidebar.classList.remove("open");
        overlay.classList.remove("show");
      } else {
        sidebar.classList.add("open");
        overlay.classList.add("show");
      }
    }
  </script>
</body>
</html>
