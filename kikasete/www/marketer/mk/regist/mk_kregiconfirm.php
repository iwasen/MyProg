<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/mk_regist.php");
include("$inc/mk_header.php");

// ���å�������
session_start();
if (!isset($_SESSION['ss_regist']))
	redirect('mk_kregi1.php');
$reg = &$_SESSION['ss_regist'];
?>
<? marketer_header('��Ͽ���Ƥγ�ǧ', PG_REGIST) ?>

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
            <table width="578" border="0" cellpadding="0" cellspacing="0">
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
              ���� 
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
              </tr>
              <tr> 
                <td>�ʲ������Ƥ���Ͽ���ޤ���������Ǥ�����<br>
                  �ʽ����ϡ�����Ͽ��My�ڡ��������Ԥ����Ȥ��Ǥ��ޤ����� </td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="10"></td>
              </tr>
              <tr> 
                <td> 
                  <form method="post" action="mk_kregicode.php">
                    <table width="553" border="0" cellpadding="0" cellspacing="0">
                      <tr> 
                        <td bgcolor="#dadada"> 
                          <table width="553" border="0" cellspacing="1" cellpadding="5" class="nor_text">
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">��</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=htmlspecialchars($reg->name1)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">̾</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=htmlspecialchars($reg->name2)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">����</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=htmlspecialchars($reg->name1_kana)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">�ᥤ</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=htmlspecialchars($reg->name2_kana)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">��ǯ����</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=$reg->birthday_y?>
                                ǯ 
                                <?=$reg->birthday_m?>
                                �� 
                                <?=$reg->birthday_d?>
                                ��</td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">����</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=decode_sex($reg->sex)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">�᡼�륢�ɥ쥹</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=htmlspecialchars($reg->mail_addr)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">�ѥ����</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=htmlspecialchars($reg->password)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">����</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=decode_shokugyou($reg->shokugyou_cd)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">�ȼ�</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=decode_gyoushu($reg->gyoushu_cd)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">����</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=decode_shokushu($reg->shokushu_cd)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0" colspan="2">��̳�����</td>
                            </tr>
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
                        <td><img src="../../images/common/spacer.gif" width="10" height="10" /></td>
                      </tr>
                      <tr> 
                        <td align="center"> 
                          <table border="0" cellspacing="0" cellpadding="0">
                            <tr> 
                              <td> 
                                
<input name="image2" type="image" src="../../images/common/entry.gif" alt="��Ͽ" width="118" height="32">
                              </td>
                              <td><img src="../../images/common/spacer.gif" width="20" height="10"></td>
                              <td> 
<a href="mk_kregi1.php"><img name="image3" src="../../images/common/shusei.gif" alt="������" width="118" height="31"></a>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </form>
                </td>
              </tr>
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
