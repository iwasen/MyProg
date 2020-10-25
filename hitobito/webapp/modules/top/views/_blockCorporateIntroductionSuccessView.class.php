<?php
/**
 * �o�i�[�L���������@�\ �\���r���[
 *
 * @package top
 * @author y.fujimori<wfujimori@xxxxxxx.co.jp>
 * @version $id$
 */

class _blockCorporateIntroductionSuccessView extends SmartyView
{

    public function execute ()
    {

        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }

        $this->setTemplate('_blockCorporateIntroduction.html');
    }
}
?>