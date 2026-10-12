# PHP Blog

A small, dependency-free blog built from scratch in PHP using a hand-written
Model–View–Controller (MVC) architecture. It ships with a public front-end
(browse, search, comment) and a role-aware admin area (posts, categories,
comments, users).

![PHP](https://img.shields.io/badge/PHP-8.1%2B-777bb4)
![MySQL](https://img.shields.io/badge/MySQL-8-4479a1)
![No dependencies](https://img.shields.io/badge/dependencies-none-brightgreen)
![License](https://img.shields.io/badge/license-MIT-blue)

---

## Features

**Public site**
- Clean URLs with a central front controller and a small custom router
- Paginated list of posts, single-post pages with comments
- Full-text search across titles and content
- Browse posts by category
- Responsive design (gradient theme, no CSS framework)

**Admin area**
- Role-aware dashboard (administrators vs. authors)
- Create / edit / delete posts, with category assignment
- Authors manage **only their own** posts; admins manage everything
- Category management, comment moderation, user management (admin only)
- Change a user's role, with safeguards against locking yourself out

**Security**
- Passwords hashed with `password_hash()` (bcrypt)
- All SQL through PDO **prepared statements**
- Output escaped with `htmlspecialchars()` (XSS protection)
- **CSRF tokens** on every state-changing request (POST)
- Session regenerated on login (session-fixation protection)
- Role checks enforced server-side on every admin route
- Errors logged, not displayed (`APP_DEBUG` flag)

---

## Tech stack

- **PHP 8.1+** (developed on 8.5) — no frameworks, no Composer
- **MySQL 8** / MariaDB via PDO
- Vanilla **HTML + CSS** (no Bootstrap on the public site)
- Works with Apache (`.htaccess`) or PHP's built-in server

---

## Project structure

```
.
├── index.php                  # Front controller + router + autoloader
├── router.php                 # Router for PHP's built-in server (php -S)
├── config.sample.php          # Configuration template (copy to config.php)
├── config.php                 # Your settings (git-ignored)
├── database.sql               # Database schema
├── seed.sql                   # Optional demo data
├── DEPLOY.md                  # Free-hosting deployment guide
├── .htaccess                  # Apache rewrite + security rules
├── assets/css/style.css       # Design system (public + admin)
└── app/
    ├── controllers/            # BaseController, Home, Single, Category,
    │                           #   Login, Register, Admin
    ├── models/                 # Connection, User, Article, Category, Comment
    └── views/                  # Public views + admin/ views
```

---

## Getting started

### Requirements

- PHP 8.1+ with the `pdo_mysql` extension
- MySQL 8 / MariaDB

### 1. Create the database

```sql
CREATE DATABASE blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Then import the schema (and optional demo data):

```bash
mysql -u root -p blog < database.sql
mysql -u root -p blog < seed.sql      # optional: creates the demo accounts
```

### 2. Configure

```bash
cp config.sample.php config.php
```

Edit `config.php` (or set the environment variables `DB_HOST`, `DB_NAME`,
`DB_USER`, `DB_PASS`, `APP_DEBUG`).

### 3. Run

**Option A — PHP built-in server (quickest):**

```bash
php -S localhost:8000 router.php
```

Open <http://localhost:8000>.

**Option B — Apache / XAMPP:**

Point the document root at the project folder (or drop it in `htdocs/`).
`mod_rewrite` and `AllowOverride All` must be enabled — the included
`.htaccess` handles clean URLs.

### Demo accounts

If you imported `seed.sql`:

| Username | Password   | Role  |
|----------|------------|-------|
| `admin`  | `admin123` | admin |
| `user`   | `user123`  | user  |

Otherwise, create an account on the **Register** page.

---

## Deployment (free hosting)

> **Step-by-step guide: [DEPLOY.md](DEPLOY.md)** — a complete walkthrough for
> deploying to InfinityFree (FTP, MySQL import, config, free HTTPS).

This is a classic Apache + MySQL app, so plain shared hosting works without any
changes. Free options that support PHP + MySQL:

- **InfinityFree** — free, no ads, `.htaccess` supported
- **HelioHost** — free Apache hosting
- **AwardSpace** / **000webhost** — simpler, tighter limits

Steps:

1. Create a database in the host's control panel and import `database.sql`
   (and `seed.sql` if you want the demo data).
2. Upload the project files (FTP or the file manager) **without** `config.php`.
3. Create `config.php` on the server with the host's database credentials
   (or set the environment variables).
4. Open your domain.

> Containers are **not** required. If you prefer a Docker-based platform
> (Render, Koyeb, Fly.io), add a `php:apache` image with `pdo_mysql` and
> `a2enmod rewrite`, plus a free MySQL instance (e.g. Aiven).

---

## License

Released under the [MIT License](LICENSE).
