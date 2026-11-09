# PHP_Framecore

A lightweight, custom-built PHP framework with an MVC-like architecture.

## Description

PHP_Framecore is a simple PHP framework designed for small to medium-sized web applications. It features a straightforward, action-based routing system and a role-based access control mechanism. The framework is built with vanilla PHP, making it easy to understand and extend for developers familiar with core PHP.

## Features

- **MVC-like Architecture:** Separation of concerns with controllers, views, and a data access layer.
- **Action-Based Routing:** A simple routing system managed through a single front-controller (`index.php`).
- **Role-Based Access Control (RBAC):** Built-in support for multiple user roles (e.g., admin, staff, user) with permission handling.
- **Environment-Based Configuration:** Uses a `.env` file to manage environment-specific settings like database credentials.
- **Database Abstraction:** Utilizes PHP's PDO for database connectivity, supporting MySQL.
- **Basic Templating:** A simple view rendering system with a base layout structure.
- **Action Logging:** Includes a mechanism to log user actions for auditing purposes.

## Project Structure

```
.
├── app
│   ├── controllers
│   ├── middleware
│   └── views
├── config
│   ├── app.php
│   ├── dbconn.php
│   └── load_env.php
├── database
│   └── phpframecore.sql
├── public
│   ├── index.php
│   └── assets
├── .env
├── .gitignore
└── .htaccess
```

## Installation and Setup

### Step 1: Clone the Repository

```bash
git clone https://github.com/BanSimplified567/PHP_Framecore.git
cd PHP_Framecore
```

### Step 2: Database Setup

1. **Create a MySQL database:**
   ```sql
   CREATE DATABASE phpframecore;
   ```

2. **Import the database schema:**
   ```bash
   mysql -u root -p phpframecore < database/phpframecore.sql
   ```
   
   Or using a GUI tool (phpMyAdmin, MySQL Workbench, etc.):
   - Open your database management tool
   - Select the `phpframecore` database
   - Import the `database/phpframecore.sql` file

3. **Verify the import:**
   ```sql
   USE phpframecore;
   SHOW TABLES;
   ```
   You should see tables like `users`, `roles`, `action_logs`, etc.

### Step 3: Configure Environment

1. **Create the `.env` file:**
   ```bash
   cp .env.example .env
   ```
   (If `.env.example` doesn't exist, create a new `.env` file manually)

2. **Update the `.env` file with your database credentials:**
   ```dotenv
   DB_HOST=localhost
   DB_USER=root
   DB_PASS=your_password_here
   DB_NAME=phpframecore
   DB_PORT=3306
   
   APP_ENV=development
   APP_DEBUG=true
   APP_URL=http://localhost:8000
   ```

### Step 4: Run the Application

#### Option A: Using PHP Built-in Server (Development)

1. **Navigate to the public directory:**
   ```bash
   cd public
   ```

2. **Start the PHP built-in server:**
   ```bash
   php -S localhost:8000
   ```

3. **Access the application:**
   Open your browser and go to:
   - `http://localhost:8000` - Home page
   - `http://localhost:8000/index.php?action=login` - Login page
   - `http://localhost:8000/index.php?action=register` - Registration page
   - `http://localhost:8000/index.php?action=admin/dashboard` - Admin dashboard (requires admin role)

#### Option B: Using Apache/Nginx (Production)

1. **Point your web server's document root to the `public` directory**
2. **Ensure `.htaccess` is enabled** (for Apache) to handle the front-controller pattern
3. **Configure virtual host** to point to the `public` folder

## Usage

The application is accessed through `index.php`, which acts as the front controller. URLs are structured using an `action` parameter.

- **Example URL:** `http://localhost:8000/index.php?action=login`
- **Example URL with parameter:** `http://localhost:8000/index.php?action=admin/dashboard`

The routing logic in `public/index.php` maps the `action` parameter to a specific controller and method.

### Default User Accounts (after importing SQL)

After importing the database, you can log in with:
- **Admin:** `admin@example.com` / `password`
- **Staff:** `staff@example.com` / `password`
- **User:** `user@example.com` / `password`

## Contributing

Contributions are welcome. Please follow these steps:

1. Fork the repository.
2. Create a new branch (`git checkout -b feature/your-feature`).
3. Commit your changes (`git commit -m 'Add some feature'`).
4. Push to the branch (`git push origin feature/your-feature`).
5. Open a Pull Request.

## License

This project is licensed under the MIT License.