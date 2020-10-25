<?php
/**
 * �Хʡ������������ǽ ɽ�����������
 *
 * @package top
 * @author y.fujimori<wfujimori@xxxxxxx.co.jp>
 * @version $id$
 */

require_once MO_WEBAPP_DIR.'/hitobito/hitobitoChannel.class.php';
require_once MO_WEBAPP_DIR.'/modules/Blog/lib/BlogStoryManager.class.php';

class _blockCorporateIntroductionAction extends Action
{
    public function execute()
    {

        $request = $this->getContext()->getRequest();

        // https ���������ʤ�Хʡ���ɽ��
        if(!(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on')){
        	$this->setupBanner($request);
        }

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
}
?>