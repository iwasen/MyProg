<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Member
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockLoginSuccessView.class.php,v 1.2 2006/01/19 07:38:31 ryu Exp $
 */

class _blockLoginSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('_blockLogin.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        if($request->getParameter('module') !='Member'){
     		$this->setAttribute('redirect_url', htmlspecialchars($_SERVER['REQUEST_URI'])) ;
        }  
    }
}

?>