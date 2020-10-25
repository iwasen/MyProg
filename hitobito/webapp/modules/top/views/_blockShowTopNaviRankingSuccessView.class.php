<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowTopNaviRankingSuccessView.class.php,v 1.1 2007/01/31 08:57:37 shimizu Exp $
 */

class _blockShowTopNaviRankingSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('_blockTopNaviRanking.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
		
        $naviranking = $request->getAttribute('navi_navi_ranking');
        if(count($naviranking) > 0){
			// 1位ナビ情報
			$navi_ranking_top = array('rank' => 1, 'id' => $naviranking[1]->getId(), 'title' => $naviranking[1]->getTitle(), 'navi_name' =>$naviranking[1]->getNaviName(), 
				'description' => $naviranking[1]->getDescription(), 'photo'=> $naviranking[1]->getPhotoId());
			$this->setAttribute('navi_ranking_top', $navi_ranking_top);
			// 2位以下ナビ情報
			unset($naviranking[1]);
	        $navi_ranking = array();
	        foreach($naviranking as $rank => $navi){
				$navi_ranking[] = array('rank' => $rank, 'id' => $navi->getId(), 'title' => $navi->getTitle(), 'navi_name' =>$navi->getNaviName());
	        	
	        }
			$this->setAttribute('navi_navi_ranking', $navi_ranking);
        }
    }
}

?>