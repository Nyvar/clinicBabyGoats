<!-- admin.php -->
<?php
// Start session or authentication check here if needed
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f4f6f9;
    }
    header {
      background: teal;
      color: white;
      padding: 15px;
      display: flex;
      align-items: center;
      font-size: 20px;
    }
    header .icon {
      margin-right: 10px;
      font-weight: bold;
    }
    .sidebar {
      width: 220px;
      background: #2c3e50;
      color: white;
      position: fixed;
      top: 60px;
      bottom: 0;
      display: flex;
    flex-direction: column;   /* stack items vertically */
    justify-content: space-between; /* push last item to bottom */
    padding-top: 20px;
      
    }
    .sidebar a {
      display: block;
      padding: 12px 20px;
      color: white;
      text-decoration: none;
      transition: background 0.3s;
    }
     .sidebar .top-links {
        flex-grow: 1; /* take available space */
    }
    .sidebar a:hover {
      background: #34495e;
    }
    .main {
      margin-left: 240px;
      padding: 30px;
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
    }
    .card {
      background: white;
      border-radius: 8px;
      padding: 30px;
      text-align: center;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      cursor: pointer;
      transition: transform 0.2s;
    }
    .card:hover {
      transform: translateY(-5px);
    }
    .card-icon {
      font-size: 40px;
      margin-bottom: 15px;
      color: teal;
    }
    .card-label {
      font-size: 18px;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <header>
    <span class="icon">✚</span> Admin
  </header>

  <div class="sidebar">
    <div class="top-links">
        <a href="managePatient.php">Search Patient</a>
        <a href="manageMedicine.php">Medicine</a>
        <a href="bookAppointment.php">Appointment</a>
    </div>
    <a href="login.php">Logout</a>
  </div>

  <div class="main">
    <a href="managePatient.php" style="text-decoration:none; color:inherit;">
    <div class="card">
        <div class="card-icon">👥</div>
        <div class="card-label">Manage Patient</div>
    </div>
    </a>

    <a href="manageDoctor.php" style="text-decoration:none; color:inherit;">
        <div class="card">
        <div class="card-icon">👨‍⚕️</div>
        <div class="card-label">Manage Doctor</div>
        </div>
    </a>
    <a href="invoice.php" style="text-decoration:none; color:inherit;">
        <div class="card">
        <div class="card-icon">📋</div>
        <div class="card-label">Create Invoice</div>
        </div>
    </a>
    <a href="manageNurse.php" style="text-decoration:none; color:inherit;">
        <div class="card">
        <div class="card-icon">👩‍⚕️</div>
        <div class="card-label">Manage Nurse</div>
        </div>
</a>
  </div>
</body>
</html>
