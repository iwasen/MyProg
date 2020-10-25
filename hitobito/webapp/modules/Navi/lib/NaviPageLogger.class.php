<?php
/**
* NaviPageLogger
* @author Ryuji
* @version $Id: NaviPageLogger.class.php,v 1.1 2006/05/06 04:57:12 ryu Exp $
*/

class NaviPageLogger
{
    static private function incrementLog($target, $navipageId, $currentTime)
    {
        $db = HNb::getAdodb();
        $currentDate = self::time2date($currentTime);
        if(self::hasRecord($navipageId, $currentTime)){
            $sql = 'UPDATE t_navi_page_daily_log SET '.$target.'='.$target.'+1 '.self::getWhereSql($navipageId, $currentDate);
            $db->Execute($sql);
        }else{
            $record = array(
                'ndl_navi_page_id' => $navipageId,
                'ndl_date'=> $currentDate,
                $target =>1
                );
            $db->AutoExecute('t_navi_page_daily_log', $record, 'INSERT');
        }
        
    }
    static public function incrementPageView($navipageId, $currentTime)
    {
        self::incrementLog('ndl_page_view', $navipageId, $currentTime);
    }
    
    static public  function incrementVisiters($navipageId, $currentTime)
    {
        if(isset($_COOKIE['navipage_visit'])){
            $visitNavipageList = explode('|', $_COOKIE['navipage_visit']);
            if(in_array($navipageId, $visitNavipageList)){
                return;
            }
        }
        self::incrementLog('ndl_visiters', $navipageId, $currentTime);
        
        $currentDate = self::time2date($currentTime);
        $expire = strtotime($currentDate) + 24*60*60;
        $visitNavipageList = array();
        if(isset($_COOKIE['navipage_visit'])){
            $visitNavipageList = explode('|', $_COOKIE['navipage_visit']);
        }
        $visitNavipageList[] = $navipageId;
        sort($visitNavipageList);
        setcookie('navipage_visit', implode('|', $visitNavipageList), $expire);
    }
    
    static public function incrementGuestMember($navipageId, $currentTime)
    {
        self::incrementLog('ndl_guest_num', $navipageId, $currentTime);
    }
    
    static public function incrementRegistMember($navipageId, $currentTime)
    {
        self::incrementLog('ndl_regist_num', $navipageId, $currentTime);
    }
    
    static public function incrementResignMember($navipageId, $currentTime)
    {
        self::incrementLog('ndl_resign_num', $navipageId, $currentTime);
    }
    
    static public function getWhereSql($navipageId, $currentDate)
    {
        $db = HNb::getAdodb();
        return ' WHERE ndl_navi_page_id='.$navipageId.' AND ndl_date='.$db->qstr($currentDate);
    }
    
    static public function hasRecord($navipageId, $currentTime)
    {
        $db = HNb::getAdodb();
        $currentDate = self::time2date($currentTime);
        $sql = 'SELECT count(*) FROM t_navi_page_daily_log '. self::getWhereSql($navipageId, $currentDate);
        $result = $db->GetOne($sql);
        return ($result >0) ? true : false;
    }
    
    static public function time2date($time)
    {
        return date('Y-m-d' ,$time);
    }
}
?>