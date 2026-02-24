<?php
// login.php - Login Page
session_start();

// If user is already logged in, redirect to calendar
if (isset($_SESSION['user_id'])) {
    header('Location: calendar.php');
    exit();
}

// Include database connection
require_once('../../assets/database.php');

$error = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Validation
    if (empty($email) || empty($password)) {
        $error = 'Lūdzu aizpildiet visus laukus!';
    } else {
        // Check user credentials
        $stmt = mysqli_prepare($savienojums, "SELECT id, username, email, password FROM BU_users WHERE email = ?");
        
        if ($stmt === false) {
            $error = 'Sistēmas kļūda. Lūdzu mēģiniet vēlāk.';
        } else {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            
            if (mysqli_stmt_num_rows($stmt) === 1) {
                mysqli_stmt_bind_result($stmt, $user_id, $username, $user_email, $hashed_password);
                mysqli_stmt_fetch($stmt);
                
                // Verify password
                if (password_verify($password, $hashed_password)) {
                    // Login successful
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['username'] = $username;
                    $_SESSION['email'] = $user_email;
                    
                    // Redirect to calendar
                    header('Location: calendar.php');
                    exit();
                } else {
                    $error = 'Nepareizs e-pasts vai parole!';
                }
            } else {
                $error = 'Nepareizs e-pasts vai parole!';
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ieiet - Budgetiva</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../../assets/image/logo.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <a href="index.php" class="back-link">← Atpakaļ</a>
                <div class="auth-logo">
                    <img src="../../assets/image/logo.png" alt="Budgetiva Logo" class="logo-img">
                    <span class="logo-text">Budgetiva</span>
                </div>
                <h1 class="auth-title">Laipni lūdzam atpakaļ!</h1>
                <p class="auth-subtitle">Ielogojieties, lai turpinātu</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form class="auth-form" id="loginForm" method="POST" action="">
                <div class="form-group">
                    <label for="email" class="form-label">E-pasts</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email"
                        class="form-input" 
                        placeholder="tavs@epasts.lv"
                        required
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Parole</label>
                    <div class="password-input-wrapper">
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            class="form-input" 
                            placeholder="••••••••"
                            required
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" class="checkbox-input">
                        <span>Atcerēties mani</span>
                    </label>
                    <a href="#" class="link">Aizmirsi paroli?</a>
                </div>

                <button type="submit" class="btn btn-primary btn-full">
                    Ieiet
                </button>
            </form>

            <div class="auth-footer">
                <p>Nav konta? <a href="register.php" class="link">Reģistrēties</a></p>
            </div>
        </div>

        <div class="auth-visual">
            <div class="visual-content">
                <h2 class="visual-title">Tavi finanšu mērķi gaida tevi</h2>
                <div class="visual-features">
                    <div class="visual-feature">
                        <span class="visual-icon"><i class="fa-solid fa-chart-simple"></i></span>
                        <span>Detalizēti ienākumu un izdevumu pārskati</span>
                    </div>
                    <div class="visual-feature">
                        <span class="visual-icon"><i class="fa-solid fa-calendar"></i></span>
                        <span>Kalendāra skats visām transakcijām</span>
                    </div>
                    <div class="visual-feature">
                        <span class="visual-icon"><i class="fa-solid fa-sack-dollar"></i></span>
                        <span>Izseko savu budžetu reāllaikā</span>
                    </div>
                    <div class="visual-feature">
                        <span class="visual-icon"><i class="fa-solid fa-bullseye"></i></span>
                        <span>Sasniedz savus finanšu mērķus</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/script.js"></script>
</body>
</html>