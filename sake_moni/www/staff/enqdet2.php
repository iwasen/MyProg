<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");

$staff_id = $_SESSION['ss_staff_id'];

if ($my_enq_no) {
	$sql = "SELECT me_enquete_id,me_send_num,me_real_send FROM t_my_enquete WHERE me_staff_id=$staff_id AND me_my_enq_no=$my_enq_no";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$send_num = $fetch->me_send_num;
		$real_send = $fetch->me_real_send;
		$enquete_id = $fetch->me_enquete_id;
	} else
		redirect('enq.php');
}

$sql = "SELECT en_enq_type,en_title,en_start_date,en_end_date,en_point FROM t_enquete WHERE en_enquete_id=$enquete_id";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);
} else
	redirect('enq.php');
?>
<? staff_header('プロジェクト詳細表示') ?>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="10"><img src="images/shim.gif" width="10" border="0"></td>
		<td width="400" valign="top"><img src="images/t_mkk_enqdet1.gif" width="400" height="20" border="0"><br><br></td>
	</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td rowspan="2" width="20"><img src="images/shim.gif" width="20" height="1" border="0"></td>
		<td width="700"><img src="images/shim.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td>
			<table width="580" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						<font size="3" class="honbun1">
						<b>ほにゃら</b> アンケート<!--プロジェクトの場合は、「●●アンケートジョブの詳細です。」と表示-->の詳細です。
						</font>
					</td>
				</tr>
			</table>
			<br><br>
			<table width="600" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>プロジェクトＩＤ</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">417</font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>プロジェクト名</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">ほにゃら</font>
					</td>
				</tr>
				<!--ＪＯＢＩＤ＆ＪＯＢ名については、プロジェクトの場合のみ表示-->
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>ジョブＩＤ</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">10125</font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>ジョブ名</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">ほにゃら</font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>種類</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">Ｗｅｂアンケート</font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>ポイント</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">10</font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>開始日</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">2001年08月07日</font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>終了日</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">2001年08月10日10時</font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>発信数</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">300</font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>送信数</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">
						300
						<a href="enq_smail.php">（送信者プロファイル）</a>
						</font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>受信数</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">
						100
						<a href="enq_rmail.php">（受信者プロファイル）</a>
						</font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>メールコンテンツ</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">
						<a href="enq_mail.php">（メールコンテンツ）</a>
						</font>
					</td>
				</tr>				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>対象者条件</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">
						<a href="enq_pollee.php">（対象者条件）</a>
						</font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>状態</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">終了</font>
					</td>
				</tr>
			</table>
			<br><br>
			<table width="600" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td width="30%" bgcolor="#eeeeee"><font size="2" class="honbun2">集計結果表示</font></td>
					<td width="70%" colspan="2">
						<font size="2" class="honbun2">
							<a href="resdet_pv.php">集計結果表示</a>
						</font>
					</td>
				</tr>
				<tr>
					<td width="30%" bgcolor="#eeeeee" rowspan="3"><font size="2" class="honbun2">CSVダウンロード</font></td>
					<td width="40%"><font size="2" class="honbun2">送信者リスト</font></td>
					<td width="30%">
						<form method="post" action="enq_dl1.php">
						<input type="hidden" name="enq_cd" value="">
						<input type="hidden" name="type" value="s">
						<font size="3" class="honbun1"><input type="submit" name="sl" value="ダウンロード"></font>
						</form>
					</td>
				</tr>
				<tr>
					<td width="40%"><font size="2" class="honbun2">受信者リスト</font></td>
					<td width="30%">
						<form method="post" action="enq_dl1.php">
						<input type="hidden" name="enq_cd" value="">
						<input type="hidden" name="type" value="recv">
						<font size="3" class="honbun1"><input type="submit" name="rl" value="ダウンロード"></font>
						</form>
					</td>
				</tr>
				<tr>
					<td width="40%"><font size="2" class="honbun2">回答＋回答者属性</font></td>
					<td width="30%">
						<form method="post" action="enq1a.php">
						<input type="hidden" name="MYECD" value="">
						<input type="hidden" name="MKCD" value="">
						<font size="3" class="honbun1"><input type="submit" name="al" value="ダウンロード"></font>
						</form>
					</td>
				</tr>
			</table>
			<br>
			<!--アンケートの場合のみ表示-->
			<table width="700" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="3"><font size="3" class="honbun1"><b>■ アンケートの結果を任意のアドレスに送信</b></font></td>
				</tr>
				<tr>
					<td colspan="3"><img src="images/shim.gif" width="10" height="10"></td>
				</tr>
				<tr>
					<td width="500"><font size="3" class="honbun1">アンケートの結果を任意のアドレスに送信することができます。<br>
					このアンケートの結果（集計結果及びＣＳＶダウンロード）を教える方のメールアドレスをご記入ください。<br>
					※ 複数の方に送る場合は、メールアドレスをカンマ区切りで入れてください。</font></td>
					<td width="15" align="center"><img src="images/line_t.gif" width="10" height="80" border="0"></td>
					<td width="185"><textarea rows="5" cols="30" name="mailadress"></textarea></td>
				</tr>
				<tr>
					<td colspan="3"><img src="images/shim.gif" width="10" height="10"></td>
				</tr>
				<tr>
					<form method="post" action="notice_myenq.php"><td colspan="3" align="right"><font size="3" class="honbun1"><input type="submit" value="登録"></font></td></form>
				</tr>
			</table>
		</td>
	</tr>
</table>

<? staff_footer() ?>
