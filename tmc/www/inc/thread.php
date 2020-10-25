<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:スレッド表示関数
'******************************************************/

// スレッド表示
function output_thread($comm_id, $depth = ' ') {
	if (strlen($depth) > MAX_THREAD_DEPTH)
		return;

	$sql = "SELECT cml_comm_id,COALESCE(cml_user_id,0) AS user_id,cml_date,cml_send_recv,cml_answer_flag,cmc_subject,cmc_comm_type"
			. " FROM t_comm_log"
			. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
			. " WHERE cml_comm_id=$comm_id";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);

		echo '<font color="gray">', $depth, '</font>';
		echo '<a class="thread" href="javascript:onclick_thread(', $comm_id, ',', $fetch->user_id, ')" title="メールの内容を表示します" onfocus="blur()" id="t', $comm_id, '">';
		if ($fetch->cml_answer_flag == DBTRUE)
			echo '<font color="red">【未回答】</font>';
		echo format_datetime($fetch->cml_date), ' ';
		echo '<font color="', $fetch->cml_send_recv == 'R' ? "brown" : "green", '">', decode_comm_type2($fetch->cmc_comm_type), decode_send_recv($fetch->cml_send_recv), '</font>';
		echo ' <font color="#555555">[', $fetch->cml_comm_id, ']</font>';
		echo ' <font color="#333388"><b>', htmlspecialchars($fetch->cmc_subject), "</b></font></a>\n";
	} else
		return;

	$depth = str_replace('├', '│', $depth);
	$depth = str_replace('└', '　', $depth);

	$sql = "SELECT cml_comm_id FROM t_comm_log WHERE cml_parent_comm_id=$comm_id ORDER BY cml_comm_id";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		output_thread($fetch->cml_comm_id, $depth . (($i == $nrow - 1) ? '└ ' : '├ '));
	}
}
function thread_script($url, $contents) {
?>
<script type="text/javascript">
<!--
function thread_sel(comm_id) {
	var f = document.form1;
	if (f.sel_id.value)
		document.all["t" + f.sel_id.value].style.backgroundColor = "";
	f.sel_id.value = comm_id;
	if (f.sel_id.value)
		document.all["t" + f.sel_id.value].style.backgroundColor = "#d0e0ff";
}
function onclick_thread(comm_id, user_id) {
	setTimeout("disp_thread(" + comm_id + "," + user_id + ")", 0);
}
function disp_thread(comm_id, user_id) {
	thread_sel(comm_id);
	if (user_id)
		disp_user_info(user_id);
<? if ($contents) { ?>
	parent.contents.location.href = "contents.php?comm_id=" + comm_id + "&url=<?=$url?>";
<? } else { ?>
	location.href = "show.php?comm_id=" + comm_id + "&url=<?=$url?>";
<? } ?>
}
//-->
</script>
<?
}
?>