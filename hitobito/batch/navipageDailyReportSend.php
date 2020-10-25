<?php
/**
* ナビページデイリーレポート送信バッチ
* 
* 前日の集計レポートを送信する
* @author Ryuji
* @version $Id: navipageDailyReportSend.php,v 1.1 2006/05/06 04:57:12 ryu Exp $
*/
require_once './batch.inc.php';

require_once '../webapp/modules/hitobito2/lib/hitobitoMailer.class.php';
require_once '../webapp/lib/HNb.class.php';
class dailyReportMailer extends hitobitoMailer
{
    private $navipageId,
        $navipageTitle,
        $naviMail,
        $reports = array();
    public function __construct($navipage_id, $navipage_title, $navi_mail)
    {
        $this->navipageId = $navipage_id;
        $this->navipageTitle = $navipage_title;
        $this->naviMail = $navi_mail;
    }
//    public function setAttribute($key, $var)
//    {
//        $this->attributes[$key] = $var;
//    }
    public function send()
    {
        $report = implode("\n", $this->reports);
        $this->setAttribute('REPORT', $report);
        $db = getDB();
        $this->loadTemplate('report2navi');
        $site_url = $db->GetOne("SELECT sys_value FROM m_system WHERE sys_key='user_page_url'");
        $url = $site_url.'index.php?module=Navi&NaviEdit&navipage_id='.$this->navipageId;
        $this->setAttribute('NAVIPAGE_TITLE',$this->navipageTitle);
        $this->setAttribute('NAVIEDIT_URL', $url);
        return parent::send($this->naviMail);
        //mail adr取得
        // navipageタイトル取得
//        printf("ID: %s<br />",$this->navipageId);
//        printf("TITLE: %s<br />",$this->navipageTitle);
//        printf("MAIL: %s<br />",$this->naviMail);
//        echo "<pre>";
//        print_r($this->attributes);
//        echo "</pre>";
    }
    public function addReport($text)
    {
        $this->reports[] = $text;
    }
}

$logger = new batchLogger(basename(__FILE__), 'ナビページデイリーレポート送信');
$db = getDB();

//有効なナビページのデイリーレポート設定取得
$field_list = array(
    't_navi_page_daily_report.*',
    't_navi_page_daily_log.*',
    'nvp_navi_page_id',
    'nvp_title',
    'mbr_mail_addr'
    );
$fields = implode(',', $field_list);
$currentTime = time();
//$currentTime = strtotime('2006-05-01 12:00:00');
$currentDate = date('Y-m-d', $currentTime);
$yesterdayDate = date('Y-m-d', $currentTime-(24*60*60));

$sql = 'SELECT '.$fields.' FROM t_navi_page_daily_report '
    .' JOIN t_navi_page_daily_log ON (ndr_navi_page_id=ndl_navi_page_id AND ndl_date='.$db->qstr($yesterdayDate).')'
    .' JOIN t_navi_page ON ndr_navi_page_id=nvp_navi_page_id'
    .' JOIN t_navi ON nvp_navi_id = nav_navi_id'
    .' JOIN t_member ON nav_member_id=mbr_member_id'
    .' WHERE ndr_status = 1'
    .' AND nvp_status=1 AND nvp_open_flag=1';
$navipageRS = $db->Execute($sql);
if($navipageRS){
    while($row = $navipageRS->FetchRow()){
        $mailer = new dailyReportMailer($row['nvp_navi_page_id'], $row['nvp_title'], $row['mbr_mail_addr']);
        //コメント数取得
        if($row['ndr_comment_report']){
            $report = sprintf("コメント件数： %s 件", $row['ndl_comment_num']);
            $mailer->addReport($report);
        }
        //トラックバック数取得
        if($row['ndr_trackback_report']){
            $report = sprintf('トラックバック件数： %s 件', $row['ndl_trackback_num']);
            $mailer->addReport($report);
        }
        //問い合わせ数取得
        if($row['ndr_contact_report']){
            $report = sprintf('問い合わせ件数： %s 件', $row['ndl_contact_num']);
            $mailer->addReport($report);
        }
        // ページビュー
        if($row['ndr_page_view_report']){
            $report = sprintf('ナビページ　ページビュー数： %s 件', $row['ndl_page_view']);
            $mailer->addReport($report);
        }
        // 訪問者数
        if($row['ndr_visiter_report']){
            $report = sprintf('ナビページ　訪問者数： %s 件', $row['ndl_visiters']);
            $mailer->addReport($report);
        }
        // アンケート回答数
        if($row['ndr_answer_report']){
            $report = sprintf('アンケート　回答数： %s 件', $row['ndl_answer_num']);
            $mailer->addReport($report);
        }
        // 新規ゲスト数
        if($row['ndr_last_guest_report']){
            $report = sprintf('ワイワイ会議　新規ゲスト登録者数： %s 件', $row['ndl_guest_num']);
            $mailer->addReport($report);
        }
        
        // 新規登録者数
        if($row['ndr_last_regist_report']){
            $report = sprintf('ワイワイ会議　新規登録者数： %s 件', $row['ndl_regist_num']);
            $mailer->addReport($report);
        }
        // 新規解除者数
        if($row['ndr_last_resign_report']){
            $report = sprintf('ワイワイ会議　新規解除者数： %s 件', $row['ndl_resign_num']);
            $mailer->addReport($report);
        }
        // 投稿者数
        if($row['ndr_poster_report']){
            $report = sprintf('ワイワイ会議　投稿者数： %s 件', $row['ndl_poster_num']);
            $mailer->addReport($report);
        }
        // 投稿数
        if($row['ndr_posts_report']){
            $report = sprintf('ワイワイ会議　投稿数： %s 件', $row['ndl_posts']);
            $mailer->addReport($report);
        }
        
        if(!$mailer->send()){
            $logger->addError('ナビページID '.$row['nvp_navi_page_id'].'のデイリーレポート送信に失敗');
        }
        unset($mailer);
    }
}
?>