<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_error.php");
include("$inc/mk_subr.php");

if ($title == '')
	$msg[] = 'タイトルを入力してください。';

if ($text == '')
	$msg[] = 'メッセージ本文を入力してください。';
?>
<? marketer_header('きかせて・net ネットリサーチにプロのエージェントを', PG_CONTACT) ?>

<!--contents start-->
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr> 
<td width="788"> 
<table width="788" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td valign="top"><img src="images/common/spacer.gif" width="10" height="15"></td>
<td bgcolor="#dbdbdb"> 
<td width="12" rowspan="2"> <img src="images/common/spacer.gif" width="18" height="10"> 
<td valign="top"><img src="images/common/spacer.gif" width="10" height="15"></td>
</tr>
<tr> 
<td width="170" valign="top" bgcolor="#e5eaf0"> 

<? marketer_menu(M_CONTACT); ?>

</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10" /> 
<td valign="top"> 
<table width="553" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td width="553"><img src="images/common_my/title_06.gif" alt="COMMON" width="553" height="25"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td><img src="images/contactus/title_01.gif" alt="contact us" width="164" height="28"> 
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>

<?
if (!$msg) {
?>

<tr> 
<td> 
<p>下記の内容でよろしいですか？<br>
<br>
内容を修正する場合は、「修正」ボタンを押してください。下記の内容でよろしければ、「送信」ボタンを押してください。 </p>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="20" /></td>
</tr>
<tr> 
<td> 

<?
$fetch = get_owner_info('mr_name1,mr_name2,mr_mail_addr');
$name1 = htmlspecialchars($fetch->mr_name1);
$name2 = htmlspecialchars($fetch->mr_name2);
$mail_addr = htmlspecialchars($fetch->mr_mail_addr);
?>

<form method="post" name"form1" action="mkk_messcompl.php">
<input type="hidden" name="title" <?=value($title)?>>
<input type="hidden" name="text" <?=value($text)?>>
<table width="553" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td bgcolor="#dadada"> 
<table width="553" border="0" cellspacing="1" cellpadding="5" class="nor_text">
<tr> 
<td width="30%" valign="top" bgcolor="#e5eaf0">送信内容</td>
<td width="70%" valign="top" bgcolor="#FFFFFF"><?=htmlspecialchars($title)?><br><br><?=nl2br($text)?></td>
</tr>
<tr> 
<td width="30%" valign="top" bgcolor="#e5eaf0">お名前（姓）</td>
<td width="70%" valign="top" bgcolor="#FFFFFF"><?=$name1?></td>
</tr>
<tr> 
<td width="30%" valign="top" bgcolor="#e5eaf0">お名前（名）</td>
<td width="70%" valign="top" bgcolor="#FFFFFF"><?=$name2?></td>
</tr>
<tr> 
<td width="30%" valign="top" bgcolor="#e5eaf0">メールアドレス</td>
<td width="70%" valign="top" bgcolor="#FFFFFF"><?=$mail_addr?> </td>
</tr>
</table>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td align="center"> 
<table border="0" cellspacing="0" cellpadding="0">
<tr> 
<td> 
<input name="image2" type="image" src="images/contactus/bt_02.gif" alt="送　信" width="108" height="31">
</td>
<td><img src="images/common/spacer.gif" width="20" height="10"></td>
<td> 
<a href="javascript:history.back()"><img name="image3" src="images/mkk_assistant/bt06.gif" alt="修　正" width="108" height="31"></a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</form>
</td>
</tr>

<?
} else {
echo("<tr><td>");
	error_msg($msg);
echo("</td></tr>");
}
?>

<tr> 
<td><img src="images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr> 
<td align="right"><a href="#top"><img src="images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="30"></td>
</tr>
</table>
</td>
</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10"></td>
</tr>
</table>
<!--footer start-->

<? marketer_footer(BLUE) ?>
