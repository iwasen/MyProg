<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:�����}�K�}�X�^�C�����
'******************************************************

SetHeaderTitle "�����}�K�}�X�^�C��", TC_MASTER

Dim mag_id

mag_id = Request.QueryString("id")
If IsEmpty(mag_id) Then
  Response.Redirect("index.asp")
End If
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
function OnSubmit_form1() {
  return confirm("�X�V���܂��B��낵���ł����H");
}
function OnClick_sakujo() {
  if (confirm("���̃����}�K���폜���Ă�낵���ł����H")) {
    location.href = "mag_master4.asp?id=<%=mag_id%>";
  }
}
function OnClick_disp(id) {
  var win;
  win = window.open("melmag_disp.asp?id=" + id, "melmag_disp");
  win.focus();
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<%
Dim DB, RS, SQL, status

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT T_MELMAG.*,MM_h_name" & vbCrLf & _
      "FROM T_MELMAG LEFT OUTER JOIN T_MELMEL ON MG_mel_id=MM_mel_id" & vbCrLf & _
      "WHERE MG_mag_id='" & mag_id & "'"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
%>
<center>
<form method="post" action="mag_master3.asp" name="form1" onSubmit="return OnSubmit_form1();">
  <table border=0 cellspacing=0 cellpadding=0 width='700'>
    <tr>
      <td>�������}�K�o�^���</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m3" width="22%">�����}�KID</td>
            <td class="n3"><input type="hidden" name="mag_id" value="<%=RS("MG_mag_id")%>"><%=RS("MG_mag_id")%></td>
          </tr>
          <tr>
            <td class="m3">�����}�K��</td>
            <td class="n3"><input type="text" name="m_name" size="50" value="<%=RS("MG_m_name")%>" maxlength="100">�@<input type="button" value="�\��" onClick="OnClick_disp('<%=RS("MG_mag_id")%>')"></td>
          </tr>
          <tr>
            <td class="m3">���s�p�x</td>
            <td class="n3"><input type="text" name="hindo" size="20" value="<%=RS("MG_hindo")%>" maxlength="30"></td>
          </tr>
          <tr>
            <td class="m3">�\�����s����</td>
            <td class="n3"><input type="text" name="busuu" size="20" value="<%=RS("MG_busuu")%>" maxlength="30"></td>
          </tr>
          <tr>
            <td class="m3">�L���r��</td>
            <td class="n3">
              <select name="keisen_id" class="np">
<%
Dim RS2
SQL = "SELECT KE_keisen_id,KE_start_line FROM T_KEISEN ORDER BY KE_keisen_id"
Set RS2 = DB.Execute(SQL)

Writeln "<option value=''" & Selected(RS("MG_keisen_id"), "") & ">�I�����Ă�������</option>"
Do Until RS2.EOF
  Writeln "<option value='" & RS2("KE_keisen_id") & "'" & Selected(RS("MG_keisen_id"), RS2("KE_keisen_id")) & ">" & ReplaceTag(ZenkakuRight(RS2("KE_start_line"), 35)) & "</option>"
  RS2.MoveNext
Loop
RS2.Close
%>
            </select>
          </tr>
          <tr>
            <td class="m3">�P�s�̕�����</td>
            <td class="n3"><input type="text" name="moji_suu" size="20" value="<%=RS("MG_moji_suu")%>"></td>
          </tr>
          <tr>
            <td class="m3">�����}�K�Љ</td>
            <td class="n3"><textarea name="shoukai_text" cols=60 rows=5><%=RS("MG_shoukai_text")%></textarea></td>
          </tr>
          <tr>
            <td class="m3">�w�Ǘp�t�q�k</td>
            <td class="n3"><input type="text" name="koudoku_url" size="60" value="<%=RS("MG_koudoku_url")%>" maxlength="100">
<%
If Len(RS("MG_koudoku_url")) > 7 Then
  Writeln "<input type='button' value='�Q��' onClick='window.open(document.form1.koudoku_url.value, ""koudoku"")'>"
End If
%>
            </td>
          </tr>
          <tr>
            <td class="m3">�ڍא����p�t�q�k</td>
            <td class="n3"><input type="text" name="shousai_url" size="60" value="<%=RS("MG_shousai_url")%>" maxlength="100">
<%
If Len(RS("MG_shousai_url")) > 7 Then
  Writeln "<input type='button' value='�Q��' onClick='window.open(document.form1.shousai_url.value, ""shousai"")'>"
