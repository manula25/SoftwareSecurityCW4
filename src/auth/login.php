<?php
session_start();
require_once '../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password']; // Get the plain-text password from the form
    $block_duration = 15; // in minutes
    $max_attempts = 3;

    $pdo = getDBConnection();

    // Fetch user from the database
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $user_id = $user['id'];

        // Check failed login attempts
        $stmt = $pdo->prepare('SELECT * FROM failed_logins WHERE user_id = ?');
        $stmt->execute([$user_id]);
        $failed_login = $stmt->fetch();

        if ($failed_login) {
            $failed_attempts = $failed_login['failed_attempts'];
            $last_failed_at = new DateTime($failed_login['last_failed_at']);
            $current_time = new DateTime();

            $time_diff = $current_time->diff($last_failed_at)->i;

            // Check if user is blocked
            if ($failed_attempts >= $max_attempts && $time_diff < $block_duration) {
                $remaining_time = $block_duration - $time_diff;
                header("Location: ../../login.php?error=Account%20is%20blocked.%20Try%20again%20in%20$remaining_time%20minutes");
                exit();
            } elseif ($time_diff >= $block_duration) {
                // Reset failed attempts after block duration
                $stmt = $pdo->prepare('UPDATE failed_logins SET failed_attempts = 0 WHERE user_id = ?');
                $stmt->execute([$user_id]);
            }
        }

        // Verify the password
        if (password_verify($password, $user['password_hash'])) {
            // Successful login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['last_activity'] = time(); // Store the current time as last activity
        
            // Reset failed attempts on successful login
            $stmt = $pdo->prepare('DELETE FROM failed_logins WHERE user_id = ?');
            $stmt->execute([$user_id]);
        
            // Logging the login action
            $stmt = $pdo->prepare('INSERT INTO user_logs (user_id, action) VALUES (?, ?)');
            $stmt->execute([$user['id'], 'Login']);
        
            header('Location: ../../');
            exit();
        } else {
            // Failed login attempt
            if ($failed_login) {
                $failed_attempts++;
                $stmt = $pdo->prepare('UPDATE failed_logins SET failed_attempts = ?, last_failed_at = NOW() WHERE user_id = ?');
                $stmt->execute([$failed_attempts, $user_id]);
            } else {
                $stmt = $pdo->prepare('INSERT INTO failed_logins (user_id, failed_attempts, last_failed_at) VALUES (?, 1, NOW())');
                $stmt->execute([$user_id]);
            }

            if ($failed_attempts >= $max_attempts) {
                header("Location: ../../login.php?error=Account%20is%20blocked%20for%2015%20minutes%20due%20to%20multiple%20failed%20login%20attempts");
            } else {
                header('Location: ../../login.php?error=Invalid%20email%20or%20password');
            }
            exit();
        }
    } else {
        // If email is not found, show an error (to prevent user enumeration, show a generic message)
        header('Location: ../../login.php?error=Invalid%20email%20or%20password');
        exit();
    }
}
?>
