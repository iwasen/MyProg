<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:ジョブ内容登録
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/enquete.php");
include("$inc/enq_check.php");

//メイン処理
set_global2('project', 'プロジェクト管理', 'ジョブ内容登録', BACK_TOP);

switch ($job_type) {
case '1':
	$parent_job_id = $parent_job_id1;
	break;
case '2':
	$parent_job_id = $parent_job_id2;
	if ($send_timing_num <= 0 || $send_timing == '') {
		$send_timing = 0;
	} elseif ($send_timing == 'hour') {
		$send_timing = 1;
		$send_timing_h = $send_timing_num;
	} elseif ($send_timing == 'day') {
		$send_timing = 2;
		$send_timing_d = $send_timing_num;
	}
	break;
}

$ok = '';
$back1 = "<input type='button' value='　戻る　' onclick='location.href=\"jb_contents.php?job_id=$job_id\"'>";
$back2 = "<input type='button' id='ok' value='　戻る　' onclick='history.back()'>";

// 親子ジョブ更新対象
if ($cpy_child)
	$sub1 = "SELECT jb_job_id FROM t_job JOIN t_enquete ON jb_enquete_id=en_enquete_id WHERE jb_job_id=$job_id OR jb_cp_job_id=$job_id AND en_status<>5 AND en_status<>7";
else
	$sub1 = "SELECT jb_job_id FROM t_job WHERE jb_job_id=$job_id";

switch ($next_action) {
// **** アンケート概要 ****
case 'summary':
	db_begin_trans();
	if ($bunkatsu)
		$set = ",jb_bunkatsu_kankaku=$bunkatsu_kankaku,jb_bunkatsu_kaisuu=$bunkatsu_kaisuu";
	else
		$set = ",jb_bunkatsu_kankaku=null,jb_bunkatsu_kaisuu=null";

	// 発信タイミング
	switch($jb_send_timing) {
	case 1:
		$jb_send_timing_d = '';
		break;
	case 2:
		$jb_send_timing_h = '';
		break;
	default:
		$jb_send_timing = '';
		$jb_send_timing_h = '';
		$jb_send_timing_d = '';
	}

	$sql = sprintf("UPDATE t_job SET jb_send_timing=%s,jb_send_timing_h=%s,jb_send_timing_d=%s,jb_send_num=%s,jb_enq_web_url=%s $set WHERE jb_job_id IN ($sub1)",
			sql_number($jb_send_timing),
			sql_number($jb_send_timing_h),
			sql_number($jb_send_timing_d),
			"CASE WHEN jb_job_id=$job_id THEN $send_num ELSE jb_send_num END",
			sql_char($enq_web_url));
	if (db_exec($sql)) {
		$sql = "UPDATE t_enquete SET en_title=%s,en_start_date=%s,en_end_date=%s,en_point=%s WHERE en_job_id IN ($sub1)";
		$sql = sprintf($sql,
			sql_char($title),
			sql_date2($en_start_year,$en_start_month,$en_start_day,$en_start_hour),
			sql_date2($en_end_year,$en_end_month,$en_end_day,$en_end_hour),
			sql_number($en_point));
		if (db_exec($sql)) {
			$ok = 'ok';
		}
	}
	if ($ok) {
		db_commit_trans();
		$msg = 'アンケート概要を登録しました。';
		$back = $back1;

	} else {
		db_rollback();
		$msg = '処理中にエラーが発生しました。';
		$back = $back2;
	}
	$title = 'ジョブ情報 更新';
	break;

// **** 対象条件 ****
case 'condition':
	switch($job_type) {
	case 1: //アンケートレスポンス
		$ct_id = '';
		break;
	case 2: //クリックレスポンス
		$teikei_qno = '';
		$teikei_andor = '';
		$teikei_ans = '';
		break;
	default:
		$ct_id = '';
		$teikei_qno = '';
		$teikei_andor = '';
		$teikei_ans = '';
	}

	db_begin_trans();

	//定型処理番号データ生成
	$sql = "DELETE FROM t_teikei WHERE tk_job_id=$job_id";
	db_exec($sql);
	if (is_array($teikei_qno)) {
		foreach ($teikei_qno as $i => $qno) {
			$sql = sprintf("INSERT INTO t_teikei (tk_job_id,tk_teikei_qno,tk_teikei_andor,tk_teikei_ans) VALUES (%s,%s,%s,%s)",
					sql_number($job_id),
					sql_number($qno),
					sql_char($teikei_andor[$i]),
					sql_char(get_multi_data($teikei_ans[$i])));
			db_exec($sql);
		}
	}

	//クリックカウンタデータ生成
	$ct_id = get_multi_data($cc_ct_id);

	$search = new search_class;
	$search->get_form();
	if (!$search_id) {
		$search_id = $search->write_db();
	} else
		$search->write_db($search_id);

	$sql = "UPDATE t_job SET jb_search_id=%s,jb_send_num=%s,jb_ct_id=%s, jb_virgin_mail=%s,jb_virgin_day=%s,jb_virgin_hour=%s WHERE jb_job_id=$job_id";
	$sql = sprintf($sql,
		sql_number($search_id),
		sql_number($send_num),
		sql_char($ct_id),
		sql_number($virgin_mail),
		sql_number($virgin_day),
		sql_number($virgin_hour));
	db_exec($sql);

	db_commit_trans();

	$title = '対象条件 更新';
	$msg = '対象条件を登録しました。';
	$back = $back1;
	break;

// **** メールコンテンツ ****
case 'mail':
	if ($enq_type == 1) {
		$question = enq_check($jb_body);
		if ($enq_err)
			break;
	}

	db_begin_trans();

	$sql = "SELECT jb_job_id,jb_enquete_id FROM t_job WHERE jb_job_id IN ($sub1) ORDER BY jb_job_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$job_id2 = $fetch->jb_job_id;
		$enquete_id = $fetch->jb_enquete_id;

		if ($mail_format == 1) {
			$jb_subject = $html_subject;
			$jb_body = $html_body;
			$jb_header = '';
			$jb_footer = '';
		}

		$body = $jb_body;

		// クリックカウンタのコピー
		if ($job_id != $job_id2) {
			$sql = "SELECT * FROM t_click_counter WHERE cc_job_id=$job_id ORDER BY cc_ct_id";
			$result2 = db_exec($sql);
			$nrow2 = pg_numrows($result2);
			if ($nrow2) {
				for ($j = 0; $j < $nrow2; $j++) {
					$fetch2 = pg_fetch_object($result2, $j);

					$sql = sprintf("SELECT cc_ct_id FROM t_click_counter WHERE cc_job_id=$job_id2 AND cc_url_name=%s AND cc_jump_url=%s",
							sql_char($fetch2->cc_url_name),
							sql_char($fetch2->cc_jump_url));
					$ct_id2 = db_fetch1($sql);
					if ($ct_id2)
						$body = str_replace("?$fetch2->cc_ct_id-%CLICK%", "?$ct_id2-%CLICK%", $body);
				}
			}
		}

		// アンケート内容
		if ($enq_type == 1) {
			$enquete = new enquete_class;

			if ($enquete_id) {
				$enquete->read_db($enquete_id);
				$enquete->question = $question;
				$enquete->write_db($enquete_id);
			}

			$body = str_replace('%QUESTION%', $enquete->get_question_num(), $body);
		}

		$sql = sprintf("UPDATE t_job SET jb_subject=%s,jb_header=%s,jb_body=%s,jb_footer=%s,jb_start_date=%s,jb_end_date=%s,jb_point_jushin=%s,jb_mail_format=%s WHERE jb_job_id=$job_id2",
				sql_char($jb_subject),
				sql_char($jb_header),
				sql_char($body),
				sql_char($jb_footer),
				sql_date2($jb_start_year, $jb_start_month, $jb_start_day, $jb_start_hour),
				sql_date2($jb_end_year, $jb_end_month, $jb_end_day, $jb_end_hour),
				sql_number($point_jushin),
				sql_number($mail_format));
		db_exec($sql);

		if ($enq_type == 0) {
			$sql = sprintf("UPDATE t_enquete SET en_start_date=%s,en_end_date=%s WHERE en_job_id=$job_id2",
					sql_date2($jb_start_year, $jb_start_month, $jb_start_day, $jb_start_hour),
					sql_date2($jb_end_year, $jb_end_month, $jb_end_day, $jb_end_hour));
			db_exec($sql);
		}
	}

	if ($ok == '') {
		db_commit_trans();
		$msg = 'メール内容を登録しました。';
		$back = "<input type='button' id='ok' value='　戻る　' onclick='location.href=\"jb_contents.php?job_id=$job_id\"'>";
	} else {
		db_rollback();
		$msg = '処理中にエラーが発生しました。';
		$back = $back2;
	}
	$title = 'メール内容 登録';
	break;
