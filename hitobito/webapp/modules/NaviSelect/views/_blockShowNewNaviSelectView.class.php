<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package NaviSelect
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowNewNaviSelectView.class.php,v 1.2 2006/01/14 18:31:22 ryu Exp $
 */


class _blockShowNewNaviSelectSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('_blockNewNaviSelect.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        
        $channelId = intval($request->getParameter('category_id'));
        if($channelId){
        	$this->setAttribute('listUrlAddParameter', '&amp;category_id='.$channelId);
        }elseif(hitobito::getNaviPageInfo()->getId() > 0){
        	$this->setAttribute('listUrlAddParameter', '&amp;navipage_id='.hitobito::getNaviPageInfo()->getId());
        }else{
        	$this->setAttribute('listUrlAddParameter', '');
        }
        
    }
}

?>