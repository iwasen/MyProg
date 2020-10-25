<?
function decode_mail_format($code) {
	switch ($code) {
	case 0:
		return 'TEXT';
	case 1:
		return 'HTML';
	}
	return '指定なし';
}

function decode_job_type($code) {
	switch ($code) {
	case 0:
		return '発信ジョブ';
	case 1:
		return 'アンケートレスポンスジョブ';
	case 2:
		return 'クリックレスポンスジョブ';
	}
	return '指定なし';
}

function show_job_name($data) {
	if ($data) {
		$sql = "SELECT jb_job_name FROM t_job WHERE jb_job_id=$data";
		$job_name = db_fetch1($sql);
		echo "$data,$job_name";
	}
}

function show_and_or($data) {
	switch ($data) {
	case 'A':
		echo 'AND';
		break;
	case 'O':
		echo 'OR';
		break;
	default:
		echo '指定なし';
		break;
	}
}

function show_virgin_mail($data) {
	switch ($data) {
	case 0:
		echo '既存会員のみ';
		break;
	Case 1:
		echo '新規会員のみ';
		break;
	Case 2:
		echo '既存会員と新規会員';
		break;
	default:
		echo '指定なし';
		break;
	}
}

function show_send_timing($timing, $timing_h, $timing_d) {
	switch ($timing) {
	case 1:
		echo '受信後、発信開始日から一斉に。あとはその都度、個別送信';
		break;
	case 2:
		echo "受信時間から $timing_h 時間後に個別送信";
		break;
	case 3:
		echo "受信日から $timing_d 日後に個別送信";
		break;
	default:
		echo '指定なし';
		break;
	}
}

// 親／子表示
function show_parent_child($job_id, $parent_job_id) {
	$sql = "SELECT jb_job_id FROM t_job WHERE jb_parent_job_id=$job_id";
	$result = db_exec($sql);
	if (pg_numrows($result))
		$str[] = 'FD';

	if ($parent_job_id)
		$str[] = '本';

	if ($str)
		echo '<font color="red" size="-1">（' . join('／', $str) . '）</font>';
}

// 定型処理表示
function show_teikei_shori($job_id) {
	$sql = "SELECT tk_teikei_qno,tk_teikei_andor,tk_teikei_ans FROM t_teikei WHERE tk_job_id=$job_id ORDER BY tk_teikei_qno";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo "質問番号：{$fetch->tk_teikei_qno}　条件：" . decode_andor($fetch->tk_teikei_andor) . "　選択番号：$fetch->tk_teikei_ans<br>";
	}
}

function show_monitor_type($data) {
	echo decode_monitor_type($data, '指定なし');
}

function show_sex($data) {
	echo decode_sex($data, '指定なし');
}

function show_age($from, $to) {
	if ($from != '' || $to != '') {
		if ($from != '')
			echo "${from}才";

			echo '〜';

		if ($to != '')
			echo "${to}才";
	} else
		echo '指定なし';
}

function show_mikikon($data) {
	echo decode_mikikon($data, '指定なし');
}

function show_name($sql) {
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_row($result, $i);
		echo "$fetch[0]　";
	}
}

function show_shokugyou($data) {
	if ($data != '') {
		$sql = "SELECT sg_shokugyou_name FROM m_shokugyou WHERE sg_shokugyou_cd IN ($data) ORDER BY sg_order";
		show_name($sql);
	} else
		echo '指定なし';
}

function show_gyoushu($data) {
	if ($data != '') {
		$sql = "SELECT gs_gyoushu_name FROM m_gyoushu WHERE gs_gyoushu_cd IN ($data) ORDER BY gs_order";
		show_name($sql);
	} else
		echo '指定なし';
}

function show_shokushu($data) {
	if ($data != '') {
		$sql = "SELECT ss_shokushu_name FROM m_shokushu WHERE ss_shokushu_cd IN ($data) ORDER BY ss_order";
		show_name($sql);
	} else
		echo '指定なし';
}

function show_area($data) {
	if ($data != '')
		echo str_replace(',', ' ', str_replace('/', '', $data));
	else
		echo '指定なし';
}

function show_genre($data) {
	if ($data != '') {
		$sql = "SELECT kg_name FROM m_genre WHERE kg_genre_cd IN ($data) ORDER BY kg_order";
		show_name($sql);
	} else
		echo '指定なし';
}

function show_conveni($data) {
	if ($data != '') {
		$sql = "SELECT cv_name FROM m_conveni WHERE cv_conveni_cd IN ($data) ORDER BY cv_order";
		show_name($sql);
	} else
		echo '指定なし';
}

function show_super($data) {
	if ($data != '') {
		$sql = "SELECT sp_name FROM m_super WHERE sp_super_cd IN ($data) ORDER BY sp_order";
		show_name($sql);
	} else
		echo '指定なし';
}

function show_family_sex($man, $woman) {
	if ($man == 't' || $woman == 't') {
		if ($man == 't')
			echo '男性　';
		if ($woman == 't')
			echo '女性';
	} else
		echo '指定なし';
}

function show_family_rel($data) {
	if ($data != '') {
		$sql = "SELECT fr_name FROM m_family_rel WHERE fr_family_rel_cd IN ($data) ORDER BY fr_family_rel_cd";
		show_name($sql);
	} else
		echo '指定なし';
}

function show_child($data) {
	if ($data == 't')
		echo 'あり';
	elseif ($data == 'f')
		echo 'なし';
	else
		echo '指定なし';
}

function show_condition($data) {
	if ($data != '')
		echo $data;
	else
		echo '指定なし';
}

function show_spouse_flg($data) {
	echo decode_spouse_flg($data, '指定なし');
}

function show_have_child($data) {
	echo decode_have_child($data, '指定なし');
}

function show_child_sex($data) {
	echo decode_child_sex($data, '指定なし');
}

function show_child_gakunen($data) {
	echo decode_child_gakunen($data, '指定なし');
}

function show_senior_flg($data) {
	echo decode_senior_flg($data, '指定なし');
}

function show_housing_form($data) {
	echo decode_housing_form($data, '指定なし');
}

function show_have_car($data) {
	echo decode_have_car($data, '指定なし');
}

function show_ma_profile($data) {
	if (is_array($data)) {
		$sql = "SELECT pfs_select_text FROM t_profile_sel WHERE pfs_profile_cd IN (" . join(',', $data) . ") ORDER BY pfs_order";
		show_name($sql);
	} else
		echo '指定なし';
}
?>