case 'hai_html':
	$sql = "SELECT * FROM m_enq_template WHERE et_template_id='hai_html'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$html_subject = $fetch->et_header;
		$html_body = $fetch->et_body;
	}
	/* no break */
case 'image':
case 'css':
case 'test_mail':
	$sql = sprintf("UPDATE t_job SET jb_subject=%s,jb_body=%s,jb_start_date=%s,jb_end_date=%s,jb_point_jushin=%s,jb_mail_format=%s WHERE jb_job_id=$job_id",
			sql_char($html_subject),
			sql_char($html_body),
			sql_date2($jb_start_year, $jb_start_month, $jb_start_day, $jb_start_hour),
			sql_date2($jb_end_year, $jb_end_month, $jb_end_day, $jb_end_hour),
			sql_number($point_jushin),
			sql_number($mail_format));
	db_exec($sql);

	if ($next_action == 'image')
		redirect("upload_image.php?job_id=$job_id");
	elseif ($next_action == 'css')
		redirect("upload_css.php?job_id=$job_id");
	elseif ($next_action == 'test_mail')
		redirect("test_mail.php?job_id=$job_id");
	elseif ($next_action == 'hai_html')
		redirect("enq_mail.php?job_id=$job_id");
	break;
default:
	redirect('../list.php');
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
<body>

<? page_header() ?>
<? menu_start() ?>

<?
if ($enq_err) {
?>
<table border=0 cellspacing=1 cellpadding=0>
	<tr>
		<td class="m0">■アンケート内容にエラーがあります</td>
	</tr>
	<tr>
		<td align="center">
			<table border=1 cellspacing=0 cellpadding=3 bordercolor="navy" width="100%">
				<tr>
					<td>
						<tt>
<?
$ary = explode("\n", $jb_body);
foreach ($ary as $lno => $line) {
	echo htmlspecialchars($line), "<br>\n";
	if (isset($enq_err[$lno])) {
		foreach ($enq_err[$lno] as $msg)
			echo "<font color='red'>>Error: $msg</font><br>\n";
	}
}
?>
						</tt>
					</td>
				</tr>
			</table>
			<br>
			<input type="button" value="　戻る　" onclick="history.back()">
		</td>
	</tr>
</table>
<?
} else {
?>
<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td align="center" class="m4"><?=$title?></td>
	</tr>
	<tr>
		<td align="center"><br><br>
			<p class="msg"><?=$msg?></p>
			<P><?=$back?></p>
		</td>
	</tr>
</table>
<?
}
?>
<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
