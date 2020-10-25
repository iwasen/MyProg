<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package hitobito2
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockHeaderAction.class.php,v 1.7 2006/11/09 07:11:24 ryu Exp $
 */
require_once MO_WEBAPP_DIR.'/hitobito/hitobitoChannel.class.php';
require_once MO_WEBAPP_DIR.'/modules/Blog/lib/BlogStoryManager.class.php';
class _blockHeaderAction extends Action
{
    public function execute()
    {

        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        // https ���������ʤ�Хʡ���ɽ��
        if(!(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on')){
        	$this->setupBanner($request);
        }
    	
    	// ��̳�ɥ֥��ǿ�����
    	$blog_id = hitobito::getAdminBlogId();
    	$blogManager = new BlogOneBlogStoryManager($blog_id);
    	$stories = $blogManager->getStoriesByCategoryId(0, TRUE, 3);
    	$request->setAttribute('hitobito2_news', $stories);
    	$request->setAttribute('hitobito2_admin_navipage_id', hitobito::getAdminNavipageId());
    	
    	//�����ե�����
    	$search_word = $request->getParameter('search_word');
    	$request->setAttribute('search_word', $search_word);
    	$search_target = $request->getParameter('search_target');
    	if($search_target != 'story' && $search_target != 'room' && $search_target !='both'){
    		$search_target = 'both';
    	}
    	$request->setAttribute('search_target', $search_target);
    	// �����ե�������hidden ==> channel , navipage
    	$search_hidden = array();
    	$navipage_id = hitobito::getNaviPageInfo()->getId();
    	$category_id = hitobito::getChannelId();
    	if($navipage_id > 0){
    		$search_hidden['navipage_id'] = $navipage_id;
    	}elseif($category_id > 0){
			$search_hidden['category_id'] = $category_id;
    	}
    	$request->setAttribute('search_hidden', $search_hidden);	
    	
        
        return View::SUCCESS;
    }
    
    private function setupBanner(&$request)
    {
		// navipage�˥Хʡ������ꤵ��Ƥ����餽���������ͥ�Хʡ����ͥ�褷��ɽ������
    	$navipage = hitobito::getNaviPageInfo();
		if($navipage->hasBanner()){
			// �ʥӥڡ����Хʡ�
			$banner = $navipage->getBanner();
		}else{
			// �����ͥ�Хʡ�
			$channelId = hitobito::getChannelId();
	    	$channel = new hitobitoChannel();
	    	$channel->load($channelId);
	    	$banner = $channel->getBanner();
		}
	    $request->setAttribute('hitobito2_banner', $banner);
    }
    
    public function isSecure()
    {
        return false;
    }
}

?>