<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:�����}�K���Q�Ɖ��
'******************************************************

SetHeaderTitle "�����}�K���", TC_HAKKOUSHA
Response.CacheControl = "no-cache"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="admin.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_disp(id) {
  var win;
  win = window.open("melmag_disp.asp?id=" + id, "melmag_disp");
  win.focus();
}
function OnClick_mel_id(id) {
  var win;
  win = window.open("mel_info.asp?id=" + id, "mel_info", "resizable=yes,scrollbars=yes,width=740");
  win.focus();
}
//-->
</SCRIPT>
</head>
<body>

<%
Dim DB, RS, SQL, mag_id

mag_id = Request.QueryString("id")
If IsEmpty(mag_id) Then
  Writeln "�w�肵�������}�K�h�c�̃f�[�^���݂���܂���B"
Else
  Set DB = OpenDB()
  Set RS = Server.CreateObject("ADODB.RecordSet")
  SQL = "SELECT *" & vbCrLf & _
        "FROM (T_MELMAG LEFT OUTER JOIN T_KEISEN ON MG_keisen_id=KE_keisen_id) LEFT OUTER JOIN T_MELMEL ON MG_mel_id=MM_mel_id" & vbCrLf & _
        "WHERE MG_mag_id='" & mag_id & "'"
  RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
%>
<center>
  <table border=0 cellspacing=0 cellpadding=0 width='700'>
    <tr>
      <td>�������}�K�o�^���</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m1" width="22%">�����}�KID</td>
            <td class="n1"><%=RS("MG_mag_id")%></td>
          </tr>
          <tr>
            <td class="m1">�����}�K��</td>
            <td class="n1"><%=ReplaceTag(RS("MG_m_name"))%>�@<input type="button" value="�\��" onClick="OnClick_disp('<%=RS("MG_mag_id")%>')"></td>
          </tr>
          <tr>
            <td class="m1">���s�p�x</td>
            <td class="n1"><%=ReplaceTag(RS("MG_hindo"))%></td>
          </tr>
          <tr>
            <td class="m1">�\�����s����</td>
            <td class="n1"><%=NumFormat(RS("MG_busuu"))%></td>
          </tr>
          <tr>
            <td class="m1">�L���r��</td>
						<td class="n1"><font class="np"><%=ReplaceTag(ZenkakuRight(RS("KE_start_line"), 35))%></font></td>
          </tr>
          <tr>
            <td class="m1">�P�s�̕�����</td>
            <td class="n1"><%=RS("MG_moji_suu")%></td>
          </tr>
          <tr>
            <td class="m1">�����}�K�Љ</td>
            <td class="n1"><textarea name="shoukai_text" cols=60 rows=5 readonly><%=RS("MG_shoukai_text")%></textarea></td>
          </tr>
          <tr>
            <td class="m1">�w�Ǘp�t�q�k</td>
            <td class="n1">
<%
If Len(RS("MG_koudoku_url")) > 7 Then
	Writeln "<a href='" & RS("MG_koudoku_url") & "' target='koudoku'>" & ReplaceTag(RS("MG_koudoku_url")) & "</a>"
Else
	Writeln ReplaceTag(RS("MG_koudoku_url"))
End If
%>
            </td>
          </tr>
          <tr>
            <td class="m1">�ڍא����p�t�q�k</td>
            <td class="n1">
<%
If Len(RS("MG_koudoku_url")) > 7 Then
	Writeln "<a href='" & RS("MG_shousai_url") & "' target='koudoku'>" & ReplaceTag(RS("MG_shousai_url")) & "</a>"
Else
	Writeln ReplaceTag(RS("MG_shousai_url"))
End If
%>
            </td>
          </tr>
          <tr>
            <td class="m1">�ǎҌ����Љ�</td>
            <td class="n1">
<%
If RS("MG_shoukai_flag1") Then
	Response.Write "�E�f�ڂ���]����i���s�Ґݒ�j"
End If
Response.Write "�@"
If RS("MG_shoukai_flag2") Then
	Response.Write "�E�f�ڂ���i�����ǐݒ�j"
