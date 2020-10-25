<?php
/**
 * ヘッダー 表示ビュー
 *
 * @package top
 * @author y.fujimori<wfujimori@xxxxxxx.co.jp>
 * @version $id$
 */

class _blockHeaderSuccessView extends SmartyView
{

    public function execute ()
    {
    
        $request = $this->getContext()->getRequest();
    
        $this->setTemplate($request->getAttribute('template'));

		$systemMaster = HNbSystemMaster::getInstance();
    	$notifyAddr = $systemMaster->getAttribute('user_page_ssl_url');
		$this->setAttribute('ssl_url', $systemMaster->getAttribute('user_page_ssl_url'));
    }
}


?>