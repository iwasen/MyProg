<?php
/**
 * @package	Blog
 * @author Ryuji
 * @version $Id: storyForm.class.php,v 1.9 2006/06/07 09:08:32 ryu Exp $
 */
require_once MO_WEBAPP_DIR .'/lib/HNbActionForm.class.php';

class BlogStoryForm extends HNbActionForm
{
//	public 	$storytitle,
//			$storybody,
//			$blog_id,
//			$member_id;
	public
		$bla_article_id,
		$bla_blog_id,
		$bla_status,
		$bla_title,
		$bla_article_text,
		$bla_ng_word,
		$bla_patrol_check,
		$bla_patrol_date,
		$bla_patrol_id,
		$bla_admin_check,
		$bla_admin_date,
		$bla_admin_id,
		$bla_open_flag,
		$bla_date,
		$bla_communication_msg,
		$bla_pickup,
		$bla_keisai_flag,
		$bla_navi_select_flag,
		$bla_comment_flag,
		$bla_trackback_flag,
		$bla_pv_id,
		$bla_member_id,
		$bla_blog_category_id,
		$bla_blog_image_id;
		
	public
		$trackbacks,
		$blog_date_option = 1;
	
	
	public function fetch($master , $request)
	{
		parent::fetch($master, $request);
		
		$strParamsKey = array('bla_title', 'bla_article_text', 'trackbacks');
		$intParamsKey = array('bla_open_flag', 'bla_navi_select_flag', 'bla_comment_flag', 'bla_trackback_flag', 'bla_blog_category_id',
			'bla_blog_image_id');
		
		foreach($strParamsKey as $key){
			$this->$key = trim($request->getParameter($key, ''));
		}
		
		foreach($intParamsKey as $key){
			$this->$key = intval($request->getParameter($key, 0));
		}
		
		$validater = new HNbValidater();
		
		// 入力必須項目のチェック 
		if(strlen($this->bla_title) == 0){
			$this->addError('タイトルを入力してください');
		}

		if(strlen($this->bla_article_text) == 0){
			$this->addError('本文を入力してください');
		}
		if($this->bla_blog_category_id == 0){
			$this->addError(HNb::tr('カテゴリを選択してください'));
		}
		
		if($this->blog_date_option == 1){
			$date_array = $request->getParameter('bla_date');
			if(!checkdate($date_array['Month'], $date_array['Day'], $date_array['Year'])){
				$this->addError(HNb::tr('タイムスタンプの指定が正しくありません'));
			}
			$timestamp = sprintf('%04d-%02d-%02d %02d:%02d:00',
				$date_array['Year'],
				$date_array['Month'],
				$date_array['Day'],
				$date_array['Hour'],
				$date_array['Minute']);
			$this->bla_date = $timestamp;
		}else{
			$this->bla_date = date("Y-m-d H:i:s");
		}
	}
	
	public function load($master, $requset)
	{
		$this->bla_title = $master->getAttribute('bla_title');
		$this->bla_article_text = $master->getAttribute('bla_article_text');
		$this->bla_open_flag = $master->getAttribute('bla_open_flag');
		$this->bla_navi_select_flag = $master->getAttribute('bla_navi_select_flag');
		$this->bla_comment_flag = $master->getAttribute('bla_comment_flag');
		$this->bla_trackback_flag = $master->getAttribute('bla_trackback_flag');
		$this->bla_blog_category_id = $master->getAttribute('bla_blog_category_id');
		$this->bla_blog_image_id = $master->getAttribute('bla_blog_image_id');
		$this->bla_date = $master->getAttribute('bla_date');
//		$this->storytitle = $master->getTitle();
//		$this->storybody  = $master->getBody();
//		$this->blog_id = $master->getBlog_id();
	}
	
	public function update($master, $request)
	{
		$master->setAttribute('bla_title', $this->bla_title);
		$master->setAttribute('bla_article_text', $this->bla_article_text);
		$master->setAttribute('bla_open_flag', $this->bla_open_flag);
		$master->setAttribute('bla_navi_select_flag', $this->bla_navi_select_flag);
		$master->setAttribute('bla_comment_flag', $this->bla_comment_flag);
		$master->setAttribute('bla_trackback_flag', $this->bla_trackback_flag);
		$master->setAttribute('bla_blog_category_id', $this->bla_blog_category_id);
		$master->setAttribute('bla_blog_image_id', $this->bla_blog_image_id);
		$master->setAttribute('bla_date', $this->bla_date);
		$master->setTrackBackLIst(explode("\n",$this->trackbacks));
		$master->setAttribute('bla_ping_flag', NULL);
//		$master->setTitle($this->storytit le);
//		$master->setBody($this->storybody);
//		$master->setPoster($this->member_id);
//		$master->setBlog_id($this->blog_id);
	}
}
?>