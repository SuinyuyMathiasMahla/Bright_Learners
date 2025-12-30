<?php
require_once 'config.php';
$pageTitle = "Welcome to BLOPS";
include 'header.php';
?>

<link rel="stylesheet" href="style.css">

<style>
/* ===============================
   HERO SECTION
================================ */
.hero {
    min-height: 85vh;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 60px 8%;
    background: linear-gradient(
        120deg,
        #1E3A8A,
        #2563eb,
        #3b82f6
    );
    color: #fff;
    gap: 40px;
    flex-wrap: wrap;
}

/* Text content */
.hero-text {
    max-width: 600px;
    animation: slideInLeft 1s ease;
}

.hero-text h1 {
    font-size: clamp(2.5rem, 5vw, 3.5rem);
    margin-bottom: 20px;
    font-weight: 800;
}

.hero-text p {
    font-size: 1.1rem;
    line-height: 1.7;
    opacity: 0.95;
}

/* Action buttons */
.hero-actions {
    display: flex;
    gap: 20px;
    margin-top: 30px;
    animation: slideInRight 1s ease;
}

.btn {
    padding: 14px 28px;
    border-radius: 30px;
    font-weight: 600;
    text-decoration: none;
    font-size: 1rem;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 10px;
}

/* Primary button */
.btn-primary {
    background: #22c55e;
    color: #fff;
    box-shadow: 0 8px 20px rgba(0,0,0,0.25);
}

.btn-primary:hover {
    background: #16a34a;
    transform: translateY(-3px);
}

/* Outline button */
.btn-outline {
    border: 2px solid #fff;
    color: #fff;
}

.btn-outline:hover {
    background: #fff;
    color: #1E3A8A;
}

/* ===============================
   FEATURES SECTION
================================ */
.features {
    padding: 60px 8%;
    background: #f8fafc;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 30px;
}

.feature-card {
    background: #fff;
    padding: 30px 25px;
    border-radius: 16px;
    text-align: center;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    transition: transform 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-8px);
}

.feature-card i {
    font-size: 2.5rem;
    color: #2563eb;
    margin-bottom: 15px;
}

.feature-card h3 {
    color: #1E3A8A;
    margin-bottom: 10px;
}

.feature-card p {
    font-size: 0.95rem;
    color: #555;
}

/* ===============================
   ANIMATIONS
================================ */
@keyframes slideInLeft {
    from { opacity: 0; transform: translateX(-40px); }
    to { opacity: 1; transform: translateX(0); }
}

@keyframes slideInRight {
    from { opacity: 0; transform: translateX(40px); }
    to { opacity: 1; transform: translateX(0); }
}

/* ===============================
   RESPONSIVE
================================ */
@media (max-width: 900px) {
    .hero {
        flex-direction: column;
        text-align: center;
    }

    .hero-actions {
        justify-content: center;
        flex-wrap: wrap;
    }
}
</style>

<!-- HERO SECTION -->
<section class="hero">
    <div class="hero-text">
        <h1>Welcome to BLOPS</h1>
        <p>
            A modern school management platform for concour preparation,
            lecture notes, payments, and continuous assessment — all in one place.
        </p>

        <div class="hero-actions">
            <a href="register.php" class="btn btn-primary">
                <i class="fa fa-user-plus"></i> Get Started
            </a>
            <a href="login.php" class="btn btn-outline">
                <i class="fa fa-sign-in-alt"></i> Login
            </a>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="features">
    <div class="feature-card">
        <i class="fa fa-book"></i>
        <h3>Lecture Materials</h3>
        <p>Access notes, PDFs, and resources uploaded by teachers.</p>
    </div>

    <div class="feature-card">
        <i class="fa fa-graduation-cap"></i>
        <h3>Concour Preparation</h3>
        <p>Prepare effectively with organized academic resources.</p>
    </div>

    <div class="feature-card">
        <i class="fa fa-credit-card"></i>
        <h3>Secure Payments</h3>
        <p>Manage school fees and payments with ease.</p>
    </div>

    <div class="feature-card">
        <i class="fa fa-file-alt"></i>
        <h3>Continuous Assessment</h3>
        <p>View CA marks and academic progress anytime.</p>
    </div>
</section>

<?php include 'footer.php'; ?>
