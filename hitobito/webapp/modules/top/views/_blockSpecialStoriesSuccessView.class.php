<?php
/**
 * 特集記事 表示ビュー
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
        
        // 特集記事
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->setTemplate('_blockSpecialStories.html');
        
    }
}
?>