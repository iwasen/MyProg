<?php
/**
 * �����ǂ���̂��m�点�ꗗ �\���r���[
 *
 * @package top
 * @author y.fujimori<wfujimori@xxxxxxx.co.jp>
 * @version $id$
 */

class _blockAdminInformationSuccessView extends SmartyView
{
    public function execute ()
    {
        $request = $this->getContext()->getRequest();
        
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->setTemplate('_blockAdminInformationSuccess.html');
        
    }
}
?>