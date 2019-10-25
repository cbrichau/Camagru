<?php
/* *********************************************************** *\
    "Register" page.
    Shows a form to a non-logged user to create an account.
\* *********************************************************** */

// Only accessible to non-logged users.
if ($_SESSION['is_logged'] === TRUE)
  header('Location: '.Config::ROOT.'');

// Initialises the form's prefill and error alerts to null.
$email_posted = '';
$username_posted = '';
$validation_alert = '';
$email_error_alert = '';
$username_error_alert = '';
$pass_error_alert = '';

$userMng = new MUserMng();

// Processes a registration confirmation
// (when a user clicks on the post-confirmation email validation link).
if (isset($_GET['confirm']))
{
  // Checks the validation code from the URL is valid.
  // If so, returns a user object matchimg the user being confirmed.
  $user = $userMng->check_registration_validation_code($_GET);
  if (is_null($user))
    $validation_alert = '<div class="alert-box error"><span>error:</span> Bad validation link.</div>';
  else
  {
    // Updates user object and database entry to validate him/her.
    // Then logs the user and redirects to home.
    $user->set_email_confirmed(TRUE);
    $userMng->modify_user($user);
    $userMng->login($user->get_username());
    header('Location: '.Config::ROOT.'');
  }
}

// Processes a registration submition
else if (isset($_POST['register']))
{
  // Prefills the form with the posted info.
  $email_posted = $_POST['email'];
  $username_posted = $_POST['username'];

  // Checks the input is valid, or returns an error message.
  $error_msg['email'] = $userMng->check_registration_email($_POST);
  $error_msg['username'] = $userMng->check_registration_username($_POST);
  $error_msg['password'] = $userMng->check_registration_password($_POST);

  // If the input is valid (i.e. no error), registers the user.
  if ($error_msg['email'] === FALSE &&
      $error_msg['username'] === FALSE &&
      $error_msg['password'] === FALSE)
  {
    // Creates the user object.
    $user = new MUser($_POST);
    $user->encrypt_and_set_password($_POST['pass']);
    $user->set_email_confirmed(md5(rand(0,1000)));
    // Adds an entry to the database.
    $userMng->register($user);
    $validation_alert = '<div class="alert-box success"><span>success:</span> An email has been sent to you with a validation link.</div>';
  }
  // If there's one or more errors, sets the corresponding alert(s).
  else
  {
    if ($error_msg['email'] != FALSE)
      $email_error_alert = '<div class="alert-box error"><span>error:</span> '.$error_msg['email'].'</div>';

    if ($error_msg['username'] != FALSE)
      $username_error_alert = '<div class="alert-box error"><span>error:</span> '.$error_msg['username'].'</div>';

    if ($error_msg['password'] != FALSE)
      $pass_error_alert = '<div class="alert-box error"><span>error:</span> '.$error_msg['password'].'</div>';
  }
}

// Sets the output values and calls the views.
$output->set_head_title('Create a new account');

require_once(Config::VIEW_HEADER);
require_once(Router::$page['view']);
require_once(Config::VIEW_FOOTER);
