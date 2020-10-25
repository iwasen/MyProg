<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:人数検索
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

//メイン処理
set_global('人数検索', BACK_TOP);

//$search = new search_class; // エージェント画面の「モニター検索」の部分においてGMOモニターを選択対応 2006/05/30
$search = new pro_search_class;
$search->get_form();

$monitor_tbl = "t_monitor";
if ($search->type == 6) { // GMOモニター  2006/03/08
    $monitor_tbl = "g_monitor";
}
$sql = "SELECT COUNT(*) FROM $monitor_tbl WHERE mn_status=0 AND mn_blacklist_flg IS NULL";
$result = db_exec($sql);
if (pg_numrows($result)) {
    $fetch = pg_fetch_row($result, 0);
    $all = $fetch[0];
}

//$where = $search->make_sql();
if ($search->type == 6) { // エージェント画面の「モニター検索」の部分においてGMOモニターを選択対応 2006/05/30
    $where = $search->make_sql2();
}else{  // その以外
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
                    <td class="m0" width=700 colspan=2 align="left">■検索結果</td>
                </tr>
                <tr>
                    <td class="m2" width="25%">検索されたモニター数</td>
                    <td class="n2"><?=number_format($find)?></td>
                </tr>
                <tr>
                    <td class="m2">全有効モニター数</td>
                    <td class="n2"><?=number_format($all)?></td>
                </tr>
                <tr>
                    <td class="m2">検索された割合</td>
                    <td class="n2"><?=number_format($find / $all * 100, 1)?>%</td>
                </tr>
                <tr>
                    <td class="m2">検索条件</td>
                    <td class="n2"><font size="-1"><?=nl2br($condition)?></font></td>
                </tr>
                <tr>
                    <td class="m2">ＳＱＬ</td>
                    <td class="n2"><?=$sql?></td>
                </tr>
            </table><br>
<?
if ($cross1_flag || $cross2_flag) {
?>
            <table border=0 cellspacing=0 cellpadding=0 width="100%">
                <tr>
                    <td class="m0">■クロスサーチ</td>
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
                <input type="submit" value="　閉じる　" onclick="window.close()">
            </div>
        </td>
    </tr>
</table>
</center>
</body>
</html>