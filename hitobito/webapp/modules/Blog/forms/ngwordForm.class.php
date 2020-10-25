<?php
/**
 * @package	Blog
 * @author Ryuji
 * @version $Id: ngwordForm.class.php,v 1.1 2006/01/09 14:31:36 ryu Exp $
 */
require_once MO_WEBAPP_DIR .'/lib/HNbActionForm.class.php';
class BlogNgwordForm extends HNbSemiAutoActionForm
{
	public
		$blog_type,
		$blog_word,
		$blog_status;
	
	protected function getStringParameterKeys()
	{
		$strParamsKey = array('blog_word');
		return $strParamsKey;
	}
	protected function getIntegerParameterKeys()
	{
		$intParamsKey = array('blog_type', 'blog_status');
		return $intParamsKey;
	}
	protected function getRequireParameterKeys()
	{
		return array(
			'blog_type' => HNb::tr('禁止項目を選択してください。'),
			'blog_word' => HNb::tr('URL/ワードを入力してください。'),
			'blog_status' => HNb::tr('ステータスを選択してください'),
		);
	}
	
	public function load($master, $requset)
	{
		$this->blog_type = $master->getAttribute('bng_type');
		$this->blog_status = $master->getAttribute('bng_status');
		$this->blog_word = $master->getAttribute('bng_word');
	}
	
	public function update($master, $request)
	{
		$master->setAttribute('bng_type', $this->blog_type);
		$master->setAttribute('bng_status', $this->blog_status);
		$master->setAttribute('bng_word', $this->blog_word);
	}
}
?>