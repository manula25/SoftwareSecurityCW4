<?php
require './src/db/connection.php';
session_start();

// Check if the user is logged in and has the correct role (admin)
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] !== 1) {
    header('Location: ./error.html');
    exit();
}

$pdo = getDBConnection();

// Fetch all users with their roles
$stmt = $pdo->query('SELECT users.id, username, email, role_name FROM users JOIN roles ON users.role_id = roles.id');
$users = $stmt->fetchAll();

// Fetch roles for adding/editing users
$stmt_roles = $pdo->query('SELECT id, role_name FROM roles');
$roles = $stmt_roles->fetchAll();

// Handle Add User form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role_id = $_POST['role_id'];

    // Insert the new user
    $stmt = $pdo->prepare('INSERT INTO users (username, email, password, role_id) VALUES (?, ?, ?, ?)');
    $stmt->execute([$username, $email, $password, $role_id]);

    header('Location: manage_users.php');
    exit();
}

// Handle Edit User form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role_id = $_POST['role_id'];

    // Update the user
    $stmt = $pdo->prepare('UPDATE users SET username = ?, email = ?, role_id = ? WHERE id = ?');
    $stmt->execute([$username, $email, $role_id, $user_id]);

    header('Location: manage_users.php');
    exit();
}

// Handle delete user request
if (isset($_GET['delete_id'])) {
    $user_id = $_GET['delete_id'];

    // Delete user-related logs from user_logs and failed_logins
    $stmt = $pdo->prepare('DELETE FROM user_logs WHERE user_id = ?');
    $stmt->execute([$user_id]);

    $stmt = $pdo->prepare('DELETE FROM failed_logins WHERE user_id = ?');
    $stmt->execute([$user_id]);

    // Delete the user
    $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
    $stmt->execute([$user_id]);

    header('Location: manage_users.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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
        <h2 class="text-4xl font-extrabold text-center text-yellow-400 mb-8 shine">Manage Users</h2>

        <!-- Users Table -->
        <div class="overflow-x-auto mb-8 fade-in" style="animation-delay: 0.2s;">
            <table class="min-w-full bg-gray-800 text-white rounded-lg shadow-lg">
                <thead>
                    <tr class="bg-purple-700 text-left text-sm uppercase font-medium">
                        <th class="py-3 px-6">Username</th>
                        <th class="py-3 px-6">Email</th>
                        <th class="py-3 px-6">Role</th>
                        <th class="py-3 px-6">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr class="border-b border-gray-700 hover:bg-gray-600 transition ease-in-out duration-300">
                            <td class="py-4 px-6"><?php echo htmlspecialchars($user['username']); ?></td>
                            <td class="py-4 px-6"><?php echo htmlspecialchars($user['email']); ?></td>
                            <td class="py-4 px-6"><?php echo htmlspecialchars($user['role_name']); ?></td>
                            <td class="py-4 px-6">
                                <a href="manage_users.php?edit_id=<?php echo $user['id']; ?>" class="text-cyan-400 hover:text-cyan-300">Edit</a> |
                                <a href="manage_users.php?delete_id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure?');" class="text-red-400 hover:text-red-300">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Add User Form -->
        <h3 class="text-2xl font-extrabold text-center text-yellow-400 mb-4 shine">Add User</h3>
        <form action="manage_users.php" method="POST" class="flex flex-col space-y-4 fade-in" style="animation-delay: 0.4s;">
            <input type="text" name="username" placeholder="Username" required class="w-full px-4 py-3 bg-gray-700 text-white border border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 transition duration-200">
            <input type="email" name="email" placeholder="Email" required class="w-full px-4 py-3 bg-gray-700 text-white border border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 transition duration-200">
            <input type="password" name="password" placeholder="Password" required class="w-full px-4 py-3 bg-gray-700 text-white border border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 transition duration-200">
            
            <select name="role_id" required class="w-full px-4 py-3 bg-gray-700 text-white border border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 transition duration-200">
                <?php foreach ($roles as $role): ?>
                    <option value="<?php echo $role['id']; ?>"><?php echo $role['role_name']; ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" name="add_user" class="w-full px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-sm transform hover:scale-105 transition duration-300">Add User</button>
        </form>

        <!-- Edit User Form (Shown when edit_id is set in URL) -->
        <?php if (isset($_GET['edit_id'])): ?>
        <?php
            $edit_id = $_GET['edit_id'];
            $stmt_edit = $pdo->prepare('SELECT * FROM users WHERE id = ?');
            $stmt_edit->execute([$edit_id]);
            $edit_user = $stmt_edit->fetch();
        ?>
        <h3 class="text-2xl font-extrabold text-center text-yellow-400 mb-4 shine">Edit User</h3>
        <form action="manage_users.php" method="POST" class="flex flex-col space-y-4 fade-in" style="animation-delay: 0.6s;">
            <input type="hidden" name="user_id" value="<?php echo $edit_user['id']; ?>">
            <input type="text" name="username" value="<?php echo htmlspecialchars($edit_user['username']); ?>" required class="w-full px-4 py-3 bg-gray-700 text-white border border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 transition duration-200">
            <input type="email" name="email" value="<?php echo htmlspecialchars($edit_user['email']); ?>" required class="w-full px-4 py-3 bg-gray-700 text-white border border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 transition duration-200">
            
            <select name="role_id" required class="w-full px-4 py-3 bg-gray-700 text-white border border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 transition duration-200">
                <?php foreach ($roles as $role): ?>
                    <option value="<?php echo $role['id']; ?>" <?php if ($role['id'] == $edit_user['role_id']) echo 'selected'; ?>>
                        <?php echo $role['role_name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" name="edit_user" class="w-full px-4 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg shadow-sm transform hover:scale-105 transition duration-300">Update User</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
