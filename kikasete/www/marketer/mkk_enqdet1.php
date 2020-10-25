<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

$owner_id = $_SESSION['ss_owner_id'];

if ($enquete_id) {
	$sql = "SELECT me_my_enq_no FROM t_my_enquete WHERE me_enquete_id=$enquete_id";
	$my_enq_no = db_fetch1($sql);
	if (!$my_enq_no) {
		$sql = "SELECT jb_job_id FROM t_job WHERE jb_enquete_id=$enquete_id";
		$job_id = db_fetch1($sql);
	}
}

if ($my_enq_no) {
	$sql = "SELECT me_enquete_id,me_real_send,me_search_id FROM t_my_enquete WHERE me_marketer_id=$owner_id AND me_my_enq_no=$my_enq_no";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$real_send = $fetch->me_real_send;
		$enquete_id = $fetch->me_enquete_id;
		$search_id = $fetch->me_search_id;
	}
}

if ($job_id) {
	$sql = "SELECT jb_enquete_id,jb_real_send,jb_search_id FROM t_job WHERE jb_job_id=$job_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$real_send = $fetch->jb_real_send;
		$enquete_id = $fetch->jb_enquete_id;
		$search_id = $fetch->jb_search_id;
	}
}

if ($enquete_id == '')
	redirect('mkk_enq.php');

$sql = "SELECT en_title,en_start_date,en_end_date,en_status FROM t_enquete WHERE en_enquete_id=$enquete_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$title = htmlspecialchars($fetch->en_title);
	$start_date = format_date($fetch->en_start_date);
	$end_date = format_datetime($fetch->en_end_date);
	$status = decode_enq_status($fetch->en_status);
} else
	redirect('mkk_enq.php');

$sql = "SELECT COUNT(*) FROM t_enquete_list JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id WHERE el_enquete_id=$enquete_id AND an_valid_flag";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_row($result, 0);
	$recv_count = $fetch[0];
	} else {
	$recv_count = 0;
	}
?>
<? marketer_header('プロジェクト詳細表示', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="400" valign="top"><img src="images/t_mkk_enqdet1.gif" width="538" height="20" border="0"><br><br></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td rowspan="2" width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
		<td width="768"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td>
			<table width="580" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						<b><?=$title?></b>についてのアンケートの詳細です。
					</td>
					<td align="right">&nbsp;</td>
				</tr>
			</table>
			<br>
			<table width="600" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>アンケート名</th>
					<td width="70%" align="center">
						<?=$title?>についてのアンケート					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>開始日</th>
					<td width="70%" align="center">
						<?=$start_date?>					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>終了日</th>
					<td width="70%" align="center">
						<?=$end_date?>					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>本アンケート回答数
					<td width="70%" align="center">
					<a href="mkk_recv_count_detail.php?enquete_id=<?=$enquete_id?>"><?=number_format($recv_count)?></a></td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>対象者条件</th>
					<td width="70%" align="center">

						<a href="mkk_enq_pollee.php?my_enq_no=<?=$my_enq_no?>">対象者条件</a>					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>状態</th>
					<td width="70%" align="center">
						<?=$status?>					</td>
				</tr>
				<tr>
				  <td align="center" bgcolor="#eeeeee" nowrap>調査票                
				  <td align="center"><a href="mkk_myenq_webprev.php?my_enq_no=<?=$my_enq_no?>">調査票</a></td>
			  </tr>
			</table>
			<br><br>
			<table width="600" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee">リアルタイム集計結果</td>
					<td width="70%" colspan="2">

							<a href="mkk_resdet_gt.php?enquete_id=<?=$enquete_id?>">全体</a>　｜<a href="mkk_resdet_sex.php?enquete_id=<?=$enquete_id?>">性別</a>　｜　<a href="mkk_resdet_age.php?enquete_id=<?=$enquete_id?>">年代</a>　｜　<a href="mkk_resdet_sex_age.php?enquete_id=<?=$enquete_id?>">性別×年代</a>｜<a href="mkk_resdet_pv.php?enquete_id=<?=$enquete_id?>"></a>					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee">回答データ</td>
					<td width="40%">・設問csv<br>
					  ・回答＋回答者属性csv</td>
					<td width="30%" align="center">
						<form method="post" action="mkk_enq_csv_zip.php">
						<input type="hidden" name="my_enq_no" value="<?=$my_enq_no?>">
						<input type="submit" value="　ダウンロード　">
						</form>
					</td>
				</tr>
			</table>
			<br>
<?
if ($my_enq_no) {
?>
<!-- add enqview -->
<form id="form1" name="form1" method="post" action="">
  <div align="center"><br />
              <input type="button" value="　戻る　" onclick="location.href='mkk_myenq.php'">
              </div>
</form>	
			<br>
<!-- //add enqview -->
<?
}
?>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
