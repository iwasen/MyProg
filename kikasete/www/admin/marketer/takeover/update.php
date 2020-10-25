<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:マーケター引継ぎ実行
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// メイン処理
set_global('marketer', 'マーケター管理', 'サービスの引継ぎ', BACK_TOP);

db_begin_trans();

// Myアンケートを引き継ぎ
$sql = "SELECT me_marketer_id,me_my_enq_no FROM t_my_enquete WHERE me_marketer_id=$from_marketer_id ORDER BY me_my_enq_no";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$rec['me_marketer_id'] = sql_number($to_marketer_id);
	$rec['me_my_enq_no'] = "COALESCE((SELECT MAX(me_my_enq_no) FROM t_my_enquete WHERE me_marketer_id=$to_marketer_id),0)+1";
	db_update('t_my_enquete', $rec, "me_marketer_id=$fetch->me_marketer_id AND me_my_enq_no=$fetch->me_my_enq_no");
}

// PROアンケートを引き継ぎ
$sql = "SELECT mep_marketer_id,mep_pro_enq_no FROM t_pro_enquete WHERE mep_marketer_id=$from_marketer_id ORDER BY mep_pro_enq_no";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$rec['mep_marketer_id'] = sql_number($to_marketer_id);
	$rec['mep_pro_enq_no'] = "COALESCE((SELECT MAX(mep_pro_enq_no) FROM t_pro_enquete WHERE mep_marketer_id=$to_marketer_id),0)+1";
	db_update('t_pro_enquete', $rec, "mep_marketer_id=$fetch->mep_marketer_id AND mep_pro_enq_no=$fetch->mep_pro_enq_no");
}

// Myパートナーを引継ぎ
$rec['ppj_marketer_id'] = sql_number($to_marketer_id);
db_update('t_mp_pjt', $rec, "ppj_marketer_id=$from_marketer_id");

// 引き継ぎ先の親マーケターIDを消す
$rec['mr_parent_id'] = 'null';
db_update('t_marketer', $rec, "mr_marketer_id=$to_marketer_id AND mr_parent_id IS NOT NULL");

// 引継ぎ後もMyアンケートを見られるようにする
/*
if ($flag) {
	$rec['mr_parent_id'] = sql_number($to_marketer_id);
	db_update('t_marketer', $rec, "mr_marketer_id=$from_marketer_id");
}
*/

db_commit_trans();
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body onLoad="document.all.ok.focus()">
<? page_header() ?>

<div align="center">
<p class="msg">サービスの引継ぎを行いました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='../../menu.php'"></p>
</div>

<? page_footer() ?>
</body>
</html>
