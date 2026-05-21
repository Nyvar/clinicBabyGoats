<?php
include("../connect_db.php"); // assumes $conn is created here

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date        = $_POST['date'];
    $doctorId    = $_POST['doctor'];
    $patientName = $_POST['patient_name'];
    $age         = $_POST['age'];
    $gender      = $_POST['gender'];

    $stmt = $con->prepare("INSERT INTO tblAppointment (Date, DoctorID, PatientName, Age, Gender) 
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisis", $date, $doctorId, $patientName, $age, $gender);

    if ($stmt->execute()) {
        echo "<script>alert('Appointment booked successfully!'); window.location='bookAppointment.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book Appointment</title>
  <style>
    body { margin:0; font-family:Arial,sans-serif; background:#f4f6f9; }
    header { background:teal; color:white; padding:15px; display:flex; align-items:center; font-size:20px; }
    header .icon { margin-right:10px; font-weight:bold; }
    .container { padding:20px; max-width:600px; margin:auto; }
    h2 { margin-bottom:20px; display:flex; justify-content:center; }
    form { background:white; padding:20px; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1); }
    label { display:block; margin:10px 0 5px; font-weight:bold; }
    input, select { width:100%; padding:10px; border:1px solid #ccc; border-radius:6px; font-size:16px; }
    button { margin-top:20px; width:100%; padding:12px; background:teal; color:white; border:none; border-radius:6px; font-size:18px; cursor:pointer; }
    button:hover { background:#006666; }
  </style>
</head>
<body>
  <header>
    <span class="icon">✚</span> Admin
  </header>

  <div class="container">
    <h2>Book an Appointment</h2>
    <form method="post" action="">
      <label for="date">Select Date</label>
      <input type="date" id="date" name="date" required>

      <label for="doctor">Doctor</label>
      <select id="doctor" name="doctor" required>
        <option value="">Available Doctor</option>
        <?php
          $doctors = $con->query("SELECT DoctorID, Name FROM tblDoctor");
          while($doc = $doctors->fetch_assoc()) {
            echo "<option value='{$doc['DoctorID']}'>{$doc['Name']}</option>";
          }
        ?>
      </select>

      <label for="patient_name">Patient Name</label>
      <input type="text" id="patient_name" name="patient_name" required>

      <label for="age">Age</label>
      <input type="number" id="age" name="age" min="0" required>

      <label for="gender">Gender</label>
      <select id="gender" name="gender" required>
        <option value="M">M</option>
        <option value="F">F</option>
      </select>

      <button type="submit">Booking</button>
    </form>
  </div>
</body>
</html>
<?php $con->close(); ?>
