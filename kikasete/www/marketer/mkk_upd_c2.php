<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/mk_regist.php");
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// ���å�������
if (!isset($_SESSION['ss_regist']))
	redirect('mkk_upd_c.php');
$reg = &$_SESSION['ss_regist'];

// �ơ��֥����¸
$reg->update_db();

// ���å�����ѿ����
unset($_SESSION['ss_regist']);
session_unregister('ss_regist');
?>
<? marketer_header('��Ͽ����ι���', PG_NULL) ?>

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

<? marketer_menu(M_REGIST); ?>

</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10" /> 
<td valign="top"> 
            <table width="553" border="0" cellpadding="0" cellspacing="0">
              <tr> 
                <td width="553"><img src="images/common_my/title_07.gif" alt="MY SETTING" width="553" height="25" /></td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="10"></td>
              </tr>
              <tr> 
                <td><img src="images/mkk_update/title_01.gif" alt="��Ͽ����ι���" width="130" height="28"> 
                </td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="20" /></td>
              </tr>
              <tr> 
                <td><img src="images/mkk_update/bt_02.gif" alt="��Ⱦ���ι��� " name="Image54" width="171" height="31" border="0"></td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="5" /></td>
              </tr>
              <tr> 
<td>

<table width="553" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td bgcolor="#dadada"> 
                          <table width="553" border="0" cellspacing="1" cellpadding="5" class="nor_text">

                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">��̳��̾</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=htmlspecialchars($reg->kinmu_name)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0" height="22">����̾</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF" height="22"> 
                                <?=htmlspecialchars($reg->kinmu_unit)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">��̾</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=htmlspecialchars($reg->kinmu_post)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">��̳��͹���ֹ�</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=$reg->kinmu_zip1?>
                                - 
                                <?=$reg->kinmu_zip2?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">��̳�轻��(��ƻ�ܸ�)</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=decode_area($reg->kinmu_area)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">��̳�轻��(�Զ�)</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=htmlspecialchars($reg->kinmu_addr1)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">��̳�轻��(Į¼������)</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=htmlspecialchars($reg->kinmu_addr2)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">��̳�轻��(�ӥ�̾�ʤ�)</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=htmlspecialchars($reg->kinmu_addr3)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">��̳�������ֹ�</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=format_tel($reg->kinmu_tel1, $reg->kinmu_tel2, $reg->kinmu_tel3)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">��̳��FAX�ֹ�</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=format_tel($reg->kinmu_fax1, $reg->kinmu_fax2, $reg->kinmu_fax3)?>
                              </td>
                            </tr>
                          </table>
            </td>
          </tr>
          <tr> 
            <td><img src="images/common/spacer.gif" width="10" height="10" /></td>
          </tr>
          <tr> 
            <td align="center"> 
�� <a href="mkk_upd_b.php">���ܾ��󹹿��ڡ�����</a>�͡��� <a href="mkk_index.php">Top�����</a> ��
            </td>
          </tr>
        </table>

				</td>
              </tr>
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
