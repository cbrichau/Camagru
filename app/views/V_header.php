<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?php echo Config::ROOT.Config::CSS_PATH; ?>main.css<?php echo '?time='.time(); ?>">
  <title><?php echo $output->get_head_title(); ?></title>
</head>

<body>

<header>
  <p id="sitename"><a href="<?php echo Config::ROOT; ?>">My 19 Purikura</a></p>
  <?php
  if ($_SESSION['is_logged'] === TRUE)
  {
    ?>
    <p id="account">
      Welcome <?php echo $_SESSION['username']; ?>
      <a href="<?php echo Config::ROOT.'account'; ?>">My account</a>
      <a href="<?php echo Config::ROOT.'?logout'; ?>">Logout</a>
    </p>
    <?php
  }
  else
  {
    ?>
    <form method="POST">
      <input type="text" name="username" placeholder="username" value="<?php echo $output->get_login_username(); ?>">
      <input type="password" name="pass" placeholder="password" value="">
      <input type="submit" name="login" value="Login">
      <p>
        <?php echo $output->get_login_error_msg(); ?>
        <a href="<?php echo Config::ROOT.'register'; ?>">Register</a>
      </p>
    </form>
    <?php
  }
  ?>
</header>

<nav>
  <a href="<?php echo Config::ROOT; ?>">Gallery</a><a href="<?php echo Config::ROOT.'montage'; ?>">Create montage</a>
</nav>

<main id="<?php echo $output->get_page_name(); ?>">
