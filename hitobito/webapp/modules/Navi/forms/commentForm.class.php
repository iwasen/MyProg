<?php

require_once MO_WEBAPP_DIR .'/lib/HNbActionForm.class.php';
abstract class NaviAdminModifyCommentForm extends HNbSemiAutoActionForm
{
	public
		$navi_poster_name,
		$navi_comment,
		$navi_status,
		$navi_date;
    /**
     * 文字列パラメータキーの配列を返す
     */
    protected function getStringParameterKeys()
    {
    	return array('navi_poster_name', 'navi_comment');
    }
    /**
     * 整数パラメータキーの配列を返す
     */
    protected function getIntegerParameterKeys()
    {
    	return array('navi_status');
    }
    /**
     * 入力必須パラメータをキー、値にエラーメセッージを入れた連想配列を返す
     * @example return array('email' => 'メールアドレスが入力されていません');
     */
    protected function getRequireParameterKeys()
    {
    	return array(
			'navi_poster_name' => HNb::tr('投稿者名を入力してください。'), 
			'navi_comment' => HNb::tr('投稿内容を入力してください。'));
    }
}
class BlogAdminModifyCommentForm extends NaviAdminModifyCommentForm
{
	public function load($master, $requset)
	{
		$this->navi_poster_name = $master->getAttribute('blo_user_name');
		$this->navi_comment = $master->getAttribute('blo_comment');
		$this->navi_status = $master->getAttribute('blo_open_flag');
		$this->navi_date = $master->getAttribute('blo_date');
	}
	
	public function update($master, $request)
	{
		
		$master->setAttribute('blo_user_name', $this->navi_poster_name);
		$master->setAttribute('blo_comment', $this->navi_comment);
		$master->setAttribute('blo_open_flag', $this->navi_status);
	}
	
}
class EnqueteAdminModifyCommentForm extends NaviAdminModifyCommentForm
{
	public function load($master, $requset)
	{
		$this->navi_poster_name = $master->getAttribute('neo_user_name');
		$this->navi_comment = $master->getAttribute('neo_comment');
		$this->navi_status = $master->getAttribute('neo_open_flag');
		$this->navi_date = $master->getAttribute('neo_date');
	}
	
	public function update($master, $request)
	{
		
		$master->setAttribute('neo_user_name', $this->navi_poster_name);
		$master->setAttribute('neo_comment', $this->navi_comment);
		$master->setAttribute('neo_open_flag', $this->navi_status);
	}
	
}
class MelmagaAdminModifyCommentForm extends NaviAdminModifyCommentForm
{
	public function load($master, $requset)
	{
		$this->navi_poster_name = $master->getAttribute('mmo_user_name');
		$this->navi_comment = $master->getAttribute('mmo_comment');
		$this->navi_status = $master->getAttribute('mmo_open_flag');
		$this->navi_date = $master->getAttribute('mmo_date');
	}
	
	public function update($master, $request)
	{
		
		$master->setAttribute('mmo_user_name', $this->navi_poster_name);
		$master->setAttribute('mmo_comment', $this->navi_comment);
		$master->setAttribute('mmo_open_flag', $this->navi_status);
	}
	
}
?>