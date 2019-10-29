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
  <p id="sitename"><a href="<?php echo Config::ROOT.'index.php?cat=home'; ?>">My 19 Purikura</a></p>
  <?php require_once(Config::VIEWS_PATH.'V_header_login.php'); ?>
</header>

<nav>
  <a href="<?php echo Config::ROOT; ?>">Gallery</a><a href="<?php echo Config::ROOT.'index.php?cat=montage'; ?>">Create montage</a>
</nav>

<main id="<?php echo $output->get_page_name(); ?>">
