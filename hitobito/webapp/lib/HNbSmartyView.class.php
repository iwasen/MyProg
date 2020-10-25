<?php
// $Id: HNbSmartyView.class.php,v 1.2 2005/11/22 15:47:29 ryu Exp $
// FILE		::	HNbSmartyView.class.php
// AUTHOR	::	Ryuji AMANO <info@ryus.biz>
// WEB		::	Ryu's Planning <http://ryus.biz/>
//
class HNbSmartyView extends SmartyView{
    public function __construct()
    {
        parent::__construct();
        if(method_exists($this, 'isOutput') && $this->isOutput()){
           // $this->systemDecorate();
        }
    }

    function systemDecorate(){
        //setup our decorator template
//        $this->setDecoratorDirectory(MO_TEMPLATE_DIR);
//        $this->setDecoratorTemplate('GlobalTemplate.html');
//        
//        //setup our decorator slots
//        //(SlotName, Module, Action)
//        $this->setSlot('menu', 'Default', 'Menu');
//         
    } 
}


?>