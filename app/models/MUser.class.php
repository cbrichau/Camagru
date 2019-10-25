<?php
class MUser
{
  private $_id_user;
  private $_email;
  private $_username;
  private $_password;
  private $_notifications_on;
  private $_email_confirmed;

  /* ******************************************************** *\
      INITILISATION
  \* ******************************************************** */

  public function __construct(array $data)
	{
		if(!empty($data))
      $this->hydrate($data);
	}

	private function hydrate(array $data)
	{
		foreach ($data as $key => $value)
		{
			$method = 'set_'.$key;
      if (method_exists($this, $method))
        $this->$method($value);
    }
	}

  /* ******************************************************** *\
      SETTERS
  \* ******************************************************** */

  public function set_id_user($arg)
  {
    $this->_id_user = (int)$arg;
  }

  public function set_email($arg)
  {
    if (filter_var($arg, FILTER_VALIDATE_EMAIL))
      $this->_email = $arg;
  }

  public function set_username($arg)
  {
    if (is_string($arg))
      $this->_username = $arg;
  }

  public function set_password($arg)
  {
    $this->_password = $arg;
  }

  public function set_notifications_on($arg)
  {
    if ($arg == TRUE)
      $this->_notifications_on = 1;
    else
      $this->_notifications_on = 0;
  }

  public function set_email_confirmed($arg)
  {
    $this->_email_confirmed = $arg;
  }

  /* -- */

  public function encrypt_and_set_password($pw)
  {
    $encrypted_pw = hash('whirlpool', $pw);
    $this->set_password($encrypted_pw);
  }

  /* ******************************************************** *\
      GETTERS
  \* ******************************************************** */

  public function get_id_user()
  {
    return $this->_id_user;
  }

  public function get_email()
  {
    return $this->_email;
  }

  public function get_username()
  {
    return $this->_username;
  }

  public function get_password()
  {
    return $this->_password;
  }

  public function get_notifications_on()
  {
    return $this->_notifications_on;
  }

  public function get_email_confirmed()
  {
    return $this->_email_confirmed;
  }
}
