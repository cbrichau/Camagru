<?php
/* *********************************************************** *\
    "Modify my account" page.
    Shows a form to a logged user to modify his/her info.
\* *********************************************************** */

// Only accessible to logged users.
if ($_SESSION['is_logged'] === FALSE)
  header('Location: '.Config::ROOT.'');

// Gets the user's current info from the DB to prefill the form.
$userMng = new MUserMng();
$user = $userMng->select_user_by('id_user', (int)$_SESSION['id_user']);
$email_form = $user->get_email();
$username_form = $user->get_username();
$notif_form = ($user->get_notifications_on() == TRUE) ? 'checked' : '';

// Initialises the modification error alerts to null.
$email_error_alert = '';
$username_error_alert = '';
$pass_error_alert = '';
$modif_success_alert = '';

// Processes the modification form.
if (isset($_POST['modify']))
{
  // Overrides the form's prefilled fields with the posted modifications.
  $email_form = $_POST['email'];
  $username_form = $_POST['username'];
  $notif_form = (isset($_POST['notifications_on'])) ? 'checked' : '';

  // Checks the input is valid, or returns an error message.
  $error_msg['email'] = $userMng->check_modify_email($_POST, $user);
  $error_msg['username'] = $userMng->check_modify_username($_POST, $user);
  $error_msg['password'] = $userMng->check_modify_password($_POST);

  // If the input is valid (i.e. no error), modifies the user info accordingly.
  if ($error_msg['email'] === FALSE &&
      $error_msg['username'] === FALSE &&
      $error_msg['password'] === FALSE)
  {
    // Updates the user object.
    $user->set_email($_POST['email']);
    $user->set_username($_POST['username']);
    $user->encrypt_and_set_password($_POST['pass']);
    if (isset($_POST['notifications_on']))
      $user->set_notifications_on(TRUE);
    else
      $user->set_notifications_on(FALSE);
    // Updates the database.
    $userMng->modify_user($user);
    $modif_success_alert = '<div class="alert-box success"><span>success:</span> Your account has been modified</div>';
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
$output->set_head_title('Modify my account');

require_once(Config::VIEW_HEADER);
require_once(Router::$page['view']);
require_once(Config::VIEW_FOOTER);
