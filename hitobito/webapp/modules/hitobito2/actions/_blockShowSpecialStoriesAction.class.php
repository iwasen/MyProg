<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package hitobito2
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowSpecialStoriesAction.class.php,v 1.2 2005/12/25 21:50:40 ryu Exp $
 */

class _blockShowSpecialStoriesAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        
        $handler = HNb::getDBHandler('t_feature_articles');
        $criteria = new HNbCriteria();
        $db = HNb::getAdodb();
        $now = $db->DBTimeStamp(time());
        $criteria->setWhere('far_status =1 AND far_start_date <='.$now.' AND far_end_date >='.$now);
        $criteria->setOrder('ORDER BY far_order ASC');
        $stories = $handler->getObjects($criteria);
        $request->setAttribute('hitobito_special_stories', $stories);
        
        
        return View::SUCCESS;
    }
    
    public function isSecure()
    {
        return false;
    }
}

?>