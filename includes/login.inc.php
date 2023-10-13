<?php
if (!isset($_POST['login-submit'])) :
    exit('File cannot be directly accessed.');
endif;

require "./connection.inc.php";

// Define POST data variables
$email = $_POST['email'];
$password = $_POST['pwd'];

// Start validation
if (empty($email) || empty($password)) :
    header("Location: ../login.php?error=emptyFields");
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) :
    header("Location: ../login.php?error=invalidemail&uid=" . $email);
else :
    // Validation passed, continue with authentication

    // Check if the email exists in the database
    $sql = "SELECT id, email, password, username, user_join, user_role FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) :
        // Verify the password
        if (password_verify($password, $user['password'])) :
            session_start();
            $_SESSION['userId'] = $user['id'];
            $_SESSION['userEmail'] = $user['email'];
            $_SESSION['userUsername'] = $user['username'];
            $_SESSION['userJoin'] = $user['user_join']; // Store the join date in the session
            $_SESSION['userRole'] = $user['user_role'];
            header("Location: ../dashboard.php?loggedIn=true");
            exit();
        else :
            header("Location: ../login.php?error=emailPasswordInvalid=true");
            exit();
        endif;
    else :
        header("Location: ../login.php?error=emailPasswordInvalid=true");
        exit();
    endif;
endif;
