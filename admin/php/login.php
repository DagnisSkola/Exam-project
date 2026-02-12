<?php
session_start();
require_once('../../assets/database.php');

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_id']) && isset($_SESSION['is_admin'])) {
    header("Location: index.php");
    exit();
}

$error = '';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Lūdzu aizpildiet visus laukus!';
    } else {
        // SQL injection protection
        $stmt = mysqli_prepare($savienojums, "SELECT id, username, password, email FROM BU_admins WHERE username = ? AND is_active = 1");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && mysqli_num_rows($result) === 1) {
            $admin = mysqli_fetch_assoc($result);

            if (password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['username'] = $admin['username'];
                $_SESSION['is_admin'] = true;
                
                $update_stmt = mysqli_prepare($savienojums, "UPDATE BU_admins SET last_login = NOW() WHERE id = ?");
                mysqli_stmt_bind_param($update_stmt, "i", $admin['id']);
                mysqli_stmt_execute($update_stmt);
                mysqli_stmt_close($update_stmt);
                
                header("Location: index.php");
                exit();
            } else {
                $error = 'Nepareizs lietotājvārds vai parole!';
            }
        } else {
            $error = 'Nepareizs lietotājvārds vai parole!';
        }
        
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Pieteikšanās - Budgetar</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../../assets/image/logo.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <img src="../../assets/image/logo.png" alt="Budgetar Logo" class="login-logo">
                <h1 class="login-title">Admin Panel</h1>
                <p class="login-subtitle">Piesakieties, lai pārvaldītu sistēmu</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" class="login-form">
                <div class="form-group">
                    <label for="username" class="form-label">Lietotājvārds</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        class="form-input" 
                        placeholder="Ievadiet lietotājvārdu"
                        required
                        autocomplete="username"
                    >
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Parole</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input" 
                        placeholder="Ievadiet paroli"
                        required
                        autocomplete="current-password"
                    >
                </div>
                
                <button type="submit" class="btn btn-primary btn-full btn-large">
                    <i class="fa-solid fa-unlock-keyhole"></i> Pieteikties
                </button>
            </form>
            
            <div class="auth-footer">
                <a href="../../user/php/index.php" class="link">← Atpakaļ uz sākumlapu</a>
            </div>
        </div>
    </div>
    
    <script src="../js/script.js"></script>
</body>
</html>