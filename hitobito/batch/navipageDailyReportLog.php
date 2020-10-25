<?php
/**
* ナビページデイリーレポートログ取得バッチ
* 
* 前日の集計レポートを送信する
* @author Ryuji
* @version $Id: navipageDailyReportLog.php,v 1.1 2006/05/06 04:57:12 ryu Exp $
*/
require_once './batch.inc.php';

require_once '../webapp/lib/HNb.class.php';
class dailyReport
{
    
    public function getNavipageCommentNum($navipageId, $from, $to)
    {
        $num = 0;
        $db = getDB();
        //blog
        $sql = "SELECT count(*) FROM t_blog_comment"
            ." JOIN t_blog_article ON blo_article_id=bla_article_id"
            ." JOIN t_navi_page ON bla_blog_id=nvp_blog_id"
            ." WHERE blo_date >= '".$from."' AND blo_date < '".$to."' AND nvp_navi_page_id=".$navipageId;
        $num += $db->GetOne($sql);

        // enq
        $sql = "SELECT count(*) FROM l_navi_enq_comment"
            ." JOIN t_navi_enquete ON neo_enquete_id=nen_enquete_id"
            ." WHERE neo_date >= '".$from."' AND neo_date < '".$to."' AND nen_navi_page_id=".$navipageId;
        $num += $db->GetOne($sql);
        
        // mailmag
        $sql = "SELECT count(*) FROM l_melmaga_comment"
            ." JOIN t_melmaga_contents ON mmo_melmaga_contents_id=mmc_melmaga_contents_id"
            ." JOIN t_navi_page ON mmc_melmaga_id=nvp_melmaga_id"
            ." WHERE mmo_date >= '".$from."' AND mmo_date < '".$to."' AND nvp_navi_page_id=".$navipageId;
        $num += $db->GetOne($sql);

        return $num;
    }

    public function getNavipageTrackbackNum($navipageId, $from, $to)
    {
        $num = 0;
        $db = getDB();
        //blog
        $sql = "SELECT count(*) FROM t_blog_trackback"
            ." JOIN t_blog_article ON blt_article_id=bla_article_id"
            ." JOIN t_navi_page ON bla_blog_id=nvp_blog_id"
            ." WHERE blt_date >= '".$from."' AND blt_date < '".$to."' AND nvp_navi_page_id=".$navipageId;
        $num += $db->GetOne($sql);

        // enq
        $sql = "SELECT count(*) FROM l_navi_enq_trackback"
            ." JOIN t_navi_enquete ON net_enquete_id=nen_enquete_id"
            ." WHERE net_date >= '".$from."' AND net_date < '".$to."' AND nen_navi_page_id=".$navipageId;
        $num += $db->GetOne($sql);
        
        // mailmag
        $sql = "SELECT count(*) FROM l_melmaga_trackback"
            ." JOIN t_melmaga_contents ON mmt_melmaga_contents_id=mmc_melmaga_contents_id"
            ." JOIN t_navi_page ON mmc_melmaga_id=nvp_melmaga_id"
            ." WHERE mmt_date >= '".$from."' AND mmt_date < '".$to."' AND nvp_navi_page_id=".$navipageId;
        $num += $db->GetOne($sql);

        return $num;
    }
    
    public function getNavipageContactNum($navipageId, $from, $to)
    {
        $sql = "SELECT count(*) FROM t_navi_inquiry"
            ." WHERE nvi_date >='".$from."' AND nvi_date < '".$to."' AND nvi_navi_page_id=".$navipageId;
        $db = getDB();
        $num = $db->GetOne($sql);
        return $num;
    }
    
    public function getNavipageAnswerNum($navipageId, $from, $to)
    {
        $sql = "SELECT count(*) FROM l_navi_enq_answer"
            ." JOIN t_navi_enquete ON nel_enquete_id = nen_enquete_id"
            ." WHERE nel_date >='".$from."' AND nel_date < '".$to."' AND nen_navi_page_id=".$navipageId;
        $db = getDB();
        $num = $db->GetOne($sql);
        return $num;
    }

    public function getRoomPosterNum($navipageId, $from, $to)
    {
        $sql = "SELECT count(DISTINCT rmk_member_id) FROM l_room_remark"
            ." JOIN t_room ON rmk_room_id=rom_room_id"
            ." JOIN t_navi_page ON rom_room_id=nvp_room_id"
            ." WHERE rmk_date >='".$from."' AND rmk_date < '".$to."' AND nvp_navi_page_id=".$navipageId;
        $db = getDB();
        $num = $db->GetOne($sql);
        return $num;
    }
    public function getRoomPosts($navipageId, $from, $to)
    {
        $sql = "SELECT count(*) FROM l_room_remark"
            ." JOIN t_room ON rmk_room_id=rom_room_id"
            ." JOIN t_navi_page ON rom_room_id=nvp_room_id"
            ." WHERE rmk_date >='".$from."' AND rmk_date < '".$to."' AND nvp_navi_page_id=".$navipageId;
        $db = getDB();
        $num = $db->GetOne($sql);
        return $num;
    }
    
