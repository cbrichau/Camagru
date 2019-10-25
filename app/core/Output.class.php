<?php
class Output extends Config
{
  private $_head_title;
/*
  private $_login_username;
  private $_login_error_msg;
  private $_page_name;*/

  /* **************************** *\
    SETTERS
  \* **************************** */

  public function set_head_title($title)
  {
    if (is_string($title))
      $this->_head_title = $title;
  }

  /*
  public function set_login_username($username)
  {
    if (is_string($username))
      $this->_login_username = $username;
  }

  public function set_login_error_msg()
  {
    $this->_login_error_msg = 'Wrong username and/or password.';
  }

  public function set_page_name($name)
  {
    $this->_page_name = $name;
  }*/

  /* **************************** *\
    GETTERS
  \* **************************** */

  public function get_head_title()
  {
    return $this->_head_title;
  }

/*
  public function get_login_username()
  {
    return $this->_login_username;
  }

  public function get_login_error_msg()
  {
    return $this->_login_error_msg;
  }

  public function get_page_name()
  {
    return $this->_page_name;
  }*/
}
