<?php
/**
 * PsySH configuration for Panada Framework.
 *
 * @author  Bernino Falya <falya.bernino@gmail.com>
 * @link    http://berninofalya.wordpress.com/
 * @package config
 */

define  ('DIR', '/opt/lampp/htdocs');
define  ('APP', '/opt/lampp/htdocs/lefalya/app/');
define  ('INDEX_FILE', '/opt/lampp/htdocs/lefalya/');
define  ('GEAR', '/opt/lampp/htdocs/lefalya/panada/');

require_once GEAR.'Gear.php';

class psysh_config {

  private $rendezvouz;
  private $required_dir;
  private $required_files;
  private $ignored_files;
  private $count_dir;

  public function __construct()
  {
    $this->rendezvouz     = array();
    $this->required_dir   = self::required_dir();
    $this->required_files = self::required_files();
    $this->ignored_files  = self::ignored_files();
    $this->count_dir      = count($this->required_dir);
  }

  public function required_dir()
  {
    return array(
      DIR.'/lefalya/panada/Resources',
	    DIR.'/lefalya/panada/Resources/Interfaces',
	    DIR.'/lefalya/app/config'
    );
  }

  public function ignored_files()
  {
    return array(
      DIR.'/lefalya/panada/Resources/Image.php'
    );
  }

  public function required_files()
  {
    return array(
      DIR.'/lefalya/panada/Drivers/Database/Mysqli.php',
	    DIR.'/lefalya/panada/Drivers/Session/Native.php',
	    DIR.'/lefalya/app/Models/System_session.php'
    );
  }

  public function getRequiredFiles_fromDir()
  {
    foreach($this->required_dir as $val){
      foreach(glob($val.'/*.php') as $filename){
        $this->required_dir[$val][] = $filename;
      }
      for($x = 0; $x <= $this->count_dir; $x++){
        unset($this->required_dir[$x]);
      }
    }
    return true;
  }

  public function getRequiredFiles_fromDir_setRendezvous()
  {
    self::getRequiredFiles_fromDir();
    foreach($this->required_dir as $l1){
      foreach($l1 as $l2){
        $this->rendezvouz[] = $l2;
      }
    }
    return true;
  }

  public function getRequiredFiles_setRendezvous()
  {
    foreach($this->required_files as $val){
      $this->rendezvouz[] = $val;
    }
    return $this->rendezvouz;
  }

  public function commit()
  {
    self::getRequiredFiles_fromDir_setRendezvous();
    self::getRequiredFiles_setRendezvous();

    foreach ($this->rendezvouz as $key => $val){
      foreach($this->ignored_files as $key => $ig){
        $key = array_search($ig, $this->rendezvouz);
        unset($this->rendezvouz[$key]);
      }
    }
    return $this->rendezvouz;
  }

  public function PsySH_main_config_array()
  {
      return array (
        'defaultIncludes' => self::commit()
    );
  }
}

$psysh = new psysh_config();
return $psysh->PsySH_main_config_array();
