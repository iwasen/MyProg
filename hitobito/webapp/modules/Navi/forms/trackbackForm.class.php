<?php

require_once MO_WEBAPP_DIR .'/lib/HNbActionForm.class.php';
abstract class NaviAdminModifyTrackbackForm extends HNbSemiAutoActionForm
{
	public
		$navi_blog_name,
		$navi_title,
		$navi_url,
		$navi_excerpt,
		$navi_status,
		$navi_date;
    /**
     * 文字列パラメータキーの配列を返す
     */
    protected function getStringParameterKeys()
    {
    	return array('navi_blog_name', 'navi_title', 'navi_url', 'navi_excerpt');
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
    		'navi_blog_name' => HNb::tr('ブログタイトルを入力してください。'),
    		'navi_title' => HNb::tr('タイトルを入力してください。'),
    		'navi_url' => HNb::tr('URLを入力してください。'),
    		'navi_excerpt' => HNb::tr('投稿内容を入力してください。'));
    }
    public function fetch($master, $request)
    {
    	parent::fetch($master, $request);
    	$validator = new HNbValidator();
    	if(!$validator->validateUrl($this->navi_url)){
    		$this->addError(HNb::tr('URLを正しく入力してください。'));
    	}
    }
}
class BlogAdminModifyTrackbackForm extends NaviAdminModifyTrackbackForm
{
	public function load($master, $requset)
	{
		$this->navi_blog_name = $master->getAttribute('blt_blog_name');
		$this->navi_title = $master->getAttribute('blt_title');
		$this->navi_url = $master->getAttribute('blt_url');
		$this->navi_excerpt = $master->getAttribute('blt_excerpt');
		$this->navi_status = $master->getAttribute('blt_open_flag');
		$this->navi_date = $master->getAttribute('blt_date');
	}
	
	public function update($master, $request)
	{
		
		$master->setAttribute('blt_blog_name', $this->navi_blog_name);
		$master->setAttribute('blt_title', $this->navi_title);
		$master->setAttribute('blt_url', $this->navi_url);
		$master->setAttribute('blt_excerpt', $this->navi_excerpt);
		$master->setAttribute('blt_open_flag', $this->navi_status);
	}
	
}
class EnqueteAdminModifyTrackbackForm extends NaviAdminModifyTrackbackForm
{
	public function load($master, $requset)
	{
		$this->navi_blog_name = $master->getAttribute('net_blog_name');
		$this->navi_title = $master->getAttribute('net_title');
		$this->navi_url = $master->getAttribute('net_url');
		$this->navi_excerpt = $master->getAttribute('net_excerpt');
		$this->navi_status = $master->getAttribute('net_open_flag');
		$this->navi_date = $master->getAttribute('net_date');
	}
	
	public function update($master, $request)
	{
		
		$master->setAttribute('net_blog_name', $this->navi_blog_name);
		$master->setAttribute('net_title', $this->navi_title);
		$master->setAttribute('net_url', $this->navi_url);
		$master->setAttribute('net_excerpt', $this->navi_excerpt);
		$master->setAttribute('net_open_flag', $this->navi_status);
	}
}
class MelmagaAdminModifyTrackbackForm extends NaviAdminModifyTrackbackForm
{
	public function load($master, $requset)
	{
		$this->navi_blog_name = $master->getAttribute('mmt_blog_name');
		$this->navi_title = $master->getAttribute('mmt_title');
		$this->navi_url = $master->getAttribute('mmt_url');
		$this->navi_excerpt = $master->getAttribute('mmt_excerpt');
		$this->navi_status = $master->getAttribute('mmt_open_flag');
		$this->navi_date = $master->getAttribute('mmt_date');
	}
	
	public function update($master, $request)
	{
		
		$master->setAttribute('mmt_blog_name', $this->navi_blog_name);
		$master->setAttribute('mmt_title', $this->navi_title);
		$master->setAttribute('mmt_url', $this->navi_url);
		$master->setAttribute('mmt_excerpt', $this->navi_excerpt);
		$master->setAttribute('mmt_open_flag', $this->navi_status);
	}
}
?>