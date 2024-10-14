<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure PHP Authentication | Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        };
    </script>
    <style>
        /* Background and animation */
        body {
            background: url('photos/log.jpg') no-repeat center center fixed;
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
<body class="min-h-screen text-white flex items-center justify-center p-6">
    <form action="./src/auth/login.php" method="POST" class="bg-dark bg-opacity-90 p-8 rounded-xl shadow-2xl w-full max-w-md space-y-6 border border-secondary backdrop-blur-lg">
        <h1 class="text-3xl font-extrabold text-center text-warning shine mb-6">Login to Your Account</h1>

        <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-500 text-white p-3 rounded-lg mb-6">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
        
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email:</label>
            <input type="email" id="email" name="email" required 
                   class="w-full px-4 py-3 bg-gray-700 text-white border border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 placeholder-gray-400" placeholder="Enter your email">
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password:</label>
            <input type="password" id="password" name="password" required 
                   class="w-full px-4 py-3 bg-gray-700 text-white border border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 placeholder-gray-400" placeholder="Enter your password">
        </div>

        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500">
                <label for="remember" class="ml-2 block text-sm text-gray-300">Remember me</label>
            </div>
            <a href="#" class="text-sm text-blue-400 hover:text-blue-500">Forgot password?</a>
        </div>

        <div>
            <input type="submit" value="Login" 
                   class="shine w-full px-4 py-3 bg-yellow-700 text-white font-semibold rounded-lg hover:bg-yellow-800 focus:outline-none focus:ring-2 focus:ring-yellow-500 cursor-pointer transition duration-300 relative overflow-hidden hover-pulse">
        </div>

        <div class="text-center mt-4">
            <p class="text-sm text-gray-400">Don't have an account? <a href="./register.php" class="text-blue-400 hover:text-blue-500">Sign up</a></p>
        </div>
    </form>
</body>
</html>
