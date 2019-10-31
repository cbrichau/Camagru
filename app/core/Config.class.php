<?php
/* ******************************************************************* *\
    Sets the file & html base paths,
    and the connection to the database.
\* ******************************************************************* */

class Config
{
  const ROOT = 'http://localhost:8081/repcamagru/'; // Also hardcoded in setup.php and create_montage_photo.js

  const MODELS_PATH = 'app/models/';
  const CONTROLLERS_PATH = 'app/controllers/';
  const VIEWS_PATH = 'app/views/';

  const VIEW_HEADER = self::VIEWS_PATH.'V_header.php';
  const VIEW_FOOTER = self::VIEWS_PATH.'V_footer.php';

  const IMAGES_PATH = 'resources/images/';
  const CSS_PATH = 'resources/css/';
  const JS_PATH = 'resources/js/';

  private static $_db;

  private static function connect_db()
  {
    require('config/database.php');
    try
    {
      self::$_db = new PDO($DB_DSN.';dbname='.$DB_NAME, $DB_USER, $DB_PASSWORD); // Connect
      self::$_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Error mode = throw PDOException.
		  self::$_db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); //Defaut fetch = associative array.
      return TRUE;
    }
    catch (PDOException $e) { return FALSE; }
  }

  public static function get_db()
  {
    if (self::$_db == NULL)
    {
      $connectable = self::connect_db();
      if ($connectable === FALSE)
        return FALSE;
    }
    return (self::$_db);
  }
}
