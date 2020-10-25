<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mk_header.php");
//include("$inc/mk_agent_list.php");

$sql = "SELECT ag_name1,ag_self_introduction FROM t_agent WHERE ag_agent_id=$agent_id";
$result = db_exec($sql);
if (pg_numrows($result))
	$fetch = pg_fetch_object($result, 0);
?>

<? marketer_header("CONTACT US | きかせて・net", PG_CONTACT); ?>

<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="788">
<table width="660"  border="0" align="center" cellpadding="0" cellspacing="0">
        <form name="form1" method="post" action="mk_agintro2.php">
<input type="hidden" name="agent_id" <?=value($agent_id)?>>
          <tr> 
<td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr> 
<td><img src="../../images/contact/agent_title.gif" alt="" width="160" height="28"></td>
</tr>
<tr> 
<td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr> 
<td>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr> 
<td width="20"><img src="../../images/common/spacer.gif" width="20" height="10"></td>
<td width="61" align="center" valign="top">
<p><img src='../../images/agent<?=$agent_id?>.jpg' width=50 height=50 border=0><br>
<span class="right_menu_title"><?=htmlspecialchars($fetch->ag_name1)?></span></p>
</td>
<td width="40"><img src="../../images/common/spacer.gif" width="40" height="10"></td>
<td class="description"><?=nl2br($fetch->ag_self_introduction)?></td>
</tr>
</table>
</td>
</tr>
<tr> 
<td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr> 
<td bgcolor="#dadada">
<table width="100%"  border="0" cellspacing="1" cellpadding="10">
<tr> 
<td width="180" valign="top" nowrap bgcolor="#e5eaf0" class="description">送信フォーム</td>
<td bgcolor="#FFFFFF" class="description"> 
<textarea name="contents" cols="65" rows="7" class="textarea_1"></textarea>
<br />
※お手数ですが、「ご連絡可能なお電話番号」を併せてご記入ください。 
</td>
</tr>
<tr> 
<td nowrap bgcolor="#e5eaf0" class="description">お名前</td>
<td bgcolor="#FFFFFF" class="description">姓 
<input name="name1" type="text" size="20">
　名 
<input name="name2" type="text" size="20">
</td>
</tr>
<tr> 
<td nowrap bgcolor="#e5eaf0" class="description">メールアドレス</td>
<td bgcolor="#FFFFFF" class="description">
<input name="mail_addr" type="text" size="50">
</td>
</tr>
<tr> 
<td nowrap bgcolor="#e5eaf0" class="description">御社名／部署名</td>
<td bgcolor="#FFFFFF" class="description">
<input name="company" type="text" size="50">
</td>
</tr>
<tr> 
<td nowrap bgcolor="#e5eaf0" class="description">きかせて・net ユーザ登録</td>
<td bgcolor="#FFFFFF" class="description">
<input name="regist" type="radio" value="2">
登録済み　 
<input name="regist" type="radio" value="1">
未登録</td>
</tr>
</table>
</td>
</tr>
<tr> 
<td><img src="../../images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td><span class="footer_text">※　ご相談内容を受信後、サービスエージェントからご連絡をさせて頂きます。</span></td>
</tr>
<tr> 
<td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr> 
<td align="center">
<input type="image" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('confirm','','../../images/common/confirm_on.gif',1)" src="../../images/common/confirm.gif" alt="確認" name="confirm" width="120" height="32" border="0">
　<a href="javascript:document.form1.reset();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('clear','','../../images/common/clear_on.gif',1)"><img src="../../images/common/clear.gif" alt="クリア" name="clear" width="120" height="32" border="0"></a></td>
</tr>
<tr> 
<td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr>
<td align="right"><a href="#top"><img src="../../images/common/go_top.gif" alt="" width="57" height="11" border="0"></a></td>
</tr>
<tr>
<td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
</tr>
</form>
</table>
<td width="1" bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="10"></td>
</tr>
</table>

<? marketer_footer(false); ?>


</body>
</html>
