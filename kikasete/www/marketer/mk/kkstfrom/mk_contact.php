<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mk_header.php");
//include("$inc/mk_agent_list.php");

// ����������ȥꥹ��
function agent_list2($top_path, $url, $cols=10) {
	$sql = "SELECT ag_agent_id,ag_name1 FROM t_agent WHERE ag_status=0 AND ag_disp_flag ORDER BY ag_agent_id DESC";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		if ($i % $cols == 0)
			echo "<tr>\n";

		echo "<td width='10%' align='center' valign='top'>\n";
		echo "<img src='{$top_path}/images/agent{$fetch->ag_agent_id}.jpg' alt='{$fetch->ag_name1}' width=50 height=50 border=0><br>\n";
		echo "<a href='javascript:void(0)' onclick='open(\"$url{$fetch->ag_agent_id}\", \"_blank\", \"width=742,height=730,scrollbars=yes,resizable=yes,status=yes\")'><span class='description'>{$fetch->ag_name1}</span></a></td>\n";
		echo "</td>\n";

		if (($i + 1) % $cols == 0)
			echo "</tr><tr><td colspan=$cols><img src='{$top_path}/images/common/spacer.gif' width='10' height='10'></td></tr>\n";
	}

	if ($i % $cols != 0) {
		for ( ; $i % $cols != 0; $i++)
			echo "<td width=65></td>\n";
		echo "</tr>\n";
	}
}

?>

<? marketer_header('CONTACT US | �������ơ�net', PG_CONTACT) ?>

<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="788">

<table width="660"  border="0" align="center" cellpadding="0" cellspacing="0">
        <form name="form1" method="post" action="mk_contact2.php">
          <tr> 
<td><img src="../../images/common/spacer.gif" width="10" height="15"></td>
</tr>
<tr> 
<td class="map"><a href="../../index.php">HOME</a>���䡡Contact Us</td>
</tr>
<tr>
<td><img src="../../images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td><img src="../../images/contact/title.gif" alt="Ĵ�����ꡧ���䤤��碌�����Ĥ�" width="201" height="28"></td>
</tr>
<tr> 
<td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr> 
<td class="description">���󥱡��ȤΥ��������������̤ʤɡ����ĤǤ⾵��ޤ����ޤ����������ơ�net �ˤĤ��ƤΤ��䤤��碌�⤳���餫�餪��<br>
���������ޤ��������Υ����ӥ�����������Ȥ��б�������ĺ���ޤ��Τǡ������ڤˤ�Ϣ��������<br>
<br>
���䤤��碌�ϡ����������ե�����򤪻Ȥ�ĺ������ <a href="mailto:k-agent@kikasete.net?subject=%81%a1%82%a8%96%e2%82%a2%8d%87%82%ed%82%b9">k-agent@kikasete.net</a>�ޤǥ᡼������겼������</td>
</tr>
<tr> 
<td><img src="../../images/common/spacer.gif" width="10" height="15"></td>
</tr>
<tr> 
<td><img src="../../images/contact/header.gif" width="661" height="23"></td>
</tr>
<tr> 
<td><img src="../../images/common/spacer.gif" width="10" height="15"></td>
</tr>
<tr> 
<td align="center">
<table width="508" border="0" cellpadding="0" cellspacing="0">
<? agent_list2($top, "{$top}/mk/agent/mk_agintro1.php?agent_id=", 8) ?>
</table>
</td>
</tr>
<tr> 
<td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr> 
<td bgcolor="#dadada">
<table width="100%"  border="0" cellspacing="1" cellpadding="10">
<tr class="description"> 
<td width="180" valign="top" nowrap bgcolor="#e5eaf0">�����ե�����</td>
<td bgcolor="#FFFFFF"> 
<textarea name="contents" cols="65" rows="7" class="textarea_1"></textarea>
<br />
��������Ǥ������֤�Ϣ���ǽ�ʤ������ֹ�פ�ʻ���Ƥ��������������� 
</td>
</tr>
<tr class="description"> 
<td nowrap bgcolor="#e5eaf0">��̾��</td>
<td bgcolor="#FFFFFF">�� 
<input name="name1" type="text" size="20">
��̾ 
<input name="name2" type="text" size="20">
</td>
</tr>
<tr class="description"> 
<td nowrap bgcolor="#e5eaf0">�᡼�륢�ɥ쥹</td>
<td bgcolor="#FFFFFF">
<input name="mail_addr" type="text" size="50">
</td>
</tr>
<tr class="description"> 
<td nowrap bgcolor="#e5eaf0">���̾������̾</td>
<td bgcolor="#FFFFFF">
<input name="company" type="text" size="50">
</td>
</tr>
<tr class="description"> 
<td nowrap bgcolor="#e5eaf0">�������ơ�net �桼����Ͽ</td>
<td bgcolor="#FFFFFF">
<input name="regist" type="radio" value="2">
��Ͽ�Ѥߡ� 
<input name="regist" type="radio" value="1">
̤��Ͽ</td>
</tr>
</table>
</td>
</tr>
<tr> 
<td><img src="../../images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td class="description">�������������Ƥ�����塢�����ӥ�����������Ȥ��餴Ϣ��򤵤���ĺ���ޤ���</td>
</tr>
<tr> 
<td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr> 
<td align="center">
<input type="image" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('confirm','','../../images/common/confirm_on.gif',1)" src="../../images/common/confirm.gif" alt="��ǧ" name="confirm" width="120" height="32" border="0">
��<a href="javascript:document.form1.reset();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('clear','','../../images/common/clear_on.gif',1)"><img src="../../images/common/clear.gif" alt="���ꥢ" name="clear" width="120" height="32" border="0"></a></td>
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
</table>
<td width="1" bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="10"></td>
</tr>
</table>

<? marketer_footer(false); ?>


</body>
</html>
