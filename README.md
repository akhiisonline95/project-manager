# Project Manager - Task Management Application

A lightweight, PHP-based task management application designed for simplicity and ease of use.

## Table of Contents
* [Setup Instructions](#setup-instructions)
* [Project Structure](#project-structure)
* [Test Credentials](#test-credentials)
* [Design Decisions](#design-decisions)

---

## Setup Instructions

### Requirements
* PHP 7.4 or higher
* MySQL 5.7+ or MariaDB
* Composer (optional, if you use external PHP packages)
* Web server (Apache, Nginx with PHP-FPM)
* Internet connection for CDN dependencies (Bootstrap, jQuery, Moment.js) or download them locally

### Steps

1.  **Clone the repository**
    ```bash
    git clone https://github.com/akhiisonline95/project-manager.git
    cd project-manager
    ```

2.  **Set up the database**
    * Create a database (example: `project_manager_db`).
    * Import provided SQL schema located in `database.sql` or run SQL scripts for `tasks`, `task_files`, `users`, `projects` tables.

3.  **Configure database connection**
    * Edit your database config file, for example:
        ```text
        /config/db.php
        ```
    * Set your DB host, name, username, and password appropriately.

4.  **Upload folder permissions**
    * Ensure the `uploads/tasks/` folder exists and is writable by the web server:
    ```bash
    mkdir -p uploads/tasks
    chmod 755 uploads/tasks
    chown www-data:www-data uploads/tasks  # adjust user/group based on your server
    ```

5.  **Serve the project**
    * Configure your web server to serve the project root (`/var/www/html/project-manager` or equivalent).
    * For local testing, you can use PHP built-in server:
    ```bash
    php -S localhost:8000 -t public
    ```
    (Adjust document root to your public or web accessible folder.)

6.  **Access the app**
    * Open in browser: `http://localhost:8000` or your server domain.

---

## Project Structure

```text.
├── .gitignore               # Specifies files and directories to be ignored by Git
├── app/                     # Contains the core application logic (models, views, controllers)
│   ├── config/              # Configuration files
│   ├── controllers/         # Handles user requests and business logic
│   ├── models/              # Manages data and database interactions
│   └── views/               # PHP templates for rendering HTML
├── database.sql             # SQL script for creating the database schema
├── public/                  # Publicly accessible folder (web root)
│   ├── assets/              # Static assets like CSS and JavaScript
│   │   ├── css/             # Stylesheets for styling the application
│   │   └── js/              # JavaScript files for client-side functionality
│   └── index.php            # The single entry point for all requests
├── README.md                # Project documentation
├── system/                  # Core framework files and libraries
│   ├── core/                # Foundational classes for the framework
│   └── libraries/           # Helper classes and custom libraries
└── uploads/                 # Storage for user-uploaded files
    └── tasks/               # Uploads specifically for task attachments
```


### Key Components

* **Controllers:** Handle business logic and route input to models and views.
* **Models:** Interact with the database; represent tasks, users, etc.
* **Views:** Display HTML content; include forms and modals populating data dynamically.
* **Uploads:** A secure folder to store uploaded task files (.pdf, .docx, .jpg, etc.).
* **Libraries used:** Bootstrap 5 for UI, jQuery for DOM manipulation, Moment.js for date handling.

---

### Test Credentials

| Role       | Username | Password | Description |
|:-----------| :--- | :--- | :--- |
| **Admin**  | `admin` | `adminpass` | Full access to all features |
| **Member** | `member1` | `memberpass` | Manage  tasks |

**Note:** The passwords above are for a test/development environment only. Be sure to change them before deployment.

---

### Design Decisions

#### 1. Technology Stack

* **PHP with PDO** for secure database interaction using prepared statements.
* **MySQL** as the RDBMS for structured data storage.
* **Bootstrap 5** for responsive frontend design with modals.
* **jQuery** for UI interactivity and DOM manipulation.
* **Moment.js** for frontend date formatting (e.g., DD-MM-YYYY).
* No heavy PHP frameworks were used for simplicity and easy customization.

#### 2. Architecture

* A basic **MVC pattern** separates the application into Models, Views, and Controllers.
* Task files are handled in a separate `task_files` table, which supports multiple attachments.
* File uploads are sanitized, validated, and saved with unique names to prevent conflicts.
* File paths are stored as **relative URLs** for portability and web access.
* Views include dynamic modals that load and format task data via jQuery and Moment.js for a user-friendly experience.

#### 3. Security

* File uploads are restricted to specific MIME types and extensions (pdf, docx, jpg).
* File size is limited to a reasonable maximum (typically 2-5 MB).
* Uploaded files are named uniquely and stored outside the public code folder.
* Prepared statements prevent **SQL injection**.
* Output is encoded with `htmlspecialchars` to prevent **XSS attacks**.
* Confirmation dialogs are used for file deletion actions.

#### 4. User Experience

* Modal popups provide seamless task editing and file viewing.
* Assigned users' dropdown is populated dynamically.
* Dates are formatted nicely via Moment.js for clarity.
* File lists display original filenames with direct downloadable links.
* File deletion includes confirmation and backend cleanup of physical files.