========================================
  PC BUILDER WEBSITE — XAMPP Setup Guide
========================================

FILES INCLUDED
--------------
  index.php         — Entry point (redirects by role)
  login.php         — Login page
  register.php      — Register page
  logout.php        — Logout handler
  user.php          — User dashboard & PC Builder
  admin.php         — Admin panel
  setup.sql         — Database setup script
  css/style.css     — Stylesheet
  includes/db.php   — Database connection
  includes/auth.php — Session/auth helpers

HOW TO INSTALL
--------------
1. Start XAMPP — make sure Apache AND MySQL are both running (green).

2. Create the Database:
   a. Open your browser and go to: http://localhost/phpmyadmin
   b. Click "New" in the left sidebar
   c. Name it "pcbuilder" → click Create
   d. Click the "SQL" tab
   e. Open setup.sql from this folder, copy all the text, paste it in, click "Go"

3. Copy this folder to XAMPP:
   - Copy the entire "pcbuilder" folder
   - Paste it into: C:\xampp\htdocs\  (Windows)
                    /Applications/XAMPP/htdocs/  (Mac)

4. Open your browser and go to:
   http://localhost/pcbuilder/

ADMIN LOGIN (pre-created)
--------------------------
  Username: admin
  Password: admin123

  ⚠ Change this password after first login!

USER FEATURES
-------------
  • Register a new account
  • Build a custom PC by selecting:
      CPU, GPU, RAM, Storage, Motherboard, PSU, Case
  • See live total price as you pick components
  • Save and name your builds
  • Delete your builds

ADMIN FEATURES
--------------
  • Dashboard stats (users, builds, avg price)
  • View all registered users
  • Promote users to admin / demote admins to user
  • Delete users (deletes all their builds too)
  • View all PC builds across all users
  • Delete any build

DATABASE SETTINGS (if needed)
------------------------------
  Edit: includes/db.php
  Default settings:
    Host:     localhost
    Username: root
    Password: (empty)
    Database: pcbuilder

  If your MySQL has a password, update DB_PASS in db.php.

========================================
