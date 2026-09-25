# Alzikrayat Photo Sharing Application

**Student Name:** مهند جمال محمد عثمان

## Project Name

Alzikrayat is a custom MVC photo-sharing web application developed for the Advanced Web Technologies project.

## Simple Description

The application allows registered users to create accounts, log in securely, upload and manage their own photos, browse a responsive gallery, view photo details, and add comments. The project uses a manual regular-expression router and separates presentation, application logic, and database access.

## Technologies

The project uses PHP 8, MySQL, PDO, HTML5, CSS3, JavaScript, Bootstrap 5, Bootstrap Icons, Apache, and XAMPP. It does not use Laravel, Django, Spring, a content-management system, or a third-party object-relational mapper.

## How to Run

1. Install XAMPP and start **Apache** and **MySQL**.
2. Configure MySQL to use port `3306`.
3. Extract the project folder into `C:\xampp\htdocs\alzikrayat`.
4. Open `http://localhost/phpmyadmin`.
5. Create a database named `alzikrayat`.
6. Import `schema.sql` into that database.
7. Confirm that `config/database.php` contains host `127.0.0.1`, port `3306`, username `root`, and password `1234`.
8. Open `http://localhost/alzikrayat/public/` in a browser.
9. Register an account, log in, upload a photo, browse the gallery, and add a comment.

The application stores uploaded images in `public/images/uploads/`. If the folder is missing, the upload controller creates it automatically when possible. Uploaded images must be JPG, PNG, GIF, or WEBP files under 5 MB.

## Project Structure

The `public` directory contains the front controller and public assets. The `core` directory contains the manual router and shared helpers. Controllers coordinate application actions, models contain prepared SQL queries, and views contain the Bootstrap-based interface. The `schema.sql` file defines the normalized MySQL database.

## Security

Passwords are stored using bcrypt through PHP `password_hash`. Login uses `password_verify`. State-changing forms use CSRF tokens, database operations use prepared statements, rendered values are escaped, and photo deletion verifies ownership. Upload validation checks the upload status, MIME type, file size, destination directory, and write permission.
