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

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] !== 2) {
    header('Location: ../../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure PHP Authentication</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      // Include Tailwind's dark mode support if needed
      tailwind.config = {
        darkMode: 'class',
      };
    </script>
    <style>
        /* Shine effect */
        .shine {
            background: linear-gradient(90deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0.1) 100%);
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

        /* Additional hover effect */
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

        /* Animated background with image */
        body {
            background: url('photos/hotel.png') no-repeat center center fixed;
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
    </style>
</head>
<body class="min-h-screen text-white flex items-center justify-center p-4">
    <div class="w-full max-w-md p-6 bg-dark bg-opacity-90 rounded-2xl shadow-2xl backdrop-filter backdrop-blur-lg border border-secondary">
        <!-- Header with Title -->
        <div class="flex items-center justify-center mb-6">
            <h1 class="text-4xl font-extrabold text-center text-warning shine">Secure PHP Authentication</h1>
        </div>

        <p class="text-lg text-center mb-4">Hello, <span class="font-semibold text-primary"><?php echo htmlspecialchars($_SESSION['username']); ?></span>!</p>
        <p class="text-center text-gray-300 mb-8">This is your dashboard. Here you can manage your account and settings.</p>

        <div class="mt-4 flex flex-col space-y-4">
            <a href="./profile.php" class="block px-4 py-3 bg-success hover:bg-success-dark text-white rounded-full text-center shadow-md transform hover:scale-105 transition duration-300 shine hover-pulse">View Profile</a>
            <a href="./settings.php" class="block px-4 py-3 bg-primary hover:bg-primary-dark text-white rounded-full text-center shadow-md transform hover:scale-105 transition duration-300 shine hover-pulse">Account Settings</a>
            <a href="./src/auth/logout.php" class="block px-4 py-3 bg-danger hover:bg-danger-dark text-white rounded-full text-center shadow-md transform hover:scale-105 transition duration-300 hover-pulse">Logout</a>
        </div>
    </div>
</body>
</html>
