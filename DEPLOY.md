# Deploying to InfinityFree

A complete walkthrough for putting this blog online, for free, on
[InfinityFree](https://infinityfree.net) (Apache + PHP + MySQL, no Docker, no
SSH). Total time: about 20–30 minutes.

---

## Before you start

- The project folder on your computer (`config.php`, `app/`, `assets/`, …).
- An FTP client — [FileZilla](https://filezilla-project.org) is recommended.
- A web browser.

> You do **not** need to change any code. The app uses root-relative links and
> `__DIR__`-based includes, so it runs from any domain root as-is.

---

## Step 1 — Create the hosting account

1. Sign up at <https://infinityfree.net> and verify your email.
2. Create a hosting account and choose a **free subdomain**
   (e.g. `myblog.rf.gd` or `myblog.infinityfreeapp.com`).
3. Open the account's **Control Panel** and note these three things, because
   you'll need them later:

   | What | Where | Looks like |
   |---|---|---|
   | FTP host / user / password | **FTP Details** | `ftpupload.net`, `if0_XXXXXXXX`, *(your password)* |
   | MySQL host | **MySQL Databases** | `sqlXXX.infinityfree.com` ← **not** `localhost` |
   | MySQL user / DB name | **MySQL Databases** | `if0_XXXXXXXX` / `if0_XXXXXXXX_blog` |

---

## Step 2 — Create the MySQL database

1. Control Panel → **MySQL Databases**.
2. Create a database, e.g. name it `blog`. InfinityFree prefixes it for you, so
   the full name becomes `if0_XXXXXXXX_blog`. **Write it down exactly.**
3. Your database username is your account username, e.g. `if0_XXXXXXXX`.
4. Set a password for the database / note the one shown.

---

## Step 3 — Import the schema

1. Control Panel → **phpMyAdmin**.
2. In the left sidebar, click your database (`if0_XXXXXXXX_blog`) to select it.
3. Click the **Import** tab.
4. **Choose File** → select `database.sql` from the project → **Go**.
   (Optionally import `seed.sql` afterwards to get the demo accounts and sample
   posts.)
5. You should now see 5 tables: `users`, `categories`, `articles`, `comments`,
   `article_categories`.

> The SQL file contains **no** `CREATE DATABASE` statement — always select the
> database first, then import.

---

## Step 4 — Point the app at the database

`config.php` (git-ignored) **auto-detects** where it runs: on `localhost` it uses
the local database, and on your live domain it uses the production values.

Open `config.php` and put your InfinityFree details in the **production** block
(the `else` branch):

```php
} else {
    // ---------- Production (InfinityFree) ----------
    define('DB_HOST', env_or('DB_HOST', 'sqlXXX.infinityfree.com'));   // MySQL host, NOT localhost
    define('DB_NAME', env_or('DB_NAME', 'if0_XXXXXXXX_blog'));         // full database name
    define('DB_USER', env_or('DB_USER', 'if0_XXXXXXXX'));              // account username
    define('DB_PASS', env_or('DB_PASS', 'your-database-password'));    // as set in the panel
}
```

Set `APP_DEBUG` to `false` so errors are logged, not shown.

> Because the same file works in both places, you can safely upload it and you'll
> never accidentally point production at your local database. `config.php` is
> git-ignored, so your credentials are never pushed to GitHub.

---

## Step 5 — Upload the files

Connect with FileZilla:

- **Host:** `ftpupload.net` (or the host shown in *FTP Details*)
- **Username:** `if0_XXXXXXXX`
- **Password:** your hosting/FTP password
- **Port:** `21` (passive mode)

Then open the **`htdocs`** folder on the server — this is your website root.
Upload the following from the project:

```
htdocs/
├── .htaccess              ← required (clean URLs + security)
├── index.php              ← front controller
├── router.php             ← optional (only used by php -S locally)
├── config.php             ← auto-detects local vs production (safe to upload)
├── app/                   ← entire folder
│   ├── controllers/
│   ├── models/
│   └── views/
└── assets/                ← entire folder
    └── css/style.css
```

**Do NOT upload:**

- `_unused/` — leftover files from another project
- `config.sample.php` — a template, not needed on the server
- `database.sql` / `seed.sql` — already imported; they're blocked by `.htaccess` anyway
- `README.md` / `DEPLOY.md` / `LICENSE` / `.gitignore` — harmless but unnecessary

> The free plan has a **10 MB per file** limit. Every file here is tiny, so you
> can safely use FTP or the Online File Manager.

---

## Step 6 — Choose the PHP version (if offered)

Control Panel → **PHP Configuration** (or *Select PHP Version*): pick
**PHP 8.2** or **8.3**. The code requires **8.1+** (`pdo_mysql` is enabled by
default).

---

## Step 7 — Test it

Visit your subdomain, e.g. `http://myblog.rf.gd`. You should see the blog home
page with the purple theme and three sample posts.

Check these:

- [ ] Home page loads with posts and categories
- [ ] Opening a post works, and comments are visible
- [ ] Search returns results
- [ ] **Register** a new account, then **Login**
- [ ] The new account can reach **Dashboard** but *not* Users/Categories
- [ ] Logging in as `admin` / `admin123` (if you imported `seed.sql`) shows the full admin area

> Free subdomains can take a few minutes (occasionally longer) to go live. If you
> see a generic page at first, wait and refresh. InfinityFree may also show a
> brief "Just a moment…" check the first time — that's their bot protection, it's
> normal.

---

## Step 8 — HTTPS

This project's `.htaccess` **already forces HTTPS**, using the rule recommended
by InfinityFree (it checks the forwarded-protocol headers so it does *not* loop
behind their reverse proxy).

1. Make sure a certificate is active: Control Panel → **Free SSL Certificates**
   (or put the site behind Cloudflare's free plan).
2. Visit `http://your-subdomain` — it should now redirect to `https://`.
3. If you ever get `ERR_TOO_MANY_REDIRECTS`, comment out the three
   `RewriteCond` lines in the **Force HTTPS** block and check your SSL setup.

---

## Troubleshooting

| Symptom | Cause / fix |
|---|---|
| **500 Internal Server Error** on every page | A `.htaccess` directive isn't allowed. Remove the `Options -Indexes` line and retry. |
| **503 / "Database connection failed"** | `DB_HOST` is still `localhost`/`127.0.0.1`. Use `sqlXXX.infinityfree.com`. |
| **"Access denied for user …"** | Wrong `DB_USER`/`DB_NAME`. Use the full prefixed names (`if0_XXXXXXXX_…`). |
| **Home page works, but every other URL is 404** | `.htaccess` didn't upload (FileZilla hides dotfiles — enable *Server → Force showing hidden files*) or `mod_rewrite` isn't picking it up. |
| **Blank white page** | Temporarily set `APP_DEBUG` to `true` in `config.php` to see the error, then set it back to `false`. |
| **CSS looks unstyled** | `assets/css/style.css` wasn't uploaded, or was uploaded to the wrong folder. |
| **Login "does nothing"** | Cookies are blocked by the browser, or you're flipping between `http` and `https`. Stay on one scheme. |
| **ERR_TOO_MANY_REDIRECTS** | The HTTPS redirect is looping. Comment out the three `RewriteCond` lines in the *Force HTTPS* block. |

---

## Notes & limits of the free plan

- No SSH, no cron, no Composer — none of which this project needs.
- Databases are only reachable from InfinityFree's own servers (that's why
  `localhost` never works).
- Free accounts can be throttled for inactivity; visiting occasionally keeps
  them alive.
- Everything you need is already in the repo — deployment is pure upload +
  config.
