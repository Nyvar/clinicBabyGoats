<?php
session_start();
include("../connect_db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT UserID, PasswordHash, Role FROM tblUser WHERE Username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userId, $hashedPassword, $role);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['UserID'] = $userId;
            $_SESSION['Role']   = $role;

            // Redirect based on role
            if ($role == 'admin') {
                header("Location: adminDashboard.php");
            } elseif ($role == 'doctor') {
                header("Location: doctorDashboard.php");
            } elseif ($role == 'patient') {
                header("Location: patientDashboard.php");
            } elseif ($role == 'nurse') {
                header("Location: nurseDashboard.php");
            }
            exit;
        } else {
            echo "<script>alert('Invalid password');</script>";
        }
    } else {
        echo "<script>alert('User not found');</script>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <style>
    body { font-family:Arial; background:#f4f6f9; display:flex; justify-content:center; align-items:center; height:100vh; }
    form { background:white; padding:20px; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1); width:300px; }
    input { width:100%; padding:10px; margin:8px 0; border:1px solid #ccc; border-radius:6px; }
    button { width:100%; padding:12px; background:teal; color:white; border:none; border-radius:6px; cursor:pointer; }
    button:hover { background:#006666; }
  </style>
</head>
<body>
  <form method="post">
    <h2>Login</h2>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
    <a href="register.php">register</a>
  </form>
</body>
</html>
