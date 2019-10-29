<?php
class Output extends Config
{
  private $_head_title; // <head><title> field.
  private $_page_name; // <main id="name"> for page-specific css.
  private $_login_username; // Prefills form on login error.
  private $_login_error_msg; // Header message on login error.

  /* **************************** *\
    SETTERS
  \* **************************** */

  public function set_head_title($title)
  {
    if (is_string($title))
      $this->_head_title = $title;
  }

  public function set_page_name($name)
  {
    $this->_page_name = $name;
  }

  public function set_login_username($username)
  {
    if (is_string($username))
      $this->_login_username = $username;
  }

  public function set_login_error_msg()
  {
    $this->_login_error_msg = 'Wrong username and/or password.';
  }

  /* **************************** *\
    GETTERS
  \* **************************** */

  public function get_head_title()
  {
    return $this->_head_title;
  }

  public function get_page_name()
  {
    return $this->_page_name;
  }

  public function get_login_username()
  {
    return $this->_login_username;
  }

  public function get_login_error_msg()
  {
    return $this->_login_error_msg;
  }
}
