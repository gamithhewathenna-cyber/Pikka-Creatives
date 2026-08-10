# Pikka Creatives — Home Page + Admin Panel

A PHP / MySQL website for the Pikka Creatives home page, with a full admin panel
for managing all content. Built to deploy directly to cPanel hosting.

---

## What's included

```
pikka/
├── index.php              ← the home page (front end)
├── contact.php            ← handles contact-form submissions
├── .htaccess              ← caching + directory protection
├── includes/
│   ├── config.php         ← EDIT THIS with your database details
│   └── functions.php      ← content helpers (do not edit)
├── assets/
│   ├── css/style.css      ← all front-end styles
│   ├── js/main.js         ← animations, accordion, form
│   └── images/            ← (place static images here if needed)
├── uploads/               ← uploaded images land here (needs write permission)
├── admin/                 ← the admin panel (login required)
│   ├── login.php
│   ├── index.php          (dashboard)
│   ├── sections.php       (all section text)
│   ├── services.php / why.php / process.php / stats.php
│   ├── images.php         (upload hero / section images)
│   ├── settings.php       (logo, accent colour, marquee, contacts)
│   ├── messages.php       (contact-form inbox)
│   └── account.php        (change password)
└── sql/
    └── pikka_db.sql       ← import this into your database
```

---

## Deployment steps (cPanel)

### 1. Upload the files
- In cPanel open **File Manager**.
- Upload the contents of this folder into `public_html` (or a subfolder such as
  `public_html/pikka` if you want it at yoursite.com/pikka).
- If you uploaded a zip, select it and choose **Extract**.

### 2. Create the database
- In cPanel open **MySQL® Databases**.
- Create a new database, e.g. `youracct_pikka`.
- Create a new database user with a strong password.
- Under **Add User To Database**, add the user to the database and grant
  **ALL PRIVILEGES**.
- Note down the database name, username and password.

### 3. Import the tables and content
- Open **phpMyAdmin** from cPanel.
- Select your new database on the left.
- Click the **Import** tab, choose `sql/pikka_db.sql`, and click **Go**.
- You should see 8 tables created and filled with the starter content.

### 4. Connect the site to the database
- Edit `includes/config.php` (File Manager → right-click → Edit).
- Set these four values to match step 2:

```php
define('DB_HOST', 'localhost');          // usually 'localhost' on cPanel
define('DB_NAME', 'youracct_pikka');
define('DB_USER', 'youracct_pikkauser');
define('DB_PASS', 'your-strong-password');
```

- Save.

### 5. Set folder permissions
- Make sure the `uploads/` folder is writable (permission **755**, or **775**
  if 755 doesn't allow uploads on your host). Right-click the folder in File
  Manager → **Change Permissions**.

### 6. Done — visit the site
- Home page:  `https://yoursite.com/`  (or `/pikka/` if in a subfolder)
- Admin panel: `https://yoursite.com/admin/`

---

## Admin login

```
Username:  admin
Password:  pikka123
```

**Change this immediately** after your first login:
Admin → **My Account** → change password.

---

## Managing content

Everything on the home page is editable from the admin panel:

- **Text & Sections** – all headings, eyebrows and body copy, grouped by section.
- **Services / Why Choose Us / Process Steps / Stats** – add, edit, delete and
  reorder items with the ↑ ↓ buttons.
- **Images** – upload the hero photo and optional section images.
- **Settings** – logo text, accent colour, the scrolling marquee, footer, and
  contact details.
- **Messages** – enquiries submitted through the contact form.

Changes appear on the live site immediately after saving.

---

## Notes

- Requires PHP 7.4+ (works on PHP 8.x) with the **mysqli** extension — standard
  on all cPanel hosts.
- The accent colour set in Settings updates the whole site's highlight colour.
- To place the site at the domain root, upload directly into `public_html`.
- The design is fully responsive and respects reduced-motion accessibility
  preferences.

---

*Phase 1 delivery — Home Page with backend. Inner pages to follow once approved.*
