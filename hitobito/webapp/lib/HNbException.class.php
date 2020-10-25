<?php

class HNbException extends Exception
{
	protected
		$forward = null,
		$view = null;
		
	public function __construct($message =null, $code =0)
	{
		parent::__construct($message, $code);
	}
	
	public function setForward($module, $action)
	{
		$this->forward = array('module' => $module, 'action'=> $action);
	}
	public function hasForward ()
	{
    	if(empty($this->forward)){
    		return FALSE;
    	}else{
    		return TRUE;
    	}
    }
    public function getForwardModule()
    {
    	return $this->forward['module'];
    }
    public function getForwardAction()
    {
    	return $this->forward['action'];
    }
    
    public function setReturnView($view)
    {
    	$this->view = $view;
    }
    public function hasReturnView()
    {
    	if(empty($this->view)){
    		return FALSE;
    	}else{
    		return TRUE;
    	}
    }
    public function getReturnView()
    {
    	return $this->view;
    }
    	
}
?>