<?php
/**
 * @package	Blog
 * @author Ryuji
 * @version $Id: categoryForm.class.php,v 1.1 2005/11/22 15:47:29 ryu Exp $
 */
require_once MO_WEBAPP_DIR .'/lib/HNbActionForm.class.php';

class BlogCategoryForm extends HNbActionForm
{
	public
		$blog_category_id,
		$blog_id,
		$category_name,
		$category_order;
		
	
	
	public function fetch($master , $request)
	{
		parent::fetch($master, $request);
		
		$strParamsKey = array('category_name');
		$intParamsKey = array('category_order');
		
		foreach($strParamsKey as $key){
			$this->$key = trim($request->getParameter($key, ''));
		}
		
		foreach($intParamsKey as $key){
			$this->$key = intval($request->getParameter($key, 0));
		}
		
		$validater = new HNbValidater();
		
		// 入力必須項目のチェック 
		if(strlen($this->category_name) == 0){
			$this->addError('カテゴリ名称を入力してください');
		}


	}
	
	public function load($master, $requset)
	{
		$this->category_name = $master->getAttribute('category_name');
		$this->category_order = $master->getAttribute('category_order');
	}
	
	public function update($master, $request)
	{
		$master->setAttribute('category_name', $this->category_name);
		$master->setAttribute('category_order', $this->category_order);
	}
}
?>