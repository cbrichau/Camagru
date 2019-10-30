<?php
//Begin or resume session.
session_start();
if (!isset($_SESSION['is_logged']))
  $_SESSION['is_logged'] = FALSE;

/**/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/*
echo '<pre>';
print_r($_POST);
echo '</pre>';

echo '<pre>';
print_r($_GET);
echo '</pre>';

echo '<pre>';
print_r($_SESSION);
echo '</pre>';
*/

// Config class defines all paths as constants
// and establishes the connection to the database.
require_once('app/core/Config.class.php');

// Output class manages all variables for the
// template header and footer.
require_once('app/core/Output.class.php');
$output = new Output();

// Error file manages any errors to be displayed
if (isset($_SESSION['error']))
  if (isset($_SESSION['error']['login']))
  {
    $output->set_login_username($_SESSION['error']['login']);
    $output->set_login_error_msg();
    unset($_SESSION['error']['login']);
  }

// Router class takes the GET and POST arrays,
// and includes the expected models and controller.
require_once('app/core/Router.class.php');
Router::include_mvc_files($_GET, $_POST, $output);
