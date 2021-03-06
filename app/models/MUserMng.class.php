<?php
/* ************************************************************** *\
    Manages User objects.
    - SELECT, ADD, MODIFY, DELETE: database manipulations.
    - REGISTER, LOGIN, LOGOUT, RESET_PASSWORD: user connections.
    - CHECK_context_values: checkers for posted data.
\* ************************************************************** */

class MUserMng extends M_Manager
{
  /* *********************************************************** *\
      SELECT, ADD, MODIFY, DELETE
  \* *********************************************************** */

  /* --- 'users' table ---*/

  public function select_user_by($key, $value)
  {
    if (in_array($key, array('id_user', 'email', 'username')) && isset($value))
    {
      $sql = 'SELECT *
              FROM users
              WHERE '.$key.' = :value';
      $query = $this->_db->prepare($sql);
      $query->bindValue(':value', $value);
      $query->execute();

      $r = $query->fetch();
      if (isset($r['id_user']))
        return new MUser($r);
    }
    return NULL;
  }

  public function add_user(MUser $user)
  {
    $sql = 'INSERT INTO users
           (email, username, password, notifications_on, email_confirmed)
           VALUES
           (:email, :username, :password, 1, :email_confirmed)';
    $query = $this->_db->prepare($sql);
    $query->bindValue(':email', $user->get_email(), PDO::PARAM_STR);
    $query->bindValue(':username', $user->get_username(), PDO::PARAM_STR);
    $query->bindValue(':password', $user->get_password(), PDO::PARAM_STR);
    $query->bindValue(':email_confirmed', $user->get_email_confirmed(), PDO::PARAM_STR);
    $query->execute();
    return $this->_db->lastInsertId();
  }

  public function modify_user(MUser $user)
  {
    $sql = 'UPDATE users
            SET email = :email,
                username = :username,
                password = :password,
                notifications_on = :notifications_on,
                email_confirmed = :email_confirmed
            WHERE id_user = :id_user';
    $query = $this->_db->prepare($sql);
    $query->bindValue(':email', $user->get_email(), PDO::PARAM_STR);
    $query->bindValue(':username', $user->get_username(), PDO::PARAM_STR);
    $query->bindValue(':password', $user->get_password(), PDO::PARAM_STR);
    $query->bindValue(':notifications_on', $user->get_notifications_on(), PDO::PARAM_INT);
    $query->bindValue(':email_confirmed', $user->get_email_confirmed(), PDO::PARAM_STR);
    $query->bindValue(':id_user', $user->get_id_user(), PDO::PARAM_INT);
    $query->execute();
  }

  /* --- 'password_resets' table ---*/

  public function select_password_reset_data($id_user)
  {
    $sql = 'SELECT password, password_confirmation_code
            FROM password_resets
            WHERE id_user = :id_user';
    $query = $this->_db->prepare($sql);
    $query->bindValue(':id_user', $id_user, PDO::PARAM_INT);
    $query->execute();

    $r = $query->fetch();
    if (isset($r['password_confirmation_code']))
    {
      $password_data['encrypted_password'] = $r['password'];
      $password_data['confirmation_code'] = $r['password_confirmation_code'];
      return $password_data;
    }
    return NULL;
  }

  public function add_password_reset(MUser $user, $password_confirmation_code)
  {
    $sql = 'INSERT INTO password_resets
           (id_user, password, password_confirmation_code)
           VALUES
           (:id_user, :password, :password_confirmation_code)
           ON DUPLICATE KEY UPDATE
           password = :password,
           password_confirmation_code = :password_confirmation_code';
    $query = $this->_db->prepare($sql);
    $query->bindValue(':id_user', $user->get_id_user(), PDO::PARAM_INT);
    $query->bindValue(':password', $user->get_password(), PDO::PARAM_STR);
    $query->bindValue(':password_confirmation_code', $password_confirmation_code, PDO::PARAM_STR);
    $query->execute();
  }

  public function delete_password_reset(MUser $user)
  {
    $sql = 'DELETE FROM password_resets
            WHERE id_user = :id_user';
    $query = $this->_db->prepare($sql);
    $query->bindValue(':id_user', $user->get_id_user(), PDO::PARAM_INT);
    $query->execute();
  }

  /* *********************************************************** *\
      REGISTER, LOGIN, LOGOUT, RESET_PASSWORD
  \* *********************************************************** */

  public function register(MUser $user)
  {
    $last_inserted_id = $this->add_user($user);
    $user->set_id_user($last_inserted_id);

    $emailMng = new MEmailMng();
    $emailMng->send_registration_confirmation($user);
  }

  public function login($username)
  {
    $user = $this->select_user_by('username', $username);
    $_SESSION['is_logged'] = TRUE;
    $_SESSION['id_user'] = $user->get_id_user();
    $_SESSION['username'] = $user->get_username();
  }

  public function logout()
  {
    unset($_SESSION['is_logged']);
    unset($_SESSION['id_user']);
    unset($_SESSION['username']);
  }

