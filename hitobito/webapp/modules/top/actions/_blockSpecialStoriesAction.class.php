<?php
/**
 * 特集記事 表示アクセス
 *
 * @package top
 * @author y.fujimori<wfujimori@xxxxxxx.co.jp>
 * @version $id$
 */


class _blockSpecialStoriesAction extends Action
{

    public function execute()
    {

        $request = $this->getContext()->getRequest();
        
        $handler = HNb::getDBHandler('t_feature_articles');
        $criteria = new HNbCriteria();
        $db = HNb::getAdodb();
        $now = $db->DBTimeStamp(time());
        $criteria->setWhere('far_status =1 AND far_start_date <='.$now.' AND far_end_date >='.$now);
        $criteria->setOrder('ORDER BY far_order ASC');
        $stories = $handler->getObjects($criteria);
        
        if(empty($stories)){
        	return View::NONE;
        }
        
        $request->setAttribute('hitobito_special_stories', $stories);
        
        return View::SUCCESS;
    }
    
}
?>