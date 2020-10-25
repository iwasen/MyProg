<?php
/**
 * @package	Weblinks
 * @author Ryuji
 * @version $Id: categoryForm.class.php,v 1.1 2005/12/12 08:05:26 ryu Exp $
 */
require_once MO_WEBAPP_DIR .'/lib/HNbActionForm.class.php';

class WeblinksCategoryForm extends HNbActionForm
{
	public
		$weblinks_category_name;
	
	public function fetch($master , $request)
	{
		parent::fetch($master, $request);
		
		$strParamsKey = array('weblinks_category_name');
//		$intParamsKey = array('category_order');
		
		foreach($strParamsKey as $key){
			$this->$key = trim($request->getParameter($key, ''));
		}
		
//		foreach($intParamsKey as $key){
//			$this->$key = intval($request->getParameter($key, 0));
//		}
		
		$validater = new HNbValidater();
		
		// 入力必須項目のチェック 
		if(strlen($this->weblinks_category_name) == 0){
			$this->addError('カテゴリ名称を入力してください');
		}


	}
	
	public function load($master, $requset)
	{
		$this->weblinks_category_name = $master->getAttribute('lct_category_name');
	}
	
	public function update($master, $request)
	{
		$master->setAttribute('lct_category_name', $this->weblinks_category_name);
	}
}
?>