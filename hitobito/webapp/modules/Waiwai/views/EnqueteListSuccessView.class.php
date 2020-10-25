<?php
/**
  *
  * @auther Ryuji
  * @version $Id: EnqueteListSuccessView.class.php,v 1.2 2006/06/07 07:32:07 aizawa Exp $
  */


require_once MO_MODULE_DIR.'/Waiwai/lib/WaiwaiBaseView.class.php';

class EnqueteListSuccessView extends WaiwaiBaseView
{
    public function execute(){
  		$request = $this->getContext()->getRequest();

        $this->setTemplate('EnqueteList.html');
        $enquete_list = $request->getAttribute('enquete_list');
        $this->setAttribute('enquete_list', $enquete_list);
        
        
        $this->useMainTemplate();
        
        
    }
}

?>