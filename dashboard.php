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
    <script>
        tailwind.config = {
            darkMode: 'class',
        };
    </script>
    <style>
        /* Background and animation */
        body {
            background: url('photos/adm.jpg') no-repeat center center fixed;
            background-size: cover;
            animation: gradientBG 15s ease infinite;
        }

        @keyframes gradientBG {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Shine effect */
        .shine {
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.2) 50%, rgba(255, 255, 255, 0.1) 100%);
            background-size: 200% 200%;
            animation: shine 2s ease infinite;
        }

        @keyframes shine {
            0% {
                background-position: 200% center;
            }

            100% {
                background-position: 0 center;
            }
        }

        /* Card and button hover effects */
        .hover-pulse:hover {
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Card styling with shine */
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

        .shine-card {
            position: relative;
            overflow: hidden;
        }

        .shine-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0));
            transform: rotate(30deg);
            animation: shine 2s infinite;
        }

        /* Button effects with shine */
        .button {
            padding: 12px 20px;
            border-radius: 25px;
            font-weight: bold;
            transition: background 0.3s, transform 0.3s;
        }

        .button:hover {
            transform: scale(1.05);
        }

        .shine-button {
            position: relative;
            overflow: hidden;
        }

        .shine-button::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0));
            transform: rotate(30deg);
            animation: shine 2s infinite;
        }
    </style>
</head>

<body class="min-h-screen text-white flex items-center justify-center p-6">
    <div class="bg-dark bg-opacity-90 p-8 rounded-xl shadow-2xl w-full max-w-md space-y-6 border border-secondary backdrop-blur-lg shine">
        <h1 class="text-4xl font-extrabold text-center text-warning shine mb-6 uppercase">Admin Panel</h1>
        <p class="text-center text-gray-300 mb-8 text-lg">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! Manage the application settings and user accounts here.</p>

        <div class="grid grid-cols-1 gap-6">
            <div class="card shine-card">
                <a href="./manage_users.php" class="block w-full py-4 button bg-blue-600 text-white text-lg text-center shine-button">Manage Users</a>
            </div>
            <div class="card shine-card">
                <a href="./site_settings.php" class="block w-full py-4 button bg-purple-600 text-white text-lg text-center shine-button">Site Settings</a>
            </div>
            <div class="card shine-card">
                <a href="./view_logs.php" class="block w-full py-4 button bg-green-600 text-white text-lg text-center shine-button">View Logs</a>
            </div>
            <div class="card shine-card">
                <a href="./src/auth/logout.php" class="block w-full py-4 button bg-red-600 text-white text-lg text-center shine-button">Logout</a>
            </div>
        </div>
    </div>
</body>

</html>
