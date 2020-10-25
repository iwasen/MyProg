<?php
/**
 * @package HNb
 * @author Ryuji
 * @version $Id: HNbActionFilter.class.php,v 1.2 2005/12/13 18:31:26 ryu Exp $
 */
 
 
class HNbActionFilter
{
	protected	$parameters = array();
	protected	$context;
	
	
	protected function fetch($context)
	{
		$this->context = $context;
	}
	
	function getCriteria()
	{
	}
	
	
	public function getExtraParameters()
	{
		return $this->parameters;
	}
	
//	protected function addParameterByInteger($name)
//	{
//		$request = $this->context->getRequest();
//		if($request->hasParameter($name)){
//			$this->parameters[$name] = intval($request->getParameter($name));
//		}		
//	}
	protected function hasParameter($name)
	{
		return isset($this->parameters[$name]);
	}

	protected function addParameter($name, $var)
	{
		$this->parameters[$name] = $var;
	}
	protected function getParameter($name)
	{
		return $this->parameters[$name];
	}
}