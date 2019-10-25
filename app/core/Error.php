<?php
if (isset($_SESSION['error']))
{
  if (isset($_SESSION['error']['login']))
  {
    $output->set_login_error();
    unset($_SESSION['error']['login']);
  }
}
