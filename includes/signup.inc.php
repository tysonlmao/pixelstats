<?php

// bail early
if (!isset($_POST['register-submit'])) :
    die("file cannot be directly accessed.");
endif;

require "./connection.inc.php";

// superglobals
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$passwordConfirm = $_POST['passwordConfirm'];

// validation
if (empty($username) || empty($email) || empty($password) || empty($passwordConfirm)) :
    // catch empty fields
    header("Location: ../register.php?error=emptyFields&username=$username&email=$email");
elseif (!preg_match("/^[a-zA-Z0-9_.-]{3,20}$/", $username)) :
    // catch bad username
    header("Location: ../register.php?error=invalidUsername&username=$username&email=$email");
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) :
    // catch invalid email
    header("Location: ../register.php?error=invalidEmail&username=$username&email=$email");
elseif ($password !== $passwordConfirm) :
    // catch password mismatch
    header("Location: ../register.php?error=passwordMismatch&username=$username&email=$email");
else :
    // all checks passed, fall through to see if username or email is already taken
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

        /**
         * @todo make signing up also reserve a spot in the user_accounts section
         */
        // Username is not taken, proceed with registration
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $pdo->prepare($sql);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);

        try {
            $stmt->execute();
            header("Location: ../register.php?error=success");
            // header("Location: ../register.php?signup=success");
        } catch (PDOException $e) {
            header("Location: ../register.php?error=sqlerror");
        }
    endif;
endif;
