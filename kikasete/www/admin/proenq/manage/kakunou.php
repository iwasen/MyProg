<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�У�異�󥱡���ɽ������
// added by kthink - delete me
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/search.php");
include("$inc/enquete.php");
include("$inc/pro_enquete.php");
include("$inc/my_search.php");
include("$inc/mye_temp.php");
include("$inc/ma_profile.php");
include("$inc/pro_enq_target.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// ����ɽ����ǯ������
function disp_date1($year, $month, $day) {
    if ($year)
        echo sprintf('%dǯ%d��%d��', $year, $month, $day);
}

// ����ɽ����ǯ��������
function disp_date2($year, $month, $day, $hour) {
    if ($year)
        echo sprintf('%dǯ%d��%d�� %d��', $year, $month, $day, $hour);
}

// �ե�����ǥ���̵ͭ
function decode_finding_flag($flag) {
    switch ($flag) {
    case DBTRUE:
        return '����';
    case DBFALSE:
        return '�ʤ�';
    }
    return '';
}

set_global('proenquete', '�У�異�󥱡��ȴ���', '�У�異�󥱡��Ȳ�����Ǽ�ƥ���', BACK_TOP);

$pro_enq  = new pro_enquete_class;
$pro_enq->read_db($marketer_id, $pro_enq_no);

$enquete = &$pro_enq->enquete;
$enquete2 = &$pro_enq->enquete2;

// �ƥ����ѣգң̤�m_system������� 2006/03/15 BTI
$testTop = "";
$sql = "SELECT sy_url_monitor from m_system";
$result = db_exec($sql);
if (pg_numrows($result)) {
    $fetch = pg_fetch_row($result, 0);
    $testTop = $fetch[0];
}
$pro_enq_target = new pro_enq_target_class;
            $pro_enq_target->read_db($pro_enq->enquete_id);
            $target_flg = $pro_enq_target->target_flg;
            $testTop = $target_flg == 't'?  $testTop.'mypage' : 'https://kkst.infoq.jp';
            $testLink = $target_flg == 't'? '<a href="'.$testTop.'/m_remlenq3a.php?enquete_id='.$pro_enq->enquete_id.'" target="_blank" title="������Ǽ�ƥ��Ȳ��̤�ɽ�����ޤ�">'.$testTop.'/m_remlenq3a.php?enquete_id='.$pro_enq->enquete_id.'</a>' : '<a href="http://www.kikasete.net/monitor/mypage/m_remlenq3a.php?enquete_id='.$pro_enq->enquete_id.'" target="_blank" title="������Ǽ�ƥ��Ȳ��̤�ɽ�����ޤ�">'.
$testTop.'/m_remlenq3a.php?enquete_id='.$pro_enq->enquete_id.'</a>';
// Ʊ��ID�ǺƲ�����ǽ
if ($renew == '1') {
    $admin_login_id = $_SESSION['admin_login_id'];
    $sql = "SELECT mn_monitor_id FROM m_admin JOIN t_test_monitor ON mn_mail_addr=ad_mail_addr WHERE ad_admin_id=$admin_login_id";
    $result = db_exec($sql);
    if (pg_numrows($result)) {
        $fetch = pg_fetch_object($result, 0);
        $monitor_id = $fetch->mn_monitor_id;

		$sql = "DELETE FROM t_answer WHERE an_enquete_id IN ($pro_enq->enquete_id,$pro_enq->enquete2_id) and an_monitor_id=$monitor_id";
		db_exec($sql);
		$sql = "DELETE FROM t_ans_select WHERE as_enquete_id IN ($pro_enq->enquete_id,$pro_enq->enquete2_id) and as_monitor_id=$monitor_id";
		db_exec($sql);
		$sql = "DELETE FROM t_ans_matrix WHERE ax_enquete_id IN ($pro_enq->enquete_id,$pro_enq->enquete2_id) and ax_monitor_id=$monitor_id";
		db_exec($sql);
    }
}

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--

// Ʊ��ID�ǺƲ�����ǽ
function onclick_renew() {
	if( confirm("�����β�����Ǽ�ƥ��ȥǡ����Ϻ������ޤ�����\n������Ǥ�����") ) {
		location.href = "kakunou.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>&renew=1";
		return true;
	}
	return false;
}

//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="right" style="font-size:x-small;color:#666666"><a href="show.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>">��Pro���󥱡���ɽ�����̤����</a></div>

<? // ������Ǽ����κݤ�ɽ�����ʤ� 2006/03/28 BTI
if( !(($enquete->status == 3 && $enquete->sub_status == 3) || $enquete->status == 4 || $enquete->status == 5 || $enquete->status == 6 || $enquete->status == 7) ) { ?>
<div align="center">

<table border=0 cellspacing=2 cellpadding=3 width="80%" frame="box">
    <tr>
        <td colspan=2>
            <table border=0 cellspacing=0 cellpadding=0 width=100%>
                <tr class="m0">
                    <td>�����󥱡��ȳ���</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="m1" width="25%">���󥱡��ȥ����ȥ�</td>
        <td class="n1" width="75%"><?=htmlspecialchars($enquete->title)?></td>
    </tr>
    <tr>
        <td class="m1">�ե�����ǥ���̵ͭ</td>
        <td class="n1"><?=decode_finding_flag($pro_enq->finding_flag)?></td>
    </tr>
    <tr>
        <td class="m1">����</td>
        <td class="n1"><? disp_date1($enquete->start_date_y, $enquete->start_date_m, $enquete->start_date_d)?></td>
    </tr>
    <tr>
        <td class="m1">��λ</td>
        <td class="n1"><? disp_date2($enquete->end_date_y, $enquete->end_date_m, $enquete->end_date_d, $enquete->end_date_h)?></td>
    </tr>
    <tr>
        <td class="m1">�ݥ����</td>
        <td class="n1"><?=$pro_enq->finding_flag == DBTRUE ? "$enquete->point / $enquete2->point" : $enquete->point?>�ݥ����</td>
    </tr>
    <tr>
        <td class="m1">�ƥ����ѣգң�</td>
        <td class="n1">
            <?=$testLink;?>
        </td>
    </tr>
	<? // Ʊ��ID�ǺƲ�����ǽ ?>
    <tr>
        <td width="25%"></td>
        <td width="75%" style="font-size:x-small;"><input type="button" value="�⤦���ٲ�����Ǽ�ƥ��Ȥ�»ܤ���" onclick="onclick_renew();">&nbsp;�������Ԥ��Ȳ�����Ǽ�ƥ���CSV�ϥꥻ�åȤ���ޤ�</td>
    </tr>
</table>
</div>

<br />
<? } ?>

<div align="center">

<table border=0 cellspacing=2 cellpadding=3 width="80%" frame="box">
    <tr>
        <td colspan=3>
            <table border=0 cellspacing=0 cellpadding=0 width=100%>
                <tr class="m0">
                    <td>�����󥱡��Ȳ������</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="m1" width="25%" rowspan="5">�ãӣ֥ǡ������������</td>
    </tr>
    <tr>
        <td class="n1">�ե�����ǥ��󥰲���<br />��ʣ�������ϥ���޶��ڤ�ǡ�</td>
        <td class="n1" style="text-align:center;">
            <form method="post" action="pe_enq_csv2.php">
            <input type="hidden" name="mode" value="1">
            <input type="hidden" name="marketer_id" value="<?=$marketer_id?>">
            <input type="hidden" name="pro_enq_no" value="<?=$pro_enq_no?>">
            <input style="text-align:center;" type="image" src="<?=$top;?>/images/dl.gif" alt="������̥ե�����Υ��������">
            </form>
    </td>
    </tr>
    <tr>
        <td class="n1">�ե�����ǥ��󥰲���<br />��ʣ�������ϣ�����������</td>
        <td class="n1" style="text-align:center;">
            <form method="post" action="pe_enq_csv3.php">
            <input type="hidden" name="mode" value="1">
            <input type="hidden" name="marketer_id" value="<?=$marketer_id?>">
            <input type="hidden" name="pro_enq_no" value="<?=$pro_enq_no?>">
            <input style="text-align:center;" type="image" src="<?=$top;?>/images/dl.gif" alt="������̥ե�����Υ��������">
            </form>
        </td>
    </tr>
    <tr>
        <td class="n1">�ե�����ǥ��󥰲������ܥ��󥱡��Ȳ���<br />��ʣ�������ϥ���޶��ڤ�ǡ�</td>
        <td class="n1" style="text-align:center;">
            <form method="post" action="pe_enq_csv2.php">
            <input type="hidden" name="mode" value="2">
            <input type="hidden" name="marketer_id" value="<?=$marketer_id?>">
            <input type="hidden" name="pro_enq_no" value="<?=$pro_enq_no?>">
            <input style="text-align:center;" type="image" src="<?=$top;?>/images/dl.gif" alt="������̥ե�����Υ��������">
            </form>
        </td>
    </tr>
    <tr>
        <td class="n1">�ե�����ǥ��󥰲������ܥ��󥱡��Ȳ���<br />��ʣ�������ϣ�����������</td>
        <td class="n1" style="text-align:center;">
            <form method="post" action="pe_enq_csv3.php">
            <input type="hidden" name="mode" value="2">
            <input type="hidden" name="marketer_id" value="<?=$marketer_id?>">
            <input type="hidden" name="pro_enq_no" value="<?=$pro_enq_no?>">
            <input type="image" src="<?=$top;?>/images/dl.gif" alt="������̥ե�����Υ��������">
            </form>
        </td>
    </tr>
</table>

<input type="hidden" name="next_action" value="app">
<input type="hidden" name="marketer_id" value="<?=$marketer_id?>">
<input type="hidden" name="pro_enq_no" value="<?=$pro_enq_no?>">
<input type="hidden" name="enquete_id" value="<?=$pro_enq->enquete_id?>">
<input type="hidden" name="start_date_y" <?=value($enquete->start_date_y)?>>
<input type="hidden" name="start_date_m" <?=value($enquete->start_date_m)?>>
<input type="hidden" name="start_date_d" <?=value($enquete->start_date_d)?>>
<input type="hidden" name="start_date_h" <?=value($enquete->start_date_h)?>>
</div>

<br />

<? page_footer() ?>
</body>
</html>
