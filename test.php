/* =======================
   Root Variables
======================= */
:root {
    --primary: #0d6efd;
    --primary-dark: #0a58ca;
    --accent: #0dcaf0;
    --bg: #f5f8ff;
    --text: #1f2933;
    --white: #ffffff;
    --danger: #dc3545;
    --success: #198754;
    --card-radius: 12px;
    --card-shadow: 0 4px 12px rgba(0,0,0,0.1);
    --transition: 0.3s ease;
}

/* =======================
   Global Reset
======================= */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', Roboto, system-ui, sans-serif;
}

body {
    background-color: var(--bg);
    color: var(--text);
    line-height: 1.6;
}

/* =======================
   Typography
======================= */
h1, h2, h3, h4, h5, h6 {
    color: var(--primary-dark);
    margin-bottom: 0.8rem;
    font-weight: 600;
}

p {
    margin-bottom: 0.8rem;
}

/* =======================
   Buttons
======================= */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-weight: 500;
    transition: background var(--transition), transform var(--transition), box-shadow var(--transition);
    text-decoration: none;
    color: var(--white);
}

.btn-primary {
    background-color: var(--primary-dark);
}

.btn-primary:hover {
    background-color: var(--primary);
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.2);
}

/* =======================
   Badges
======================= */
.badge {
    display: inline-block;
    padding: 0.2rem 0.5rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--white);
    text-align: center;
}

.badge-success { background-color: var(--success); }
.badge-danger { background-color: var(--danger); }

/* =======================
   Profile
======================= */
.profile-small {
    display: none;
    padding: 1rem;
    background: var(--primary);
    color: var(--white);
    border-radius: var(--card-radius);
    margin: 1rem auto;
    max-width: 1000px;
}

.profile-small h2 {
    font-size: 1.5rem;
    text-align: center;
}

.sprof {
    display: grid;
    grid-template-columns: 1fr 4fr;
    gap: 1rem;
    align-items: center;
}

.profile-pic {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    border: 3px solid var(--white);
    object-fit: cover;
    transition: transform var(--transition);
    margin: 5px;
}

.profile-pic:hover {
    transform: scale(1.05);
}

.user-info {
    display: grid;
    justify-items: start;
}

.full-name {
    font-weight: 600;
}

/* =======================
   Dashboard Layout
======================= */
.dashboard {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    flex-wrap: wrap;
}

.left-sidebar {
    flex: 0 0 230px;
}

.dashboard-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

/* =======================
   Sidebar & Navigation
======================= */
.sidebar {
    background-color: var(--primary);
    padding: 1rem;
    border-radius: var(--card-radius);
    color: var(--white);
}

.side-nav {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-top: 1rem;
}

.side-nav a {
    text-decoration: none;
    color: var(--white);
    background: rgba(255,255,255,0.1);
    padding: 0.5rem 0.8rem;
    border-radius: 6px;
    transition: background var(--transition), transform var(--transition);
}

.side-nav a:hover {
    background: var(--accent);
    transform: translateX(4px);
    font-weight: 600;
}

/* =======================
   Cards
======================= */
.card {
    background: var(--white);
    border-radius: var(--card-radius);
    box-shadow: var(--card-shadow);
    padding: 1.5rem;
    margin: 1rem 0;
    transition: transform var(--transition), box-shadow var(--transition);
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.card h2 {
    font-size: 1.6rem;
    text-align: center;
    color: var(--primary-dark);
    margin-bottom: 1rem;
}

.alert {
    background-color: #ffe6e6;
    border-left: 5px solid var(--danger);
    padding: 0.8rem;
    border-radius: 6px;
    font-weight: bold;
    text-align: center;
}

/* =======================
   Departments Grid
======================= */
.department-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1rem;
}

.department-card {
    text-decoration: none;
    background-color: #f0f7ff;
    border-radius: 10px;
    padding: 1rem;
    text-align: center;
    color: var(--primary-dark);
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    transition: transform var(--transition), background var(--transition), box-shadow var(--transition);
}

.department-card:hover {
    background-color: #e6ffb3;
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}

.department-card h3 {
    margin-bottom: 0.5rem;
    font-size: 1.2rem;
}

.department-card p {
    font-size: 0.9rem;
    color: #555;
}

/* =======================
   Requirements Grid
======================= */
.requirements-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1rem;
}

.req-item {
    background-color: #f8faff;
    padding: 1rem;
    border-radius: 6px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    transition: transform var(--transition);
}

.req-item:hover {
    transform: translateY(-3px);
}

/* =======================
   Responsive Media Queries
======================= */
@media (max-width: 900px) {
    .left-sidebar { display: none; }
    .profile-small { display: block; }
    .dashboard { flex-direction: column; }
}

@media (max-width: 650px) {
    .sprof { grid-template-columns: 1fr; text-align: center; }
    .user-info { justify-items: center; }
    .department-card p { font-size: 0.8rem; }
    .req-item { font-size: 0.85rem; }
}

@media (max-width: 480px) {
    .btn { padding: 0.4rem 0.6rem; font-size: 0.85rem; }
    .card h2 { font-size: 1.4rem; }
}
