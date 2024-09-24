<?php
session_start(); // Start the session to track user login status

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
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
            background: url('path/to/your-image.jpg') no-repeat center center fixed;
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
    <div class="w-full max-w-md p-6 bg-gray-800 bg-opacity-90 rounded-2xl shadow-2xl backdrop-filter backdrop-blur-lg border border-gray-600">
        <!-- Header with Title (removed arrow) -->
        <div class="flex items-center justify-center mb-6">
            <h1 class="text-4xl font-extrabold text-center text-yellow-400 shine">Secure PHP Authentication</h1>
        </div>

        <?php if ($isLoggedIn): ?>
            <!-- Content for logged-in users -->
            <p class="text-lg text-center mb-4">Hello, <span class="font-semibold"><?php echo htmlspecialchars($_SESSION['username']); ?></span>!</p>
            <div class="mt-4 flex justify-center space-x-4">
                <a href="./src/auth/logout.php" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-full shadow-md transform hover:scale-105 transition duration-300 hover-pulse">Logout</a>

                <?php if ($_SESSION['role_id'] == 1): ?>
                    <!-- Admin dashboard -->
                    <a href="./dashboard.php" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-md transform hover:scale-105 transition duration-300 shine hover-pulse">Go to Admin Dashboard</a>
                <?php else: ?>
                    <!-- User dashboard -->
                    <a href="./my_dashboard.php" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-md transform hover:scale-105 transition duration-300 shine hover-pulse">Go to User Dashboard</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <!-- Content for guests -->
            <p class="text-center text-gray-300 mb-4">Please login or register to continue.</p>
            <div class="flex flex-col space-y-4">
                <a href="./login.php" class="px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-full text-center shadow-md transform hover:scale-105 transition duration-300 shine hover-pulse">Login</a>
                <a href="./register.php" class="px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-full text-center shadow-md transform hover:scale-105 transition duration-300 shine hover-pulse">Register</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
