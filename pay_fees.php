<?php
require_once 'config.php';
requireRole('student');

$message = "";
$receiptId = null;
$defaultReceiver = "683352382";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_name     = trim($_POST['student_name']);
    $amount           = floatval($_POST['amount']);
    $student_account  = trim($_POST['student_account']);
    $receiver_account = $defaultReceiver; // fixed as requested

    if ($student_name && $amount > 0 && $student_account) {
        // simulate payment success
        $stmt = $pdo->prepare("INSERT INTO fee_payments (student_id,student_name,amount,student_account,receiver_account,status) VALUES (?,?,?,?,?,?)");
        $stmt->execute([
            $_SESSION['user_id'],
            $student_name,
            $amount,
            $student_account,
            $receiver_account,
            'success'
        ]);
        $receiptId = $pdo->lastInsertId();
        $message = "Payment successful. Your receipt has been generated.";
    } else {
        $message = "Please fill all required fields correctly.";
    }
}

// fetch student account
$st = $pdo->prepare("SELECT student_account FROM students WHERE id = ?");
$st->execute([$_SESSION['user_id']]);
$student = $st->fetch(PDO::FETCH_ASSOC);

$pageTitle = "Pay Fees - BLOPS";
include 'header.php';
?>
<style>
  /* ===== PAGE TITLE ===== */
h1 {
    text-align: center;
    margin-top: 30px;
    font-size: 28px;
    font-weight: 800;
    color: #0d6efd;
}

/* ===== FORM PAGE WRAPPER ===== */
.form-page {
    max-width: 520px;
    margin: 35px auto;
    background: #ffffff;
    padding: 30px 35px;
    border-radius: 14px;
    box-shadow: 0 12px 28px rgba(0,0,0,0.1);
}

.form-page h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #0a58ca;
}

/* ===== ALERT ===== */
.alert {
    background: #e7f1ff;
    color: #084298;
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 15px;
    font-weight: 600;
    text-align: center;
}

/* ===== FORM ===== */
.data-form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 6px;
    color: #555;
}

.form-group input {
    padding: 10px 14px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 15px;
    transition: all 0.3s ease;
}

/* Disabled input */
.form-group input:disabled {
    background: #f1f3f5;
    color: #666;
    cursor: not-allowed;
}

/* Focus effect */
.form-group input:focus {
    outline: none;
    border-color: #0d6efd;
    box-shadow: 0 0 0 2px rgba(13,110,253,0.15);
}

/* Helper text */
.form-group small {
    font-size: 12px;
    color: #888;
    margin-top: 4px;
}

/* ===== BUTTON ===== */
.btn {
    padding: 12px 20px;
    border-radius: 30px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

/* Primary button */
.btn-primary {
    background: #28a745;
    color: #fff;
    border: none;
}

.btn-primary:hover {
    background: #9acd32; /* lemon green */
    color: #000;
    transform: translateY(-2px);
}

/* Outline button */
.btn-outline {
    border: 2px solid #0d6efd;
    color: #0d6efd;
    background: transparent;
}

.btn-outline:hover {
    background: #0d6efd;
    color: #fff;
}

/* ===== RECEIPT BOX ===== */
.receipt-box {
    margin-top: 25px;
    padding: 20px 25px;
    border-radius: 12px;
    background: #f8f9fa;
    border-left: 6px solid #28a745;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
}

.receipt-box h3 {
    margin-top: 0;
    margin-bottom: 12px;
    color: #198754;
}

.receipt-box p {
    margin: 6px 0;
    font-size: 14px;
}

/* ===== FOOT LABEL ===== */
.labels {
    display: block;
    text-align: center;
    margin: 30px 0;
    font-size: 13px;
    color: #777;
    font-weight: 600;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 600px) {
    .form-page {
        margin: 20px;
        padding: 25px;
    }

    h1 {
        font-size: 22px;
    }
}

</style>
<link rel="stylesheet" href="style.css">
<h1>Pay fees</h1>
<section class="form-page">
    <h2>Fees Payment</h2>
    <?php if ($message): ?>
        <div class="alert"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post" class="data-form" id="feeForm">
        <div class="form-group">
            <label>Student Name</label>
            <input type="text" name="student_name"
                   value="<?= htmlspecialchars($_SESSION['full_name']) ?>" required>
        </div>
        <div class="form-group">
            <label>Amount (XAF)</label>
            <input type="number" name="amount" min="1000" step="100" required>
        </div>
        <div class="form-group">
            <label>Student Account</label>
            <input type="text" name="student_account"
                   value="<?= htmlspecialchars($student['student_account']) ?>" required>
        </div>
        <div class="form-group">
            <label>Receiver Account</label>
            <input type="text" value="<?= $defaultReceiver ?>" disabled>
            <small>Default receiver account is fixed.</small>
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-money-bill"></i> Submit Payment
        </button>
    </form>

    <?php if ($receiptId): ?>
        <div class="receipt-box">
            <h3>Payment Receipt</h3>
            <p><strong>Receipt ID:</strong> #<?= $receiptId ?></p>
            <p><strong>Name:</strong> <?= htmlspecialchars($student_name) ?></p>
            <p><strong>Amount:</strong> <?= number_format($amount, 0) ?> XAF</p>
            <p><strong>Student Account:</strong> <?= htmlspecialchars($student_account) ?></p>
            <p><strong>Receiver Account:</strong> <?= htmlspecialchars($defaultReceiver) ?></p>
            <a href="receipt_download.php?type=fees&id=<?= $receiptId ?>" class="btn btn-outline">
                <i class="fa fa-download"></i> Download Receipt (PDF)
            </a>
        </div>
    <?php endif; ?>
</section>
<label class="labels">
    bright_learners 2025@BLOPS Limbe HAPAWISE
    </label>
<?php include 'footer.php'; ?>