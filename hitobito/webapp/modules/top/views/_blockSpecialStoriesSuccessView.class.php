<?php
/**
 * ���W�L�� �\���r���[
 *
 * @package top
 * @author y.fujimori<wfujimori@xxxxxxx.co.jp>
 * @version $id$
 */


class _blockSpecialStoriesSuccessView extends SmartyView
{

    public function execute ()
    {
    
        $request = $this->getContext()->getRequest();
        
        // ���W�L��
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->setTemplate('_blockSpecialStories.html');
        
    }
}
?>