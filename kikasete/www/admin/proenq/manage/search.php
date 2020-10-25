<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｐｒｏアンケート人数検索処理
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/pro_search.php");
include("$inc/enquete.php");
include("$inc/pro_enquete.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// added by BTI 2006/03/07
$pro_enq =& new pro_enquete_class;
$pro_enq->read_db($marketer_id, $pro_enq_no);
$search =& $pro_enq->search;
$search->read_db($pro_enq->search_id);
// 人数検索
$find = $search->get_monitor_num($marketer_id, $pro_enq_no);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>検索結果</title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>

<div align="center">
<table width=200 border=1>
    <tr>
        <td class="m2" align="center">対象人数検索結果</td>
    </tr>
    <tr>
        <td class="n1" align="center"><?=number_format($find)?> 人</td>
    </tr>
</table>
<br>
<input type="button" value="　閉じる　" onclick="window.close()">
</div>

</body>
</html>
