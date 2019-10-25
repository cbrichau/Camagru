<?php
/* *********************************************************** *\
    "Login" processing (no page).
    Allows users to log in from the header form.
\* *********************************************************** */

// Only accessible to non-logged users.
if ($_SESSION['is_logged'] === TRUE)
  header('Location: '.Router::$page['path'].'');

// Processes the login form.
if (isset($_POST['login']))
{
  //$output->set_login_username($_POST['username']);

  // Checks the input is valid, or returns an error message.
  $userMng = new MUserMng();
  $error_msg = $userMng->check_login_username($_POST);
  if ($error_msg === FALSE)
    $error_msg = $userMng->check_login_password($_POST);

  // If the input is valid (i.e. no error), logs the user.
  if ($error_msg === FALSE)
    $userMng->login($_POST['username']);

  // If there's an error, ..........
  //else
    //$output->set_login_error_msg();
}

// Redirects to current page.
header('Location: '.Router::$page['path'].'');
