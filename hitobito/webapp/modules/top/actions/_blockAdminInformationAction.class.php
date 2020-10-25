<?php
/**
 * �����ǂ���̂��m�点�ꗗ �\���A�N�V����
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
        
    	// �����ǃu���O�ŐV�L��
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