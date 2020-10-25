<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowNaviRankingSuccessView.class.php,v 1.2 2006/01/12 11:10:16 ryu Exp $
 */

class _blockShowNaviRankingSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('_blockNaviRanking.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
		
        $naviranking = $request->getAttribute('navi_navi_ranking');
        if(count($naviranking) > 0){
			// 	top
			$navi_ranking_top = array('rank' => 1, 'id' => $naviranking[1]->getId(), 'title' => $naviranking[1]->getTitle(), 'navi_name' =>$naviranking[1]->getNaviName(), 
				'description' => $naviranking[1]->getDescription(), 'photo'=> $naviranking[1]->getPhotoId());
			$this->setAttribute('navi_ranking_top', $navi_ranking_top);
			//2°Ì°Ê²¼
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