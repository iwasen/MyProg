<?php
/**
 * �����o�[�o�^ �\���r���[
 *
 * @package top
 * @author y.fujimori<wfujimori@xxxxxxx.co.jp>
 * @version $id$
 */

class _blockTopGoNaviSuccessView extends SmartyView
{

    public function execute ()
    {

        $request = $this->getContext()->getRequest();

        // �����o�[�o�^
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }

        $this->setTemplate('_blockTopGoNavi.html');
    }
}

?>