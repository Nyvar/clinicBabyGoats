<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f6f7;
      margin: 0;
    }
    header {
      background-color: #01a59a;
      color: white;
      padding: 15px;
      text-align: center;
      font-size: 24px;
      font-weight: bold;
    }
    .sidebar {
      width: 200px;
      background-color: #00796b;
      position: fixed;
      top: 60px;
      bottom: 0;
      padding-top: 20px;
    }
    .sidebar a {
      display: block;
      color: white;
      padding: 12px;
      text-decoration: none;
      font-weight: bold;
    }
    .sidebar a:hover {
      background-color: #004d40;
    }
    .main {
      margin-left: 220px;
      padding: 20px;
    }
    .card-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
    }
    .card {
      background-color: white;
      width: 200px;
      height: 150px;
      border-radius: 10px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.2);
      text-align: center;
      padding: 20px;
      cursor: pointer;
      transition: 0.3s;
    }
    .card:hover {
      transform: scale(1.05);
    }
    .card img {
      width: 50px;
      height: 50px;
    }
    .card h3 {
      margin-top: 10px;
      color: #333;
    }
  </style>
</head>
<body>

<header>
  <img src="hospital_logo.png" alt="Logo" style="vertical-align: middle; width:40px;">
  Admin Dashboard
</header>

<div class="sidebar">
  <a href="#">Search Patient</a>
  <a href="#">Medicine</a>
  <a href="#">Appointment</a>
  <a href="#">Logout</a>
</div>

<div class="main">
  <div class="card-container">
    <div class="card">
      <img src="patient_icon.png" alt="Manage Patient">
      <h3>Manage Patient</h3>
    </div>
    <div class="card">
      <img src="doctor_icon.png" alt="Manage Doctor">
      <h3>Manage Doctor</h3>
    </div>
    <div class="card">
      <img src="invoice_icon.png" alt="Create Invoice">
      <h3>Create Invoice</h3>
    </div>
    <div class="card">
      <img src="nurse_icon.png" alt="Manage Nurse">
      <h3>Manage Nurse</h3>
    </div>
  </div>
</div>

</body>
</html>
