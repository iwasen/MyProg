<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/check.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");
include("$inc/error.php");

if ($addr_list == '')
	$msg = '送信先のメールアドレスを入力してください。';
else {
	$addr_list = str_replace("\n", ',', $addr_list);
	$ary = explode(',', $addr_list);
	foreach ($ary as $mail_addr) {
		$mail_addr = trim($mail_addr);
		if (!check_mail_addr($mail_addr)) {
			$msg = '正しくないメールアドレスが入力されているようです。';
			break;
		}
	}
}
?>
<? staff_header('アンケート結果を教える') ?>

<?
if (!$msg) {
?>
<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="10"><img src="images/shim.gif" width="10" border="0"></td>
		<td width="400" valign="top"><font size="3" class="honbun1">≪アンケート結果を任意のアドレスに送信≫</font><br><br></td>
	</tr>
</table>
<form method="post" action="nmyenq_com.php">
	<input type="hidden" name="enquete_id" value="<?=$enquete_id?>">
	<table width="580" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<font size="3" class="honbun1">下記のアドレスにあなたのアンケートの結果をお知らせします。<br>
				アドレスに間違いがないかご確認ください。<br>
				間違いがなければ、申請ボタンを押してください。<br>
				</font>
			</td>
		</tr>
	</table>
	<br>
	<table width="580" border="1" cellspacing="0" cellpadding="3">
		<tr>
			<td><font size="3" class="honbun1">
<?
foreach ($ary as $mail_addr) {
	$mail_addr = trim($mail_addr);
	echo "$mail_addr<br>";
	echo '<input type="hidden" name="mail_addr[]" value="', htmlspecialchars($mail_addr), '">';
}
?>
			</font></td>
		</tr>
		<tr>
			<td align="center">
				<font size="3" class="honbun1">
					<input type="submit" value="　申請　">　
					<input type="button" value="　修正　" onclick="history.back()">
				</font>
			</td>
		</tr>
	</table>
</form>
<?
} else
	error_msg($msg);
?>
<? staff_footer() ?>
