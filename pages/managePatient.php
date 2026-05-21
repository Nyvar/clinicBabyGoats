<?php
include("../connect_db.php");
// Fetch patients
// $sql    = "SELECT ID, Name, PhoneNumber, Procedure, Date FROM tblpatient";
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$sql = "
  SELECT 
    p.PatientID AS ID,
    p.Name,
    p.Contact,
    t.Name AS treatment,
    th.DateOfTreatment AS Date
    FROM tblPatient p
  INNER JOIN tblTreatmentHistory th ON p.PatientID = th.PatientID
  INNER JOIN tblTreatment t ON th.TreatmentID = t.TreatmentID
  INNER JOIN tblDoctor d ON th.DoctorID = d.DoctorID
";
if ($search !== "") {
    $safeSearch = $con->real_escape_string($search);
    $sql .= " WHERE p.Name LIKE '%$safeSearch%' OR p.PhoneNumber LIKE '%$safeSearch%'";
}
$sql .= " ORDER BY th.DateOfTreatment DESC";
$result = $con->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Patient</title>
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
    .container {
      padding: 20px;
    }
    .search-bar {
      margin-bottom: 20px;
    }
    .search-bar input {
      width: 80%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 16px;
    }
    .search-bar button { padding:10px 15px; background:teal; color:white; border:none; border-radius:6px; cursor:pointer; }
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
    th {
      background: teal;
      color: white;
    }
    tr:hover {
      background: #f1f1f1;
    }
  </style>
</head>
<body>
  <header>
    <span class="icon">✚</span> Admin
  </header>
  <div class="container">
    <div class="search-bar">
      <form method="get" action="">
        <input type="text" name="search" placeholder="Entered search text" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
      </form>
    </div>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>PhoneNumber</th>
          <th>Procedure</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($row["ID"]); ?></td>
              <td><?php echo htmlspecialchars($row["Name"]); ?></td>
              <td><?php echo htmlspecialchars($row["Contact"]); ?></td>
              <td><?php echo htmlspecialchars($row["TreatmentName"]); ?></td>
              <td><?php echo htmlspecialchars($row["DateOfTreatment"]); ?></td>
              <td><?php echo htmlspecialchars($row["DoctorName"]); ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="6">No treatment history found</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>

<?php
$con->close();
?>
