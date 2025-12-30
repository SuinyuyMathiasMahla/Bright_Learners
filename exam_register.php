<?php
require_once 'config.php';
requireRole('student');

$defaultReceiver = "683352382";
$message = "";
$status  = null;

// check if fees paid
$feesStmt = $pdo->prepare("SELECT COUNT(*) FROM fee_payments WHERE student_id=? AND status='success'");
$feesStmt->execute([$_SESSION['user_id']]);
$hasPaidFees = $feesStmt->fetchColumn() > 0;

if (!$hasPaidFees) {
    $message = "You must pay fees before registering for exams.";
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_name    = trim($_POST['student_name']);
    $amount          = floatval($_POST['amount']);
    $sender_account  = trim($_POST['sender_account']);
    $exam_name       = $_POST['exam_name'];
    $receiver_account = $defaultReceiver;

    if ($student_name && $amount > 0 && $sender_account && $exam_name) {
        // simulate success/fail logic; for now always success
        $status = 'success';

        $stmt = $pdo->prepare("INSERT INTO exam_registrations (student_id,student_name,exam_name,amount,sender_account,receiver_account,status) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([
            $_SESSION['user_id'],
            $student_name,
            $exam_name,
            $amount,
            $sender_account,
            $receiver_account,
            $status
        ]);

        if ($status === 'success') {
            $message = "Exam registration successful.";
        } else {
            $message = "Payment failed. Please try again.";
        }
    } else {
        $message = "Please fill all required fields.";
    }
}

$pageTitle = "Exam Registration - BLOPS";
include 'header.php';
?>

<style>/* ===== GLOBAL STYLES ===== */
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #e0f7ff, #ffffff);
    margin: 0;
    padding: 0;
    color: #333;
}

a {
    color: #0d6efd;
    text-decoration: none;
    transition: all 0.3s ease;
}

a:hover {
    text-decoration: underline;
}

/* ===== PAGE TITLE ===== */
h1 {
    text-align: center;
    margin-top: 40px;
    font-size: 32px;
    font-weight: 800;
    color: #0d6efd;
    text-shadow: 1px 1px 3px rgba(0,0,0,0.1);
}

/* ===== FORM CONTAINER ===== */
.form-page {
    max-width: 560px;
    margin: 50px auto;
    background: #ffffff;
    padding: 45px 40px;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.form-page:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 50px rgba(0,0,0,0.12);
}

.form-page h2 {
    text-align: center;
    margin-bottom: 30px;
    color: #0d6efd;
    font-size: 24px;
}

/* ===== ALERT BOX ===== */
.alert {
    background: #d1e7ff;
    color: #0b3d91;
    padding: 14px 18px;
    border-radius: 12px;
    margin-bottom: 25px;
    font-weight: 600;
    text-align: center;
    border-left: 6px solid #0d6efd;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}

/* ===== FORM ELEMENTS ===== */
.data-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
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

.form-group input,
.form-group select {
    padding: 14px 16px;
    border-radius: 12px;
    border: 1px solid #ccc;
    font-size: 15px;
    transition: all 0.3s ease;
    background: #fdfdfd;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #0d6efd;
    box-shadow: 0 0 0 4px rgba(13,110,253,0.15);
}

.form-group input:disabled {
    background: #f1f3f5;
    color: #888;
    cursor: not-allowed;
}

/* ===== BUTTONS ===== */
.btn {
    padding: 14px 22px;
    border-radius: 50px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
}

.btn-primary {
    background: linear-gradient(135deg, #28a745, #4ade80);
    color: #fff;
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #218838, #3fc24a);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
}

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
    margin-top: 30px;
    padding: 25px 30px;
    border-radius: 15px;
    background: #f0f8ff;
    border-left: 6px solid #28a745;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.receipt-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.1);
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

/* ===== FOOTER LABEL ===== */
.labels {
    display: block;
    text-align: center;
    margin: 40px 0;
    font-size: 13px;
    color: #777;
    font-weight: 600;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 600px) {
    .form-page {
        margin: 20px;
        padding: 35px 25px;
    }

    h1 {
        font-size: 26px;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<body>
    <h1>Pay for concour Registration</h1>
<section class="form-page">
    <h2>Exam Registration & Payment</h2>
    <?php if ($message): ?>
        <div class="alert"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if ($hasPaidFees): ?>
        <form method="post" class="data-form">
            <div class="form-group">
                <label>Student Name</label>
                <input type="text" name="student_name"
                       value="<?= htmlspecialchars($_SESSION['full_name']) ?>" required>
            </div>
            <div class="form-group">
                <label>Exam Name</label>
                <select name="exam_name" required>
                    <option value="">-- Select Exam --</option>
                    <option value="Nursing">Nursing</option>
                    <option value="Medicine">Medicine</option>
                    <option value="FET">FET</option>
                    <option value="COT">COT</option>
                    <option value="Midwifery">Midwifery</option>
                </select>
            </div>
            <div class="form-group">
                <label>Amount (XAF)</label>
                <input type="number" name="amount" min="1000" step="100" required>
            </div>
            <div class="form-group">
                <label>Sender Account</label>
                <input type="text" name="sender_account" required>
            </div>
            <div class="form-group">
                <label>Receiver Account</label>
                <input type="text" value="<?= $defaultReceiver ?>" disabled>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-credit-card"></i> Pay & Register
            </button>
           <a href="student_dashboard.php">
               Return Home
            </a>
        </form>
    <?php endif; ?>
</section>
    <label class="labels">
    bright_learners 2025@BLOPS Limbe HAPAWISE
    </label>
</body>
<?php include 'footer.php'; ?>