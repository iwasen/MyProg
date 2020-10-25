<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:ジョブ情報更新
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
include("$inc/search.php");

// メイン処理
set_global2('project', 'プロジェクト管理', 'ジョブ情報更新', BACK_TOP);

switch ($job_type) {
case '0';
	$parent_job_id = '';
	break;
case '1':
	$parent_job_id = $parent_job_id1;
	break;
case '2':
	$parent_job_id = $parent_job_id2;
}

// ジョブコピーおよび親子ジョブ
if ($parent_job && $parent_job_id0) {
	$cp_job_id = 'COALESCE(jb_cp_job_id,jb_job_id)';
	$cpy_job_id = $parent_job_id0;
} elseif ($cpy_job_id)
	$cp_job_id = 'null';

$ok = "";
switch ($next_action) {
case 'new':	// 新規登録
	db_begin_trans();

	if ($cpy_job_id == '') {
		$sql = "INSERT INTO t_job (jb_project_id,jb_job_type,jb_job_name,jb_parent_job_id,jb_mail_format,jb_category) VALUES (%s,%s,%s,%s,0,%s)";
	} else {
		$sql = "INSERT INTO t_job (jb_project_id,jb_job_type,jb_job_name,jb_parent_job_id,jb_get_profile,jb_get_sa,jb_get_fa,jb_send_timing,jb_send_timing_h,jb_send_timing_d,jb_teikei_qno,jb_teikei_andor,jb_teikei_ans,jb_ct_id,jb_mail_format,jb_subject,jb_from,jb_header,jb_body,jb_footer,jb_start_date,jb_end_date,jb_sql,jb_send_num,jb_bunkatsu_kankaku,jb_bunkatsu_kaisuu,jb_point_jushin,jb_virgin_mail,jb_html_pict,jb_virgin_day,jb_virgin_hour,jb_enq_web_url,jb_cp_job_id,jb_category)"
				. " SELECT %s,%s,%s,%s,jb_get_profile,jb_get_sa,jb_get_fa,jb_send_timing,jb_send_timing_h,jb_send_timing_d,jb_teikei_qno,jb_teikei_andor,jb_teikei_ans,jb_ct_id,jb_mail_format,jb_subject,jb_from,jb_header,jb_body,jb_footer,jb_start_date,jb_end_date,jb_sql,jb_send_num,jb_bunkatsu_kankaku,jb_bunkatsu_kaisuu,jb_point_jushin,jb_virgin_mail,jb_html_pict,jb_virgin_day,jb_virgin_hour,jb_enq_web_url,$cp_job_id,%s FROM t_job WHERE jb_job_id=$cpy_job_id";
	}
	$sql = sprintf($sql,
				sql_number($pjt_id),
				sql_number($job_type),
				sql_char($job_name),
				sql_number($parent_job_id),
				sql_number($category));
	if (!db_exec($sql))
		$ok = "ng1";

	$job_id = get_currval('t_job_jb_job_id_seq');
	$i = 0;
	while($sn_mail_adr[$i]) {
		$sql = sprintf("INSERT INTO t_send_notice (sn_job_id,sn_seq_no,sn_mail_adr) VALUES (%s,%s,%s)",
			sql_number($job_id),
			sql_number($i + 1),
			sql_char($sn_mail_adr[$i]));
		if (!db_exec($sql))
			$ok = "ng2";
		$i++;
	}

	if ($cpy_job_id) {
		// アンケートと対象条件のコピー
		$enquete = new enquete_class;
		$search = new search_class;

		$sql = "SELECT jb_enquete_id,jb_search_id FROM t_job WHERE jb_job_id=$cpy_job_id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);

			if ($fetch->jb_enquete_id) {
				$enquete->read_db($fetch->jb_enquete_id);
				$enquete->status = 0;
				$enquete->enq_type = $enq_type;
				$enquete->job_id = $job_id;
				if ($enq_type == 0)
					$enquete->end_date_y = '';
				$enquete_id = $enquete->write_db();
			}

			if ($fetch->jb_search_id) {
				$search->read_db($fetch->jb_search_id);
				$search_id = $search->write_db();
			}
		}

		// クリックカウンタのコピー
		$sql = "SELECT * FROM t_click_counter WHERE cc_job_id=$cpy_job_id ORDER BY cc_ct_id";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		if ($nrow) {
			$sql = "SELECT jb_body FROM t_job WHERE jb_job_id=$job_id";
			$body = db_fetch1($sql);
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_object($result, $i);
				$sql = sprintf("INSERT INTO t_click_counter (cc_job_id,cc_url_name,cc_jump_url,cc_start_date,cc_end_date) VALUES (%s,%s,%s,%s,%s)",
						sql_number($job_id),
						sql_char($fetch->cc_url_name),
						sql_char($fetch->cc_jump_url),
						sql_date($fetch->cc_start_date),
						sql_date($fetch->cc_end_date));
				db_exec($sql);
				$ct_id = get_currval('t_click_counter_cc_ct_id_seq');

				$body = str_replace("?$fetch->cc_ct_id-%CLICK%", "?$ct_id-%CLICK%", $body);
			}
			$sql = "UPDATE t_job SET jb_body=" . sql_char($body) . " WHERE jb_job_id=$job_id";
			db_exec($sql);
		}
	} else {
		$sql = sprintf("INSERT INTO t_enquete (en_enq_kind,en_enq_type,en_job_id) VALUES (3,%s,%s)",
				sql_number($enq_type),
				sql_number($job_id));
		if (db_exec($sql))
			$enquete_id = get_currval('t_enquete_en_enquete_id_seq');
	}

	$sql = sprintf("UPDATE t_job SET jb_enquete_id=%s,jb_search_id=%s WHERE jb_job_id=$job_id",
			sql_number($enquete_id),
			sql_number($search_id));
	if (!db_exec($sql))
		$ok = "ng3";

	if ($ok == "") {
		db_commit_trans();
		$msg = "ジョブ情報を登録しました。<br><br><font color='black' size=-1>（プロジェクト一覧の「ジョブ名」をクリックして、ジョブの詳細を設定して下さい。）</font>";
		$back = "location.href='list.php'";
	} else {
		db_rollback();
		$msg = '処理中にエラーが発生しました。';
		$back = 'history.back()';
	}
	$title = "ジョブ情報 更新";
	break;

