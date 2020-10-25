<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");

$staff_id = $_SESSION['ss_staff_id'];

if ($enquete_id) {
	$sql = "SELECT me_my_enq_no FROM t_my_enquete WHERE me_enquete_id=$enquete_id";
	$my_enq_no = db_fetch1($sql);
	if (!$my_enq_no) {
		$sql = "SELECT jb_job_id FROM t_job WHERE jb_enquete_id=$enquete_id";
		$job_id = db_fetch1($sql);
	}
}

if ($my_enq_no) {
	$sql = "SELECT me_enquete_id,me_send_num,me_real_send,me_search_id FROM t_my_enquete WHERE me_staff_id=$staff_id AND me_my_enq_no=$my_enq_no";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$send_num = number_format($fetch->me_send_num);
		$real_send = number_format($fetch->me_real_send);
		$enquete_id = $fetch->me_enquete_id;
		$search_id = $fetch->me_search_id;
	}
}

if ($enquete_id == '')
	redirect('enq.php');

$sql = "SELECT en_enq_type,en_title,en_start_date,en_end_date,en_point,en_status FROM t_enquete WHERE en_enquete_id=$enquete_id";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$title = htmlspecialchars($fetch->en_title);
	$enq_type = decode_enq_type($fetch->en_enq_type);
	$point = number_format($fetch->en_point);
	$start_date = format_date($fetch->en_start_date);
	$end_date = format_datetime($fetch->en_end_date);
	$status = decode_enq_status($fetch->en_status);
	$ret = $fetch->en_status == 7 ? 'endpjtlist.php' : 'pjtlist.php';
} else
	redirect('enq.php');

$sql = "SELECT COUNT(*) FROM t_enquete_list JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id WHERE el_enquete_id=$enquete_id";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_row($result, 0);
	$recv_count = number_format($fetch[0]);
	$recv_rate  = $real_send ? round($recv_count/$real_send*100,1) : 0;
} else {
	$recv_count = 0;
	$recv_rate  = 0;
}
?>
<? staff_header('プロジェクト詳細表示') ?>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
			<table width="580" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						<font size="3" class="honbun1">
						<b><?=$title?></b> アンケートの詳細です。
						</font>
					</td>
					<td align="right"><input type="button" value="　戻る　" onclick="location.href='<?=$ret?>'"></td>
				</tr>
			</table>
			<br>
			<table width="600" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>アンケート名</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2"><?=$title?></font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>種類</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2"><?=$enq_type?></font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>ポイント</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2"><?=$point?></font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>開始日</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2"><?=$start_date?></font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>終了日</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2"><?=$end_date?></font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>発信数</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2"><?=$real_send?>	<a href="enq_smail.php?enquete_id=<?=$enquete_id?>">（発信者プロファイル）</a></font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>受信数</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2"><?=$recv_count?> <a href="enq_rmail.php?enquete_id=<?=$enquete_id?>">（受信者プロファイル）</a>
						</font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>返信率</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2"><?=$recv_rate?>%</font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>メールコンテンツ</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">
						<a href="enq_mail.php?enquete_id=<?=$enquete_id?>">（メールコンテンツ）</a>
						</font>
					</td>
				</tr>				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>対象者条件</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">
						<a href="enq_pollee.php?search_id=<?=$search_id?>">（対象者条件）</a>
						</font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>状態</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2"><?=$status?></font>
					</td>
				</tr>
			</table>
			<br><br>
			<table width="600" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td width="30%" bgcolor="#eeeeee"><font size="2" class="honbun2">集計結果表示</font></td>
					<td width="70%" colspan="2">
						<font size="2" class="honbun2">
							<a href="resdet_sex.php?enquete_id=<?=$enquete_id?>">性別</a>　｜　<a href="resdet_age.php?enquete_id=<?=$enquete_id?>">年代</a>　｜　<a href="resdet_sex_age.php?enquete_id=<?=$enquete_id?>">性別・年代（１０歳毎）</a>　｜　<a href="resdet_pv.php?enquete_id=<?=$enquete_id?>">年代（５歳毎）・性別</a>
						</font>
					</td>
				</tr>
				<tr>
					<td width="30%" bgcolor="#eeeeee" rowspan="4"><font size="2" class="honbun2">CSVダウンロード</font></td>
					<td width="40%"><font size="2" class="honbun2">送信者リスト</font></td>
					<td width="30%">
						<form method="post" action="enq_dl1.php">
						<input type="hidden" name="enquete_id" value="<?=$enquete_id?>">
						<input type="hidden" name="type" value="send">
						<font size="3" class="honbun1"><input type="submit" value="ダウンロード"></font>
						</form>
					</td>
				</tr>
				<tr>
					<td width="40%"><font size="2" class="honbun2">受信者リスト</font></td>
					<td width="30%">
						<form method="post" action="enq_dl1.php">
						<input type="hidden" name="enquete_id" value="<?=$enquete_id?>">
						<input type="hidden" name="type" value="recv">
						<font size="3" class="honbun1"><input type="submit" value="ダウンロード"></font>
						</form>
					</td>
				</tr>
				<tr>
					<td width="40%"><font size="2" class="honbun2">回答＋回答者属性<br>（複数回答はカンマ区切りで）</font></td>
					<td width="30%">
						<form method="post" action="enq1a.php">
						<input type="hidden" name="enquete_id" value="<?=$enquete_id?>">
						<font size="3" class="honbun1"><input type="submit" value="ダウンロード"></font>
						</form>
					</td>
				</tr>
				<tr>
					<td width="40%"><font size="2" class="honbun2">回答＋回答者属性<br>（複数回答は０、１形式）</font></td>
					<td width="30%">
						<form method="post" action="enq1b.php">
						<input type="hidden" name="enquete_id" value="<?=$enquete_id?>">
						<font size="3" class="honbun1"><input type="submit" value="ダウンロード"></font>
						</form>
					</td>
				</tr>
			</table>
			<br>
<?
if ($my_enq_no) {
?>
			<!--アンケートの場合のみ表示-->
			<form method="post" action="notice_myenq.php">
			<input type="hidden" name="enquete_id" value="<?=$enquete_id?>">
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
					<td width="185"><textarea rows="5" cols="30" name="addr_list"></textarea></td>
				</tr>
				<tr>
					<td colspan="3"><img src="images/shim.gif" width="10" height="10"></td>
				</tr>
				<tr>
					<td colspan="3" align="right"><font size="3" class="honbun1"><input type="submit" value="　登録　"></font></td>
				</tr>
			</table>
			</form>
<?
}
?>

<? staff_footer() ?>
