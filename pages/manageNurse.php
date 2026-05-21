<?php
include("../connect_db.php"); // assumes $con is created here

// Get search term
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

// Base SQL: join Nurse with Room assignments
$sql = "
  SELECT 
    n.NurseID AS ID,
    n.Name,
    GROUP_CONCAT(r.RoomName SEPARATOR ', ') AS Rooms,
    MAX(a.AssignedDate) AS LastAssignedDate
  FROM tblNurse n
  LEFT JOIN tblNurseRoomAssignment a ON n.NurseID = a.NurseID
  LEFT JOIN tblRoom r ON a.RoomID = r.RoomID
";

// Add filtering if search term exists
if ($search !== "") {
    $safeSearch = $con->real_escape_string($search);
    $sql .= " WHERE n.Name LIKE '%$safeSearch%' OR n.PhoneNumber LIKE '%$safeSearch%'";
}

$sql .= " GROUP BY n.NurseID ORDER BY n.NurseID ASC";
$result = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Nurse</title>
  <style>
    body { margin:0; font-family:Arial,sans-serif; background:#f4f6f9; }
    header { background:teal; color:white; padding:15px; display:flex; align-items:center; font-size:20px; }
    header .icon { margin-right:10px; font-weight:bold; }
    .container { padding:20px; }
    .search-bar { margin-bottom:20px; }
    .search-bar input { width:80%; padding:10px; border:1px solid #ccc; border-radius:6px; font-size:16px; }
    .search-bar button { padding:10px 15px; background:teal; color:white; border:none; border-radius:6px; cursor:pointer; }
    table { width:100%; border-collapse:collapse; background:white; box-shadow:0 2px 6px rgba(0,0,0,0.1); }
    th, td { padding:12px; text-align:left; border-bottom:1px solid #ddd; }
    th { background:teal; color:white; }
    tr:hover { background:#f1f1f1; }
  </style>
</head>
<body>
  <header>
    <span class="icon">✚</span> Admin
  </header>

  <div class="container">
    <h2>Manage Nurse</h2>
    <div class="search-bar">
      <form method="get" action="">
        <input type="text" name="search" placeholder="Hinted search text" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
      </form>
    </div>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>PhoneNumber</th>
          <th>Nursing room(s)</th>
          <th>Last Assigned Date</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($row["ID"]); ?></td>
              <td><?php echo htmlspecialchars($row["Name"]); ?></td>
              <td><?php echo htmlspecialchars($row["PhoneNumber"]); ?></td>
              <td><?php echo $row["Rooms"] ? htmlspecialchars($row["Rooms"]) : "Not assigned"; ?></td>
              <td><?php echo $row["LastAssignedDate"] ? htmlspecialchars($row["LastAssignedDate"]) : "N/A"; ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="5">No nurses found</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
<?php $con->close(); ?>
