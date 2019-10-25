<?php
/* *********************************************************** *\
    "Logout" processing (no page).
    Allows users to log out from the header link.
\* *********************************************************** */

// Logs out the user.
$userMng = new MUserMng();
$userMng->logout();

// Redirects to home.
header('Location: '.Config::ROOT.'');
