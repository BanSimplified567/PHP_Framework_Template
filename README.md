# PHP_Framecore

A lightweight, custom-built PHP framework with an MVC-like architecture.

## Description

PHP_Framecore is a simple PHP framework designed for small to medium-sized web applications. It features a straightforward, action-based routing system and a role-based access control mechanism. The framework is built with vanilla PHP, making it easy to understand and extend for developers familiar with core PHP.

## Features

- **MVC-like Architecture:** Separation of concerns with controllers, views, and a data access layer.
- **Action-Based Routing:** A simple routing system managed through a single front-controller (`index.php`).
- **Role-Based Access Control (RBAC):** Built-in support for multiple user roles (e.g., admin, staff, cashier) with permission handling.
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

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/BanSimplified567/PHP_Framecore.git
    cd PHP_Framecore
    ```

2.  **Database Setup:**
    -   Import the `database/phpframecore.sql` file into your MySQL database.
    -   Create a `.env` file by copying the `.env.example` (if one exists) or by creating a new one.

3.  **Configure Environment:**
    -   Update the `.env` file with your database credentials and other environment-specific settings.

    ```dotenv
    DB_HOST=localhost
    DB_USER=root
    DB_PASS=
    DB_NAME=phpframecore
    DB_PORT=3306
    ```

4.  **Web Server Configuration:**
    -   Point your web server's document root to the `public` directory.
    -   Ensure your web server (e.g., Apache) is configured to handle `.htaccess` files to enable the front-controller pattern.

## Usage

The application is accessed through `index.php`, which acts as the front controller. URLs are structured using an `action` parameter.

-   **Example URL:** `http://localhost/index.php?action=login`
-   **Example URL with parameter:** `http://localhost/index.php?action=admin/dashboard`

The routing logic in `public/index.php` maps the `action` parameter to a specific controller and method.

## Contributing

Contributions are welcome. Please follow these steps:

1.  Fork the repository.
2.  Create a new branch (`git checkout -b feature/your-feature`).
3.  Commit your changes (`git commit -m 'Add some feature'`).
4.  Push to the branch (`git push origin feature/your-feature`).
5.  Open a Pull Request.

## License

This project is licensed under the MIT License.
# PHP_Framecore
