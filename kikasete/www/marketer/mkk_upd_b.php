<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/select.php");
$inc = "$top/inc";
include("$inc/mk_regist.php");
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// ���å�������
if (!isset($_SESSION['ss_regist']) || !$cont) {
	$_SESSION['ss_regist'] = new marketer_regist;
	$reg = &$_SESSION['ss_regist'];
	$reg->read_db($_SESSION['ss_marketer_id']);
} else
	$reg = &$_SESSION['ss_regist'];
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
<td align="center">
						<form method="post" action="mkk_upd_b_check.php">
<table width="539" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td bgcolor="#dadada"><table width="539"  border="0" cellspacing="1" cellpadding="5">
                      <tr class="description">
                        <td width="180" height="30" bgcolor="#e5eaf0">��̾���ʴ�����</td>
                        <td height="30" bgcolor="#FFFFFF"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="40" class="description">��</td>
                              <td><input name="name1" type="text" size="20" maxlength="25" <?=value($reg->name1)?>></td>
                              <td width="40" class="description">̾</td>
                              <td><input name="name2" type="text" size="20" maxlength="25" <?=value($reg->name2)?>></td>
                            </tr>
                        </table></td>
                      </tr>
                      <tr class="description">
                        <td width="180" height="30" bgcolor="#e5eaf0">��̾���ʥեꥬ�ʡ�</td>
                        <td bgcolor="#FFFFFF"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="40" class="description">����</td>
                              <td><input name="name1_kana" type="text" size="20" maxlength="25" <?=value($reg->name1_kana)?>></td>
                              <td width="40" class="description">�ᥤ</td>
                              <td><input name="name2_kana" type="text" size="20" maxlength="25" <?=value($reg->name2_kana)?>></td>
                            </tr>
                        </table></td>
                      </tr>
                      <tr class="description">
                        <td width="180" height="30" bgcolor="#e5eaf0">��ǯ����</td>
                        <td bgcolor="#FFFFFF"><img src="images/common/spacer.gif" width="35" height="10">
                            <input name="birthday_y" type="text" size="6" maxlength="4" <?=value($reg->birthday_y)?>>
      ǯ��
      <input name="birthday_m" type="text" size="3" maxlength="2" <?=value($reg->birthday_m)?>>
      �
      <input name="birthday_d" type="text" size="3" maxlength="2" <?=value($reg->birthday_d)?>>
      ������Ⱦ�ѿ�����</td>
                      </tr>
                      <tr class="description">
                        <td width="180" height="30" bgcolor="#e5eaf0">����</td>
                        <td bgcolor="#FFFFFF"><img src="images/common/spacer.gif" width="35" height="10">
                            <input name="sex" type="radio" <?=value_checked('1', $reg->sex)?>>
      ������
      <input name="sex" type="radio" <?=value_checked('2', $reg->sex)?>>
      ����</td>
                      </tr>
                      <tr class="description">
                        <td width="180" height="30" bgcolor="#e5eaf0">�᡼�륢�ɥ쥹</td>
                        <td bgcolor="#FFFFFF"><img src="images/common/spacer.gif" width="35" height="10" maxlength="100">
                            <input name="mail_addr" type="text" size="35"<?=value($reg->mail_addr)?>></td>
                      </tr>
                      <tr class="description">
                        <td width="180" height="30" bgcolor="#e5eaf0">�ѥ����</td>
                        <td bgcolor="#FFFFFF"><img src="images/common/spacer.gif" width="35" height="10">
                            <input name="password" type="password" size="10" <?=value($reg->password)?>>
      ����Ⱦ�ѱѿ���6��20ʸ���ޤǡ�</td>
                      </tr>
                      <tr class="description">
                        <td width="180" height="30" bgcolor="#e5eaf0">�ѥ���ɳ�ǧ</td>
                        <td bgcolor="#FFFFFF"><img src="images/common/spacer.gif" width="35" height="10">
                            <input name="password2" type="password" size="10" <?=value($reg->password2)?>>
      ����Ⱦ�ѱѿ���6��20ʸ���ޤǡ�</td>
                      </tr>
                      <tr class="description">
                        <td width="180" height="30" bgcolor="#e5eaf0">����</td>
                        <td bgcolor="#FFFFFF"><img src="images/common/spacer.gif" width="35" height="10">
                            <select name="shokugyou_cd"><? select_shokugyou('���򤷤Ƥ�������', $reg->shokugyou_cd) ?></select>
                          </td>
                      </tr>
                      <tr class="description">
                        <td width="180" height="30" bgcolor="#e5eaf0">�ȼ�</td>
                        <td bgcolor="#FFFFFF"><img src="images/common/spacer.gif" width="35" height="10">
                            <select name="gyoushu_cd"><? select_gyoushu('���򤷤Ƥ�������', $reg->gyoushu_cd, 2) ?></select>
                            </td>
                      </tr>
                      <tr class="description">
                        <td width="180" height="30" bgcolor="#e5eaf0">����</td>
                        <td bgcolor="#FFFFFF"><img src="images/common/spacer.gif" width="35" height="10">
                            <select name="shokushu_cd"><? select_shokushu('���򤷤Ƥ�������', $reg->shokushu_cd) ?></select>
                            </td>
                      </tr>
                    </table></td>
                  </tr>
			  <tr> 
                <td colspan="2"><img src="images/common/spacer.gif" width="10" height="20"></td>
              </tr>
				  <tr>
				  <td align="center" colspan="2" bgcolor="#ffffff">
				  					<input name="image2" type="image" src="images/common/kousin.gif" alt="����" width="130" height="32">
                          			<a href="mkk_upd_b.php"><img src="images/common/motoback.gif" border="0"></a> 
                        </td>
				  </tr>
                </table>
                						</form>
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
