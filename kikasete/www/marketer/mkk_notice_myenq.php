<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/check.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_error.php");

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
<? marketer_header('Ｍｙアンケート結果を教える', PG_NULL) ?>

<?
if (!$msg) {
?>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/shim.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/shim.gif" width="10" border="0"></td>
		<td width="400" valign="top">≪Ｍｙアンケート結果を任意のアドレスに送信≫<br><br></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td rowspan="2" width="20"><img src="images/shim.gif" width="20" height="1" border="0"></td>
		<td width="768"><img src="images/shim.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td>
		<form method="post" action="mkk_nmyenq_com.php">
			<input type="hidden" name="enquete_id" value="<?=$enquete_id?>">
			<table width="580" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						下記のアドレスにあなたのＭｙアンケートの結果をお知らせします。<br>
						アドレスに間違いがないかご確認ください。<br>
						間違いがなければ、申請ボタンを押してください。<br>
					</td>
				</tr>
			</table>
			<br>
			
        <table width="580" border="0" cellspacing="0" cellpadding="3">
          <tr>
					<td>
<?
foreach ($ary as $mail_addr) {
	$mail_addr = trim($mail_addr);
	echo "$mail_addr<br>";
	echo '<input type="hidden" name="mail_addr[]" value="', htmlspecialchars($mail_addr), '">';
}
?>
					</td>
				</tr>
				<tr>
					<td align="center">
						
							<input type="submit" value="　申請　">　
							<input type="button" value="　修正　" onclick="history.back()">
						
					</td>
				</tr>
			</table>
		</form>
		</td>
	</tr>
</table>
<? } else{?>
<table width="788" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><? error_msg($msg);?></td>
  </tr>
</table>
<? }?>
<? marketer_footer() ?>
