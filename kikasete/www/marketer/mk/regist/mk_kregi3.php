<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mk_regist.php");
include("$inc/mk_header.php");
include("$inc/mk_agent_list.php");

// ���å�������
session_start();
if (!isset($_SESSION['ss_regist']))
	redirect('mk_kregi1.php');
$reg = &$_SESSION['ss_regist'];
?>
<? marketer_header('��°�������������Ͽ', PG_REGIST) ?>

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
                <td><img src="../../images/regist/side_mypage_on.gif" alt="My�ڡ�����Ͽ" width="170" height="31"></td>
              </tr>
              <tr>
                
                <td><a href="mk_regist.php#mail" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image59','','../../images/regist/side_mailnews_on.gif',1)"><img src="../../images/regist/side_mailnews.gif" alt="�᡼��˥塼����Ͽ" name="Image59" width="170" height="31" border="0"></a></td>
              </tr>
            </table></td>
          <td width="1" bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="10" />
          <td width="12" valign="top"><img src="../../images/common/spacer.gif" width="12" height="10">          
          <td valign="top">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td class="map"><a href="../../index.php">HOME</a>���䡡<a href="mk_regist.php">��Ͽ</a>���䡡My�ڡ�����Ͽ</td>
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
                <td width="578"><img src="../../images/regist/header1.gif" alt="My�ڡ�����Ͽ" width="578" height="38"></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td align="center"><img src="../../images/regist/sequence4.gif" width="539" height="30"></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td>���塢���ʤ��Υ��ݡ��Ȥ򤵤���ĺ���ޤ���°����������Ȥ�����ĺ���ޤ������ΰ������餴��˾�Υ�������Ȥ����Ӥ���������̾���򥯥�å����ޤ��ȡ�����������Ȥη��򡦼��ʾҲ�ɽ������ޤ��Τǡ����һ��ͤˤʤ��äƲ�������</td>
              </tr>
              <tr> 
                <td align="center">��</td>
              </tr>
              <form name="form1" action="mk_kregi3_check.php">
                <tr> 
                  <td align="center"> 
                    <table width="553" border="0" cellspacing="0" cellpadding="0">
                      <? agent_select($reg->agent_id, 10, "$top/mk/agent/") ?>
                      <tr> 
                        <td bgcolor="#FFFFFF">&nbsp; </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr> 
                  <td><img src="../../images/common/spacer.gif" width="10" height="30"></td>
                </tr>
                <tr> 
                  <td align="center"> 
                    <input type="image" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('next','','../../images/common/next_on.gif',1)" src="../../images/common/next.gif" alt="����" name="next" width="120" height="32" border="0">
                    &nbsp; <a href="mk_kregi2.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('back','','../../images/common/pback_on.gif',1)"><img src="../../images/common/pback.gif" alt="���" name="back" width="120" height="32" border="0"></a>&nbsp; 
                    <a href="javascript:document.form1.reset();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('clear','','../../images/common/clear_on.gif',1)"><img src="../../images/common/clear.gif" alt="���ꥢ" name="clear" width="120" height="32" border="0"></a></td>
                </tr>
              </form>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
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
