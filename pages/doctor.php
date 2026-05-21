<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Doctor Dashboard</title>
  <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Arial, sans-serif;
      background-color: #f5f7fa;
    }
    .sidebar {
      width: 220px;
      background-color: #2c3e50; /* theme color */
      color: #fff;
      position: fixed;
      top: 0;
      bottom: 0;
      padding-top: 20px;
    }
        .sidebar h2 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 30px; /* bigger text */
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    color: #fff; /* keep text white for contrast */
    background-color: #50e18c; /* slight green background */

    padding: 12px;
    }

    .sidebar h2::before {
      content: "✚";
      font-weight: bold;
      color: #ededed;
      font-size: 40px; /* even bigger cross */
    }
    .sidebar a {
      display: flex;
      align-items: center;
      gap: 10px;
      color: white;
      padding: 12px 20px;
      text-decoration: none;
      font-size: 15px;
    }
    .sidebar a:hover {
      background-color: #34495e;
    }
    .main {
      margin-left: 240px;
      padding: 20px;
    }
    .header {
      font-size: 28px;
      font-weight: bold;
      margin-bottom: 20px;
      color: #2c3e50; /* theme color */
    }
    .grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      grid-auto-flow: row dense;
    }
    .card {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      padding: 20px;
    }
    .card h3 {
      margin-top: 0;
      margin-bottom: 15px;
      font-size: 18px;
      color: #2c3e50; /* theme color */
    }
    /* Upcoming appointments layout */
    .appointment {
      display: flex;
      justify-content: space-between; /* time left, details right */
      align-items: center;
      margin: 8px 0;
      padding: 8px 0;
      border-bottom: 1px solid #eee;
    }
    .appointment .time {
      font-size: 18px;
      font-weight: bold;
      color: #2c3e50;
    }
    .appointment .details {
      font-size: 14px;
      color: #34495e;
    }
    /* Today’s Tasks styling */
    .task {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin: 8px 0;
      padding: 10px;
      border-radius: 6px;
      background-color: #f9f9f9;
      font-weight: bold;
      color: #2c3e50;
    }
    .task.done {
      background-color: #d5f5e3; /* light green for completed */
      color: #2c3e50;
    }
    .calendar {
      height: 250px;
    }
    /* Recent Patients styling */
    .patient {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: 8px 0;
      padding: 10px;
      border-bottom: 1px solid #eee;
      background-color: #f9f9f9;
      border-radius: 6px;
    }
    .patient .name {
      font-weight: bold;
      font-size: 15px;
      color: #2c3e50; /* theme color */
    }
    .patient .info {
      font-size: 13px;
      color: #7f8c8d;
      text-align: right;
    }
    .view-all {
      display: block;
      margin-top: 10px;
      text-align: right;
      font-size: 13px;
      color: #3498db;
      text-decoration: none;
    }
  </style>
</head>
<body>

<div class="sidebar">
  <h2>Doctor</h2>
  <a href="#">🏠 Dashboard</a>
  <a href="#">👨‍⚕️ Patients</a>
  <a href="#">📅 Appointments</a>
</div>

<div class="main">
  <div class="header">Doctor Dashboard</div>
  <div class="grid">
    <!-- Upcoming Appointments -->
    <div class="card">
      <h3>Upcoming Appointments</h3>
      <div class="appointment">
        <div class="time">9:00 AM</div>
        <div class="details">John Cena (Room 2)</div>
      </div>
      <div class="appointment">
        <div class="time">10:30 AM</div>
        <div class="details">Peter Parker (Room 5)</div>
      </div>
      <div class="appointment">
        <div class="time">11:00 AM</div>
        <div class="details">Jaime Lannister (Room 1)</div>
      </div>
      <div class="appointment">
        <div class="time">12:00 PM</div>
        <div class="details">Robb Stark (Room 3)</div>
      </div>
    </div>

    <!-- Today’s Tasks -->
    <div class="card">
      <h3>Today's Tasks</h3>
      <div class="task done">Update Treatment Notes</div>
      <div class="task">Review Lab Results</div>
    </div>

    <!-- Calendar -->
    <div class="card calendar-card">
      <h3>Calendar</h3>
      <div id="calendar" class="calendar"></div>
    </div>

    <!-- Recent Patients -->
    <div class="card">
      <h3>Recent Patients</h3>
      <div class="patient">
        <div class="name">Jaime Lannister</div>
        <div class="info">Age 50 • Downtown Upper East Side</div>
      </div>
      <div class="patient">
        <div class="name">Robb Stark</div>
        <div class="info">Age 64 • NY Lower East Side</div>
      </div>
      <div class="patient">
        <div class="name">Robb Stark</div>
        <div class="info">Age 67 • NY Upper West Side</div>
      </div>
      <div class="patient">
        <div class="name">Michael B. Jordan</div>
        <div class="info">Age 34 • Seattle Downtown</div>
      </div>
      <a href="#" class="view-all">View All</a>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      events: [
        { title: 'Consultation - John Cena', start: '2026-05-21T09:00:00' },
        { title: 'Consultation - Peter Parker', start: '2026-05-21T10:30:00' }
      ]
    });
    calendar.render();
  });
</script>

</body>
</html>
