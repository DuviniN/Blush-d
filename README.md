# Blush-d

Blush-d is a cosmetic e‑commerce website built with PHP and MySQL. It supports three roles with clear permissions and a simple flow for browsing, purchasing, and managing products.

## Roles and Permissions

- Manager
  - Add new products
  - Add stock to existing products
  - View sales/ordering details

- Admin
  - Edit and delete products and product details
  - Create and manage Manager accounts
  - View product details, customer list, and customer reviews

- Customer
  - Register and log in
  - Browse and buy cosmetic products easily
  - Submit product reviews

Notes
- The site has a landing page as the first page visitors see.
- Customers must register to buy; otherwise they can log in if they already have an account.
- There is already one Admin account in the system. Only Admin can create Manager accounts.
- Admin and Manager cannot self‑register. They receive credentials separately.

Default Credentials (for local testing)
- ADMIN: email `admin@gmail.com`, password `Admin@1234`
- MANAGER: email `manager@gmail.com`, password `Manager@1234`

## Project Structure (high level)

```
Blush-d/
├── pages/               # UI pages (admin, manager, customer, auth)
├── components/          # Reusable UI components
├── server/              # PHP controllers and config
├── assets/              # CSS/JS/Images
└── Dumps/               # SQL dump(s) for database setup
```

## Database Setup (MySQL)

1. Start MySQL (via XAMPP Control Panel).
2. Open phpMyAdmin at http://localhost/phpmyadmin.
3. Create a new database (for example `blush_d`).
4. Import the SQL dump:
   - Go to the Import tab.
   - Choose the dump file located at `Dumps/init.sql` (or the latest dump you have in `Dumps/`).
   - Click Import.
5. Verify tables and initial records (including the default Admin/Manager where applicable).

Database config file
- Update connection settings in `server/config/db.php` to match your local MySQL setup (host, username, password, and database name you created).

## Run with XAMPP

1. Copy or place this project under your XAMPP htdocs folder, for example:
   - `c:\xampp\htdocs\Blush-d`
2. Start Apache and MySQL from the XAMPP Control Panel.
3. Ensure `server/config/db.php` points to your database.
4. Visit the site in your browser:
   - http://localhost/Blush-d

Login/Registration
- Customers register via the registration page.
- Admin and Manager accounts are not created via registration. Use the provided credentials or have Admin create Manager accounts from the Admin interface.

## Tech Stack

- PHP 8.x, MySQL
- HTML, CSS, JavaScript

## Notes for Developers

- Keep SQL dumps in `Dumps/` and update the README with any schema changes.
- Do not expose real production credentials; the defaults above are for local testing only.

