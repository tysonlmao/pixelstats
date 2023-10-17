<?php
include "./config.php";
// Bail early if the form was not submitted directly
if (!isset($_POST['register-submit'])) :
    die("File cannot be directly accessed.");
endif;

require "./connection.inc.php";

// Superglobals
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$passwordConfirm = $_POST['passwordConfirm'];

// Verify reCAPTCHA
$recaptchaSecret = RECAPTCHA_SECRET_KEY; // Replace with your Secret Key
$recaptchaResponse = $_POST['g-recaptcha-response'];

$recaptchaVerify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecret&response=$recaptchaResponse");
$recaptchaData = json_decode($recaptchaVerify);

if (!$recaptchaData->success || $recaptchaData->score < 0.5) {
    // Adjust the score threshold as needed
    header("Location: ../register.php?error=recaptchaFailed&username=$username&email=$email");
    exit();
}

// Validation
if (empty($username) || empty($email) || empty($password) || empty($passwordConfirm)) :
    // Catch empty fields
    header("Location: ../register.php?error=emptyFields&username=$username&email=$email");
elseif (!preg_match("/^[a-zA-Z0-9_.-]{3,20}$/", $username)) :
    // Catch bad username
    header("Location: ../register.php?error=invalidUsername&username=$username&email=$email");
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) :
    // Catch invalid email
    header("Location: ../register.php?error=invalidEmail&username=$username&email=$email");
elseif ($password !== $passwordConfirm) :
    // Catch password mismatch
    header("Location: ../register.php?error=passwordMismatch&username=$username&email=$email");
else :
    // All checks passed, fall through to see if username or email is already taken
    $sql_check_username = "SELECT username FROM users WHERE username = :username";
    $stmt_check_username = $pdo->prepare($sql_check_username);
    $stmt_check_username->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt_check_username->execute();

    $sql_check_email = "SELECT email FROM users WHERE email = :email";
    $stmt_check_email = $pdo->prepare($sql_check_email);
    $stmt_check_email->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt_check_email->execute();

    if ($stmt_check_username->rowCount() > 0) :
        header("Location: ../register.php?error=usernameTaken&username=$username&email=$email");
    elseif ($stmt_check_email->rowCount() > 0) :
        header("Location: ../register.php?error=emailTaken&username=$username&email=$email");
    else :

        // All checks passed, proceed to register the user in the users table
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $pdo->prepare($sql);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $newUserId = $pdo->lastInsertId(); // Get the ID of the newly registered user

            // Insert a row into the user_accounts table
            $sql_user_accounts = "INSERT INTO user_accounts (user_id, main_account, alt_account, cactus_kit) VALUES (:user_id, NULL, NULL, NULL)";
            $stmt_user_accounts = $pdo->prepare($sql_user_accounts);
            $stmt_user_accounts->bindParam(':user_id', $newUserId, PDO::PARAM_INT);
            $stmt_user_accounts->execute();

            header("Location: ../register.php?error=success");
        } catch (PDOException $e) {
            header("Location: ../register.php?error=sqlerror");
        }
    endif;
endif;