case 'update':	// 更新
	db_begin_trans();

	$sql = "UPDATE t_job SET jb_job_type=%s,jb_job_name=%s,jb_parent_job_id=%s,jb_mail_format=%s WHERE jb_job_id=$job_id";
	$sql = sprintf($sql,
		sql_number($job_type),
		sql_char($job_name),
		sql_number($parent_job_id),
		sql_number('jb_mail_format'));
	if (!db_exec($sql))
		$ok = "ng1";

	$sql = "DELETE FROM t_send_notice WHERE sn_job_id=$job_id";
	db_exec($sql);
	$i = 0;
	while($sn_mail_adr[$i]) {
		$sql = sprintf("INSERT INTO t_send_notice (sn_job_id,sn_seq_no,sn_mail_adr) VALUES (%s,%s,%s)",
			sql_number($job_id),
			sql_number($i + 1),
			sql_char($sn_mail_adr[$i]));
		if (!db_exec($sql))
			$ok = "ng2";
		$i++;
	}

	if ($ok == "") {
		db_commit_trans();
		$msg = 'ジョブ情報を更新しました。';
		$back = "location.href='list.php'";
	} else {
		db_rollback();
		$msg = '処理中にエラーが発生しました。';
		$back = 'history.back()';
	}
	$title = "ジョブ情報 更新";
	break;

case 'delete':	// 削除
	db_begin_trans();
	$sql = "SELECT jb_enquete_id,jb_search_id FROM t_job WHERE jb_job_id=$job_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		if ($fetch->jb_enquete_id) {
			$sql= "DELETE FROM t_enquete WHERE en_enquete_id=$fetch->jb_enquete_id";
			db_exec($sql);
		}

		if ($fetch->jb_search_id) {
			$sql= "DELETE FROM t_search WHERE sr_search_id=$fetch->jb_search_id";
			db_exec($sql);
		}
	}
	$sql = "DELETE FROM t_send_notice WHERE sn_job_id=$job_id";
	db_exec($sql);
	$sql = "DELETE FROM t_job WHERE jb_job_id=$job_id";
	db_exec($sql);
	db_commit_trans();
	$title = "ジョブ情報　更新";
	$msg = 'ジョブ情報を削除しました。';
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
<? menu_script() ?>
</head>
<body onLoad="document.all.ok.focus()">

<? page_header() ?>
<? menu_start() ?>

<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td align="center" class="m4"><?=$title?></td>
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
