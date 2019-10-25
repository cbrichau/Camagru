<?php
class Config
{
  const ROOT = 'http://cbrichau.alwaysdata.net/camagru/';

  const MODELS_PATH = 'app/models/';
  const CONTROLLERS_PATH = 'app/controllers/';
  const VIEWS_PATH = 'app/views/';

  const VIEW_HEADER = self::VIEWS_PATH.'V_header.php';
  const VIEW_FOOTER = self::VIEWS_PATH.'V_footer.php';

  const IMAGES_PATH = 'resources/images/';
  const CSS_PATH = 'resources/css/';
  const JS_PATH = 'resources/js/';

  private static $_db;

  /* ******************************************************************* *\
      CONNECT_BDD:  Creates the connection to the DB
      GET_DB:  Gets the connection to the db (first connects if not done).
  \* ******************************************************************* */

  private static function connect_db()
  {
		try
		{
      $db_host = 'localhost';
      $db_name = 'cbrichau_camagru';
      $db_user = 'root';
      $db_pass = 'password';
			self::$_db = new PDO('mysql:host='.$db_host.';dbname='.$db_name.'', $db_user, $db_pass); // Connect
      self::$_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Error mode = throw PDOException.
			self::$_db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); //Defaut fetch = associative array.
		}
		catch (PDOException $e){ die('DB error: '.$e->getMessage()); }
  }

  public static function get_db()
  {
    if (self::$_db == NULL)
      self::connect_db();
    return (self::$_db);
  }
}
