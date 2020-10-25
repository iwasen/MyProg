<?php
/**
* �ʥӥڡ����ǥ��꡼��ݡ��������Хå�
* 
* �����ν��ץ�ݡ��Ȥ���������
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
        //mail adr����
        // navipage�����ȥ����
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

$logger = new batchLogger(basename(__FILE__), '�ʥӥڡ����ǥ��꡼��ݡ�������');
$db = getDB();

//ͭ���ʥʥӥڡ����Υǥ��꡼��ݡ����������
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
        //�����ȿ�����
        if($row['ndr_comment_report']){
            $report = sprintf("�����ȷ���� %s ��", $row['ndl_comment_num']);
            $mailer->addReport($report);
        }
        //�ȥ�å��Хå�������
        if($row['ndr_trackback_report']){
            $report = sprintf('�ȥ�å��Хå������ %s ��', $row['ndl_trackback_num']);
            $mailer->addReport($report);
        }
        //�䤤��碌������
        if($row['ndr_contact_report']){
            $report = sprintf('�䤤��碌����� %s ��', $row['ndl_contact_num']);
            $mailer->addReport($report);
        }
        // �ڡ����ӥ塼
        if($row['ndr_page_view_report']){
            $report = sprintf('�ʥӥڡ������ڡ����ӥ塼���� %s ��', $row['ndl_page_view']);
            $mailer->addReport($report);
        }
        // ˬ��Կ�
        if($row['ndr_visiter_report']){
            $report = sprintf('�ʥӥڡ�����ˬ��Կ��� %s ��', $row['ndl_visiters']);
            $mailer->addReport($report);
        }
        // ���󥱡��Ȳ�����
        if($row['ndr_answer_report']){
            $report = sprintf('���󥱡��ȡ��������� %s ��', $row['ndl_answer_num']);
            $mailer->addReport($report);
        }
        // ���������ȿ�
        if($row['ndr_last_guest_report']){
            $report = sprintf('�磻�磻��ġ�������������Ͽ�Կ��� %s ��', $row['ndl_guest_num']);
            $mailer->addReport($report);
        }
        
        // ������Ͽ�Կ�
        if($row['ndr_last_regist_report']){
            $report = sprintf('�磻�磻��ġ�������Ͽ�Կ��� %s ��', $row['ndl_regist_num']);
            $mailer->addReport($report);
        }
        // ��������Կ�
        if($row['ndr_last_resign_report']){
            $report = sprintf('�磻�磻��ġ���������Կ��� %s ��', $row['ndl_resign_num']);
            $mailer->addReport($report);
        }
        // ��ƼԿ�
        if($row['ndr_poster_report']){
            $report = sprintf('�磻�磻��ġ���ƼԿ��� %s ��', $row['ndl_poster_num']);
            $mailer->addReport($report);
        }
        // ��ƿ�
        if($row['ndr_posts_report']){
            $report = sprintf('�磻�磻��ġ���ƿ��� %s ��', $row['ndl_posts']);
            $mailer->addReport($report);
        }
        
        if(!$mailer->send()){
            $logger->addError('�ʥӥڡ���ID '.$row['nvp_navi_page_id'].'�Υǥ��꡼��ݡ��������˼���');
        }
        unset($mailer);
    }
}
?>