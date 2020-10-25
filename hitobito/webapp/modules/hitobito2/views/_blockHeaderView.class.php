<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package hitobito2
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockHeaderView.class.php,v 1.5 2007/12/06 07:12:53 cvshitobito Exp $
 */


class _blockHeaderSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        if ($user->isAuthenticated()) {
            $this->setTemplate('_blockHeaderAuthenticated.html');
        } else {
            $this->setTemplate('_blockHeader.html');
        }
        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        // 検索ターゲットラジオボタン
        $search_target_options = array(
			'story' => HNb::tr('記事検索'),
			'room' => HNb::tr('ワイワイ会議室検索'),
			'both' => HNb::tr('両方'));
		$this->setAttribute('search_target_options', $search_target_options);
        $this->setAttribute('redirect_url', htmlspecialchars($_SERVER['REQUEST_URI']));
    }
}


?>