<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure PHP Authentication | Register</title>
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
            background: url('photos/reg.jpg') no-repeat center center fixed;
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
            <h1 class="text-4xl font-extrabold text-center text-warning shine">Register</h1>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-500 text-white p-3 rounded-lg mb-6">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <form action="./src/auth/register.php" method="POST" class="space-y-6">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-300">Username</label>
                <input type="text" id="username" name="username" required class="mt-2 w-full px-4 py-3 bg-gray-700 text-white border border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 placeholder-gray-400" placeholder="Enter your username">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                <input type="email" id="email" name="email" required class="mt-2 w-full px-4 py-3 bg-gray-700 text-white border border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 placeholder-gray-400" placeholder="Enter your email">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
                <input type="password" id="password" name="password" required class="mt-2 w-full px-4 py-3 bg-gray-700 text-white border border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 placeholder-gray-400" placeholder="Enter your password">
            </div>

            <div>
                <input type="submit" value="Register" class="w-full py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer transition duration-300 shine hover-pulse">
            </div>
        </form>
        <p class="text-sm text-center text-gray-400 mt-6">Already have an account? <a href="./login.php" class="text-blue-400 hover:text-blue-500 transition duration-200">Login here</a></p>
    </div>
</body>
</html>
