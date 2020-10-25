<?php
/**
 * バナー広告＆検索機能 表示アクション
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

        // https アクセスならバナー非表示
        if(!(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on')){
        	$this->setupBanner($request);
        }

        return View::SUCCESS;
    }
    
    private function setupBanner(&$request)
    {
		// navipageにバナーが設定されていたらそちらをチャンネルバナーより優先して表示する
    	$navipage = hitobito::getNaviPageInfo();
		if($navipage->hasBanner()){
			// ナビページバナー
			$banner = $navipage->getBanner();
		}else{
			// チャンネルバナー
			$channelId = hitobito::getChannelId();
	    	$channel = new hitobitoChannel();
	    	$channel->load($channelId);
	    	$banner = $channel->getBanner();
		}
	    $request->setAttribute('hitobito2_banner', $banner);
    }
}
?>