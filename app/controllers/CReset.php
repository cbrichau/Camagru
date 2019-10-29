<?php
/* *********************************************************** *\
    "Reset my password" page.
    Shows a form to non-logged users to reset their password.
\* *********************************************************** */

// Only accessible to non-logged users.
if ($_SESSION['is_logged'] === TRUE)
  header('Location: '.Config::ROOT.'');

// Initialises the form's error/validation alerts to null.
$email_posted = '';
$email_error_alert = '';
$pass_error_alert = '';
$validation_alert = '';

$userMng = new MUserMng();

if (isset($_GET['confirm']))
{
  // Checks the validation code from the URL is valid.
  // If so, returns a user object matching the user being confirmed.
  $user = $userMng->check_reset_validation_code($_GET);
  if (is_null($user))
    $validation_alert = '<div class="alert-box error"><span>error:</span> Bad validation link.</div>';
  else
  {
    // Updates database entry to reflect the modifed password.
    // Remove the validation from the database.
    // Then logs the user and redirects to home.
    $userMng->modify_user($user);
    $userMng->delete_password_reset($user);
    $userMng->login($user->get_username());
    header('Location: '.Config::ROOT.'');
  }
}

// Processes the reset form.
if (isset($_POST['reset']))
{
  // Prefills the form with the posted info.
  $email_posted = $_POST['email'];

  // Checks the input is valid, or returns an error message.
  $error_msg['email'] = $userMng->check_reset_email($_POST);
  $error_msg['password'] = $userMng->check_reset_password($_POST);

  // If the input is valid (i.e. no error), modifies the user info accordingly.
  if ($error_msg['email'] === FALSE &&
      $error_msg['password'] === FALSE)
  {
    // Gets the user matching the email.
    $user = $userMng->select_user_by('email', $_POST['email']);
    // Updates the user object with the new password.
    $user->encrypt_and_set_password($_POST['pass']);
    // Saves the pending change in the database.
    $password_confirmation_code = md5(rand(0,1000));
    $userMng->reset_password($user, $password_confirmation_code);
    $validation_alert = '<div class="alert-box success"><span>success:</span> An email has been sent to you with a validation link.</div>';
  }
  // If there's one or more errors, sets the corresponding alert(s).
  else
  {
    if ($error_msg['email'] != FALSE)
      $email_error_alert = '<div class="alert-box error"><span>error:</span> '.$error_msg['email'].'</div>';

    if ($error_msg['password'] != FALSE)
      $pass_error_alert = '<div class="alert-box error"><span>error:</span> '.$error_msg['password'].'</div>';
  }
}

// Sets the output values and calls the views.
$output->set_head_title('Reset my password');

require_once(Config::VIEW_HEADER);
require_once(Router::$page['view']);
require_once(Config::VIEW_FOOTER);
