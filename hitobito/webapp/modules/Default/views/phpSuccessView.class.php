<?php
// $Id: phpSuccessView.class.php,v 1.2 2005/11/22 15:47:29 ryu Exp $
// FILE		::	phpSuccessView.class.php
// AUTHOR	::	Ryuji AMANO <info@ryus.biz>
// WEB		::	Ryu's Planning <http://ryus.biz/>
//

class phpSuccessView extends SmartyView{
    function execute(){
        $this->setTemplate('dummy.html');
        $this->setAttribute('content', $this->getContext()->getRequest()->getAttribute('content'));
        $this->useMainTemplate();
    }
}
?>