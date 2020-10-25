<?php

class Error404SuccessView extends SmartyView
{
	public function execute()
	{
		$this->setTemplate('error404.html');
		$this->useMainTemplate();
	}

}
?>