  public function reset_password(MUser $user, $password_confirmation_code)
  {
    $this->add_password_reset($user, $password_confirmation_code);

    $emailMng = new MEmailMng();
    $emailMng->send_reset_confirmation($user, $password_confirmation_code);
  }

  /* *********************************************************** *\
      CHECK_REGISTRATION/LOGIN/MODIFY/RESET_values
      Checks input from $_POST is valid,
      or returns an error message.
  \* *********************************************************** */

  /* --- REGISTRATION --- */

  public function check_registration_email(array $post)
  {
    if (empty($post['email']))
      return 'Please enter an email address.';

    if ($this->is_valid_email_format($post['email']) === FALSE)
      return 'Invalid email address.';

    $user = $this->select_user_by('email', $post['email']);
    if (!is_null($user))
      return 'Email taken.';

    return FALSE;
  }

  public function check_registration_username(array $post)
  {
    if (empty($post['username']))
      return 'Please enter a username.';

    if ($this->is_valid_string_format($post['username']) === FALSE)
      return 'Invalid username.';

    $user = $this->select_user_by('username', $post['username']);
    if (!is_null($user))
      return 'Username taken.';

    return FALSE;
  }

  public function check_registration_password(array $post)
  {
    if (empty($post['pass']) ||
        empty($post['passcheck']) ||
        $post['pass'] != $post['passcheck'])
      return 'Please enter matching passwords.';

    if (strlen($post['pass']) < 6)
      return 'Please use a password at least 6-character long.';

    if (preg_match('/^[A-Za-z0-9]*$/', $post['pass']))
      return 'Please use at least one special character in your password.';

    return FALSE;
  }

  public function check_registration_validation_code(array $get)
  {
    if (empty($get['confirm']))
      return NULL;

    $split = explode('-', $get['confirm']);
    if (count($split) > 2 || $this->is_valid_int_format($split[0]) === FALSE)
      return NULL;

    $user = $this->select_user_by('id_user', $split[0]);
    if (is_null($user) || $user->get_email_confirmed() != $split[1])
      return NULL;

    return $user;
  }

  /* --- LOGIN --- */

  public function check_login_username(array $post)
  {
    if (empty($post['username']))
      return 'error';

    if ($this->is_valid_string_format($post['username']) === FALSE)
      return 'error';

    $user = $this->select_user_by('username', $post['username']);
    if (is_null($user))
      return 'error';

    return FALSE;
  }

  public function check_login_email_confirmed(array $post)
  {
    $user = $this->select_user_by('username', $post['username']);
    if ($user->get_email_confirmed() != 1)
      return 'error';

    return FALSE;
  }

  public function check_login_password(array $post)
  {
    if (empty($post['pass']))
      return 'error';

    $user_posted = new MUser(array('username' => $post['username']));
    $user_posted->encrypt_and_set_password($post['pass']);
    $user_fetched = $this->select_user_by('username', $post['username']);
    if ($user_posted->get_password() != $user_fetched->get_password())
      return 'error';

    return FALSE;
  }

  /* --- MODIFY --- */

  public function check_modify_email(array $post, MUser $user)
  {
    if (empty($post['email']))
      return 'Please enter an email address.';

    if ($this->is_valid_email_format($post['email']) === FALSE)
      return 'Invalid email address.';

    if ($post['email'] != $user->get_email())
    {
      $user_check = $this->select_user_by('email', $post['email']);
      if (!is_null($user_check))
        return 'Email taken.';
    }

    return FALSE;
  }

  public function check_modify_username(array $post, MUser $user)
  {
    if (empty($post['username']))
      return 'Please enter a username.';

    if ($this->is_valid_string_format($post['username']) === FALSE)
      return 'Invalid username.';

    if ($post['username'] != $user->get_username())
    {
      $user = $this->select_user_by('username', $post['username']);
      if (!is_null($user))
        return 'Username taken.';
    }

    return FALSE;
  }

  public function check_modify_password(array $post)
  {
    return $this->check_registration_password($post);
  }

  /* --- RESET --- */

  public function check_reset_email(array $post)
  {
    if (empty($post['email']))
      return 'Please enter an email address.';

    if ($this->is_valid_email_format($post['email']) === FALSE)
      return 'Invalid email address.';

    $user_check = $this->select_user_by('email', $post['email']);
    if (is_null($user_check))
      return 'No account for this email.';

    return FALSE;
  }

  public function check_reset_password(array $post)
  {
    return $this->check_registration_password($post);
  }

  public function check_reset_validation_code(array $get)
  {
    if (empty($get['confirm']))
      return NULL;

    $split = explode('-', $get['confirm']);
    if (count($split) > 2 || $this->is_valid_int_format($split[0]) === FALSE)
      return NULL;

    $user = $this->select_user_by('id_user', $split[0]);
    if (is_null($user))
      return NULL;

    $password_data = $this->select_password_reset_data($split[0]);
    if ($password_data['confirmation_code'] != $split[1])
      return NULL;

    $user->set_password($password_data['encrypted_password']);
    return $user;
  }
}
