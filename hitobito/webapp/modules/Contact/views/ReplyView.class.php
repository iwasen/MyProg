<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Contact
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: ReplyView.class.php,v 1.1 2005/12/22 23:35:50 ryu Exp $
 */

class ReplyInputView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('reply.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->useMainTemplate();
        
        $inquiry = $request->getAttribute('inquiry');
        $this->setAttribute('inquiry', $inquiry);
        switch($inquiry->getAttribute('nvi_inquiry_type')){
        	case 1:
        	case 2:
        		$view_template = '_view_2.inc.html';
        		break;
        	case 3:
        	case 4:
        		$view_template = '_view_1.inc.html';
        		break;
        	case 5:
        		$view_template = '_view_3.inc.html';
        		break;
        	default:
        		if(strlen($inquiry->getAttribute('nvi_company_name')) > 0){
        			$view_template = '_view_1.inc.html';
        		}else{
        			$view_template = '_view_2.inc.html';
        		}
        }
        $this->setAttribute('inquiry_view_template', $view_template);
               $inquiry_type_options = array(
        	1 => HNb::tr('記事について'),
        	2 => HNb::tr('ワイワイ会議室について'),
        	3 => HNb::tr('仕事依頼'),
        	4 => HNb::tr('取材依頼'),
        	5 => HNb::tr('質問'),
        	9 => HNb::tr('その他')
        );
        $this->setAttribute('inquiry_type_options', $inquiry_type_options);
        
    }
}

class ReplySuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('result.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->useMainTemplate();
    }
}

class ReplyErrorView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('error.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->useMainTemplate();
    }
}
?>