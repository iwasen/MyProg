<?php
/**
 * @package	Blog
 * @author Ryuji
 * @version $Id: commentForm.class.php,v 1.4 2009/04/23 02:32:03 aizawa Exp $
 */
require_once MO_WEBAPP_DIR .'/lib/HNbActionForm.class.php';
require_once MO_WEBAPP_DIR.'/modules/Blog/lib/NgwordValidator.class.php';

class BlogCommentForm extends HNbActionForm
{
	public
		$blog_comment_name,
		$blog_comment_text;
		
	
	
	public function fetch($master , $request)
	{
		parent::fetch($master, $request);
		
		$strParamsKey = array('blog_comment_name', 'blog_comment_text');
//		$intParamsKey = array('category_order');
		
		foreach($strParamsKey as $key){
			$this->$key = trim($request->getParameter($key, ''));
		}
		
//		foreach($intParamsKey as $key){
//			$this->$key = intval($request->getParameter($key, 0));
//		}

		if (!$request->getParameter('blog_key_change')) {
			$validater = new HNbValidater();
		
			// ����ɬ�ܹ��ܤΥ����å� 
			if(strlen($this->blog_comment_name) == 0){
				$this->addError('̾�������Ϥ��Ƥ�������');
			}

			if(strlen($this->blog_comment_text) == 0){
				$this->addError('�����Ȥ����Ϥ��Ƥ�������');
			}

			$blog_key_string = $request->getParameter('blog_key_string');
			if ($blog_key_string == '') {
				$this->addError('�������ʸ��������Ϥ��Ƥ�������');
			} elseif (strcasecmp(mb_convert_kana($blog_key_string, 'a'), $_SESSION['captcha_keystring']) != 0) {
				$this->addError('�������ʸ��������������ޤ���');
			}
		
	    	$NgWordValidator = new BlogNgwordValidator(hitobito::getNaviPageInfo()->getId());
	    	if(!$NgWordValidator->validateText($this->blog_comment_name)
		    		|| !$NgWordValidator->validateText($this->blog_comment_text)){
	    		$this->addError(HNb::tr('��Ƥ˺ݤ����Ϥ򤴱�θ���������������դ��Ϥ��äƤ��뤿�ᡢ<br>����Ƥ�ȿ�ǤǤ��ޤ���Ǥ�����'));
	    	}
		}

		unset($_SESSION['captcha_keystring']);
	}
	
	public function load($master, $requset)
	{
		$this->blog_comment_name = $master->getAttribute('blo_user_name');
		$this->blog_comment_text = $master->getAttribute('blo_comment');
		
	}
	
	public function update($master, $request)
	{
		$master->setAttribute('blo_user_name', $this->blog_comment_name);
		$master->setAttribute('blo_comment', $this->blog_comment_text);
	}
}
?>