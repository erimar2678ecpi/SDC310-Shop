# SDC310 Shop

Minimal PHP 8 shop built for the SDC310 course. It demonstrates MVC-style controllers, session-backed carts, and an admin CRUD area for managing products.

## Requirements
- PHP 8.0+
- MySQL 8 (MariaDB works)
- Composer (for PSR-4 autoloading)
- Web server pointing to `public/` (Apache + XAMPP is fine)

## Setup
1. **Clone the repo**  
   ```bash
   git clone https://github.com/erimar2678ecpi/SDC310-Shop.git
   cd SDC310-Shop
   ```
2. **Install dependencies** (just sets up the autoloader):  
   ```bash
   composer install
   ```
3. **Environment configuration**  
   - Copy `.env.example` to `.env` and set `DB_HOST`, `DB_NAME`, etc.  
   - Ensure `config/config.php` points `base_path` to your public web root (e.g., `/Week2Shop/public` under XAMPP).
4. **Database**  
   - Create the database defined in `.env`.  
   - Import `sql/schema.sql` then `sql/seed.sql` for starter products and an admin user.
5. **Serve the app**  
   - For Apache/XAMPP, drop the repo inside `htdocs` and browse to `http://localhost/Week2Shop/public`.  
   - For PHP’s built-in server: `php -S localhost:8000 -t public`.

## Usage Highlights
- Catalog lists products with pagination and cart controls.
- Cart enforces quantity limits, recalculates stock on each request, and now routes through a confirmation screen that shows subtotal, 10% shipping, 5% tax, and the grand total before clearing the session.
- Admin area (login with seeded credentials) covers create/update/delete workflows for products.

## Skills Learned / Tech Stack
- PHP 8 + Composer autoloading (PSR-4)
- MySQL/MariaDB with PDO
- Session-backed cart logic and CSRF protection
- Lightweight MVC controllers/views
- Basic accessibility (ARIA roles, keyboard navigation)

## Accessibility & Usability
- Skip-link and focus styling guarantee keyboard users can reach `main` quickly.
- Flash message containers use `role="status"` + `aria-live` for screen readers.
- Buttons have descriptive labels and consistent focus outlines; colors meet WCAG AA contrast (dark text on light backgrounds, buttons ≥ 4.5:1).

## Screenshots
- `docs/screenshots/catalog.png` Catalog Page
- `docs/screenshots/cart.png` Cart Page
- `docs/screenshots/confirm_cart.png` Confirm Page
- `docs/screenshots/admin.png` Admin Page

## Testing Checklist
- Catalog pagination, out-of-stock state, and graceful DB failures.
- Cart flows: add/remove/update, checkout confirmation (valid + missing CSRF), empty cart guard.
- Admin CRUD forms with both valid and invalid payloads.

## Deploy Notes
**Environment checklist**
- [ ] PHP 8.0+ with `pdo_mysql`
- [ ] MySQL credentials configured in `.env`
- [ ] `public/` set as the web root
- [ ] File permissions allow PHP to write session data

**Minimal deployment guide**
1. Copy the repository to the server (e.g., via `git pull` or `scp`).
2. Run `composer install --no-dev`.
3. Configure Apache/Nginx to point the virtual host root to `public/`.
4. Set environment variables or `.env`.
5. Import schema + seed if this is a fresh database.
6. Restart the web server and hit `/catalog` to verify.

## Repository
[SDC310-Shop](https://github.com/erimar2678ecpi/SDC310-Shop)
