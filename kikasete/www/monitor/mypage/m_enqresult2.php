<?
$top = './..';
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");
include("$inc/mn_error.php");

if ($seq_no == '')
	redirect('m_index.php');

if ($episode == '')
	$msg[] = 'エピソードが入力されていないようです。';

if ($handle_name == '')
	$msg[] = 'ハンドルネームが入力されていないようです。';

if (!$msg) {
	$monitor_id = $_SESSION['ss_monitor_id'];
	$sql = "SELECT * FROM t_episode WHERE ep_seq_no=$seq_no AND ep_monitor_id=$monitor_id";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		// エピソード保存
		$sql = sprintf("INSERT INTO t_episode (ep_seq_no,ep_monitor_id,ep_episode,ep_handle_name) VALUES (%s,%s,%s,%s)",
				sql_number($seq_no),
				sql_number($monitor_id),
				sql_char($episode),
				sql_char($handle_name));
		db_exec($sql);

		// エピソード件数更新
		$sql = "SELECT * FROM t_episode_sum WHERE eu_seq_no=$seq_no";
		$result = db_exec($sql);
		if (pg_numrows($result) == 0)
			$sql = "INSERT INTO t_episode_sum (eu_seq_no,eu_sum) VALUES ($seq_no,1)";
		else
			$sql = "UPDATE t_episode_sum SET eu_sum=eu_sum+1 WHERE eu_seq_no=$seq_no";
		db_exec($sql);
	}
}
?>
<? monitor_menu() ?>
<?
if (!$msg) {
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="left" background="<?=$img?>/title_bg.gif"><img src="<?=$img?>/title_enq.gif" width="320" height="39" alt="今週のきかせて１万人アンケート"></td>
	</tr>
	<TR>
		<TD background="<?=$img?>/title_catch_03.gif"><IMG SRC="<?=$img?>/spacer.gif" WIDTH=1 HEIGHT=2></TD>
	</TR>
	<TR><TD>　</TD></TR>
	<tr>
		<td><div align="center"><font color="#2b2b63"><b>「１万人アンケート」へのエピソード、受け付けました。</b></font><font size="2"><br></font><font size="2"><br>エピソードを送っていただき、ありがとうございました！<br>次回もぜひ「きかせて！１万人アンケート」に参加してくださいね。<br><br></font><p><hr width="100%"></div></td>
	</tr>
</table>
<?
} else
	error_msg($msg, "location.href='m_enqresult.php?seq_no=$seq_no&enquete_id=$enquete_id&retry=1&episode=" . urlencode($episode) . "&handle_name=" . urlencode($handle_name) . "'");
?>