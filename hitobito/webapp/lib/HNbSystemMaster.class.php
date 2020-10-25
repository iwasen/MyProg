<?php

class HNbSystemMaster
{
	private static $instance;
	
	private $attributes = array();

	
	public static function getInstance()
	{
       if (!isset(self::$instance)) {
           $c = __CLASS__;
           self::$instance = new $c;
       }
       return self::$instance;
	} 
	private function __construct()
	{
		$this->loadSystemMaster();
	}
	
	private function loadSystemMaster()
	{
		$db = HNb::getAdodb();
		$result = $db->GetAll("SELECT * FROM m_system");
		foreach($result as $vars){
			$this->setAttribute($vars['sys_key'], $vars['sys_value']);
		}
	}
	
	private function setAttribute($key,$value)
	{
		$this->attributes[$key] = $value;
	}
	public function getAttribute($key)
	{
		return $this->attributes[$key];
	}
	public function getAttributes()
	{
		return $this->attributes;
	}
}
?>