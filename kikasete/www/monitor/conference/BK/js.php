<?
$top = './..';
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_header.php");
?>

<?monitor_menu()?>
<!-- ����ƥ�����ΤΥ������Ϥ��δ֤�ɽ�� -->
    <TD VALIGN="TOP" ALIGN="CENTER" WIDTH="10"><IMG SRC="<?=$img?>/spacer.gif" WIDTH="10" HEIGHT="1"></TD>
    <TD VALIGN="TOP" ALIGN="CENTER">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" background="<?=$img?>/csv_ttlbg.gif"><img src="<?=$img?>/csv_ttl.gif" alt="������٤��ļ��Ȥϡ�" width="243" height="39"></td>
        </tr>
        <tr>
          <td colspan="2"><img src="<?=$img?>/spacer.gif" width="1" height="1"></td>
        </tr>
        <tr>
          <td colspan="2" bgcolor="#C0E2FA"><img src="<?=$img?>/spacer.gif" width="1" height="1"></td>
        </tr>
        <tr>
          <td width="175" rowspan="3" bgcolor="#E8F6FF"><img src="<?=$img?>/csv_photo.gif" width="175" height="188"></td>
          <td bgcolor="#E8F6FF"><img src="<?=$img?>/csv_copy.gif" alt="��������˷礫���ʤ��֥���ӥˡס���������֥��ȡ��衼���ɡ��פ�֥��㥹�������Υ����ѡ����������ޤ��ֿ����ʡ�ã��" width="397" height="59"></td>
        </tr>
        <tr>
          <td height="1" bgcolor="#FFFFFF"><img src="<?=$img?>/spacer.gif" width="1" height="1"></td>
        </tr>
        <tr>
          <td bgcolor="#E8F6FF" class="fs12_lh130"><p>��������������̩�夷����Ź�俷���ʤ�ȯ�����ä��򡢥�˥�������ѥ���åפ����ꥵ�����ѡ��ȥʡ��ο͡����ꥢ�륿����Ǥ�����٤ꤷ�Ƥ����꤬�֤�����٤��ļ��פǤ���<br>
              <br>
              ���ä��Ƥ���ꥵ�����ѡ��ȥʡ������ˤϼ���ݥ���ȤȤ��ƣ��������ȯ���ǣ������ݥ���ȤȤ��ʤ��ۡ�<br>
              <br>
              ������⤼�ҥꥵ�����ѡ��ȥʡ��罸�Τ��Τ餻���褿�黲�ä��Ƥ�����������<br>
            </p>
          </td>
        </tr>
        <tr>
          <td height="3" colspan="2" background="<?=$img?>/csv_ttl_under.gif"><img src="<?=$img?>/spacer.gif" width="1" height="3"></td>
        </tr>
      </table>
      <br>
      <br>
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr align="left" valign="bottom">
          <td colspan="3"><a href="cvs.php" target="_blanck"><img src="<?=$img?>/tab_cvs_off.gif" alt="����ӥ˲�ļ�" width="160" height="31" hspace="1" border="0"><a href="new_product.php"><img src="<?=$img?>/tab_newproduct_off.gif" alt="�����ʲ�ļ�" width="160" height="31" hspace="1" border="0"></a><a href="iy.php"><img src="<?=$img?>/tab_iy_off.gif" alt="���ȡ��衼���ɡ���ļ�" width="160" height="31" hspace="1" border="0"></a><a href="js.php"><img src="<?=$img?>/tab_js_on.gif" alt="���㥹����ļ�" width="160" height="30" hspace="1" border="0"></a></td>
        </tr>
        <tr>
          <td width="230" align="center" valign="top" bgcolor="#F5FAF6" class="kaigishitsu_left">
            <table width="225"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="fs12_lh130"><br>
                  �֥���ӥ˲�ļ��פǤϡ�����ӥ��繥���ʥ��С�������Ź�Ǹ��Ĥ��������ʤ䤪��������ξ��ʤʤɤˤĤ��ƥ磻�磻�ä��Ƥ��ޤ��� ���ɤ�ʾ��ʤ�ή�ԤäƤ���Τ����ɤ���ɤ����ʤ�����Τ��ʤɤ� ������ʬ����ޤ���<br>
                  <br>
                  <a href="js_lg.php" target="_blanck"><img src="<?=$img?>/csv_button_conf.gif" alt="��ļ��������Ƥߤ�" width="116" height="18" border="0"></a><br>
                  <br></td>
              </tr>
              <tr>
                <td height="1" bgcolor="#2E7435"><img src="<?=$img?>/spacer.gif" width="1" height="1"></td>
              </tr>
              <tr>
                <td><br>
                  ������٤��ļ��˻��ä���ˤϡ�<br>
                  �ꥵ�����ѡ��ȥʡ��ؤΤ��������ߤ�<br>
                  ɬ�פǤ���<br>
                  <br>
                  <a href="rp.php"><img src="<?=$img?>/csv_button_resear.gif" alt="�ꥵ�����ѡ��ȥʡ��Ȥ�" width="116" height="18" border="0"></a>
                </td>
              </tr>
            </table>
          </td>
          <td width="1" background="<?=$img?>/csv_bar.gif" bgcolor="#F5FAF6" class="kaigishitsu_center"><img src="<?=$img?>/spacer.gif" width="1" height="1"></td>
          <td align="center" bgcolor="#F5FAF6" class="kaigishitsu_right"><br>
            <table width="98%"  border="0" cellspacing="1" cellpadding="3">
              <tr>
                <td colspan="5" bgcolor="#46834C"><img src="<?=$img?>/csv_member.gif" alt="���üԥꥹ��" width="79" height="15" hspace="3" vspace="0"></td>
              </tr>
              <tr bgcolor="#AFE8B4">
                <td align="center" nowrap>�ϥ�ɥ�̾</td>
                <td align="center" nowrap>����</td>
                <td align="center" nowrap>ǯ��</td>
                <td align="center" bgcolor="#AFE8B4">�ｻ�ϰ� </td>
                <td align="center">�褯�Ԥ����㥹��Ź��</td>
              </tr>
              <tr valign="top" bgcolor="#EFEFEF">
                <td valign="top" nowrap>�ͤ�����������</td>
                <td valign="top" nowrap>����</td>
                <td valign="top" nowrap>20���Ⱦ</td>
                <td valign="top" nowrap>�����</td>
                <td>�����������Ź</td>
              </tr>
              <tr valign="top" bgcolor="#EFEFEF">
                <td valign="top" nowrap>���ä�</td>
                <td valign="top" nowrap>����</td>
                <td valign="top" nowrap>40��</td>
                <td valign="top" nowrap>��ʬ��</td>
                <td>���ƥ���ʬŹ</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>�Ѥ�����</td>
                <td valign="top" nowrap>����</td>
                <td valign="top" nowrap>30��Ⱦ</td>
                <td valign="top" nowrap>���θ�</td>
                <td valign="top">���㥹������Ź</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>������</td>
                <td valign="top" nowrap>����</td>
                <td valign="top" nowrap>39��</td>
                <td valign="top" nowrap>��̸�</td>
                <td valign="top">���㥹��ƣ��Ź</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>�䤨�����</td>
                <td valign="top" nowrap>����</td>
                <td valign="top" nowrap>47��</td>
                <td valign="top" nowrap>��̸�</td>
                <td valign="top">���㥹�����Ź����ܥ��ƥ�</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>�դ���</td>
                <td valign="top" nowrap>����</td>
                <td valign="top" nowrap>20���Ⱦ</td>
                <td valign="top" nowrap>���ո�</td>
                <td valign="top">����̯ŵ���ƥ�</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>���������</td>
                <td valign="top" nowrap>����</td>
                <td valign="top" nowrap>43��</td>
                <td valign="top" nowrap>���ո�</td>
                <td valign="top">���㥹������Ź</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>���祳����</td>
                <td valign="top" nowrap>����</td>
                <td valign="top" nowrap>30��Ⱦ��</td>
                <td valign="top" nowrap>�����</td>
                <td valign="top">���ⲬSATY</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>�뤦</td>
                <td valign="top" nowrap>����</td>
                <td valign="top" nowrap>30��Ⱦ��</td>
                <td valign="top" nowrap>�����</td>
                <td valign="top">���ƥ����ڡʥޥ�������ڡ�,���ӥ��ƥ�,���㥹����ð</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>�����</td>
                <td valign="top" nowrap>����</td>
                <td valign="top" nowrap>28��</td>
                <td valign="top" nowrap>���ո�</td>
                <td valign="top">���㥹���Ź</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>�Ĥ�</td>
                <td valign="top" nowrap>����</td>
                <td valign="top" nowrap>30��Ⱦ��</td>
                <td valign="top" nowrap>ʼ�˸�</td>
                <td valign="top">���ƥ��������ॹ��Ź</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>�ޤ�ޥ�</td>
                <td valign="top" nowrap>����</td>
                <td valign="top" nowrap>28��</td>
                <td valign="top" nowrap>�����</td>
                <td valign="top">���㥹��ƣ���Ź,Ȭ�����ƥ�</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>���ޤ���</td>
                <td valign="top" nowrap>����</td>
                <td valign="top" nowrap>30��</td>
                <td valign="top" nowrap>��̸�</td>
                <td valign="top">������Ϳ��</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>���ȥ졼��</td>
                <td valign="top" nowrap>����</td>
                <td valign="top" nowrap>22��</td>
                <td valign="top" nowrap>ʡ����</td>
                <td valign="top">���ƥ������Ź</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>�꤯�ޥ�</td>
                <td valign="top" nowrap>����</td>
                <td valign="top" nowrap>27��</td>
                <td valign="top" nowrap>���θ�</td>
                <td valign="top">���㥹������</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>jet�ޥ�</td>
                <td valign="top" nowrap>����</td>
                <td valign="top" nowrap>30����Ⱦ</td>
                <td valign="top" nowrap>�����</td>
                <td valign="top">�������󤯤������åԥ󥰥��󥿡�Ź</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>�ᤤ����</td>
                <td valign="top" nowrap>����</td>
                <td valign="top" nowrap>28��</td>
                <td valign="top" nowrap>�����</td>
                <td valign="top">���ƥ���ɴ�����Ź,���㥹�����ϸ�Ź</td>
              </tr>
            </table>
            <br>
          </td>
        </tr>
      </table>
    </TD>
<!-- ����ƥ�����ΤΥ������Ϥ��δ֤�ɽ�� -->
			</TD>
		</TR>
	</TBODY>
</TABLE>
</BODY>
</HTML>
