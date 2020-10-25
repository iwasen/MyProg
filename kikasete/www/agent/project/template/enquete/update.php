<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:質問文マスタ登録
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");

//メイン処理
set_global2('project', 'プロジェクト管理｜テンプレート編集', '質問文テンプレート更新', BACK_TOP);

switch ($next_action) {
case 'new':
	db_begin_trans();
	$sql = "INSERT INTO t_enquete (en_enq_kind) VALUES (3)";
	if (db_exec($sql)) {
		$enquete_id = get_currval('t_enquete_en_enquete_id_seq');
		$i = 1;
		while ($eq_question_text[$i]) {
			$sql= "INSERT INTO t_enq_question (eq_enquete_id,eq_question_no,eq_question_text,eq_question_type,eq_fa_flag) VALUES ($enquete_id,%s,%s,%s,%s)";
			$sql = sprintf($sql,
				sql_number($i),
				sql_char($eq_question_text[$i]),
				sql_number($eq_question_type[$i]),
				sql_bool('f'));
			if (db_exec($sql)) {
				$j = 1;
				while($es_sel_text[$i][$j] && $eq_question_type[$i] != '3') {
					$sql = "INSERT INTO t_enq_select (es_enquete_id,es_question_no,es_sel_no,es_sel_text) VALUES ($enquete_id,%s,%s,%s)";
					$sql = sprintf($sql,
						sql_number($i),
						sql_number($j),
						sql_char($es_sel_text[$i][$j]));
					if (!db_exec($sql)) {
						$ng = 'ng';
					}
				$j++;
				}
			} else {
				$ng = 'ng';
			}
			$i++;
		}
	} else {
		$ng = 'ng';
	}
	if ($ng == "") {
		$sql = "INSERT INTO m_pjt_question (pq_title,pq_enquete_id) VALUES (%s,%s)";
		$sql = sprintf($sql,
			sql_char($pq_title),
			sql_number($enquete_id));
		db_exec($sql);
		db_commit_trans();
		$back = "location.href='list.php'";
		$msg = "登録しました。";
	} else {

	}
	break;
case 'update':
	db_begin_trans();
	$sql = "SELECT pq_seq_no FROM m_pjt_question WHERE pq_title=".sql_char($pq_title)." AND pq_seq_no<>$pq_no";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_pjt_question SET pq_title=".sql_char($pq_title)." WHERE pq_seq_no=$pq_no";
		db_exec($sql);
		$sql = "DELETE FROM t_enq_question WHERE eq_enquete_id=$en_id";
		db_exec($sql);
		$sql = "DELETE FROM t_enq_select WHERE es_enquete_id=$en_id";
		db_exec($sql);

		$i = 1;
		while ($eq_question_text[$i]) {
			$sql= "INSERT INTO t_enq_question (eq_enquete_id,eq_question_no,eq_question_text,eq_question_type,eq_fa_flag) VALUES ($en_id,%s,%s,%s,%s)";
			$sql = sprintf($sql,
				sql_number($i),
				sql_char($eq_question_text[$i]),
				sql_number($eq_question_type[$i]),
				sql_bool('f'));
			if (db_exec($sql)) {
				$j = 1;
				while($es_sel_text[$i][$j] && $eq_question_type[$i] != '3') {
					$sql = "INSERT INTO t_enq_select (es_enquete_id,es_question_no,es_sel_no,es_sel_text) VALUES ($en_id,%s,%s,%s)";
					$sql = sprintf($sql,
						sql_number($i),
						sql_number($j),
						sql_char($es_sel_text[$i][$j]));
					if (!db_exec($sql)) {
						$ng = 'ng';
					}
				$j++;
				}
			} else {
			}
			$i++;
		}
		db_commit_trans();
		$msg = '登録しました。';
		$back = "location.href='list.php'";
	} else {
		db_rollback();
		$msg = 'テンプレート名は既に登録されています。';
		$back = "history.back()";
	}
	break;
case 'delete':
	db_begin_trans();
	$sql = "DELETE FROM m_pjt_question WHERE pq_seq_no=$pq_no";
	db_exec($sql);
	$sql = "DELETE FROM t_enquete WHERE en_enquete_id=$en_id";
	db_exec($sql);
	$sql = "DELETE FROM t_enq_question WHERE eq_enquete_id=$en_id";
	db_exec($sql);
	$sql = "DELETE FROM t_enq_select WHERE es_enquete_id=$en_id";
	db_exec($sql);
	db_commit_trans();

	$back = "location.href='list.php'";
	$msg = "削除しました。";
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
<? menu_script() ?>
</head>
<body onLoad="document.all.ok.focus()">

<? page_header() ?>
<? menu_start() ?>

<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td align="center" class="m4"><?=$g_title?></td>
	</tr>
	<tr>
		<td align="center"><br><br>
			<p class="msg"><?=$msg?></p>
			<p><input type="button" id="ok" value="　戻る　" onclick="<?=$back?>"></p>
		</td>
	</tr>
</table>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
