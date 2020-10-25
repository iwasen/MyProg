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
<? marketer_header('Ｐｒｏアンケート結果を教える', PG_NULL) ?>

<?
if (!$msg) {
?>
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
		<td width="400" valign="top"><span style="font-weight:bold;">≪Ｐｒｏアンケート結果を任意のアドレスに送信≫</span><br><br></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td rowspan="2" width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
		<td width="768"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td>
		<form method="post" action="pe_nmyenq_com.php">
			<input type="hidden" name="pro_enq_no" value="<?=$pro_enq_no?>">
			<table width="580" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td align="center">
						下記のアドレスにあなたのＰｒｏアンケートの結果をお知らせします。<br>
						アドレスに間違いがないかご確認ください。<br>
						間違いがなければ、送信ボタンを押してください。<br>
						
					</td>
				</tr>
				<tr>
					<td><img src="images/common/spacer.gif" width="1" height="20" border="0">
					</td>
				</tr>
				<tr>
					<td bgcolor="#dadada" align="center">
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
					<td><img src="images/common/spacer.gif" width="1" height="20" border="0">
					</td>
				</tr>
				<tr>
					<td align="center">

							<input type="image" src="images/mkk_bt/transmit.gif" alt="送信" width="108" height="31">
							<a href="javascript:history.back();"><img src="images/mkk_bt/back.gif" alt="戻る" name="image2" width="108" 

height="31" border="0"></a>


					</td>
				</tr>
			</table>
		</form>
		</td>
	</tr>
</table>
<?
} else{?>

<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
  <tr>
    <td><? error_msg($msg);?></td>
  </tr>
</table>

<? }?>

</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="1" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
