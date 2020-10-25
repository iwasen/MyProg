<?php

class IndexAction extends Action
{
	public function execute()
	{
		$controller = $this->getContext()->getController();
		$controller->forward('contents', 'ShowPage');
		return View::NONE;
	}
}		
?>