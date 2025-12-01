<?php
session_start();
if (!isset($_SESSION['manager'])) {
  header("Location: manager_login.php");
  exit;
}
include("db_connection.php");
$employees = $conn->query("SELECT * FROM employee ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manager Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body{font-family:"Segoe UI",Tahoma,sans-serif;background:#f8fafc;margin:0;min-height:100vh;display:flex;flex-direction:column}
    .navbar{background:#fff;padding:15px 40px;display:flex;align-items:center;justify-content:space-between;box-shadow:0 0 10px rgba(0,0,0,.1);position:sticky;top:0;z-index:1000}
    .navbar .logo{width:120px}
    .container{flex:1;padding:20px;max-width:1200px;padding-left:60px}
    .employee-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:20px}
    .card{background:#fff;padding:20px;text-align:center;border-radius:10px;box-shadow:0 6px 15px rgba(0,0,0,.08);cursor:pointer;transition:.3s}
    .card:hover{transform:translateY(-5px)}
    .card i{font-size:40px;color:#3b82f6;margin-bottom:10px}
    footer{background:#fff;text-align:center;padding:15px;font-size:14px;box-shadow:0 -2px 10px rgba(0,0,0,.1)}
    .modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.6);justify-content:center;align-items:center;z-index:2000}
    .modal-content{background:#fff;padding:25px;border-radius:12px;width:95%;max-width:800px;position:relative;box-shadow:0 8px 25px rgba(0,0,0,.2);max-height:90vh;overflow-y:auto}
    .btn{padding:12px 18px;border:none;border-radius:8px;background:linear-gradient(90deg,#06b6d4,#3b82f6);color:#fff;font-weight:600;cursor:pointer;font-size:15px}
    .btn:hover{background:linear-gradient(90deg,#3b82f6,#1d4ed8)}
    .close{position:absolute;top:10px;right:15px;font-size:20px;cursor:pointer;color:#555}
    label{font-weight:600;color:#374151;display:block;margin-bottom:6px}
    input,textarea{width:100%;padding:12px;border:1px solid #d1d5db;border-radius:8px;font-size:15px}
    textarea{resize:none}
    .section{display:none}
    .section.active{display:block}
    .work-item{padding:12px;border:1px solid #e5e7eb;border-radius:8px;margin-bottom:10px;background:#f9fafb}
    .work-item h4{margin:0 0 5px 0;color:#111827}
    .work-item p{margin:0;font-size:14px;color:#374151}
    .modal-header{display:flex;align-items:center;justify-content:center;position:relative;margin-bottom:20px}
    .modal-header h2{margin:0;color:#1d4ed8}
    .back-arrow{position:absolute;left:10px;top:50%;transform:translateY(-50%);cursor:pointer;font-size:18px;color:#1d4ed8;display:none}
  </style>
</head>
<body>
  <div class="navbar">
    <img src="assets/logo.png" class="logo" alt="Logo" />
    <h2 style="margin:0 auto;text-align:center;flex:1;">Manager Dashboard</h2>
    <div style="position:absolute;right:40px;">
      <span><?php echo date("l, d M Y"); ?></span> |
      Welcome, <?php echo $_SESSION['manager']; ?> |
      <a href="logout.php" style="color:#1d4ed8;font-weight:600;text-decoration:none;">Logout</a>
    </div>
  </div>

  <div class="container">
    <h2 style="margin-bottom:20px;">All Employees</h2>
    <div class="employee-grid">
      <?php while ($emp = $employees->fetch_assoc()): ?>
        <div class="card" onclick="openEmployeeModal('<?php echo $emp['id']; ?>','<?php echo $emp['name']; ?>','<?php echo $emp['email']; ?>')">
          <i class="fa-solid fa-user"></i>
          <h3><?php echo $emp['name']; ?></h3>
          <p><?php echo $emp['email']; ?></p>
        </div>
      <?php endwhile; ?>
    </div>
  </div>

  <!-- Employee Modal -->
  <div id="employeeModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal('employeeModal')">&times;</span>

      <!-- Header with centered title and back arrow at corner -->
      <div class="modal-header">
        <div id="backArrow" class="back-arrow" onclick="switchSection('workSection')">
          <i class="fa-solid fa-arrow-left"></i>
        </div>
        <h2>Employee Work</h2>
      </div>

      <!-- Work Assigned Section -->
      <div id="workSection" class="section active">
        <h3>📋 Work Assigned</h3>
        <div id="assignedTasks">Loading...</div>
        <div style="text-align:center;margin-top:15px;">
          <button class="btn" onclick="switchSection('assignSection')">➕ Assign New Work</button>
        </div>
      </div>

      <!-- Assign New Work Section -->
      <div id="assignSection" class="section">
        <h3>➕ Assign New Work</h3>
        <form method="POST" action="save_work.php" style="display:flex;flex-direction:column;gap:18px;">
          <input type="hidden" id="employee_id" name="employee_id">
          <div class="form-group">
            <label for="employee_name">Employee Name</label>
            <input type="text" id="employee_name" name="employee_name" readonly>
          </div>
          <div class="form-group">
            <label for="employee_email">Employee Email</label>
            <input type="text" id="employee_email" name="employee_email" readonly>
          </div>
          <div class="form-group">
            <label for="work">Work Detail</label>
            <textarea id="work" name="work" rows="4" required></textarea>
          </div>
          <div class="form-group">
            <label for="deadline">Deadline</label>
            <input type="text" id="deadline" name="deadline" placeholder="Select Date & Time" required>
          </div>
          <button type="submit" class="btn"><i class="fa-solid fa-paper-plane"></i> Save Work</button>
        </form>
      </div>
    </div>
  </div>

  <footer><p>&copy; 2025 Multi Login System. All Rights Reserved.</p></footer>

  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script>
    function openEmployeeModal(id,name,email){
      document.getElementById('employee_id').value=id;
      document.getElementById('employee_name').value=name;
      document.getElementById('employee_email').value=email;
      document.getElementById('employeeModal').style.display="flex";

      // default to Work Assigned section
      switchSection('workSection');

      // Load assigned work dynamically
      fetch("view_work.php?employee_id="+id)
      .then(res=>res.text())
      .then(data=>{
        document.getElementById('assignedTasks').innerHTML=data;
      })
      .catch(()=>{ document.getElementById('assignedTasks').innerHTML="Error loading tasks"; });
    }

    function closeModal(id){ document.getElementById(id).style.display="none"; }

    function switchSection(sectionId){
      document.querySelectorAll('.section').forEach(sec=>sec.classList.remove('active'));
      document.getElementById(sectionId).classList.add('active');

      // Show back arrow only in assign section
      if(sectionId === 'assignSection'){
        document.getElementById('backArrow').style.display = "block";
      } else {
        document.getElementById('backArrow').style.display = "none";
      }
    }

    flatpickr("#deadline",{ enableTime:true, time_24hr:false, dateFormat:"Y-m-d H:i:S", defaultDate:new Date() });
  </script>
</body>
</html>