End If
%>
            </td>
          </tr>
          <tr>
            <td class="m3">�ǎҌ����Љ�</td>
            <td class="n3">
              <input type="checkbox" name="shoukai_flag1" value="1" <%=Checked(RS("MG_shoukai_flag1"))%>>�f�ڂ���]����i���s�Ґݒ�j�@
              <input type="checkbox" name="shoukai_flag2" value="1" <%=Checked(RS("MG_shoukai_flag2"))%>>�f�ڂ���i�����ǐݒ�j
            </td>
          </tr>
          <tr>
            <td class="m3">�߂��ς�ID</td>
            <td class="n3"><input type="text" name="melonpan_id" size="20" value="<%=RS("MG_melonpan_id")%>" maxlength="20"></td>
          </tr>
          <tr>
            <td class="m3">���s�V�X�e��</td>
            <td class="n3">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n3c" width=130><input type="checkbox" name="mms_melonpan" value="1" <%=Checked(RS("MG_mms_melonpan"))%>>�߂��ς�</td>
              <td class="n3c" width=130><input type="checkbox" name="mms_mag" value="1" <%=Checked(RS("MG_mms_mag"))%>>�܂��܂�</td>
              <td class="n3c" width=130><input type="checkbox" name="mms_pubz" value="1" <%=Checked(RS("MG_mms_pubz"))%>>Pubzine</td>
              <td class="n3c" width=130><input type="checkbox" name="mms_melma" value="1" <%=Checked(RS("MG_mms_melma"))%>>melma!</td>
              </tr>
              <tr>
              <td class="n3c" width=130><input type="checkbox" name="mms_macky" value="1" <%=Checked(RS("MG_mms_macky"))%>>Macky</td>
              <td class="n3c" width=130><input type="checkbox" name="mms_kapu" value="1" <%=Checked(RS("MG_mms_kapu"))%>>�J�v���C�g</td>
              <td class="n3c" width=130><input type="checkbox" name="mms_emag" value="1" <%=Checked(RS("MG_mms_emag"))%>>E-MAGAZINE</td>
              <td class="n3c" width=130><input type="checkbox" name="mms_tengoku" value="1" <%=Checked(RS("MG_mms_tengoku"))%>>�����}�K�V��</td>
              </tr>
              <tr>
              <td class="n3c" width=130><input type="checkbox" name="mms_tiara" value="1" <%=Checked(RS("MG_mms_tiara"))%>>�e�B�A���I�����C��</td>
              <td class="n3c" width=130><input type="checkbox" name="mms_merubox" value="1" <%=Checked(RS("MG_mms_merubox"))%>>MERU-BOX</td>
              <td class="n3c" width=130><input type="checkbox" name="mms_dokuji" value="1" <%=Checked(RS("MG_mms_dokuji"))%>>�Ǝ��z�M</td>
              <td class="n3c" width=130><input type="checkbox" name="mms_other" value="1" <%=Checked(RS("MG_mms_other"))%>>���̑�</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m3">�����}�K�J�e�S��</td>
            <td class="n3">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n3c" width=170><input type="checkbox" name="cat_int" value="1" <%=Checked(RS("MG_cat_int"))%>>�C���^�[�l�b�g</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_pcc" value="1" <%=Checked(RS("MG_cat_pcc"))%>>�p�\�R��/�R���s���[�^�S��</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_soft" value="1" <%=Checked(RS("MG_cat_soft"))%>>�\�t�g�E�F�A/�v���O���~���O</td>
              </tr>
              <tr>
              <td class="n3c" width=170><input type="checkbox" name="cat_mvl" value="1" <%=Checked(RS("MG_cat_mvl"))%>>�g��/���o�C��</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_bns" value="1" <%=Checked(RS("MG_cat_bns"))%>>�r�W�l�X�S��</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_seiji" value="1" <%=Checked(RS("MG_cat_seiji"))%>>����/�o��</td>
              </tr>
              <tr>
              <td class="n3c" width=170><input type="checkbox" name="cat_kabu" value="1" <%=Checked(RS("MG_cat_kabu"))%>>����/����</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_fsn" value="1" <%=Checked(RS("MG_cat_fsn"))%>>�t�@�b�V����/���e</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_lif" value="1" <%=Checked(RS("MG_cat_lif"))%>>��炵/���L</td>
              </tr>
              <tr>
              <td class="n3c" width=170><input type="checkbox" name="cat_shumi" value="1" <%=Checked(RS("MG_cat_shumi"))%>>�</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_res" value="1" <%=Checked(RS("MG_cat_res"))%>>�O����/���X�g����</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_tvl" value="1" <%=Checked(RS("MG_cat_tvl"))%>>���s/�n��</td>
              </tr>
              <tr>
              <td class="n3c" width=170><input type="checkbox" name="cat_spt" value="1" <%=Checked(RS("MG_cat_spt"))%>>�X�|�[�c/���W���[</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_gamble" value="1" <%=Checked(RS("MG_cat_gamble"))%>>�M�����u��</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_hlt" value="1" <%=Checked(RS("MG_cat_hlt"))%>>���N/���/�玙</td>
              </tr>
              <tr>
              <td class="n3c" width=170><input type="checkbox" name="cat_art" value="1" <%=Checked(RS("MG_cat_art"))%>>�A�[�g/���|</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_shp" value="1" <%=Checked(RS("MG_cat_shp"))%>>�V���b�s���O</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_ent" value="1" <%=Checked(RS("MG_cat_ent"))%>>�G���^�[�e�C�����g</td>
              </tr>
              <tr>
              <td class="n3c" width=170><input type="checkbox" name="cat_game" value="1" <%=Checked(RS("MG_cat_game"))%>>�Q�[��/�N�C�Y</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_movie" value="1" <%=Checked(RS("MG_cat_movie"))%>>�f��/���y/�|�\</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_uranai" value="1" <%=Checked(RS("MG_cat_uranai"))%>>�肢</td>
              </tr>
              <tr>
              <td class="n3c" width=170><input type="checkbox" name="cat_std" value="1" <%=Checked(RS("MG_cat_std"))%>>�w�K/����</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_shikaku" value="1" <%=Checked(RS("MG_cat_shikaku"))%>>���i</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_gogaku" value="1" <%=Checked(RS("MG_cat_gogaku"))%>>��w</td>
              </tr>
              <tr>
              <td class="n3c" width=170><input type="checkbox" name="cat_nws" value="1" <%=Checked(RS("MG_cat_nws"))%>>�j���[�X</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_zatsu" value="1" <%=Checked(RS("MG_cat_zatsu"))%>>�G�w</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_kenshou" value="1" <%=Checked(RS("MG_cat_kenshou"))%>>����/�v���[���g</td>
              </tr>
              <tr>
              <td class="n3c" width=170><input type="checkbox" name="cat_homepage" value="1" <%=Checked(RS("MG_cat_homepage"))%>>�z�[���y�[�W�Љ�</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_hitokoto" value="1" <%=Checked(RS("MG_cat_hitokoto"))%>>����ЂƂ��ƌn</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_other" value="1" <%=Checked(RS("MG_cat_other"))%>>���̑�</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m3">�ǎґw�i�N��j</td>
            <td class="n3">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n3c" width=90><input type="checkbox" name="age_10m" value="1" <%=Checked(RS("MG_age_10m"))%>>10��j</td>
              <td class="n3c" width=90><input type="checkbox" name="age_20m" value="1" <%=Checked(RS("MG_age_20m"))%>>20��j</td>
              <td class="n3c" width=90><input type="checkbox" name="age_30m" value="1" <%=Checked(RS("MG_age_30m"))%>>30��j</td>
              <td class="n3c" width=90><input type="checkbox" name="age_40m" value="1" <%=Checked(RS("MG_age_40m"))%>>40��j</td>
              <td class="n3c" width=90><input type="checkbox" name="age_50m" value="1" <%=Checked(RS("MG_age_50m"))%>>50��ȏ�j</td>
              </tr>
              <tr>
              <td class="n3c" width=90><input type="checkbox" name="age_10f" value="1" <%=Checked(RS("MG_age_10f"))%>>10�㏗</td>
              <td class="n3c" width=90><input type="checkbox" name="age_20f" value="1" <%=Checked(RS("MG_age_20f"))%>>20�㏗</td>
              <td class="n3c" width=90><input type="checkbox" name="age_30f" value="1" <%=Checked(RS("MG_age_30f"))%>>30�㏗</td>
              <td class="n3c" width=90><input type="checkbox" name="age_40f" value="1" <%=Checked(RS("MG_age_40f"))%>>40�㏗</td>
              <td class="n3c" width=90><input type="checkbox" name="age_50f" value="1" <%=Checked(RS("MG_age_50f"))%>>50��ȏ㏗</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m3">�ǎґw�i�E�Ɓj</td>
            <td class="n3">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n3c" width=100><input type="checkbox" name="shk_stu" value="1" <%=Checked(RS("MG_shk_stu"))%>>�w��</td>
              <td class="n3c" width=100><input type="checkbox" name="shk_enp" value="1" <%=Checked(RS("MG_shk_enp"))%>>�Љ�l</td>
              <td class="n3c" width=100><input type="checkbox" name="shk_syf" value="1" <%=Checked(RS("MG_shk_syf"))%>>��w</td>
              <td class="n3c" width=100><input type="checkbox" name="shk_other" value="1" <%=Checked(RS("MG_shk_other"))%>>���̑�</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m3">�ǎґw�i�G���A�j</td>
            <td class="n3">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n3c" width=100><input type="checkbox" name="are_ht" value="1" <%=Checked(RS("MG_are_ht"))%>>�k�C��/���k</td>
              <td class="n3c" width=100><input type="checkbox" name="are_kt" value="1" <%=Checked(RS("MG_are_kt"))%>>�֓�</td>
              <td class="n3c" width=100><input type="checkbox" name="are_cb" value="1" <%=Checked(RS("MG_are_cb"))%>>����</td>
              <td class="n3c" width=100><input type="checkbox" name="are_kk" value="1" <%=Checked(RS("MG_are_kk"))%>>�ߋE</td>
              </tr>
              <tr>
              <td class="n3c" width=100><input type="checkbox" name="are_cs" value="1" <%=Checked(RS("MG_are_cs"))%>>����/�l��</td>
              <td class="n3c" width=100><input type="checkbox" name="are_ks" value="1" <%=Checked(RS("MG_are_ks"))%>>��B</td>
              <td class="n3c" width=100><input type="checkbox" name="are_fr" value="1" <%=Checked(RS("MG_are_fr"))%>>�C�O</td>
              <td class="n3c" width=100><input type="checkbox" name="are_zk" value="1" <%=Checked(RS("MG_are_zk"))%>>�S��</td>
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
            <td class="m3" width="22%">���s��</td>
            <td class="n3"><a href="mel_master2.asp?id=<%=RS("MG_mel_id")%>"><%=RS("MG_mel_id")%></a>&nbsp;<%=ReplaceTag(RS("MM_h_name"))%></td>
          </tr>
          <tr>
            <td class="m3" width="22%">�T���s�W��</td>
            <td class="n3"><input type="text" name="shuu_keisuu" value="<%=RS("MG_shuu_keisuu")%>"></td>
          </tr>
          <tr>
            <td class="m3">�܂��܂��W��</td>
            <td class="n3"><input type="text" name="magmag_keisuu" value="<%=FormatNumber(NVL(RS("MG_magmag_keisuu"), 0) * 100, 1)%>"> %</td>
          </tr>
          <tr>
            <td class="m3">�ŐV���s����</td>
            <td class="n3"><input type="text" name="new_busuu" value="<%=RS("MG_new_busuu")%>"></td>
          </tr>
          <tr>
            <td class="m3">���</td>
            <td class="n3">
              <select name="status">
                <%status = RS("MG_status")%>
                <option value="0" <%=Selected(status, 0)%>>������</option>
                <option value="1" <%=Selected(status, 1)%>>�x�~��</option>
                <option value="2" <%=Selected(status, 2)%>>�p��</option>
                <option value="3" <%=Selected(status, 3)%>>������~�i�����ǔ��f�E�����̋��U�\���j</option>
                <option value="4" <%=Selected(status, 4)%>>������~�i�����ǔ��f�E�L������������j</option>
                <option value="5" <%=Selected(status, 5)%>>������~�i�����ǔ��f�E���e����낵���Ȃ��j</option>
                <option value="8" <%=Selected(status, 8)%>>���s�ҋx�</option>
                <option value="9" <%=Selected(status, 9)%>>���s�ґމ�</option>
              </select>
            </td>
          </tr>
          <tr>
            <td class="m3">�o�^��</td>
            <td class="n3"><%=TimeFormat(RS("MG_regist_date"))%></td>
          </tr>
          <tr>
            <td class="m3">�ŏI�X�V��</td>
            <td class="n3"><%=TimeFormat(RS("MG_last_update"))%></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <br>
  <input type="submit" value=" �X�V ">
  <input type="reset" value="���Z�b�g">
  <input type="button" value=" �폜 " onClick="OnClick_sakujo()">
  <input type="button" value=" �߂� " onClick="history.back()">

</form>
</center>

<%
RS.Close
%>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
