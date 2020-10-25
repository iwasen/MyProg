<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・リクルート承認処理
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
set_global2('mypartner', 'Myパートナー管理', 'Myパートナー　該当条件設定', BACK_TOP);

// アンケートID取得
$sql = "SELECT ppj_enquete_id FROM t_mp_pjt WHERE ppj_pjt_id=$pjt_id";
$enquete_id = db_fetch1($sql);

// モニター選定用SQL生成
if (is_array($and_or2)) {
	foreach ($and_or2 as $qno => $and_or) {
		$tmp1 = '';
		$n = 0;
		if (is_array($sel_no[$qno])) {
			foreach ($sel_no[$qno] as $sel) {
				if (strchr($sel, ':')) {
					$ary = explode(':', $sel);
					$tmp2 = "ax_hyousoku_no={$ary[0]} AND ax_hyoutou_no={$ary[1]}";
					$tbl = 't_ans_matrix';
					$pre = 'ax';
				} else {
					$tmp2 = "as_sel_no=$sel";
					$tbl = 't_ans_select';
					$pre = 'as';
				}

				or_join($tmp1, $tmp2);
				$n++;
			}
		}

		if ($tmp1 != '') {
			if ($and_or == 'A')
				$tmp3 = "(SELECT COUNT(*) FROM $tbl WHERE {$pre}_enquete_id=$enquete_id AND {$pre}_monitor_id=pmn_monitor_id AND {$pre}_question_no=$qno AND ($tmp1))=$n";
			else
				$tmp3 = "EXISTS (SELECT * FROM $tbl WHERE {$pre}_enquete_id=$enquete_id AND {$pre}_monitor_id=pmn_monitor_id AND {$pre}_question_no=$qno AND ($tmp1))";
		}

		if ($and_or1 == 'A')
			and_join($tmp4, $tmp3);
		else
			or_join($tmp4, $tmp3);
	}
}
$where = "pmn_pjt_id=$pjt_id";
if ($tmp4 != '')
	and_join($where, $tmp4);

// 該当者人数検索処理
if ($next_action == 'search') {
	$sql = "SELECT COUNT(*) FROM t_mp_monitor WHERE $where";
	$count = db_fetch1($sql);
	redirect("require_search.php?n=$count");
}

db_begin_trans();

$rec['ppj_and_or'] = sql_char($and_or1);
$rec['ppj_select_sql'] = sql_char($where);
db_update('t_mp_pjt', $rec, "ppj_pjt_id=$pjt_id");

db_delete('t_mp_select', "psl_pjt_id=$pjt_id");

if (is_array($and_or2)) {
	foreach ($and_or2 as $qno => $and_or) {
		if ($sel_no[$qno]) {
			$rec['psl_pjt_id'] = sql_number($pjt_id);
			$rec['psl_question_no'] = sql_number($qno);
			$rec['psl_sel_no'] = sql_char(join(',', $sel_no[$qno]));
			$rec['psl_and_or'] = sql_char($and_or);
			db_insert('t_mp_select', $rec);
		}
	}
}

db_commit_trans();
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body>
<? page_header() ?>
<? menu_start() ?>

<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td align="center" class="m4"><?=$g_title?></td>
	</tr>
	<tr>
		<td align="center"><br>
			<p class="msg">該当者条件を設定しました。</p>
			<p><input type="button" value="　戻る　" onclick="location.href='r_job.php?pjt_id=<?=$pjt_id?>'"></p>
		</td>
	</tr>
</table>

<? menu_end() ?>
<? page_footer() ?>
</body>
</html>
