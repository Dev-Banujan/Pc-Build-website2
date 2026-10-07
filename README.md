# PC Builder

A PHP and MySQL web application for browsing PC components, creating custom PC configurations, saving builds, and managing products through an admin dashboard.

The project also includes a component shop with category browsing, search, stock status, and WhatsApp-based order enquiries.

## Features

### Customer features

- Create an account and sign in securely.
- Browse PC components and ready-made PCs by category.
- Search products and filter them by category.
- Build a custom PC by selecting a CPU, GPU, RAM, storage, motherboard, power supply, and case.
- See the estimated build total update instantly.
- Save named PC builds and view or delete saved builds later.
- Send a pre-filled product enquiry through WhatsApp.

### Admin features

- View platform statistics, including users, builds, products, and average build price.
- Add products with descriptions, pricing, stock status, and images.
- Enable or disable product availability.
- View and delete products.
- View users, change user roles, and delete user accounts.
- View and delete customer PC builds.

## Built With

- PHP
- MySQL / MariaDB
- HTML, CSS, and JavaScript
- XAMPP (Apache and MySQL)

## Prerequisites

Before running the project, install [XAMPP](https://www.apachefriends.org/) and make sure that both **Apache** and **MySQL** are running.

## Installation

1. Clone or download this repository.

2. Copy the `pcbuilder` folder into your XAMPP web root:

   ```text
   C:\xampp\htdocs\
   ```

3. Open phpMyAdmin at:

   ```text
   http://localhost/phpmyadmin
   ```

4. Import `setup.sql`:

   - Select the **Import** tab in phpMyAdmin.
   - Choose `setup.sql` from this project.
   - Click **Import**.

   This creates the `pcbuilder` database, the required tables, and sample products.

5. Check the database settings in `includes/db.php` if your MySQL credentials are different:

   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'pcbuilder');
   ```

6. Configure the WhatsApp number in `includes/config.php`. Use the country code and number without `+`, spaces, or leading zeroes:

   ```php
   define('WHATSAPP_NUMBER', 'your_number_here');
   ```

7. Open the application in your browser:

   ```text
   http://localhost/pcbuilder/
   ```

## Create the Admin Account

After importing the database, open the following page once:

```text
http://localhost/pcbuilder/setup_admin.php
```

Create your admin username, email address, and a strong password. Once the admin account is created, delete `setup_admin.php` from the deployed server for security.

> `setup.sql` already includes the sample catalogue. Do not import `insert_products.sql` as well unless you intentionally want to add another set of product records.

## Project Structure

```text
pcbuilder/
├── admin.php              # Admin dashboard and product management
├── index.php              # Application entry point
├── login.php              # User login
├── logout.php             # Logout handler
├── products.php           # Product catalogue and WhatsApp enquiries
├── register.php           # User registration
├── setup.sql              # Database schema and sample data
├── setup_admin.php        # One-time admin account setup
├── user.php               # Custom PC builder and saved builds
├── css/
│   └── style.css           # Application styles
├── includes/
│   ├── auth.php            # Authentication helpers
│   ├── config.php          # Site and WhatsApp settings
│   └── db.php              # Database connection
└── uploads/                # Product images
```

## Product Categories

The sample catalogue includes CPUs, GPUs, RAM, HDDs, SSDs, motherboards, monitors, prebuilt PCs, keyboards, mice, coolers, power supplies, and cases.

## Security Notes

- Use a strong MySQL password in production and update `includes/db.php` accordingly.
- Replace the WhatsApp number in `includes/config.php` before deployment.
- Delete `setup_admin.php` after creating the administrator account.
- Do not commit production passwords or other sensitive configuration values to a public repository.

## Future Improvements

- Compatibility checking between selected components.
- Shopping cart and online payment integration.
- Order history and order-status tracking.
- Product editing and image removal in the admin panel.
- Email verification and password reset.

## License

This project is intended for educational and personal use. Add a license file before distributing it publicly.
