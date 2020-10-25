<?php
/**
 * Smarty テンプレートを使うメール送信用クラス
 * 
 * @package	HNb
 * @author Ryuji
 * @version $Id: HNbMailer.class.php,v 1.1 2005/11/22 15:47:29 ryu Exp $
 */
require_once MO_WEBAPP_DIR.'/lib/smarty/libs/Smarty.class.php';

class HNbMailer {
	private $from;
	private $to;
	private $title;
	private $template;
	private $templateDir;
	private $_attributes = array();


    public function setAttribute($key, $var)
	{
    	$this->_attributes[$key] = $var;
    }
	
	public function setTemplate($tplFile)
	{
		$this->template = $tplFile;
	}
	
	public function setTemplatePath($tplPath)
	{
		$this->templateDir = $tplPath;
	}
	
	public function setTo($to)
	{
		$this->to = $to;
	}
	
	public function setSubject($subject)
	{
		$this->title = $subject;
	}
	
	public function setFrom($from)
	{
		$this->from = $from;
	}
    
    function send(){
    	
    	$tpl = new smarty();
		$tpl->left_delimiter = '<{';
		$tpl->right_delimiter = '}>';
    	$tpl->template_dir = $this->templateDir;
    	$tpl->compile_dir = MO_CACHE_DIR;
    	    	
    	foreach($this->_attributes as $key => $var){
    		$tpl->assign($key, $var);
    	}
    	
    	$message = $tpl->fetch($this->template);
		$from = 'From: '.$this->from;
		
		$result = mb_send_mail($this->to, $this->title, $message, $from);

		if(MO_DEBUG){
			echo '<!-- '.$message.'-->';
		}

		return $result;    	
    	
    	 
    }
}


?>