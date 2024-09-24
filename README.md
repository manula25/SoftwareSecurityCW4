# Secure Authentication and User Management System

This repository contains the source code for a **Secure Authentication and User Management** web application built using **PHP**, **MySQL**, and **Tailwind CSS**. The application implements a custom **Authentication, Authorization, and Accounting (AAA)** framework to securely manage users, roles, and access permissions.

## Features

- **User Registration and Authentication**:
  - Users can sign up and log in using a secure system with password hashing.
  - Admin users are registered automatically when a username starts with `adm` and ends with `99@` during registration.
  - The system restricts access to sensitive areas based on user roles (admin, user).
  
- **Role-Based Access Control (RBAC)**:
  - Admin users can assign roles (admin/user) to other registered users.
  - Specific functionalities are restricted to certain roles, ensuring a secure and well-managed environment.
  
- **Failed Login Attempts Tracking**:
  - Failed login attempts are tracked, and after a certain number of failed attempts, the user’s account may be locked or further actions triggered.

- **User Logs**:
  - All user activities (e.g., login, log out, role changes) are logged to ensure accountability.
  
- **Password Hashing**:
  - Passwords are hashed using secure hashing algorithms (e.g., Bcrypt) to ensure that even in the event of a data breach, passwords remain protected.
  
- **Session Management**:
  - Secure session handling ensures that user sessions are maintained and protected, avoiding session hijacking.
  
- **Custom Role Assignment by Admin**:
  - Only admin users have the authority to change the roles of other users post-registration.

## Technologies Used

- **PHP**: Backend server-side scripting language.
- **MySQL**: Database used for storing user information, roles, and logs.
- **Tailwind CSS**: For responsive and modern user interface design.
- **Composer**: PHP dependency manager for third-party libraries.
- **Aiven.io**: Cloud-hosted MySQL database platform.

## Application Structure

- **Database Design**:  
  - The database consists of three key tables: `users`, `roles`, and `user_logs`.
  - A `users` table stores credentials (username, password hash, email) and roles.
  - The `roles` table manages user roles (admin, user).
  - A `user_logs` table tracks user actions and timestamps for auditing purposes.

- **User Authentication**:
  - The system uses PHP sessions to manage user logins and permissions.
  - Passwords are securely hashed before storage using Bcrypt.

- **Authorization and Access Control**:
  - The application implements a role-based access control (RBAC) system where admins can assign and modify user roles.
  - Admin users have access to all functionalities, while regular users have limited access.

## Hosting

- The application is deployed on a public server at:  
  **[http://64.227.168.89/SecureAuth](http://64.227.168.89/SecureAuth)**  
  It connects to a MySQL database hosted on **Aiven.io**.

## How to Run Locally

### Prerequisites:
- PHP 7.x or higher
- MySQL or a compatible database
- Composer

### Setup:
1. **Clone the repository**:
   ```bash
   git clone https://github.com/yourusername/SecureAuth.git
   ```
2. **Navigate to the project directory**:
   ```bash
   cd SecureAuth
   ```
3. **Install dependencies**:
   ```bash
   composer install
   ```
4. **Set up the database**:
   - Create a new MySQL database (e.g., `secureauth`).
   - Import the `secureauth.sql` file (if available) to set up tables:
     ```bash
     mysql -u yourusername -p secureauth < database/secureauth.sql
     ```
   - Update the database connection details in the `config.php` file.

5. **Run the application**:
   - If using PHP’s built-in server, run the following command:
     ```bash
     php -S localhost:8000
     ```

6. **Access the application**:
   Open a browser and navigate to `http://localhost:8000`.

### Database Schema
- **users**: Contains user details (username, password hash, email, role_id).
- **roles**: Manages different roles (e.g., admin, user).
- **user_logs**: Logs every user action with timestamps.
- **failed_logins**: Tracks login attempts for security purposes.

### Example Database Queries
To query the database, you can run the following SQL commands:

```sql
-- Insert default roles (admin and user)
INSERT INTO roles (role_name) VALUES ('admin'), ('user');

-- Fetch all user logs
SELECT * FROM user_logs ORDER BY timestamp DESC;

-- Get all users with their roles
SELECT u.username, r.role_name FROM users u
JOIN roles r ON u.role_id = r.id;
```

## Screenshots

### 1. **User Registration**
![Screenshot 2024-09-16 181104](https://github.com/user-attachments/assets/68942627-327d-4787-bb35-cd634b93c6ab)


### 2. **Admin Dashboard**
![Screenshot 2024-09-16 181448](https://github.com/user-attachments/assets/72d3ec2d-f09e-4dc0-a455-f2676779d030)


### 3. **User Logs**
![Screenshot 2024-09-16 181821](https://github.com/user-attachments/assets/0a9a0f15-f39d-433b-9f08-75ae8ce0ecd5)


## Future Improvements

- **Multi-Factor Authentication (MFA)**: Adding email-based or app-based MFA for additional security.
- **Encryption**: Implementing end-to-end encryption for sensitive data transmissions.
- **Account Locking**: Automatically lock accounts after a set number of failed login attempts for added security.
- **Password Reset via Email**: Allowing users to securely reset their password through an email-based token system.

## Acknowledgements
Special thanks to all the open-source libraries, tools, and platforms that helped make this project possible.

## License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.


