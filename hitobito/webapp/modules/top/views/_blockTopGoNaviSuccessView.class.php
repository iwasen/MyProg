<?php
/**
 * メンバー登録 表示ビュー
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

        // メンバー登録
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }

        $this->setTemplate('_blockTopGoNavi.html');
    }
}

?>