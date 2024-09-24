<?php
session_start();

// Set the session timeout duration (10 minutes)
$session_timeout = 10 * 60; // 10 minutes in seconds

// Check if the session has expired
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $session_timeout) {
    // Logging the logout action before destroying session
    require_once '../db/connection.php';
    $pdo = getDBConnection();
    $stmt = $pdo->prepare('INSERT INTO user_logs (user_id, action) VALUES (?, ?)');
    $stmt->execute([$_SESSION['user_id'], 'Logout due to inactivity']);

    // Session expired, destroy it and redirect to login page
    session_unset();
    session_destroy();
    header('Location: ../../login.php?error=Session%20expired%20due%20to%20inactivity');
    exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] !== 1) {
    header('Location: ../../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #3b82f6, #9333ea, #f59e0b);
            background-size: 300% 300%;
            animation: gradientAnimation 10s ease infinite;
            font-family: 'Poppins', sans-serif;
        }
        
        @keyframes gradientAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .shine {
            position: relative;
            overflow: hidden;
        }
        .shine::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.0));
            transform: rotate(30deg);
            animation: shine 2s infinite;
        }
        @keyframes shine {
            0% {
                left: -150%;
                top: 0;
            }
            100% {
                left: 150%;
                top: 0;
            }
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .fade-in {
            animation: fadeIn 1s ease-out;
        }
        .card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover {
            transform: translateY(-10px) scale(1.05);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
        }
        .button {
            padding: 12px 20px;
            border-radius: 25px;
            font-weight: bold;
            transition: background 0.3s, transform 0.3s;
        }
        .button:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen text-white">
    <div class="bg-gray-900 p-8 rounded-xl shadow-2xl max-w-md w-full shine">
        <h1 class="text-4xl font-bold text-center mb-6 uppercase">ADMIN PANEL</h1>
        <p class="text-center text-gray-300 mb-8 text-lg fade-in">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! Manage the application settings and user accounts here.</p>

        <div class="grid grid-cols-1 gap-6">
            <div class="card">
                <a href="./manage_users.php" class="block w-full py-4 button bg-blue-600 text-white text-lg text-center">Manage Users</a>
            </div>
            <div class="card">
                <a href="./site_settings.php" class="block w-full py-4 button bg-purple-600 text-white text-lg text-center">Site Settings</a>
            </div>
            <div class="card">
                <a href="./view_logs.php" class="block w-full py-4 button bg-green-600 text-white text-lg text-center">View Logs</a>
            </div>
            <div class="card">
                <a href="./src/auth/logout.php" class="block w-full py-4 button bg-red-600 text-white text-lg text-center">Logout</a>
            </div>
        </div>
    </div>
</body>
</html>
