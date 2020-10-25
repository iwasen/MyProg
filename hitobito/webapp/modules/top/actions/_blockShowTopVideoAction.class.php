<?php
/**
 * ビデオ 表示アクション
 *
 * @package top
 * @author y.fujimori<wfujimori@xxxxxxx.co.jp>
 * @version $id$
 */

require_once dirname(__FILE__).'/../config/config.inc.php';
class _blockShowTopVideoAction extends Action
{

    public function execute()
    {
    
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        if(TOP_VIDEO_BLOCK_VIEW_STATUS != 1){
        	return View::NONE;
        }

        return View::SUCCESS;
    }
}

?>