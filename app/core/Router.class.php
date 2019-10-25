<?php
class Router extends Config
{
  public static $page = array('name' => 'home',
                              'path' => '',
                              'controller' => 'CHome.php',
                              'view' => 'VHome.php',
                              'action' => '',
                              );

  /* ******************************************************** *\
      DEFINE_PAGE_ELEMENTS:
      Defines the expected output page and action.
  \* ******************************************************** */

  private static function define_page_elements($GET_array, $POST_array)
  {
    $url_params = filter_var_array($GET_array, FILTER_SANITIZE_URL);

    // Set the id and the files of the expected page (default is Home).
    if (isset($url_params['cat']))
    {
      self::$page['name'] = strtolower($url_params['cat']);
      self::$page['path'] = self::$page['name'];
      self::$page['controller'] = 'C'.ucfirst(self::$page['name']).'.php';
      self::$page['view'] = 'V'.ucfirst(self::$page['name']).'.php';

      if (isset($url_params['id_image']))
      {
        self::$page['name'] = 'montage_image';
        self::$page['id_image'] = $url_params['id_image'];
        self::$page['path'] = strtolower($url_params['cat']).'/'.$url_params['id_image'];
        self::$page['controller'] = 'CMontageImage.php';
        self::$page['view'] = 'VMontageImage.php';
      }
    }

    if (isset($POST_array['login']))
    {
      self::$page['action'] == 'login';
      self::$page['controller'] = 'CLogin.php';
    }
    else if (isset($GET_array['logout']))
    {
      self::$page['action'] == 'logout';
      self::$page['controller'] = 'CLogout.php';
    }

    // Set the proper paths for the page's files.
    self::$page['path'] = Config::ROOT.self::$page['path'];
    self::$page['controller'] = Config::CONTROLLERS_PATH.self::$page['controller'];
    self::$page['view'] = Config::VIEWS_PATH.self::$page['view'];
  }

  /* ******************************************************** *\
      INCLUDE_MODELS:
      Auto-loads all necessary models.
  \* ******************************************************** */

  private static function include_models()
  {
    spl_autoload_register(function($className)
    {
      require_once(Config::MODELS_PATH.$className.'.class.php');
    });
  }

  /* ******************************************************** *\
      INCLUDE_CONTROLLER:
      Sets the <main id="name"> for page-specific css
      Includes the expected controller.
  \* ******************************************************** */

  private static function include_controller($output)
  {
    if (file_exists(self::$page['controller']) && file_exists(self::$page['view']))
    {
      $output->set_page_name(self::$page['name']);
      require_once(self::$page['controller']);
    }
    else
      header('Location: '.Config::ROOT.'');//404 redirect would be cleaner
  }

  /* ******************************************************** *\
      INCLUDE_MVC_FILES:
      Gets expected files and includes them.
  \* ******************************************************** */

  public static function include_mvc_files($GET_array, $POST_array, $output)
  {
    self::define_page_elements($GET_array, $POST_array);
    self::include_models();
    self::include_controller($output);
  }
}
