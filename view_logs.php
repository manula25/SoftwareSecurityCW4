<?php 
require './src/db/connection.php';
session_start();

// Check if the user is logged in and has the correct role (admin)
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] !== 1) {
    // If the user is not an admin, redirect to an error page or homepage
    header('Location: ./error.php');
    exit();
}

try {
    // Connect to the database
    $pdo = getDBConnection();
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit();
}

// Fetch all logs
$stmt = $pdo->query('SELECT user_logs.id, users.username, user_logs.action, user_logs.timestamp 
                     FROM user_logs
                     JOIN users ON user_logs.user_id = users.id
                     ORDER BY user_logs.timestamp DESC');
$logs = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure PHP Authentication | View Logs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Background gradient animation */
        body {
            background: linear-gradient(45deg, #2c3e50, #2980b9, #8e44ad, #f39c12);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Element animations */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 1.2s ease-out forwards;
        }

        @keyframes fadeIn {
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .shine {
            position: relative;
            display: inline-block;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
            background-size: 200%;
            animation: shine 3s ease-in-out infinite;
        }

        @keyframes shine {
            0% { background-position: -200%; }
            50% { background-position: 200%; }
            100% { background-position: -200%; }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-5xl p-6 bg-gray-900 bg-opacity-80 rounded-2xl shadow-2xl backdrop-blur-lg border border-gray-700 fade-in">
        <!-- Header with Title -->
        <h1 class="text-4xl font-extrabold text-center text-yellow-400 mb-8 shine">System Logs</h1>

        <!-- Logs Table -->
        <div class="overflow-x-auto mb-8 fade-in" style="animation-delay: 0.2s;">
            <table class="min-w-full bg-gray-800 text-white rounded-lg shadow-lg">
                <thead>
                    <tr class="bg-blue-600 text-left text-sm uppercase font-medium">
                        <th class="py-3 px-6">Username</th>
                        <th class="py-3 px-6">Action</th>
                        <th class="py-3 px-6">Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr class="border-b border-gray-700 hover:bg-gray-600 transition ease-in-out duration-300">
                            <td class="py-4 px-6"><?php echo htmlspecialchars($log['username']); ?></td>
                            <td class="py-4 px-6"><?php echo htmlspecialchars($log['action']); ?></td>
                            <td class="py-4 px-6"><?php echo htmlspecialchars($log['timestamp']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Back to Admin Dashboard Link -->
        <div class="mt-6 text-center">
            <a href="./dashboard.php" class="text-blue-400 hover:text-blue-300 transition duration-200">Back to Admin Dashboard</a>
        </div>
    </div>
</body>
</html>
