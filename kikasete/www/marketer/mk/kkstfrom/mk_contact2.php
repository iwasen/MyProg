<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/check.php");
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/mk_header.php");
include("$inc/mk_error.php");

if ($contents == '')
	$msg[] = 'お問合せ内容を入力してください。';

if ($name1 == '')
	$msg[] = 'お名前（姓）を入力してください。';

if ($name2 == '')
	$msg[] = 'お名前（名）を入力してください。';

if ($mail_addr == '')
	$msg[] = 'メールアドレスを入力してください。';
elseif (!check_mail_addr($mail_addr))
	$msg[] = 'メールアドレスが正しく入力されていないようです。';

if ($company == '')
	$msg[] = '御社名を入力してください。';

if ($regist == '')
	$msg[] = 'きかせて・net ユーザー登録の有無を選択してください。';
?>

<? marketer_header('CONTACT US | きかせて・net', PG_CONTACT) ?>

<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>

<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="788">
<?
if (!$msg) {
?>

<table width="660"  border="0" align="center" cellpadding="0" cellspacing="0">
              <form method="post" action="mk_contact3.php">
                <input type="hidden" name="contents" <?=value($contents)?>>
<input type="hidden" name="name1" <?=value($name1)?>>
<input type="hidden" name="name2" <?=value($name2)?>>
<input type="hidden" name="mail_addr" <?=value($mail_addr)?>>
<input type="hidden" name="company" <?=value($company)?>>
<input type="hidden" name="regist" <?=value($regist)?>>

<tr> 
<td><img src="../../images/common/spacer.gif" width="10" height="15"></td>
</tr>
<tr> 
<td class="map"><a href="../../index.php">HOME</a>　＞　Contact Us</td>
</tr>
<tr>
<td><img src="../../images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td><img src="../../images/contact/title.gif" alt="調査依頼：お問い合わせ受けつけ" width="201" height="28"></td>
</tr>
<tr> 
<td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr> 
<td class="description">下記の内容でよろしいですか？<br>
内容を修正する場合は、「修正」ボタンを押して下さい。決定の場合は「送信」ボタンを押して下さい。</td>
</tr>
<tr> 
<td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr> 
<td bgcolor="#dadada">
<table width="100%"  border="0" cellspacing="1" cellpadding="10">
<tr class="description"> 
<td width="180" valign="top" bgcolor="#e5eaf0">送信フォーム</td>
<td bgcolor="#FFFFFF"><?=nl2br($contents)?><br /><br />
<div class="footer_text" style="color:red;">（お電話番号のご記入漏れはございませんか？）</div></td>
</tr>
<tr class="description"> 
<td bgcolor="#e5eaf0">お名前</td>
<td bgcolor="#FFFFFF"><?=htmlspecialchars($name1)?><?=htmlspecialchars($name2)?></td>
</tr>
<tr class="description"> 
<td bgcolor="#e5eaf0">メールアドレス</td>
<td bgcolor="#FFFFFF"><?=htmlspecialchars($mail_addr)?></td>
</tr>
<tr class="description"> 
<td bgcolor="#e5eaf0">御社名／部署名</td>
<td bgcolor="#FFFFFF"><?=htmlspecialchars($company)?></td>
</tr>
<tr class="description"> 
<td bgcolor="#e5eaf0">きかせて・net ユーザ登録</td>
<td bgcolor="#FFFFFF"><?=decode_regist($regist)?></td>
</tr>
</table>
</td>
</tr>
<tr> 
<td><img src="../../images/common/spacer.gif" width="10" height="30"></td>
</tr>
<tr> 
<td align="center"><a href="javascript:history.back();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('edit','','../../images/common/shusei_ov.gif',1)"><img src="../../images/common/shusei.gif" alt="修正" name="edit" width="118" height="31" border="0"></a>　
<input type="image" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('sousin','','../../images/common/send_on.gif',1)" src="../../images/common/send.gif" alt="送信" name="sousin" width="120" height="32" border="0">
</td>
</tr>
<tr>
<td align="center"><img src="../../images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr> 
<td align="right"><a href="#top"><img src="../../images/common/go_top.gif" alt="" width="57" height="11" border="0"></a></td>
</tr>
<tr> 
<td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
</tr>
</form>
</table></tr>
</table>
<?
} else {
?>
<table width="788" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
	<? error_msg($msg);?>
	<? echo("&nbsp;");?></td>
  </tr>
</table>
<? }?>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer(false); ?>
</body>
</html>
