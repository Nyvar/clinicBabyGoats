<?php
include("../connect_db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO tblUser (Username, PasswordHash, Role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!'); window.location='login.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <style>
    body { font-family:Arial; background:#f4f6f9; display:flex; justify-content:center; align-items:center; height:100vh; }
    form { background:white; padding:20px; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1); width:300px; }
    input, select { width:100%; padding:10px; margin:8px 0; border:1px solid #ccc; border-radius:6px; }
    button { width:100%; padding:12px; background:teal; color:white; border:none; border-radius:6px; cursor:pointer; }
    button:hover { background:#006666; }
  </style>
</head>
<body>
  <form method="post">
    <h2>Register</h2>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <select name="role" required>
      <option value="">Select Role</option>
      <option value="doctor">Doctor</option>
      <option value="patient">Patient</option>
      <option value="nurse">Nurse</option>
      <option value="admin">Admin</option>
    </select>
    <button type="submit">Register</button>
  </form>
</body>
</html>
