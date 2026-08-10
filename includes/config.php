<?php
/* =====================================================================
   Pikka Creatives — Database Configuration
   ---------------------------------------------------------------------
   EDIT THESE 4 VALUES to match your cPanel MySQL database, then upload.
   In cPanel: MySQL Databases > create a database + user, add user to DB.
   ===================================================================== */

define('DB_HOST', 'localhost');                        // usually 'localhost' on cPanel
define('DB_NAME', 'creaeina_pikkacreatives');          // your database name
define('DB_USER', 'creaeina_pikkacradmin');            // your database username
define('DB_PASS', 'wfBs.R{A#lA~JZM9');                 // your database password

/* --- Do not edit below this line ------------------------------------ */

mysqli_report(MYSQLI_REPORT_OFF); // handle errors gracefully, no exceptions

function db() {
    static $conn = null;
    if ($conn === null) {
        $conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (!$conn) {
            die('<div style="font-family:sans-serif;max-width:600px;margin:80px auto;padding:30px;border:1px solid #eee;border-radius:12px">
                 <h2 style="color:#F1592A">Database connection failed</h2>
                 <p>Please open <code>includes/config.php</code> and check your database
                 host, name, username and password. In cPanel, create a MySQL database
                 and user, then assign the user to the database with all privileges.</p></div>');
        }
        mysqli_set_charset($conn, 'utf8mb4');
    }
    return $conn;
}
