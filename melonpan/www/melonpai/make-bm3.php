<?php

/*======================================================================================*/
/*	(main)										*/
/*======================================================================================*/
//include ("../sub/head.inc");

$p_titl = "ブックマークの追加";

mex_passchk($conn, $melonpai_id, $melonpai_pswd);

$chk_num = count($check_box);
$err_cnt = 0;
$sac_cnt = 0;
$wor_cnt = 0;
$job_err_msg = "";
$job_sac_msg = "";
$job_wor_msg = "";
for ($i = 0; $i<$chk_num; $i++) {
	$mag_id = $magid_list[$check_box[$i]];
	$category_id = $categ_select[$check_box[$i]];
	$publisher_id = $pub_list[$check_box[$i]];
	$mag_nm = $mag_nm_list[$check_box[$i]];

	if ($category_id == 0) {
		$job_err_msg .= "　".$mag_id . "：" . stripcslashes($mag_nm) . "（カテゴリーを選択してください）<br>";
		$err_cnt++;
		$read_flg = FALSE;
	} else {
		$sql = "SELECT * FROM T_BOOKMARK WHERE";
		$sql .= " melonpai_id = " . $melonpai_id;
		$sql .= " and category_id = " . $category_id;
		$sql .= " and mag_id = '" . $mag_id . "'";
		if (!$result = db_exec($sql)) {
			$job_err_msg .= $mag_id . "：" . $mag_nm . "<br>";
			$err_cnt++;
			$read_flg = FALSE;
		} else {
			if (pg_numrows($result)) {
				$job_wor_msg .= "　".$mag_id . "：" . stripcslashes($mag_nm) . "は、登録されています。<br>";
				$wor_cnt++;
				$read_flg = FALSE;
			} else {
				$read_flg = TRUE;
			}
		}
	}
	if ($read_flg) {
		db_begin_trans();
		if (($bm_id = update_squ_key ("T_BOOKMARK")) != "-1") {
			db_commit_trans();
			$read_flg = TRUE;
		} else {
			db_rollback();
			$job_err_msg .= $w_mag_id . "<br>";
			$err_cnt++;
			$read_flg = FALSE;
		}
	}
	if ($read_flg) {
		$sql = "INSERT INTO T_BOOKMARK ( ";
		$sql .= " bm_id, melonpai_id, category_id, mag_id, publisher_id, modify_dt)";
		$sql .= " VALUES ( ";
		$sql .= " $bm_id, $melonpai_id, $category_id, '$mag_id', $publisher_id, 'now')";

		if (!db_exec($sql)) {
			$job_err_msg .= $mag_id . "：" . stripcslashes($mag_nm) . "<br>";
			$err_cnt++;
		} else {
			$job_sac_msg .= "　".$mag_id . "：" . stripcslashes($mag_nm) . "<br>";
			$sac_cnt++;
		}
	}
}
$kekka_msg = "<b>&lt;■追加指示： " . $chk_num . "件&gt;</b><br>";
if ($err_cnt > 0) {
	$last_msg .= "<br>　■追加失敗： " . $err_cnt . "件<br>" . $job_err_msg;
}
if ($wor_cnt > 0) {
	$last_msg .= "<br>　■重複登録： " . $wor_cnt . "件<br>" . $job_wor_msg;
}
if ($sac_cnt > 0) {
	$last_msg .= "<br>　■正常終了： " . $sac_cnt . "件<br>" . $job_sac_msg;
}
if ($chk_num == 0) {
	$last_msg = "追加するメルマガが選択されていません。チェックボックスにチェックを入れ忘れていませんか？";
}
include("tmpl_html/make-bm3.html");

?>
