<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:�{�o�^�����`�F�b�N�̃����}�K���C�����
'******************************************************

SetHeaderTitle "�{�o�^�����`�F�b�N", TC_HAKKOUSHA
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
  with (document.form1) {
    if (shuu_keisuu.value == "") {
      alert("�T���s�W������͂��Ă��������B");
      shuu_keisuu.focus();
      return false;
    }
  }
  return true;
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<%
Dim DB, RS, SQL, mag_id

mag_id = Request.QueryString("id")
If IsEmpty(mag_id) Then
  Response.Redirect("index.asp")
End If

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT * FROM V_MELMAG WHERE MG_mag_id='" & mag_id & "'"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
%>
<center>
<form method="post" action="init_check3.asp" name="form1" onSubmit="return OnSubmit_form1();">
  <table border=0 cellspacing=0 cellpadding=0 width='700'>
    <tr>
      <td>�����s�ҏ��</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m1" width="23%">���s��ID</td>
            <td class="n1"><%=RS("MM_mel_id")%></td>
          </tr>
          <tr>
            <td class="m1">���s�Җ�</td>
            <td class="n1"><%=RS("MM_h_name")%></td>
          </tr>
          <tr>
            <td class="m1">e-mail�A�h���X</td>
            <td class="n1"><%=RS("MM_mail_adr")%></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td>�������}�K���</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m1" width="23%">�����}�KID</td>
            <td class="n1"><input type="hidden" name="mag_id" value="<%=RS("MG_mag_id")%>"><%=RS("MG_mag_id")%></td>
          </tr>
          <tr>
            <td class="m1">�����}�K��</td>
            <td class="n1"><input type="text" name="m_name" size="50" value="<%=RS("MG_m_name")%>" maxlength="100"></td>
          </tr>
          <tr>
            <td class="m1">���s�p�x</td>
            <td class="n1"><input type="text" name="hindo" size="20" value="<%=RS("MG_hindo")%>" maxlength="30"></td>
          </tr>
          <tr>
            <td class="m1">���s����</td>
            <td class="n1"><input type="text" name="busuu" size="20" value="<%=RS("MG_busuu")%>" maxlength="30"></td>
          </tr>
          <tr>
            <td class="m1">�L���r��</td>
            <td class="n1">
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
            <td class="m1">�P�s�̕�����</td>
            <td class="n1"><input type="text" name="moji_suu" size="20" value="<%=RS("MG_moji_suu")%>"></td>
          </tr>
          <tr>
            <td class="m1">�����}�K�Љ</FONT></TD>
            <td class="n1"><textarea name="shoukai_text" cols=60 rows=5><%=RS("MG_shoukai_text")%></textarea></td>
          </tr>
          <tr>
            <td class="m1">�w�Ǘp�t�q�k</FONT></TD>
            <td class="n1"><input type="text" name="koudoku_url" size="50" value="<%=RS("MG_koudoku_url")%>" maxlength="100">
<%
If Len(RS("MG_koudoku_url")) > 0 Then
  Writeln "<input type='button' value='�Q��' onClick='window.open(document.form1.koudoku_url.value, ""koudoku"")'>"
End If
%>
            </td>
          </tr>
          <tr>
            <td class="m1">�ڍא����p�t�q�k</FONT></TD>
            <td class="n1"><input type="text" name="shousai_url" size="50" value="<%=RS("MG_shousai_url")%>" maxlength="100">
<%
If Len(RS("MG_shousai_url")) > 0 Then
  Writeln "<input type='button' value='�Q��' onClick='window.open(document.form1.shousai_url.value, ""shousai"")'>"
