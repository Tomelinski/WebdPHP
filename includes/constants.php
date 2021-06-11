<?php 
/**
 *Constants page
 *
 * The page where all constants are stored in the document
 * 
 * @Author  Tom Zielinski <tom.zielinski@dcmail.ca>
 * @version 2.0 (dec, 18th 2020)
 */

/******* COOKIES *******/
define("COOKIE_LIFESPAN", "259200");

/*******Max Lengths********/
define("MINIMUM_PASSWORD_LENGTH", 6);
define("MAXIMUM_PASSWORD_LENGTH", 15);
define("MINIMUM_PHONENUMBER_LENGTH", 1000000000);
define("MAXIMUM_PHONENUMBER_LENGTH", 9999999999);
define("MAX_FIRST_NAME_LENGTH", 20);
define("MAX_LAST_NAME_LENGTH", 30);
define("MAXIMUM_EMAIL_LENGTH", 255);
define("USERS_ON_PAGE", 10);
define("MAXIMUM_FILE_SIZE", 3000000);
define("MAXIMUM_FILE_SIZE_MB", "3MB");

/******* USER TYPES *******/
define("ADMIN", 's');
define("AGENT", 'a');
define("CLIENT", 'c');
define("PENDING", 'p');
define("DISABLED", 'd');

/******* DATABASE CONTAINS *******/
define("DB_HOST", "localhost");
define("DATABASE", "zielinskit_db");
define("DB_ADMIN", "zielinskit");
define("DB_PORT", "5432");
define("DB_PASSWORD", "100559389");

/*********** EMAIL ************/
define("PASSWORD_RESET_EMAIL", "To reset your password copy and paste the link below into your browser (NOTE: This link will expire in two hours):
    http://opentech.durhamcollege.org/webd3201/zielinskit/lab04/index.php
    If you did not request your password to be reset, please ignore this email and your password will stay as it is.");

?>