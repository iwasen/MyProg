<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:�Ϳ�����
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/search.php");
include("$inc/decode.php");
include("$inc/cross_search.php");
include("$inc/pro_search.php");

//�ᥤ�����
set_global('�Ϳ�����', BACK_TOP);

//$search = new search_class; // ����������Ȳ��̤Ρ֥�˥��������פ���ʬ�ˤ�����GMO��˥����������б� 2006/05/30
$search = new pro_search_class;
$search->get_form();

$monitor_tbl = "t_monitor";
if ($search->type == 6) { // GMO��˥���  2006/03/08
    $monitor_tbl = "g_monitor";
}
$sql = "SELECT COUNT(*) FROM $monitor_tbl WHERE mn_status=0 AND mn_blacklist_flg IS NULL";
$result = db_exec($sql);
if (pg_numrows($result)) {
    $fetch = pg_fetch_row($result, 0);
    $all = $fetch[0];
}

//$where = $search->make_sql();
if ($search->type == 6) { // ����������Ȳ��̤Ρ֥�˥��������פ���ʬ�ˤ�����GMO��˥����������б� 2006/05/30
    $where = $search->make_sql2();
}else{  // ���ΰʳ�
    $where = $search->make_sql();
}

$sql = "SELECT COUNT(*) FROM $monitor_tbl WHERE $where";
$result = db_exec($sql);
if (pg_numrows($result)) {
    $fetch = pg_fetch_row($result, 0);
    $find = $fetch[0];
}
$condition = $search->get_condition();
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<center>
<table border=0 cellspacing=2 cellpadding=3>
    <tr>
        <td>
            <table border=0 cellspacing=2 cellpadding=3 width="100%">
                <tr>
                    <td class="m4" align="center"><?=$g_title?></td>
                </tr>
            </table>
            <table class="small" border=0 cellspacing=2 cellpadding=2 width="100%">
                <tr>
                    <td class="m0" width=700 colspan=2 align="left">���������</td>
                </tr>
                <tr>
                    <td class="m2" width="25%">�������줿��˥�����</td>
                    <td class="n2"><?=number_format($find)?></td>
                </tr>
                <tr>
                    <td class="m2">��ͭ����˥�����</td>
                    <td class="n2"><?=number_format($all)?></td>
                </tr>
                <tr>
                    <td class="m2">�������줿���</td>
                    <td class="n2"><?=number_format($find / $all * 100, 1)?>%</td>
                </tr>
                <tr>
                    <td class="m2">�������</td>
                    <td class="n2"><font size="-1"><?=nl2br($condition)?></font></td>
                </tr>
                <tr>
                    <td class="m2">�ӣѣ�</td>
                    <td class="n2"><?=$sql?></td>
                </tr>
            </table><br>
<?
if ($cross1_flag || $cross2_flag) {
?>
            <table border=0 cellspacing=0 cellpadding=0 width="100%">
                <tr>
                    <td class="m0">������������</td>
                </tr>
                <tr>
                    <td>
                        <table border=1 cellspacing=0 cellpadding=3 bordercolorlight="#f0f0f0" frame="box" class="small">
            <? cross_search($cross1_flag ? $cross1 : '', $cross2_flag ? $cross2 : '', $where) ?>
                        </table>
                    </td>
                </tr>
            </table><br>
<?
}
?>
            <div align="center">
                <input type="submit" value="���Ĥ��롡" onclick="window.close()">
            </div>
        </td>
    </tr>
</table>
</center>
</body>
</html>