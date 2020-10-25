<?php
/**
 * 注目ナビ 表示アクション
 *
 * @package top
 * @author y.fujimori<wfujimori@xxxxxxx.co.jp>
 * @version $id$
 */

require_once MO_WEBAPP_DIR.'/modules/Blog/lib/BlogStoryManager.class.php';
class _blockShowTopTodaysPushNavipageAction extends Action
{

    public function execute()
    {
    
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        
        // ナビランキング
        $naviManager = new hitobitoNavipageManager();
        $todayspush = $naviManager->getTodaysPushNavipage();


		$patterns[0] = '/\s[\r\n]/';
		$patterns[1] = '/　　/';
		// ナビ概要取得（改行及びHTMLタグの削除）
		$navi_outline = strip_tags (preg_replace($patterns,' ', $todayspush[1][navi_outline]));
		if(mb_strlen($navi_outline) > 80){
			$navi_outline = mb_substr($navi_outline,0,80);
			$navi_outline_flg = 1;
		}

		// 最新記事取得（改行及びHTMLタグの削除）
		$last_body = strip_tags (preg_replace($patterns,' ', $todayspush[1][last_body]));
		if(mb_strlen($last_body) > 96){
			$last_body = mb_substr($last_body,0,96);
			$navi_body_flg = 1;
		}
        
        $todays_push = array(
	        'photo_id'			=>$todayspush[1][photo_id],
	        'title'				=>$todayspush[1][title],
	        'navipage_id'		=>$todayspush[1][navipage_id],
	        'navi_name'			=>$todayspush[1][navi_name],
	        'navi_outline'		=>$navi_outline,
	        'last_blog_id'		=>$todayspush[1][last_blog_id],
	        'last_title'		=>$todayspush[1][last_title],
	        'last_body'			=>$last_body,
	        'navi_outline_flg'	=>$navi_outline_flg,
	        'navi_body_flg'		=>$navi_body_flg,
	        'last_update'		=>$todayspush[1][last_update]
        );
        
        $request->setAttribute('navi_todays_push', $todays_push);
        return View::SUCCESS;
    }
}
?>