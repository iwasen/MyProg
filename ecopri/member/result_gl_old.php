<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:今日の実績入力（ガソリン）
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/result_input.php");
include("$inc/mypage.php");


//曜日取得
function get_week($date) {
	$week = array('<font color="red">日</font>', '月', '火', '水', '木', '金', '<font color="dodgerblue">土</font>');
	return $week[date('w', $date)];
}

// ゼロ→null
function zero_to_null($num) {
	if ($num == 0)
		return '';
	else
		return $num;
}

// commitフラグをチェックして表示を変える
function commit_check($column, $column_name, $flag, $size, $maxlength) {
	if ($flag)
		echo zero_to_null($column);
	else
		echo "<input type=\"text\" size=$size maxlength=$maxlength name=\"{$column_name}[]\" " . value(zero_to_null($column)) . ">";
}

// セッション処理
if (!$_SESSION['ss_result_input']) {
	$_SESSION['ss_result_input'] = new result_input;
	$reg = &$_SESSION['ss_result_input'];

} else
	$reg = &$_SESSION['ss_result_input'];

$reg->get_member_info();
$reg->link_check_d($ym);
$reg->read_db($_SESSION['ss_seq_no']);
$reg->get_last_day($reg->bd_y, $reg->bd_m);

$gl_use = explode(',', $reg->gl_use);
$gl_sum = explode(',', $reg->gl_sum);
$use_total = array_sum(explode(",",$reg->gl_use));
$sum_total = array_sum(explode(",",$reg->gl_sum));

?>
<html>
<head>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<form name="form1" method="post" action="result_gl_check.php">
<table width="714" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF"> <img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="top">
		<br>
			<table border="0" align="center">
				<tr align="center" valign="middle">
					<td rowspan="3" align="right" width=267>
<?
if ($reg->before_month != '') {
?>
						<a href="result_gl.php?ym=<?=$reg->before_month?>"><img src="img/button/sengetu.gif" border="0" align="middle" alt="先月" title="先月"></a>
<?
}
?>
					</td>
					<td align="center" align="center" width=180>
						<img src="img/year/2.gif" border="0" width="16" height="21">
						<img src="img/year/0.gif" border="0" width="16" height="21">
						<img src="img/year/0.gif" border="0" width="16" height="21">
						<img src="img/year/<?=substr($reg->bd_y,3,1)?>.gif" border="0" width="16" height="21">
						<img src="img/year/year.gif" border="0" width="21" height="21">
					</td>
					<td rowspan="3" align="left" width=267>
<?
if ($reg->next_month != '') {
?>
						<a href="result_gl.php?ym=<?=$reg->next_month?>"><img src="img/button/jigetu.gif" border="0" align="middle" alt="次月" title="次月"></a>
<?
}
?>
					</td>
				</tr>

				<tr>
					<td align="center"><img src="img/month/<?=sprintf("%02d", $reg->bd_m)?>.gif" border="0" width="63" height="28"></td>
				</tr>

				<tr>
					<td align="center">
					<img src="img/year/title.gif" border="0" width="143" height="28">
					</td>
				</tr>
			</table>
		</td>

		<td width="35" align="right" valign="top">
		<img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>
	<tr>
		<td><br></td>
		<td align="center" valign="top">
		<hr width="95%" size="1">
		<br>
			<table border="0" cellpadding="0" cellspacing="0" width=720 height="90%" bgcolor="#cc66ff">
				<tr>
					<td align="center" valign="top">
						<table border="0" cellspacing="0" cellpadding="0" width=720 bgcolor="#ffffff">
							<tr>
								<td width="128"><a href="result_gm.php"><img src="img/result_input/old/tab_gomi1.gif" border="0"></a></td>
								<td width="128"><a href="result_ol.php"><img src="img/result_input/old/tab_touyu1.gif" border="0"></a></td>
								<td width="128"><img src="img/result_input/old/tab_gasoline1.gif" border="0"></td>
								<td><img src="img/spacer.gif" height=10 width=10></td>
							</tr>
							<tr>
								<td colspan="4" height="10" bgcolor="#cc66ff"><img src="img/spacer.gif" height=10 width=10></td>
							</tr>
						</table>
						<table border="0" cellspacing="0" cellpadding="0" width="95%" bgcolor="#cc66ff">
							<tr>
								<td>
									<table border="0" cellspacing="0" cellpadding="0" width="700" bgcolor="ffffff">

										<tr>
											<td width=150><br></td>
											<td width="300">
												<table border="0" width=300>
													<tr>
														<!-- 月変更場所 -->
														<td align="center"><img src="img/result_input/old/gasoline.gif" border="0" width="210" height="50"></td>
													</tr>
												</table>

												<table border="1" bordercolordark="orange" bordercolorlight="orange" cellspacing="1" cellpadding="0">
													<tr bgcolor="gold">
														<td align="center" width=40>日</td>
														<td width=40 align="center">曜日</td>
														<td align="center" width=100>使用量</td>
														<td align="center" width=100>金額</td>
													</tr>
<?
for ($i = 1; $i <= $reg->last_day; $i++) {
	$date = mktime (0,0,0,$reg->bd_m, $i, $reg->bd_y);
?>
													<tr>
														<td align="right"><?=$i?>&nbsp;&nbsp;</td>
														<td align="center"><?=get_week($date)?></td>
														<td align="right"><?=commit_check($gl_use[$i - 1], 'gl_use', $reg->commit_flag, 6, 4)?> L&nbsp;&nbsp;</td>
														<td align="right"><?=commit_check($gl_sum[$i - 1], 'gl_sum', $reg->commit_flag, 8, 5)?> 円&nbsp;&nbsp;</td>
													</tr>
<?
}
?>
													<tr bgcolor="blanchedalmond">
														<td align="center" colspan=2>合計</td>
														<td align="right"><?=$use_total?> L&nbsp;&nbsp;</td>
														<td align="right"><?=$sum_total?> 円&nbsp;&nbsp;</td>
													</tr>

												</table><br>
											</td>
											<td>
												<table border="0" width=250>
													<tr><td height=42><br></td></tr>
												</table>
												<table border="0" cellpadding="0" cellspacing="0" width=250>
													<tr height=80>
<?
if (!$reg->commit_flag) {
?>
														<td align="left" valign="top"><input type="image" src="img/result_input/result_gasoline.gif" border="0" width="158" height="57"></td>
<?
}
?>
													</tr>
													<tr height=650>
														<td align="center" align="left" valign="middle"><img src="img/result_input/zou.gif" border="0" width="100" height="93"></td>
													</tr>
													<tr height=80>
<?
if (!$reg->commit_flag) {
?>
														<td align="left" valign="top"><input type="image" src="img/result_input/result_gasoline.gif" border="0" width="158" height="57"></td>
<?
}
?>
													</tr>
												</table>
<!--
											<td align="center" valign="middle" align="left" rowspan="3" height="70"><img src="img/result_input/zou.gif" border="0" width="100" height="93"></td>
-->
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td colspan="4" height="10" bgcolor="#cc66ff"><img src="img/spacer.gif" height=10 width=10></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		<hr width="95%" size="1"></td>
		<td><br></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom"> <img src="img/kado_3.gif" width="35" height="35"></td>

		<td align="center" valign="bottom">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center">
					<a href="main.php"><img src="img/button/main.gif" border="0" width="61" height="30"></a>
					</td>
				</tr>
				<tr>
					<td height="7">　</td>
				</tr>
			</table>
		</td>

		<td width="35" height="35" align="right" valign="bottom">
		<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
</form>
</div>
</body>
</html>