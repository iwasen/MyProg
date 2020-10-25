<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:１万人アンケート管理
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enquete.php");

function save_enquete($enquete_id) {
	$enquete = new enquete_class;

	$enquete->status = $_POST['status'];
	$enquete->enq_kind = 1;
	$enquete->enq_type = 2;
	$enquete->start_date_y = trim(mb_convert_kana($_POST['start_date_y'], 'n'));
	$enquete->start_date_m = trim(mb_convert_kana($_POST['start_date_m'], 'n'));
	$enquete->start_date_d = trim(mb_convert_kana($_POST['start_date_d'], 'n'));
	$enquete->start_date_h = trim(mb_convert_kana($_POST['start_date_h'], 'n'));
	$enquete->end_date_y = trim(mb_convert_kana($_POST['end_date_y'], 'n'));
	$enquete->end_date_m = trim(mb_convert_kana($_POST['end_date_m'], 'n'));
	$enquete->end_date_d = trim(mb_convert_kana($_POST['end_date_d'], 'n'));
	$enquete->end_date_h = trim(mb_convert_kana($_POST['end_date_h'], 'n'));
	$enquete->point = trim(mb_convert_kana($_POST['point'], 'n'));

	$enquete->title = trim($_POST['title']);
	$enquete->description = trim($_POST['description']);
	for ($i = 1; $i <= $_POST['question_cnt']; $i++) {
		$enquete->question[$i] = new question_class;
		$question = &$enquete->question[$i];
		$question->question_text = $_POST["question_text$i"];
		$question->question_type = $_POST["question_type$i"];
		for ($j = 1; $j <= $_POST["sel_cnt$i"]; $j++) {
			$question->sel_text[$j] = $_POST["sel_text$i"][$j - 1];
			$question->sel_text_s[$j] = $_POST["sel_text_s$i"][$j - 1];
		}
	}

	return $enquete->write_db($enquete_id);
}

// メイン処理
set_global('monitor', 'モニター管理', '１万人アンケート管理', BACK_TOP);

switch ($next_action) {
case 'new':
	db_begin_trans();
	$enqeute_id = save_enquete('');
	$sql = sprintf("INSERT INTO t_free_enquete (fe_top_page,fe_enquete_id,fe_report_addr) VALUES (%s,%s,%s)",
				sql_number($top_page),
				sql_number($enqeute_id),
				sql_char(str_replace("\n", ',', trim($report_addr))));
	db_exec($sql);
	db_commit_trans();
	$msg = '１万人アンケートを登録しました。';
	$back = "location.href='list.php'";
	break;
case 'update':
	$sql = "SELECT fe_enquete_id FROM t_free_enquete WHERE fe_seq_no=$seq_no";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_row($result, 0);
		$enquete_id = $fetch[0];
	}
	db_begin_trans();
	$enquete_id = save_enquete($enquete_id);
	$sql = sprintf("UPDATE t_free_enquete SET fe_top_page=%s,fe_enquete_id=%s,fe_report_addr=%s WHERE fe_seq_no=$seq_no",
				sql_number($top_page),
				sql_number($enquete_id),
				sql_char(str_replace("\n", ',', trim($report_addr))));
	db_exec($sql);
	db_commit_trans();
	$msg = '１万人アンケートを更新しました。';
	$back = "location.href='list.php'";
	break;
case 'delete':
	$sql = "SELECT fe_enquete_id FROM t_free_enquete WHERE fe_seq_no=$seq_no";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_row($result, 0);
		$enquete_id = $fetch[0];
		$sql = "DELETE FROM t_enquete WHERE en_enquete_id=$enquete_id";
		db_exec($sql);
	}
	$sql = "DELETE FROM t_free_enquete WHERE fe_seq_no=$seq_no";
	db_exec($sql);
	$msg = '１万人アンケートを削除しました。';
	$back = "location.href='list.php'";
	break;
default:
	redirect('list.php');
}
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
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$back?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
