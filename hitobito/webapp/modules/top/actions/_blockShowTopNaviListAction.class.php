<?php
/**
 * ナビ一覧 ナビ一覧表示アクション
 *
 * @package top
 * @author y.fujimori<wfujimori@xxxxxxx.co.jp>
 * @version $id$
 */

class _blockShowTopNaviListAction extends Action
{
    public function execute ()
    {
    }
    
    public function getDefaultView ()
    {
        return View::SUCCESS;
    }
    
    public function getRequestMethods ()
    {
      return Request::NONE;
    }
}
?>