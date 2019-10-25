<?php
class MComment
{
  private $_id_comment;
  private $_id_image;
  private $_id_user;
  private $_username;
  private $_publication_date;
  private $_comment;

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

  public function set_id_comment($arg)
  {
    $this->_id_comment = (int)$arg;
  }

  public function set_id_image($arg)
  {
    $this->_id_image = $arg;
  }

  public function set_id_user($arg)
  {
    $this->_id_user = (int)$arg;
  }

  public function set_username($arg)
  {
    if (is_string($arg))
      $this->_username = $arg;
  }

  public function set_publication_date($arg)
  {
    $this->_publication_date = $arg; //check input
  }

  public function set_comment($arg)
  {
    if (is_string($arg))
      $this->_comment = $arg;
  }

  /* ******************************************************** *\
      GETTERS
  \* ******************************************************** */

  public function get_id_comment()
  {
    return $this->_id_comment;
  }

  public function get_id_image()
  {
    return $this->_id_image;
  }

  public function get_id_user()
  {
    return $this->_id_user;
  }

  public function get_username()
  {
    return $this->_username;
  }
  
  public function get_publication_date()
  {
    return $this->_publication_date;
  }

  public function get_comment()
  {
    return $this->_comment;
  }
}
