<?php
//error_reporting(0);
require_once(MO_SMARTY_DIR.'/Smarty.class.php');
require_once MO_WEBAPP_DIR.'/modules/hitobito2/lib/breadcrumb.class.php';
abstract class SmartyView extends View
{
	private $smarty;
	private $multiSlots = array();
	private static $_instance;


   static function getInstance()
   {
       
       if (!self::$_instance) {
           
           self::$_instance = new Smarty();
           
       } else {
           
           self::$_instance->Smarty();
           
       }
       
       return self::$_instance;
       
   }

   public function __construct()
   {

       $this->smarty = SmartyView::getInstance();
//       $this->smarty->clear_all_assign();
       $this->smarty->clear_config();
       $this->smarty->config_dir   = MO_CONFIG_DIR;
       $this->smarty->cache_dir    = MO_CACHE_DIR;
       $this->smarty->plugins_dir  = array("plugins","plugins_local");
       $this->smarty->left_delimiter = "<{";
       $this->smarty->right_delimiter = "}>";
		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'){
       		$this->smarty->register_outputfilter(array(&$this, 'relative_link2http_link'));
		}else{
       		$this->smarty->register_outputfilter(array(&$this, 'convertSslLink'));
		}

		$this->setGlobalAttribute();
   }
	public function relative_link2http_link($tpl_output, &$smarty)
	{
		$http_url = hitobito::getUrl();
		$tpl_output = preg_replace('/href[ \s\t\n]*=[ \s\t\n]*[\'"]{0,1}index.php([a-zA-Z0-9.-_\/?&;]*)[\'" >]{0,1}/', 'href="'.$http_url.'index.php$1"', $tpl_output);
		return $tpl_output;
	}
	public function convertSslLink($tpl_output, &$smarty)
	{
		// fullpath link to relative link
		$url = 'http://www.hitobito.net/';
		$rel_output = preg_replace('/=([ \s\t\n]*[\'"]{0,1})'.preg_quote($url, '/').'/', '=$1', $tpl_output);
		
		$ssl_url = hitobito::getSystemMaster('user_page_ssl_url');
		$amp = '(&|&amp;)';
		$index = 'index\.php\?';
		$pat = array();
		$rep = array();
		$pat[] = '/=([ \s\t\n]*[\'"]{0,1})'.$index.'module=Member'.$amp.'action=Login'.'/';
		$rep[] = '=$1'.$ssl_url.'index.php?module=Member&amp;action=Login';
		$pat[] = '/=([ \s\t\n]*[\'"]{0,1})'.$index.'module=Member'.$amp.'action=Regist'.'/';
		$rep[] = '=$1'.$ssl_url.'index.php?module=Member&amp;action=Regist';
		$pat[] = '/=([ \s\t\n]*[\'"]{0,1})'.$index.'module=Member'.$amp.'action=RegistNavi'.'/';
		$rep[] = '=$1'.$ssl_url.'index.php?module=Member&amp;action=RegistNavi';
		$pat[] = '/=([ \s\t\n]*[\'"]{0,1})'.$index.'module=Member'.$amp.'action=ModifyProfile'.'/';
		$rep[] = '=$1'.$ssl_url.'index.php?module=Member&amp;action=ModifyProfile';
		$pat[] = '/=([ \s\t\n]*[\'"]{0,1})'.$index.'module=Member'.$amp.'action=Edit'.'/';
		$rep[] = '=$1'.$ssl_url.'index.php?module=Member&amp;action=Edit';
		$pat[] = '/=([ \s\t\n]*[\'"]{0,1})'.$index.'module=Waiwai'.$amp.'action=RegistMember'.'/';
		$rep[] = '=$1'.$ssl_url.'index.php?module=Waiwai&amp;action=RegistMember';
		$pat[] = '/=([ \s\t\n]*[\'"]{0,1})'.$index.'module=Waiwai'.$amp.'action=NewRegist'.'/';
		$rep[] = '=$1'.$ssl_url.'index.php?module=Waiwai&amp;action=NewRegist';
		
		
		$tpl_output = preg_replace($pat, $rep, $rel_output);
		return $tpl_output;
	}
   
	protected function setGlobalAttribute()
	{ 
		$navipage = hitobito::getNaviPageInfo();
		$this->setAttribute('hitobitoNaviPageInfo', $navipage);
		$this->setAttribute('hitobitoUrl', hitobito::getUrl());
		$master = hitobito::getSystemMasterArray();
		$this->setAttribute('hitobitoSystemMaster', $master);
		$this->setAttribute('hitobitoSslUrl', $master['user_page_ssl_url']);
	}

    public function setTemplate ($template)
    {
    	parent::setTemplate($template);
    	$module = $this->getContext()->getModuleName();
    	$this->smarty->compile_id = $module;

    }
    
    
    public function clearAttributes ()
    {
        $this->smarty->clear_all_assign();
    }
    
    public function getAttributeNames ()
    {
        return array_keys($this->smarty->get_template_vars());
    }

    public function & getAttribute ($name)
    {
        return $this->smarty->get_template_vars($name);
    }
    
    public function & removeAttribute ($name)
    { 
        $retval = $this->smarty->get_template_vars($name);
        
        $this->smarty->clear_assign($name);

        return $retval;
    }

    public function setAttribute ($name, $value)
    {

        $this->smarty->assign($name, $value);
    }

    public function setAttributeByRef ($name, &$value)
    {
        $this->smarty->assign_by_ref($name, $value);
    }

    public function setAttributes ($attributes)
    {
        $this->smarty->assign($attributes);
    }
    
    public function setAttributesByRef (&$attributes)
    {
        $this->smarty->assign_by_ref($attributes);
    }
	
	public function appendAttribute($name, $value)
	{
		
		$this->smarty->append($name, $value);
	}

    public function & getEngine ()
    {
        return $this->smarty;
    }

	public function & render ()
	{
	
	    $retval = null;
	
	    // execute pre-render check
	    $this->preRenderCheck();
	
	    // get the render mode
	    $mode = $this->getContext()->getController()->getRenderMode();
	
	    $this->getEngine()->template_dir = $this->getDirectory();
	    $this->getEngine()->compile_dir  = MO_SMARTY_CACHE_DIR;
	
	    if ($mode == View::RENDER_CLIENT && !$this->isDecorator())
	    {
	        // render directly to the client
	        $this->getEngine()->display($this->getTemplate());
	    }
	    else if ($mode != View::RENDER_NONE)
	    {
	        // render to variable
	        $retval = $this->getEngine()->fetch($this->getTemplate());
	
	        // now render our decorator template, if one exists
	        if ($this->isDecorator())
	        {
	            $retval =& $this->decorate($retval);
	        }
	
	        if ($mode == View::RENDER_CLIENT)
	        {
	            echo($retval);
	            $retval = null;
	        }
	    }
	    return $retval;
	}
	
	protected function & decorate (&$content)
	{
	
	    // call our parent decorate() method
//	    parent::decorate($content);
        // alias controller
        $controller = $this->getContext()->getController();

        // get original render mode
        $renderMode = $controller->getRenderMode();

        // set render mode to var
        $controller->setRenderMode(self::RENDER_VAR);

        // grab the action stack
        $actionStack = $controller->getActionStack();

        // loop through our slots, and replace them one-by-one in the
        // decorator template
        $slots =& $this->getSlots();

        foreach ($slots as $name => &$slot)
        {

            // grab this next forward's action stack index
            $index = $actionStack->getSize();
            // forward to the first slot action
            $controller->forward($slot['module_name'],
                                 $slot['action_name']);

            // grab the action entry from this forward
            $actionEntry = $actionStack->getEntry($index);

            // set the presentation data as a template attribute
            $presentation =& $actionEntry->getPresentation();

            $this->setAttributeByRef($name, $presentation);

        }
		
			//print_r($this->smarty->_tpl_vars);
		// add Ryuji 
		$multiSlots = $this->multiSlots;
        foreach ($multiSlots as &$mslot)
        {
            // grab this next forward's action stack index
            $index = $actionStack->getSize();
            // forward to the first slot action
            $controller->forward($mslot['module_name'],
                                 $mslot['action_name']);

            // grab the action entry from this forward
            $actionEntry = $actionStack->getEntry($index);
//			print_r($this->smarty->_tpl_vars);

            // set the presentation data as a template attribute
            $presentation =& $actionEntry->getPresentation();

            $this->appendAttribute($mslot['attributename'], $presentation);

        }

        // put render mode back
        $controller->setRenderMode($renderMode);

        // set the decorator content as an attribute
        $this->setAttributeByRef('content', $content);

        // return a null value to satisfy the requirement
        $retval = null;

//        return $retval;
	
	    // render the decorator template and return the result
	    $decoratorTemplate = $this->getDecoratorDirectory() . '/' . $this->getDecoratorTemplate();

		$this->smarty->compile_id = 'HNb';	
	    $retval = $this->getEngine()->fetch($decoratorTemplate);
	
	    return $retval;
	}


	/**
	 * HNb�Υᥤ��ƥ�ץ졼�Ȥǽ��Ϥ��롣
	 */
    public function useMainTemplate()
	{
		if(substr($this->getContext()->getActionName(),0,1) == '_' ){
			// ����!��������Ϥޤ�Υ��������ΤȤ��ϡ����Υ᥽�åɸƤӽФ���ػߤ��ￎ�
			return ;
		}
        //setup our decorator template
        $this->setDecoratorDirectory(MO_TEMPLATE_DIR);

        if($this->getContext()->getActionName() == 'Index' && $this->getContext()->getModuleName() == 'Default'){
	        $this->setDecoratorTemplate('TopTemplate.html');
		}else{
	        $this->setDecoratorTemplate('GlobalTemplate.html');
		}
		$HNbBlockManager = new HNbBlockManager($this->getContext());
		
		$headBlockList = $HNbBlockManager->getHeadBlocks();
		if(count($headBlockList) > 0){
			foreach($headBlockList as $block){
				$this->setMultiSlot('HNbHeadBlocks', $block['module'], $block['action']);
			}
		}
		
		$leftBlockList = $HNbBlockManager->getLeftBlocks();
		if(count($leftBlockList) > 0){
			foreach($leftBlockList as $block){
				$this->setMultiSlot('HNbLeftBlocks', $block['module'], $block['action']);
			}
		}

		$centerBlockList = $HNbBlockManager->getCenterBlocks();
		if(count($centerBlockList) > 0){
			foreach($centerBlockList as $block){
				$this->setMultiSlot('HNbCenterBlocks', $block['module'], $block['action']);
			}
		}

		$rightBlockList = $HNbBlockManager->getRightBlocks();
		if(count($rightBlockList) > 0){
			$this->setAttribute('HNb_show_right_block', TRUE);
			foreach($rightBlockList as $block){
				$this->setMultiSlot('HNbRightBlocks', $block['module'], $block['action']);
			}
		}
		
		$header = $HNbBlockManager->getHeader();
		$this->setSlot('HNbHeader', $header['module'], $header['action']);
		
		
		$breadcrumbBuilder = new hitobitoBreadcrumbBuilder($this->getContext());
		$this->setAttribute('hitobito2_breadcrumb', $breadcrumbBuilder->render());
		
    }
    

    /**
    * popup�ѥƥ�ץ졼�Ȥǽ��Ϥ��ￎ�
    */
    public function usePopupTemplate()
    {
        $this->setDecoratorDirectory(MO_TEMPLATE_DIR);
        $this->setDecoratorTemplate('PopupTemplate.html');
    }

	/**
	 * First in First out �ǽ���륹��åȤ򥻥åȤ���
	 * 
	 * 
	 * 
	 * @param	string attribute̾
	 * @param	string �⥸�塼��̾
	 * @param	string ���������̎�
	 * 
	 * @return	void
	 */
	public function setMultiSlot ($attributeName, $moduleName, $actionName)
    {
		$this->multiSlots[] = array(
			'attributename' => $attributeName,
			'module_name' => $moduleName,
			'action_name' => $actionName);

    }


// function execute(){
//     $reqMod = $this->getContext()->getController()->getRequestModule();
//     $reqAct = $this->getContext()->getController()->getRequestAction();
//     $thisMod = $this->getContext()->getController()->getModule();
//     $thisAct = $this->getContext()->getController()->getAction();
//     //if($reqMod == $thisMod && $reqAct == $reqMod){
//         $this->systemDecorate();
//     //}
//     parent::execute();
// }

}
error_reporting(MO_ERROR_REPORTING);

?>