End If
%>
            </td>
          </tr>
          <tr>
            <td class="m1">�ǎҌ����Љ�</td>
            <td class="n1">
              <input type="checkbox" name="shoukai_flag1" value="1" <%=Checked(RS("MG_shoukai_flag1"))%>>�f�ڂ���]����i���s�Ґݒ�j�@
              <input type="checkbox" name="shoukai_flag2" value="1" <%=Checked(RS("MG_shoukai_flag2"))%>>�f�ڂ���i�����ǐݒ�j
            </td>
          </tr>
          <tr>
            <td class="m1">�߂��ς�ID</td>
            <td class="n1"><input type="text" name="melonpan_id" size="20" value="<%=RS("MG_melonpan_id")%>" maxlength="20"></td>
          </tr>
          <tr>
            <td class="m1">���s�V�X�e��</td>
            <td class="n1">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n1c" width=130><input type="checkbox" name="mms_melonpan" value="1" <%=Checked(RS("MG_mms_melonpan"))%>>�߂��ς�</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_mag" value="1" <%=Checked(RS("MG_mms_mag"))%>>�܂��܂�</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_pubz" value="1" <%=Checked(RS("MG_mms_pubz"))%>>Pubzine</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_melma" value="1" <%=Checked(RS("MG_mms_melma"))%>>melma!</td>
              </tr>
              <tr>
              <td class="n1c" width=130><input type="checkbox" name="mms_macky" value="1" <%=Checked(RS("MG_mms_macky"))%>>Macky</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_kapu" value="1" <%=Checked(RS("MG_mms_kapu"))%>>�J�v���C�g</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_emag" value="1" <%=Checked(RS("MG_mms_emag"))%>>E-MAGAZINE</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_tengoku" value="1" <%=Checked(RS("MG_mms_tengoku"))%>>�����}�K�V��</td>
              </tr>
              <tr>
              <td class="n1c" width=130><input type="checkbox" name="mms_tiara" value="1" <%=Checked(RS("MG_mms_tiara"))%>>�e�B�A���I�����C��</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_merubox" value="1" <%=Checked(RS("MG_mms_merubox"))%>>MERU-BOX</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_dokuji" value="1" <%=Checked(RS("MG_mms_dokuji"))%>>�Ǝ��z�M</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_other" value="1" <%=Checked(RS("MG_mms_other"))%>>���̑�</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m1">�����}�K�J�e�S��</td>
            <td class="n1">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_int" value="1" <%=Checked(RS("MG_cat_int"))%>>�C���^�[�l�b�g</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_pcc" value="1" <%=Checked(RS("MG_cat_pcc"))%>>�p�\�R��/�R���s���[�^�S��</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_soft" value="1" <%=Checked(RS("MG_cat_soft"))%>>�\�t�g�E�F�A/�v���O���~���O</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_mvl" value="1" <%=Checked(RS("MG_cat_mvl"))%>>�g��/���o�C��</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_bns" value="1" <%=Checked(RS("MG_cat_bns"))%>>�r�W�l�X�S��</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_seiji" value="1" <%=Checked(RS("MG_cat_seiji"))%>>����/�o��</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_kabu" value="1" <%=Checked(RS("MG_cat_kabu"))%>>����/����</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_fsn" value="1" <%=Checked(RS("MG_cat_fsn"))%>>�t�@�b�V����/���e</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_lif" value="1" <%=Checked(RS("MG_cat_lif"))%>>��炵/���L</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_shumi" value="1" <%=Checked(RS("MG_cat_shumi"))%>>�</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_res" value="1" <%=Checked(RS("MG_cat_res"))%>>�O����/���X�g����</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_tvl" value="1" <%=Checked(RS("MG_cat_tvl"))%>>���s/�n��</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_spt" value="1" <%=Checked(RS("MG_cat_spt"))%>>�X�|�[�c/���W���[</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_gamble" value="1" <%=Checked(RS("MG_cat_gamble"))%>>�M�����u��</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_hlt" value="1" <%=Checked(RS("MG_cat_hlt"))%>>���N/���/�玙</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_art" value="1" <%=Checked(RS("MG_cat_art"))%>>�A�[�g/���|</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_shp" value="1" <%=Checked(RS("MG_cat_shp"))%>>�V���b�s���O</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_ent" value="1" <%=Checked(RS("MG_cat_ent"))%>>�G���^�[�e�C�����g</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_game" value="1" <%=Checked(RS("MG_cat_game"))%>>�Q�[��/�N�C�Y</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_movie" value="1" <%=Checked(RS("MG_cat_movie"))%>>�f��/���y/�|�\</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_uranai" value="1" <%=Checked(RS("MG_cat_uranai"))%>>�肢</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_std" value="1" <%=Checked(RS("MG_cat_std"))%>>�w�K/����</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_shikaku" value="1" <%=Checked(RS("MG_cat_shikaku"))%>>���i</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_gogaku" value="1" <%=Checked(RS("MG_cat_gogaku"))%>>��w</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_nws" value="1" <%=Checked(RS("MG_cat_nws"))%>>�j���[�X</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_zatsu" value="1" <%=Checked(RS("MG_cat_zatsu"))%>>�G�w</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_kenshou" value="1" <%=Checked(RS("MG_cat_kenshou"))%>>����/�v���[���g</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_homepage" value="1" <%=Checked(RS("MG_cat_homepage"))%>>�z�[���y�[�W�Љ�</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_hitokoto" value="1" <%=Checked(RS("MG_cat_hitokoto"))%>>����ЂƂ��ƌn</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_other" value="1" <%=Checked(RS("MG_cat_other"))%>>���̑�</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m1">�ǎґw�i�N��j</td>
            <td class="n1">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n1c" width=90><input type="checkbox" name="age_10m" value="1" <%=Checked(RS("MG_age_10m"))%>>10��j</td>
              <td class="n1c" width=90><input type="checkbox" name="age_20m" value="1" <%=Checked(RS("MG_age_20m"))%>>20��j</td>
              <td class="n1c" width=90><input type="checkbox" name="age_30m" value="1" <%=Checked(RS("MG_age_30m"))%>>30��j</td>
              <td class="n1c" width=90><input type="checkbox" name="age_40m" value="1" <%=Checked(RS("MG_age_40m"))%>>40��j</td>
              <td class="n1c" width=90><input type="checkbox" name="age_50m" value="1" <%=Checked(RS("MG_age_50m"))%>>50��ȏ�j</td>
              </tr>
              <tr>
              <td class="n1c" width=90><input type="checkbox" name="age_10f" value="1" <%=Checked(RS("MG_age_10f"))%>>10�㏗</td>
              <td class="n1c" width=90><input type="checkbox" name="age_20f" value="1" <%=Checked(RS("MG_age_20f"))%>>20�㏗</td>
              <td class="n1c" width=90><input type="checkbox" name="age_30f" value="1" <%=Checked(RS("MG_age_30f"))%>>30�㏗</td>
              <td class="n1c" width=90><input type="checkbox" name="age_40f" value="1" <%=Checked(RS("MG_age_40f"))%>>40�㏗</td>
              <td class="n1c" width=90><input type="checkbox" name="age_50f" value="1" <%=Checked(RS("MG_age_50f"))%>>50��ȏ㏗</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m1">�ǎґw�i�E�Ɓj</td>
            <td class="n1">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n1c" width=100><input type="checkbox" name="shk_stu" value="1" <%=Checked(RS("MG_shk_stu"))%>>�w��</td>
              <td class="n1c" width=100><input type="checkbox" name="shk_enp" value="1" <%=Checked(RS("MG_shk_enp"))%>>�Љ�l</td>
              <td class="n1c" width=100><input type="checkbox" name="shk_syf" value="1" <%=Checked(RS("MG_shk_syf"))%>>��w</td>
              <td class="n1c" width=100><input type="checkbox" name="shk_other" value="1" <%=Checked(RS("MG_shk_other"))%>>���̑�</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m1">�ǎґw�i�G���A�j</td>
            <td class="n1">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n1c" width=100><input type="checkbox" name="are_ht" value="1" <%=Checked(RS("MG_are_ht"))%>>�k�C��/���k</td>
              <td class="n1c" width=100><input type="checkbox" name="are_kt" value="1" <%=Checked(RS("MG_are_kt"))%>>�֓�</td>
              <td class="n1c" width=100><input type="checkbox" name="are_cb" value="1" <%=Checked(RS("MG_are_cb"))%>>����</td>
              <td class="n1c" width=100><input type="checkbox" name="are_kk" value="1" <%=Checked(RS("MG_are_kk"))%>>�ߋE</td>
              </tr>
              <tr>
              <td class="n1c" width=100><input type="checkbox" name="are_cs" value="1" <%=Checked(RS("MG_are_cs"))%>>����/�l��</td>
              <td class="n1c" width=100><input type="checkbox" name="are_ks" value="1" <%=Checked(RS("MG_are_ks"))%>>��B</td>
              <td class="n1c" width=100><input type="checkbox" name="are_fr" value="1" <%=Checked(RS("MG_are_fr"))%>>�C�O</td>
              <td class="n1c" width=100><input type="checkbox" name="are_zk" value="1" <%=Checked(RS("MG_are_zk"))%>>�S��</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m1" width=160>�T���s�W��</td>
            <td class="n1"><input type="text" name="shuu_keisuu" value="<%=RS("MG_shuu_keisuu")%>"></td>
          <tr>
            <td class="m1" width=160>�܂��܂��W��</td>
            <td class="n1"><input type="text" name="magmag_keisuu" value="<%If IsNull(RS("MG_magmag_keisuu")) Then Response.Write "100" Else Response.Write FormatNumber(NVL(RS("MG_magmag_keisuu"), 0) * 100,1)%>"> %</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <br>
  <input type="submit" value="�@�n�j�@">
  <input type="reset" value="���Z�b�g">

</form>
</center>

<%
RS.Close
%>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
