<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�У�異�󥱡����ѹ�����
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/enquete.php");
include("$inc/pro_enquete.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// ���󥱡��ȼ»ܾ��������
function select_enq_status($sel) {
    echo "<option ", value_selected('5', $sel), ">�»���</option>";
    echo "<option ", value_selected('6', $sel), ">������</option>";
    echo "<option ", value_selected('7', $sel), ">��λ</option>";
    echo "<option ", value_selected('8', $sel), ">�ƿ���</option>"; // �ںƿ������ɲ� 2006/03/30 BTI
    echo "<option ", value_selected('9', $sel), ">����Ѥ�</option>";
}

set_global('proenquete', '�У�異�󥱡��ȴ���', '�У�異�󥱡��Ⱦ����ѹ�', BACK_TOP);

// ���å�������
if ($edit) {
    $pro_enq = &$_SESSION['ss_pro_enq'];
    $pro_enq_no = $pro_enq->pro_enq_no;
} else {
    $_SESSION['ss_pro_enq'] = new pro_enquete_class;
    $pro_enq = &$_SESSION['ss_pro_enq'];
    $pro_enq->read_db($marketer_id, $pro_enq_no);
}

$enquete = &$pro_enq->enquete;
$enquete2 = &$pro_enq->enquete2;

// �ե�����ǥ���̵ͭ�ν����
if ($pro_enq->finding_flag == '')
    $pro_enq->finding_flag = DBFALSE;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update1.php">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
    <tr>
        <td class="m0">���»ܳ���</td>
    </tr>
    <tr>
        <td width="20%" class="m1">���󥱡��ȥ����ȥ�</td>
        <td width="80%" class="n1">
            <input type="text" name="title" size="46" <?=value($enquete->title)?>>
        </td>
    </tr>
    <tr>
        <td class="m1">���󥱡��ȷ���</td>
        <td class="n1">
            <?=$pro_enq->finding_flag == DBTRUE ? '�ե�����ǥ��󥰤���' : '�ե�����ǥ��󥰤ʤ�'?>
            <input type="hidden" name="finding_flag" <?=value($pro_enq->finding_flag)?>>
        </td>
    </tr>
    <tr>
        <td class="m1">��������</td>
        <td class="n1">
            <input type="text" name="start_date_y" <?=value($enquete->start_date_y)?> size="5" maxlength="4">ǯ
            <input type="text" name="start_date_m" <?=value($enquete->start_date_m)?> size="3" maxlength="2">��
            <input type="text" name="start_date_d" <?=value($enquete->start_date_d)?> size="3" maxlength="2">��
        </td>
    </tr>
        <td class="m1">��λ����</td>
        <td class="n1">
            <input type="text" name="end_date_y" <?=value($enquete->end_date_y)?> size="5" maxlength="4">ǯ
            <input type="text" name="end_date_m" <?=value($enquete->end_date_m)?> size="3" maxlength="2">��
            <input type="text" name="end_date_d" <?=value($enquete->end_date_d)?> size="3" maxlength="2">��
            <input type="text" name="end_date_h" <?=value($enquete->end_date_h)?> size="3" maxlength="2">��
        </td>
    </tr>
    <tr>
        <td class="m1">����ץ���</td>
        <td class="n1">
<?
$ans_cond = explode("\n", $pro_enq->ans_cond);
for ($i = 0; $i < 5; $i++) {
?>
                <input type="text" name="ans_cond[]" size=50 maxlength=100 <?=value($ans_cond[$i])?>><br>
<?
}
?>
        </td>
    </tr>
    <tr>
        <td class="m1">�ݥ���ȿ�</td>
        <td class="n1">
            <input class="number" type="text" name="point" size="3" maxlength="3" <?=value($enquete->point)?>>
<?
if ($pro_enq->finding_flag == DBTRUE) {
?>
            / <input class="number" type="text" name="point2" size="3" maxlength="3" <?=value($enquete2->point)?>>
<?
}
?>
            �ݥ����
        </td>
    </tr>
    <tr>
        <td class="m1">�»ܾ���</td>
        <td class="n1"><select name="status"><? select_enq_status($enquete->status) ?></select></td>
    </tr>
</table>
<br>
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="button" value="����롡" onclick="location.href='show.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>'">
<input type="hidden" name="next_action">
<input type="hidden" name="marketer_id" <?=value($marketer_id)?>>
<input type="hidden" name="pro_enq_no" <?=value($pro_enq_no)?>>
<input type="hidden" name="enq_type" value="2">
<input type="hidden" name="point" <?=value($enquete->point)?>>
</form>
</div>

<? page_footer() ?>
</body>
</html>
