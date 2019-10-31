<?php
if ($_SESSION['is_logged'] === TRUE)
{
  ?>
  <p id="account">
    Welcome <?php echo $_SESSION['username']; ?>
    <a href="<?php echo Config::ROOT.'index.php?cat=account'; ?>">My account</a>
    <a href="<?php echo Config::ROOT.'index.php?logout'; ?>">Logout</a>
  </p>
  <?php
}
else
{
  ?>
  <form method="POST">
    <input type="text" name="username" placeholder="username" value="<?php echo htmlspecialchars($output->get_login_username()); ?>">
    <input type="password" name="pass" placeholder="password" value="">
    <input type="submit" name="login" value="Login">
    <p>
      <?php echo $output->get_login_error_msg(); ?>
      <a href="<?php echo Config::ROOT.'index.php?cat=register'; ?>">Register</a> or
      <a href="<?php echo Config::ROOT.'index.php?cat=reset'; ?>">Reset password</a>.
    </p>
  </form>
  <?php
}
?>
