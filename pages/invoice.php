<?php
include("../connect_db.php");

// Fetch medicines from tblTreatment
$medicines = $con->query("SELECT TreatmentID, Name, Price FROM tbltreatment");

// Auto-increment receipt number
$result = $con->query("SELECT MAX(invoiceID) AS maxNum FROM tblinvoice");

$row = $result->fetch_assoc();
$receiptNumber = $row['maxNum'] ? $row['maxNum'] + 1 : 1;



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Patient info
    $username      = $_POST['username'];
    $address       = $_POST['address'];
    $patientNumber = $_POST['patient_number'];
    $gender        = $_POST['gender'];
    $dob           = $_POST['dob'];

    // Invoice info
    $doctorID      = $_POST['doctorID'];
    $paymentMethod = $_POST['payment_method'];
    $date          = date("Y-m-d"); // or $_POST['date']
    $total         = $_POST['total'];

    $tax =$_POST['tax'];
    $subtotal= $_POST['subtotal'];

    // Medicines
    $medicines = $_POST['medicine'];
    $qtys      = $_POST['qty'];
    $lineTotals= $_POST['line_total'];

    // Insert patient
    $queryPatient = "INSERT INTO tblPatient (Name, Address, Contact, Gender, DOB) 
                     VALUES ('$username', '$address', '$patientNumber', '$gender', '$dob')";
    mysqli_query($con, $queryPatient);
    $patientID = mysqli_insert_id($con);

    //  Insert invoice
    $queryInvoice = "INSERT INTO tblinvoice ( PatientID,DoctorID, DateIssued, TotalAmount) 
                     VALUES ( '$patientID','$doctorID', '$date', '$total')";
    mysqli_query($con, $queryInvoice);
    $invoiceID = mysqli_insert_id($con);

    // Insert invoice details + update stock
    for ($i = 0; $i < count($medicines); $i++) {
        $treatmentID = $medicines[$i];
        $qty         = $qtys[$i];
        $lineTotal   = $lineTotals[$i];
        $subsubtotal =$subtotal[$i];

        // Insert detail
        $queryDetail = "INSERT INTO tblinvoicedetail (InvoiceID, TreatmentID, Qty, Price,Tax,SubTotal) 
                        VALUES ('$invoiceID', '$treatmentID', '$qty', '$lineTotal','$tax','$subsubtotal') ";
        mysqli_query($con, $queryDetail);

        // Update stock
        // $queryStock = "UPDATE tblTreatment SET stock = stock - $qty WHERE TreatmentID = '$treatmentID'";
        // mysqli_query($con, $queryStock);
    }

    echo "<script>alert('Invoice saved successfully!'); window.location='admin.php';</script>";
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invoice</title>
  <style>
    body { margin:0; font-family:Arial,sans-serif; background:#f4f6f9; }
    header { background:teal; color:white; padding:15px; display:flex; align-items:center; font-size:20px; }
    header .icon { margin-right:10px; font-weight:bold; }
    .container { padding:20px; max-width:900px; margin:auto; }
    h2 { margin-bottom:20px;display:flex;justify-content:center; }
    form { background:white; padding:20px; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1); }
    label { display:block; margin:10px 0 5px; font-weight:bold; }
    input, select { padding:8px; border:1px solid #ccc; border-radius:6px; font-size:14px; }
    table { width:100%; border-collapse:collapse; margin-top:20px; }
    th, td { padding:10px; border:1px solid #ddd; text-align:left; }
    th { background:teal; color:white; }
    button { margin-top:20px; width:100%; padding:12px; background:teal; color:white; border:none; border-radius:6px; font-size:18px; cursor:pointer; }
    button:hover { background:#006666; }
  </style>
</head>
<body>
  <header>
    <span class="icon">✚</span> Admin
  </header>

  <div class="container">
    <h2>Surgery Bill Receipt</h2>
    <form method="post" action="invoice.php">
      <p style="display:flex; justify-content:right;"><strong>Receipt Number:</strong> <?php echo $receiptNumber; ?></p>
    
      <div style="display:flex;gap: 55%;">

        <div style="justify-content:left;">
          <label>Username</label>
          <input type="text" name="username" required>
          <label>Patient Number</label>
          <input type="text" name="patient_number" required>
          <label>Responsible Doctor</label>
            <select name="doctorID" required>
              <option value="">Responsible Doctor</option>

              <?php
              $doc = "SELECT UserID,Username FROM tbluser WHERE role='Doctor' ";
              $result = mysqli_query($con, $doc);
              while ($row = mysqli_fetch_assoc($result)):?> 
                <option value="<?php echo $row['UserID']; ?>">
                  <?php echo $row['Username']; ?>
                </option>
              <?php endwhile;
              ?>
            </select>
          <label>Payment Method</label>
          <select name="payment_method" required>
            <option value="CASH">Cash</option>
            <option value="CREDIT CARD">Credit Card</option>
          </select>
        </div>

        <div style=" justify-content:right;">
              <label >Address</label>
              <input type="text" name="address" required>
              <label>Gender</label>
              <input type="text" name="gender" required>
              <label>DOB</label>
              <input type="text" name="dob" required>
          </div>
      </div>


      <h3>Medicines / Services</h3>
      <table id="itemsTable">
        <thead>
          <tr>
            <th>Medicine</th>
            <th>Qty</th>
            <th>price ($)</th>
            <th>line Total</th>
          </tr>
        </thead>
        <tbody>
  <tr>
    <td>
      <select name="medicine[]" class="medicine-select" >
        <option value="">Select Medicine</option>
        <?php 
        $medicines = $con->query("SELECT TreatmentID, Name, Price FROM tblTreatment");
        while($med = $medicines->fetch_assoc()): ?>
          <option value="<?php echo $med['TreatmentID']; ?>" 
                  data-price="<?php echo $med['Price']; ?>">
            <?php echo $med['Name']; ?>
          </option>
        <?php endwhile; ?>
      </select>
    </td>
    <td><input type="number" name="qty[]" min="1" class="qty" ></td>
    <td><input type="number" name="price[]" step="0.01" class="price" readonly></td>
    <td><input type="number" name="line_total[]" step="0.01" class="line-total" readonly></td>
  </tr>
</tbody>

      </table>
      <div style="margin-top:20px; text-align:right;">
        <p><strong>Subtotal:</strong> $<span id="subtotal" >0.00</span></p>
        <p><strong>Tax (0.6%):</strong> $<span id="tax" >0.00</span></p>
        <p><strong>Total:</strong> $<span id="total" >0.00</span></p>
        </div>
        <input type="hidden" id="subtotal_input" name="subtotal">
       <input type="hidden" id="tax_input" name="tax">
        <input type="hidden" id="total_input" name="total">


      <button type="submit">Save Invoice</button>
    </form>
  </div>

<script>
 
document.addEventListener("DOMContentLoaded", function() {
  const table = document.getElementById("itemsTable").getElementsByTagName("tbody")[0];
  const subtotalEl = document.getElementById("subtotal");
  const taxEl = document.getElementById("tax");
  const totalEl = document.getElementById("total");

  function addRow() {
    const newRow = table.rows[0].cloneNode(true);
    newRow.querySelectorAll("input, select").forEach(el => { el.value = ""; });
    table.appendChild(newRow);
  }

     function calculateTotals() {
        let subtotal = 0;
        table.querySelectorAll("tr").forEach(row => {
          const qty   = parseFloat(row.querySelector(".qty").value)   || 1;
          const price = parseFloat(row.querySelector(".price").value) || 0;
          const lineTotal = qty * price;
          if (lineTotal > 0) {
            row.querySelector(".line-total").value = lineTotal.toFixed(2);
            subtotal += lineTotal;
          }
        });
        const tax = subtotal * 0.006; // 0.6%
        const total = subtotal + tax;
        subtotalEl.textContent = subtotal.toFixed(2);
        taxEl.textContent = tax.toFixed(2);
        totalEl.textContent = total.toFixed(2);

        // Update hidden inputs for PHP
        document.getElementById('subtotal_input').value = subtotal.toFixed(2);
        document.getElementById('tax_input').value = tax.toFixed(2);
        document.getElementById('total_input').value = total.toFixed(2);
      }

      table.addEventListener("change", function(e) {
        if (e.target.classList.contains("medicine-select")) {
          const price = e.target.selectedOptions[0].getAttribute("data-price");
          e.target.closest("tr").querySelector(".price").value = price;
        }
        calculateTotals();
        // Auto-add new row if last one is filled
        const lastRow = table.rows[table.rows.length - 1];
        const lastSelect = lastRow.querySelector(".medicine-select");
        const lastQty = lastRow.querySelector(".qty");
        const lastPrice = lastRow.querySelector(".price");
        const lastLineTotal = lastRow.querySelector(".line-total");

        if (lastSelect.value && lastQty.value && lastPrice.value && lastLineTotal.value) {
          addRow();
        }
      });

      table.addEventListener("input", calculateTotals);
  });


</script>
</body>
</html>
<?php $con->close(); ?>
