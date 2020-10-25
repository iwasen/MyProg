<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_subr.php");

$owner_id = $_SESSION['ss_owner_id'];
?>
<? marketer_header('エージェントへメッセージを送信', PG_CONTACT) ?>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="400" valign="top"><img src="images/t_mkk_message.gif" width="538" height="20"><br><br></td>
	</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td rowspan="2" width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
		<td width="700"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td>
			<form method="post" name"form1" action="mkk_messconfirm.php">
			<table width="600" border="1" cellspacing="0" cellpadding="3">
				<tr>
					<td bgcolor="#eeeeee">タイトル</td>
				</tr>
				<tr>
					<td><input type="text" size="54" name="title" value="（例）○○の件について"></td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee">メッセージ本文</td>
				</tr>
				<tr>
					<td><textarea rows="15" cols="70" wrap="hard" name="text"></textarea></td>
				</tr>
				<tr>
					<td>
<?
// 名前とメールアドレス取得
$fetch = get_owner_info('mr_name1,mr_name2,mr_mail_addr');
$marketer_name = htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2");
$mail_addr = htmlspecialchars($fetch->mr_mail_addr);
?>
						<table width="580" border="0" cellspacing="0" cellpadding="3">
							<tr>
								<td width="30%">お名前
								<td width="70%"><?=$marketer_name?>
							</tr>
							<tr>
								<td width="30%">メールアドレス
								<td width="70%"><?=$mail_addr?>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="center">
						<table width="570" border="0" cellspacing="0" cellpadding="3">
							<tr>
								<td align="center"><input type="submit" value="　確　認　">　<input type="reset" value="　クリア　"></td>
							</tr>
							<tr>
								<td>※ メッセージを受信後、専属エージェントからご連絡させていただきます。</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			</form>
			<br>
			<form method="post" name="form2" action="mkk_messdelm.php">
			<table width="700" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><b>過去に専属エージェントへ送ったメッセージ一覧</b>（過去に送ったメッセージがある場合表示させる）</td>
				</tr>
				<tr>
					<td>タイトルをクリックすると、過去にエージェントへ送信したメッセージの詳細をみることができます。</td>
				</tr>
			</table>
			<br>
			<table width="600" border="1" cellspacing="0" cellpadding="3">
				<tr bgcolor="#eeeeee">
					<td width="65%">タイトル</td>
					<td width="25%">送信日時</td>
					<td width="10%">削除</td>
				</tr>
<?
// エージェントからのメッセージ一覧取得
$sql = "SELECT co_seq_no,co_regist_date,co_title"
		. " FROM t_communication"
		. " WHERE co_marketer_id=$owner_id AND co_status=0 AND co_direction=1 ORDER BY co_regist_date DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$seq_no = $fetch->co_seq_no;
	$title = htmlspecialchars($fetch->co_title);
	$regist_date = format_date($fetch->co_regist_date);
?>
				<tr>
					<td width="65%"><a href="mkk_pastmess1.php?seq_no=<?=$seq_no?>"><?=$title?></a></td>
					<td width="25%" align="right"><?=$regist_date?></td>
					<td width="10%"><input type="checkbox" name="delete[]" value="<?=$seq_no?>"></td>
				</tr>
<?
}
?>
			</table>
			<br>
			<table width="600" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="right"><input type="submit" value="削除"></td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>

<? marketer_footer() ?>