End If
%>
            </td>
          </tr>
          <tr>
            <td class="m1">�߂��ς�ID</td>
            <td class="n1"><%=RS("MG_melonpan_id")%></td>
          </tr>
          <tr>
            <td class="m1">���s�V�X�e��</td>
            <td class="n1">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n1c" width=130><input type="checkbox" name="mms_melonpan"<%=Checked(RS("MG_mms_melonpan"))%> onclick="return false">�߂��ς�</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_mag"<%=Checked(RS("MG_mms_mag"))%> onclick="return false">�܂��܂�</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_pubz"<%=Checked(RS("MG_mms_pubz"))%> onclick="return false">Pubzine</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_melma"<%=Checked(RS("MG_mms_melma"))%> onclick="return false">melma!</td>
              </tr>
              <tr>
              <td class="n1c" width=130><input type="checkbox" name="mms_macky"<%=Checked(RS("MG_mms_macky"))%> onclick="return false">Macky</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_kapu"<%=Checked(RS("MG_mms_kapu"))%> onclick="return false">�J�v���C�g</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_emag"<%=Checked(RS("MG_mms_emag"))%> onclick="return false">E-MAGAZINE</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_tengoku"<%=Checked(RS("MG_mms_tengoku"))%> onclick="return false">�����}�K�V��</td>
              </tr>
              <tr>
              <td class="n1c" width=130><input type="checkbox" name="mms_tiara"<%=Checked(RS("MG_mms_tiara"))%> onclick="return false">�e�B�A���I�����C��</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_merubox"<%=Checked(RS("MG_mms_merubox"))%> onclick="return false">MERU-BOX</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_dokuji"<%=Checked(RS("MG_mms_dokuji"))%> onclick="return false">�Ǝ��z�M</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_other"<%=Checked(RS("MG_mms_other"))%> onclick="return false">���̑�</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m1">�����}�K�J�e�S��</td>
            <td class="n1">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_int"<%=Checked(RS("MG_cat_int"))%> onclick="return false">�C���^�[�l�b�g</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_pcc"<%=Checked(RS("MG_cat_pcc"))%> onclick="return false">�p�\�R��/�R���s���[�^�S��</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_soft"<%=Checked(RS("MG_cat_soft"))%> onclick="return false">�\�t�g�E�F�A/�v���O���~���O</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_mvl"<%=Checked(RS("MG_cat_mvl"))%> onclick="return false">�g��/���o�C��</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_bns"<%=Checked(RS("MG_cat_bns"))%> onclick="return false">�r�W�l�X�S��</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_seiji"<%=Checked(RS("MG_cat_seiji"))%> onclick="return false">����/�o��</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_kabu"<%=Checked(RS("MG_cat_kabu"))%> onclick="return false">����/����</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_fsn"<%=Checked(RS("MG_cat_fsn"))%> onclick="return false">�t�@�b�V����/���e</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_lif"<%=Checked(RS("MG_cat_lif"))%> onclick="return false">��炵/���L</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_shumi"<%=Checked(RS("MG_cat_shumi"))%> onclick="return false">�</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_res"<%=Checked(RS("MG_cat_res"))%> onclick="return false">�O����/���X�g����</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_tvl"<%=Checked(RS("MG_cat_tvl"))%> onclick="return false">���s/�n��</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_spt"<%=Checked(RS("MG_cat_spt"))%> onclick="return false">�X�|�[�c/���W���[</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_gamble"<%=Checked(RS("MG_cat_gamble"))%> onclick="return false">�M�����u��</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_hlt"<%=Checked(RS("MG_cat_hlt"))%> onclick="return false">���N/���/�玙</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_art"<%=Checked(RS("MG_cat_art"))%> onclick="return false">�A�[�g/���|</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_shp"<%=Checked(RS("MG_cat_shp"))%> onclick="return false">�V���b�s���O</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_ent"<%=Checked(RS("MG_cat_ent"))%> onclick="return false">�G���^�[�e�C�����g</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_game"<%=Checked(RS("MG_cat_game"))%> onclick="return false">�Q�[��/�N�C�Y</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_movie"<%=Checked(RS("MG_cat_movie"))%> onclick="return false">�f��/���y/�|�\</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_uranai"<%=Checked(RS("MG_cat_uranai"))%> onclick="return false">�肢</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_std"<%=Checked(RS("MG_cat_std"))%> onclick="return false">�w�K/����</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_shikaku"<%=Checked(RS("MG_cat_shikaku"))%> onclick="return false">���i</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_gogaku"<%=Checked(RS("MG_cat_gogaku"))%> onclick="return false">��w</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_nws"<%=Checked(RS("MG_cat_nws"))%> onclick="return false">�j���[�X</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_zatsu"<%=Checked(RS("MG_cat_zatsu"))%> onclick="return false">�G�w</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_kenshou"<%=Checked(RS("MG_cat_kenshou"))%> onclick="return false">����/�v���[���g</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_homepage"<%=Checked(RS("MG_cat_homepage"))%> onclick="return false">�z�[���y�[�W�Љ�</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_hitokoto"<%=Checked(RS("MG_cat_hitokoto"))%> onclick="return false">����ЂƂ��ƌn</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_other"<%=Checked(RS("MG_cat_other"))%> onclick="return false">���̑�</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m1">�ǎґw�i�N��j</td>
            <td class="n1">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n1c" width=90><input type="checkbox" name="age_10m"<%=Checked(RS("MG_age_10m"))%> onclick="return false">10��j</td>
              <td class="n1c" width=90><input type="checkbox" name="age_20m"<%=Checked(RS("MG_age_20m"))%> onclick="return false">20��j</td>
              <td class="n1c" width=90><input type="checkbox" name="age_30m"<%=Checked(RS("MG_age_30m"))%> onclick="return false">30��j</td>
              <td class="n1c" width=90><input type="checkbox" name="age_40m"<%=Checked(RS("MG_age_40m"))%> onclick="return false">40��j</td>
              <td class="n1c" width=90><input type="checkbox" name="age_50m"<%=Checked(RS("MG_age_50m"))%> onclick="return false">50��ȏ�j</td>
              </tr>
              <tr>
              <td class="n1c" width=90><input type="checkbox" name="age_10f"<%=Checked(RS("MG_age_10f"))%> onclick="return false">10�㏗</td>
              <td class="n1c" width=90><input type="checkbox" name="age_20f"<%=Checked(RS("MG_age_20f"))%> onclick="return false">20�㏗</td>
              <td class="n1c" width=90><input type="checkbox" name="age_30f"<%=Checked(RS("MG_age_30f"))%> onclick="return false">30�㏗</td>
              <td class="n1c" width=90><input type="checkbox" name="age_40f"<%=Checked(RS("MG_age_40f"))%> onclick="return false">40�㏗</td>
              <td class="n1c" width=90><input type="checkbox" name="age_50f"<%=Checked(RS("MG_age_50f"))%> onclick="return false">50��ȏ㏗</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m1">�ǎґw�i�E�Ɓj</td>
            <td class="n1">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n1c" width=100><input type="checkbox" name="shk_stu"<%=Checked(RS("MG_shk_stu"))%> onclick="return false">�w��</td>
              <td class="n1c" width=100><input type="checkbox" name="shk_enp"<%=Checked(RS("MG_shk_enp"))%> onclick="return false">�Љ�l</td>
              <td class="n1c" width=100><input type="checkbox" name="shk_syf"<%=Checked(RS("MG_shk_syf"))%> onclick="return false">��w</td>
              <td class="n1c" width=100><input type="checkbox" name="shk_other"<%=Checked(RS("MG_shk_other"))%> onclick="return false">���̑�</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m1">�ǎґw�i�G���A�j</td>
            <td class="n1">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n1c" width=100><input type="checkbox" name="are_ht"<%=Checked(RS("MG_are_ht"))%> onclick="return false">�k�C��/���k</td>
              <td class="n1c" width=100><input type="checkbox" name="are_kt"<%=Checked(RS("MG_are_kt"))%> onclick="return false">�֓�</td>
              <td class="n1c" width=100><input type="checkbox" name="are_cb"<%=Checked(RS("MG_are_cb"))%> onclick="return false">����</td>
              <td class="n1c" width=100><input type="checkbox" name="are_kk"<%=Checked(RS("MG_are_kk"))%> onclick="return false">�ߋE</td>
              </tr>
              <tr>
              <td class="n1c" width=100><input type="checkbox" name="are_cs"<%=Checked(RS("MG_are_cs"))%> onclick="return false">����/�l��</td>
              <td class="n1c" width=100><input type="checkbox" name="are_ks"<%=Checked(RS("MG_are_ks"))%> onclick="return false">��B</td>
              <td class="n1c" width=100><input type="checkbox" name="are_fr"<%=Checked(RS("MG_are_fr"))%> onclick="return false">�C�O</td>
              <td class="n1c" width=100><input type="checkbox" name="are_zk"<%=Checked(RS("MG_are_zk"))%> onclick="return false">�S��</td>
              </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td>�����̑��̏��</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m1" width="22%">���s��</td>
            <td class="n1"><a href="Javascript:OnClick_mel_id('<%=RS("MG_mel_id")%>')"><%=RS("MG_mel_id")%></a>&nbsp;<%=ReplaceTag(RS("MM_h_name"))%></td>
          </tr>
          <tr>
            <td class="m1">�T���s�W��</td>
            <td class="n1"><%=RS("MG_shuu_keisuu")%></td>
          </tr>
          <tr>
            <td class="m1">�܂��܂��W��</td>
						<td class="n1"><%=FormatNumber(NVL(RS("MG_magmag_keisuu"), 0) * 100, 1)%>%</td>
          </tr>
          <tr>
            <td class="m1">�ŐV���s����</td>
						<td class="n1"><%=NumFormat(RS("MG_new_busuu"))%></td>
          </tr>
          <tr>
            <td class="m1">���</td>
            <td class="n1">
<%
Select Case RS("MG_status")
Case 0
	Response.Write "������"
Case 1
	Response.Write "�x�~��"
Case 2
	Response.Write "�p��"
Case 3
	Response.Write "������~�i�����ǔ��f�E�����̋��U�\���j"
Case 4
	Response.Write "������~�i�����ǔ��f�E�L������������j"
Case 5
	Response.Write "������~�i�����ǔ��f�E���e����낵���Ȃ��j"
Case 8
	Response.Write "���s�ҋx�"
Case 9
	Response.Write "���s�ґމ�"
End Select
%>
            </td>
          </tr>
          <tr>
            <td class="m1">�o�^��</td>
            <td class="n1"><%=TimeFormat(RS("MG_regist_date"))%></td>
          </tr>
          <tr>
            <td class="m1">�ŏI�X�V��</td>
            <td class="n1"><%=TimeFormat(RS("MG_last_update"))%></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</center>

<%
  RS.Close
End If
%>

</body>
</html>
