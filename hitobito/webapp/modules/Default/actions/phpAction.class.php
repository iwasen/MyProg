<?php
// $Id: phpAction.class.php,v 1.2 2005/11/22 15:47:29 ryu Exp $
// FILE		::	phpAction.class.php
// AUTHOR	::	Ryuji AMANO <info@ryus.biz>
// WEB		::	Ryu's Planning <http://ryus.biz/>
//

class phpAction extends Action{

    function execute(){
        ob_start();
        echo "PHP ECHO ";
        $content = ob_get_contents();
        ob_end_clean();
        $request = $this->getContext()->getRequest();
        $request->setAttribute('content', $content);
        return View::SUCCESS;
    }

}

?>