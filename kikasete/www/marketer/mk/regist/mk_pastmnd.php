<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/mk_header.php");
?>
<? marketer_header('�᡼��˥塼����Ͽ��λ', PG_REGIST) ?>

<style type="text/css">
<!--
.style1 {font-size: 10}
-->
</style>

<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="788">
    <table width="788" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td valign="top"><img src="../../images/common/spacer.gif" width="10" height="15" /></td>
          <td bgcolor="#dbdbdb">        
          <td valign="top">        
          <td valign="top">&nbsp;</td>
          <td valign="top"><img src="../../images/common/spacer.gif" width="20" height="15"></td>
        </tr>
        <tr>
          <td width="170" valign="top" bgcolor="#e5eaf0">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="right" bgcolor="#FFFFFF"><img src="../../images/regist/side_regist.gif" alt="����Ͽ" width="57" height="18"></td>
              </tr>
              <tr>
                
                <td><a href="mk_regist.php#mypage" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image60','','../../images/regist/side_mypage_on.gif',1)"><img src="../../images/regist/side_mypage.gif" alt="My�ڡ�����Ͽ" name="Image60" width="170" height="31" border="0"></a></td>
              </tr>
              <tr>
                <td><img src="../../images/regist/side_mailnews_on.gif" alt="�᡼��˥塼����Ͽ" width="170" height="31"></td>
              </tr>
            </table></td>
          <td width="1" bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="10" />
          <td width="12" valign="top"><img src="../../images/common/spacer.gif" width="12" height="10">          
          <td valign="top">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td class="map"><a href="../../index.php">HOME</a>���䡡<a href="mk_regist.php">��Ͽ</a>���䡡<a href="mk_pastmn_1.php">�᡼��˥塼���Хå��ʥ�С��ꥹ��</a>���䡡�᡼��˥塼���Хå��ʥ�С�</td>
</tr>
<tr>
<td><img src="../../images/common/spacer.gif" width="10" height="10" /></td>
</tr>
              <tr> 
                <td><img src="../../images/regist/title.gif" alt="����Ͽ�ˤĤ���" width="114" height="28"></td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="30"></td>
              </tr>
              <tr> 
                <td width="578"><img src="../../images/regist/header4.gif" alt="�᡼��˥塼���Хå��ʥ�С�" width="578" height="23"></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td align="right"> 
                  <table border=0 cellspacing=0 cellpadding=0 width="100%">
                    <tr> 
                      <td class="footer_text" width="150"> 
                        <?
if ($no > 0) {
?>
                        <a href="mk_pastmnd.php?no=<?=$no - 1?>">&lt;&lt;�������ʥ�С�</a> 
                        <?
}
?>
                      </td>
                      <td align="center" width="278" class="footer_text"><a href="mk_pastmn_1.php?no=<?=$no?>">�᡼��˥塼���Хå��ʥ�С��ꥹ�Ȥ����</a></td>
                      <td align="right" class="footer_text" width="150"> 
                        <?
if ($no > 1) {
?>
                        <a href="mk_pastmnd.php?no=<?=$no + 1?>">���Υʥ�С�&gt;&gt;</a> 
                        <?
}
?>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="10"></td>
              </tr>
              <tr> 
                <td align="center"> 
                  <?
// �᡼��˥塼��
$sql = 'SELECT ns_subject,ns_body'
		. ' FROM t_mail_news'
		. " WHERE ns_status<=1 AND ns_target IN (2,4) ORDER BY ns_regist_date DESC limit 2 OFFSET $no";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow) {
	$fetch = pg_fetch_object($result, 0);
	$title = htmlspecialchars($fetch->ns_subject);
	$body = $fetch->ns_body;
}
?>
                  <table width="450" border="0" cellspacing="1" cellpadding="5" bgcolor="#dadada">
                    <tr bgcolor="#ffffff" class="description"> 
                      <td>
                        <?=nl2br($body)?>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="40"></td>
              </tr>
              <tr> 
                <td align="right"><a href="#top"><img src="../../images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="30"></td>
              </tr>
            </table>
          </td>
          <td valign="top">&nbsp;</td>
        </tr>
      </table>
<td width="1" bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="10"></td>
</tr>
</table>

<? marketer_footer(BLUE); ?>

</body>
</html>
