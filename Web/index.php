<?php
define('ROOT_DIR', '../');

if (!file_exists(ROOT_DIR . 'config/config.php')) {
    die('Missing config/config.php. Please refer to the installation instructions.');
}

require_once(ROOT_DIR . 'Pages/LoginPage.php');
require_once(ROOT_DIR . 'Presenters/LoginPresenter.php');

$page = new LoginPage();

if ($page->LoggingIn()) {
    $page->Login();
}

if ($page->ChangingLanguage()) {
    $page->ChangeLanguage();
}

// Input Sanitization (Crucial)
function sanitizeInput($input) {
    // Use prepared statements or parameterized queries in your SQL interactions.
    // This is the MOST IMPORTANT fix to prevent SQL injection.
    // Example with PDO (replace with your actual database connection):
    
    $pdo = new PDO('your_database_dsn', 'username', 'password'); 
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email"); 
    $stmt->bindParam(':email', $input, PDO::PARAM_STR);
    $stmt->execute();

    // Additional sanitization for non-SQL contexts
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');  // Prevent XSS
    $input = strip_tags($input);                             // Remove HTML tags
    return $input;
}

// Sanitize the 'redirect' parameter
if (isset($_GET['redirect'])) {
    $redirect = sanitizeInput($_GET['redirect']);
} else {
    // Set a safe default redirect
    $redirect = '/Web/dashboard.php'; 
}

$page->PageLoad();

// ... (Rest of your index.php code) 

// Later, when using the $redirect variable:
echo "<input type='hidden' name='resume' value='" . $redirect . "' />";