    public function getGuestNum($navipageId, $from, $to)
    {
        $sql = "SELECT count(*) FROM t_room_member"
            ." JOIN t_room ON rmm_room_id=rom_room_id"
            ." JOIN t_navi_page ON rom_room_id=nvp_room_id"
            ." WHERE nvp_navi_page_id=".$navipageId
            ." AND rmm_guest_date >='".$from."' AND rmm_guest_date < '".$to."' AND rmm_status=2";
        $db = getDB();
        $num = $db->GetOne($sql);
        return $num;
    }
    public function getRegistNum($navipageId, $from, $to)
    {
        $sql = "SELECT count(*) FROM t_room_member"
            ." JOIN t_room ON rmm_room_id=rom_room_id"
            ." JOIN t_navi_page ON rom_room_id=nvp_room_id"
            ." WHERE nvp_navi_page_id=".$navipageId
            ." AND rmm_regist_date >='".$from."' AND rmm_regist_date < '".$to."' AND rmm_status=3";
        $db = getDB();
        $num = $db->GetOne($sql);
        return $num;
    }
    public function getResignNum($navipageId, $from, $to)
    {
        $sql = "SELECT count(*) FROM t_room_member"
            ." JOIN t_room ON rmm_room_id=rom_room_id"
            ." JOIN t_navi_page ON rom_room_id=nvp_room_id"
            ." WHERE nvp_navi_page_id=".$navipageId
            ." AND rmm_resign_date >='".$from."' AND rmm_resign_date < '".$to."' AND rmm_status=9";
        $db = getDB();
        $num = $db->GetOne($sql);
        return $num;
    }

}

function insertReportLog($reportLog)
{
    $db = getDB();
    $sql = "SELECT count(*) FROM t_navi_page_daily_log WHERE ndl_navi_page_id=".$reportLog['ndl_navi_page_id']." AND ndl_date=".$db->qstr($reportLog['ndl_date']);
    if($db->GetOne($sql)){
        $result = $db->AutoExecute('t_navi_page_daily_log', $reportLog, 'UPDATE', "ndl_navi_page_id=".$reportLog['ndl_navi_page_id']." AND ndl_date=".$db->qstr($reportLog['ndl_date']));
    }else{
        $result = $db->AutoExecute('t_navi_page_daily_log', $reportLog, 'INSERT');
    }
    return $result;
}

$logger = new batchLogger(basename(__FILE__), 'ナビページデイリーレポートログ取得');
$db = getDB();


$currentTime = time();
//$currentTime = strtotime('2005-10-21 12:00:00');
$currentDate = date('Y-m-d', $currentTime);
$yesterdayDate = date('Y-m-d', $currentTime-(24*60*60));


//ナビページIDリストの取得
$sql = 'SELECT nvp_navi_page_id FROM t_navi_page WHERE nvp_status=1 AND nvp_open_flag=1 ORDER BY nvp_navi_page_id';
$naviPageIdRS = $db->Execute($sql);
if($naviPageIdRS){
    while($row = $naviPageIdRS->FetchRow()){
        $navipageId = $row['nvp_navi_page_id'];
        $reportLog = array(
            'ndl_navi_page_id' => $navipageId,
            'ndl_date' => $yesterdayDate
        );
        
        //コメント数取得
        $reportLog['ndl_comment_num'] =  dailyReport::getNavipageCommentNum($navipageId, $yesterdayDate, $currentDate);
        // トラックバック数取得
        $reportLog['ndl_trackback_num'] =  dailyReport::getNavipageTrackbackNum($navipageId, $yesterdayDate, $currentDate);
        // 問い合わせ件数取得
        $reportLog['ndl_contact_num'] =  dailyReport::getNavipageContactNum($navipageId, $yesterdayDate, $currentDate);
        
        // ページビュー
        // 訪問者数
        // 回答数
        $reportLog['ndl_answer_num'] =  dailyReport::getNavipageAnswerNum($navipageId, $yesterdayDate, $currentDate);
        
        // 新規ゲスト数
        $reportLog['ndl_guest_num'] =  dailyReport::getGuestNum($navipageId, $yesterdayDate, $currentDate);
        // 新規登録数
        $reportLog['ndl_regist_num'] =  dailyReport::getRegistNum($navipageId, $yesterdayDate, $currentDate);
        // 新規解除数
        $reportLog['ndl_resign_num'] =  dailyReport::getResignNum($navipageId, $yesterdayDate, $currentDate);
        
        // 投稿者数
        $reportLog['ndl_poster_num'] =  dailyReport::getRoomPosterNum($navipageId, $yesterdayDate, $currentDate);
        // 投稿数
        $reportLog['ndl_posts'] =  dailyReport::getRoomPosts($navipageId, $yesterdayDate, $currentDate);
        
        //取得結果の保存
        $result = insertReportLog($reportLog);
        
    }
    
}
?>