<?php
/**
 * 事務局からのお知らせ一覧 表示アクション
 *
 * @package top
 * @author y.fujimori<wfujimori@xxxxxxx.co.jp>
 * @version $id$
 */

require_once dirname(__FILE__).'/../config/config.inc.php';
class _blockAdminInformationAction extends Action
{

    public function execute ()
    {
        
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        
    	// 事務局ブログ最新記事
    	$blog_id = hitobito::getAdminBlogId();
    	$blogManager = new BlogOneBlogStoryManager($blog_id);
    	$stories = $blogManager->getStoriesByCategoryId(0, TRUE, 3);
        
        if(empty($stories) || ADMIN_INFO_BLOCK_VIEW_STATUS != 1){
        	return View::NONE;
        }
    	$request->setAttribute('hitobito2_news', $stories);
    	$request->setAttribute('hitobito2_admin_navipage_id', hitobito::getAdminNavipageId());
        
        return View::SUCCESS;
    }
}
?>