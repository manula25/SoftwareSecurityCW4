<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        };
    </script>
    <style>
        /* Background and animation */
        body {
            background: url('photos/ss.jpg') no-repeat center center fixed;
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

        /* Smooth hover effect for inputs */
        input:hover, input:focus {
            transition: transform 0.2s ease-in-out;
            transform: scale(1.02);
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
    </style>
</head>
<body class="flex items-center justify-center min-h-screen text-white p-6">
    <div class="bg-dark bg-opacity-90 p-8 rounded-xl shadow-2xl max-w-md w-full border border-secondary backdrop-blur-lg">
        <h1 class="text-3xl font-extrabold text-center text-warning shine mb-6">User Profile</h1>

        <?php
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require './src/db/connection.php';
        $pdo = getDBConnection();
        $message = '';
        $error = '';

        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $stmt = $pdo->prepare('SELECT username, email FROM users WHERE id = ?');
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
            if (!$user) {
                $error = 'User not found.';
            }
        } else {
            $error = 'You are not logged in.';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_username = trim($_POST['username']);
            $new_email = trim($_POST['email']);
            if (empty($new_username) || empty($new_email)) {
                $error = 'Username and email cannot be empty.';
            } else {
                $stmt = $pdo->prepare('UPDATE users SET username = ?, email = ? WHERE id = ?');
                $stmt->execute([$new_username, $new_email, $user_id]);
                $_SESSION['username'] = $new_username;
                $message = 'Profile updated successfully!';
            }
        }

        if (isset($_POST['deleteAccount'])) {
            try {
                $pdo->beginTransaction();
                $stmt = $pdo->prepare('DELETE FROM user_logs WHERE user_id = ?');
                $stmt->execute([$user_id]);
                $stmt = $pdo->prepare('DELETE FROM failed_logins WHERE user_id = ?');
                $stmt->execute([$user_id]);
                $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
                $stmt->execute([$user_id]);
                $pdo->commit();
                session_destroy();
                header('Location: ./login.php');
                exit();
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = 'Failed to delete the account: ' . $e->getMessage();
            }
        }
        ?>

        <!-- Profile Update Form -->
        <form action="./profile.php" method="POST" class="space-y-6">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-300">Username</label>
                <input type="text" id="username" name="username" required
                    value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>"
                    class="w-full px-4 py-3 bg-gray-700 text-white border border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 placeholder-gray-400">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                <input type="email" id="email" name="email" required
                    value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"
                    class="w-full px-4 py-3 bg-gray-700 text-white border border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 placeholder-gray-400">
            </div>

            <div class="flex justify-between">
                <button type="submit" class="shine w-full py-3 bg-yellow-700 text-white font-semibold rounded-lg hover:bg-yellow-800 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition duration-300 hover-pulse">
                    Update Profile
                </button>
            </div>
        </form>

        <!-- Account Deletion Form -->
        <form action="./profile.php" method="POST" class="mt-6">
            <input type="hidden" name="deleteAccount" value="1">
            <button type="submit" class="shine w-full py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 transition duration-300 hover-pulse">
                Delete Account
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="./my_dashboard.php" class="text-gray-400 hover:text-blue-400 transition duration-200">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
