<?
/******************************************************
' System :ICP会議室ページ
' Content:スレッド表示関数
'******************************************************/

define('MAX_THREAD_DEPTH', 100);

// スレッド表示
function output_thread($job_id, $disp_type, $disp_member_id, $offset, $limit, $link = true, $remark_id = 0, $parent_remark_id = 0, $count = 0, $depth = 0) {
	global $marking_ary;

	if ($depth > MAX_THREAD_DEPTH)
		return;

	$and = $disp_member_id ? " AND (rm_disp_member_id=$disp_member_id OR rm_disp_member_id=0)" : '';

	$sql = sprintf(
				"SELECT rm_remark_id,rm_seq_no,rm_date,rm_subject,rm_remark_type,rm_member_id,rm_child_flag,rm_marking_id"
			. " FROM l_remark"
			. " WHERE rm_job_id=%s AND %s=%s AND rm_status=1 AND rm_disp_type=%s" . $and
			. " ORDER BY rm_remark_id",
			sql_number($job_id),
			$remark_id != 0 ? 'rm_remark_id' : 'rm_parent_remark_id',
			sql_number($remark_id != 0 ? $remark_id : $parent_remark_id),
			sql_number($disp_type));
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		if ($count >= $offset) {
			// ニックネーム取得（開始メールの場合は差出人名を取得）
			$nickname = get_nickname($fetch->rm_remark_type, $job_id, $fetch->rm_member_id);

			echo str_repeat('　', $depth);

			if ($fetch->rm_marking_id != '')
				echo '<span style="background-color:', $marking_ary[$fetch->rm_marking_id], '">';

			if ($link)
				echo '<a href="contents_top.php?id=', encrypt_id($fetch->rm_remark_id), '" target="contents" title="この投稿を表示">';

			echo $depth ? '└' : '', '[', $fetch->rm_seq_no, '] ', htmlspecialchars($fetch->rm_subject), '　', htmlspecialchars($nickname), '　', format_datetime($fetch->rm_date);

			if ($link)
				echo '</a>';

			if ($fetch->rm_marking_id != '')
				echo '</span>';

			echo "\n";
		}

		$count++;

		if ($fetch->rm_child_flag == DBTRUE)
			$count = output_thread($job_id, $disp_type, $disp_member_id, $offset, $limit, $link, 0, $fetch->rm_remark_id, $count, $depth + 1);

		if ($limit != 0 && $count >= $offset + $limit)
			break;
	}

	return $count;
}
?>