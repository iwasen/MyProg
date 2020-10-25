<?php

class Error404Action extends Action
{
	public function execute()
	{
		return View::SUCCESS;
	}
	
	public function getRequestMethods()
	{
		return Request::POST | Request::GET;
	}
}
?>