<?php
include("../connect_db.php");

// Fetch medicines from tblTreatment
$medicines = $con->query("SELECT TreatmentID, Name, Price FROM tblTreatment");

// Auto-increment receipt number
$result = $con->query("SELECT MAX(invoiceID) AS maxNum FROM tblInvoice");
$row = $result->fetch_assoc();
$receiptNumber = $row['maxNum'] ? $row['maxNum'] + 1 : 1;
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
    <form method="post" action="saveInvoice.php">
      <p style="display:flex; justify-content:right;"><strong>Receipt Number:</strong> <?php echo $receiptNumber; ?></p>

      <label>Username</label>
      <input type="text" name="username" required>

      <label>Address</label>
      <input type="text" name="address" required>

      <label>Patient Number</label>
      <input type="text" name="patient_number" required>

      <label>Payment Method</label>
      <select name="payment_method" required>
        <option value="CASH">Cash</option>
        <option value="CREDIT CARD">Credit Card</option>
      </select>

      <h3>Medicines / Services</h3>
      <table id="itemsTable">
        <thead>
          <tr>
            <th>Medicine</th>
            <th>Qty</th>
            <th>price ($)</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <select name="medicine[]" class="medicine-select" required>
                <option value="">Select Medicine</option>
                <?php while($med = $medicines->fetch_assoc()): ?>
                  <option value="<?php echo $med['TreatmentID']; ?>" data-price="<?php echo $med['Price']; ?>">
                    <?php echo $med['Name']; ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </td>
            <td><input type="number" name="qty[]" min="1" class="qty" required></td>
            <td><input type="number" name="line_total[]" step="0.01" class="line-total" readonly></td>
          </tr>
        </tbody>
      </table>
      <div style="margin-top:20px; text-align:right;">
        <p><strong>Subtotal:</strong> $<span id="subtotal">0.00</span></p>
        <p><strong>Tax (0.6%):</strong> $<span id="tax">0.00</span></p>
        <p><strong>Total:</strong> $<span id="total">0.00</span></p>
        </div>

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
      const qty = parseFloat(row.querySelector(".qty").value) || 0;
      const rate = parseFloat(row.querySelector(".rate").value) || 0;
      const lineTotal = qty * rate;
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
  }

  table.addEventListener("change", function(e) {
    if (e.target.classList.contains("medicine-select")) {
      const price = e.target.selectedOptions[0].getAttribute("data-price");
      e.target.closest("tr").querySelector(".rate").value = price;
    }
    calculateTotals();

    // Auto-add new row if last one is filled
    const lastRow = table.rows[table.rows.length - 1];
    const lastSelect = lastRow.querySelector(".medicine-select");
    const lastQty = lastRow.querySelector(".qty");
    if (lastSelect.value && lastQty.value) {
      addRow();
    }
  });

  table.addEventListener("input", calculateTotals);
});

</script>
</body>
</html>
<?php $con->close(); ?>
