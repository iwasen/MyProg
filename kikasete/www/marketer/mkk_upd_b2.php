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
	redirect('mkk_upd_b.php');
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
                <td><img src="images/mkk_update/bt_01.gif" alt="���ܾ���ι��� " name="Image54" width="171" height="31" border="0"></td>
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
					<td align="center">
						�� <a href="mkk_upd_c.php">��Ⱦ��󹹿��ڡ�����</a> �͡��� <a href="mkk_index.php">Top�����</a>  ��
					</td>
				</tr>
              </table>
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
