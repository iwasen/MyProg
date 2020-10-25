<?php

class IndexAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        $controller->forward('Navi', 'ShowNaviPage');
        return View::NONE;
    }

}
?>