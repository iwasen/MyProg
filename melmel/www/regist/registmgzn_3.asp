<!--#INCLUDE FILE="../melmel_inc.asp"-->
<html>
<head>
<title>�߂�߂�Enet�{�o�^</title>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<LINK rel="stylesheet" href="../melmel.css">
</head>
<body>
<%
Session("seq") = Request.Form("seq")
Session("password") = Request.Form("password")

Dim DB, RS, SQL
Dim mel_id

Set DB = OpenDB()
SQL = "SELECT *" & vbCrLf & _
      "FROM T_PROVREG LEFT OUTER JOIN (SELECT * FROM T_MELMEL WHERE MM_status<>9) AS SUB1 ON PR_mel_id=MM_mel_id" & vbCrLf & _
      "WHERE REPLACE(STR(PR_seq_no,5), ' ', '0')='" & Session("seq") & "' AND PR_password='" & Session("password") & "' AND PR_status=1"
Set RS = DB.Execute(SQL)

If RS.EOF Then
%>
<B>�{�o�^</B><BR><P>
ID ���p�X���[�h�ɃG���[������܂��B
<%
Else
  mel_id = RS("MM_mel_id")
  Session("mel_id") = mel_id
%>
<script LANGUAGE="javascript">
<!--
var IsChange = false;
function changePasswd() { IsChange = true; }

function num_chk(v1) {
	var p1 = v1;
	for (i=0; i<p1.length; i++){
		if (p1.substring(i,i+1)<"0" || p1.substring(i,i+1)>"9") {
			return(false);
		}
	}
	return true;
}

function len_chk(v1) {
	if (v1.length < 6) {
		return(false);
	}
	return(true);
}

function form_validator(theform)
{
<%
If IsNull(mel_id) Then
%>
	if ( theform.h_name.value == "" ) {
		alert("���s�l�̖��O : ���̗��͕K�����͂��Ă��������B");
		theform.h_name.focus();
		return(false);
	}
	if ( theform.madd.value == "" ) {
		alert("���[���A�h���X : ���̗��͕K�����͂��Ă��������B");
		theform.madd.focus();
		return(false);
	}
<%
End If
%>
	if ( theform.m_name.value == "" ) {
		alert("���[���}�K�W���� : ���̗��͕K�����͂��Ă��������B");
		theform.m_name.focus();
		return(false);
	}
	if (theform.busuu.value == "" ) {
		alert("���s���� : ���̗��͕K�����͂��Ă��������B");
		theform.busuu.focus();
		return(false);
	}
	if (!num_chk(theform.busuu.value)){
		alert("���s�����F���s�����͔��p�̐��l�ł������������B");
		theform.busuu.focus();
		return(false);
	}
  if (theform.keisen_id.value == "") {
    alert("�L���r���F�L���r����I�����Ă��������B");
    theform.keisen_id.focus();
    return false;
  }
  var cnt = 0;
  if (theform.cat_int.checked)
    cnt++;
  if (theform.cat_pcc.checked)
    cnt++;
  if (theform.cat_soft.checked)
    cnt++;
  if (theform.cat_mvl.checked)
    cnt++;
  if (theform.cat_bns.checked)
    cnt++;
  if (theform.cat_seiji.checked)
    cnt++;
  if (theform.cat_kabu.checked)
    cnt++;
  if (theform.cat_fsn.checked)
    cnt++;
  if (theform.cat_lif.checked)
    cnt++;
  if (theform.cat_shumi.checked)
    cnt++;
  if (theform.cat_res.checked)
    cnt++;
  if (theform.cat_tvl.checked)
    cnt++;
  if (theform.cat_spt.checked)
    cnt++;
  if (theform.cat_gamble.checked)
    cnt++;
  if (theform.cat_hlt.checked)
    cnt++;
  if (theform.cat_art.checked)
    cnt++;
  if (theform.cat_shp.checked)
    cnt++;
  if (theform.cat_ent.checked)
    cnt++;
  if (theform.cat_game.checked)
    cnt++;
  if (theform.cat_movie.checked)
    cnt++;
  if (theform.cat_uranai.checked)
    cnt++;
  if (theform.cat_std.checked)
    cnt++;
  if (theform.cat_shikaku.checked)
    cnt++;
  if (theform.cat_gogaku.checked)
    cnt++;
  if (theform.cat_nws.checked)
    cnt++;
  if (theform.cat_zatsu.checked)
    cnt++;
  if (theform.cat_kenshou.checked)
    cnt++;
  if (theform.cat_homepage.checked)
    cnt++;
  if (theform.cat_hitokoto.checked)
    cnt++;
  if (theform.cat_other.checked)
    cnt++;
  if (cnt > 2) {
    alert("�����}�K�̃J�e�S���͂Q�ȓ��őI�����Ă��������B")
    return false;
  }
<%
If IsNull(mel_id) Then
%>
	if ((theform.password.value == "") || (!IsChange)) {
		alert("�p�X���[�h : ���s�҃p�X���[�h��ݒ肵�Ă��������B");
		theform.password.focus();
		return(false);
	}
	if (theform.password.value != theform.password2.value) {
		alert("�p�X���[�h : �p�X���[�h����v���܂���B");
		theform.password.focus();
		return(false);
	}
	if (!len_chk(theform.password.value)) {
		alert("�p�X���[�h : �p�X���[�h��6�����ȏ�łȂ���΂Ȃ�܂���B");
		theform.password.focus();
		return(false);
	}
	if ( theform.bk_nm.value == "" ) {
		alert("��s�E�M�p���ɖ� : ���̗��͕K�����͂��Ă��������B");
		theform.bk_nm.focus();
		return(false);
	}
	if ( theform.bk_st.value == "" ) {
		alert("��s�E�M�p���Ɏx�X�� : ���̗��͕K�����͂��Ă��������B");
		theform.bk_st.focus();
		return(false);
	}
	if ( theform.bk_acc.value == "" ) {
		alert("�����ԍ� : ���̗��͕K�����͂��Ă��������B");
		theform.bk_acc.focus();
		return(false);
	}
	if ( theform.bk_knm.value == "" ) {
		alert("�������`�l : ���̗��͕K�����͂��Ă��������B");
		theform.bk_knm.focus();
		return(false);
	}
<%
End If
%>
  return true;
}
//-->
</script>
<B>�{�o�^���</B>
<P>�K����m�F��A�{�o�^���s���Ă��������B���̍ہA�K���A�p�X���[�h��ύX���Ă��������B<BR>
<P>���o�^�̎���������������\�����Ă���܂��B�K�v�ł���ΓK�X�C�����Ă��������B<BR>

<P><center>

<%=String(80, "-")%><BR>

</center>
<strong><font size="3" color="#FF0000">�������ӁE�E�E�߂�߂�net����z�M�����L���́A2001�N2��1�����A�u�܂��܂��v���甭�s����郁���}�K�Ɍf�ڂ��邱�Ƃ��ł��Ȃ��Ȃ�܂��B�\�߂������̏�A�u�{�o�^�v�{�^���������Ă��������B</font></strong><center>

<form method="POST" action="registmgzn_4.asp" ONSUBMIT="return form_validator(this)" name="theform">
  <input type="hidden" name="seq" value="<%=Right("00000" & RS("PR_seq_no"), 5)%>">
  <input type="hidden" name="mel_id" value="<%=mel_id%>">
  <table border="1" width="650" bordercolor="#FF80C0" bordercolorlight="#FF80C0" bordercolordark="#FF80C0" class="kihon">
<%
If IsNull(mel_id) Then
%>
    <tr>
      <td width="201">�����s�҂̂����O</td>
      <td width="449"><input type="text" name="h_name" size="40" value="<%=RS("PR_h_name")%>"></td>
    </tr>
    <tr>
      <td width="201">�����s�҂�e-mail�A�h���X</td>
      <td width="449"><input type="text" name="madd" size="50" value="<%=RS("PR_mail_adr")%>"></td>
    </tr>
    <tr>
      <td width="201">���i�@�l�̏ꍇ�j��Ж�</td>
      <td width="449"><input type="text" name="c_name" size="50" value="<%=RS("PR_c_name")%>"></td>
    </tr>
<%
Else
%>
    <tr>
      <td width="201">�����s�҂̂����O</td>
      <td width="449"><input type="hidden" name="h_name" value="<%=RS("PR_h_name")%>"><%=RS("PR_h_name")%></td>
    </tr>
    <tr>
      <td width="201">�����s�҂�e-mail�A�h���X</td>
      <td width="449"><input type="hidden" name="madd" value="<%=RS("PR_mail_adr")%>"><%=RS("PR_mail_adr")%></td>
    </tr>
    <tr>
      <td width="201">���i�@�l�̏ꍇ�j��Ж�</td>
      <td width="449"><input type="hidden" name="c_name" value="<%=RS("PR_c_name")%>"><%=RS("PR_c_name")%></td>
    </tr>
<%
End If
%>
    <tr>
      <td width="201">�������}�K��</td>
      <td width="449"><input type="text" name="m_name" size="50" value="<%=RS("PR_m_name")%>"></td>
    </tr>
    <tr>
      <td width="201">�����s����(�����ł͂���܂���!)</td>
      <td width="449"><input type="text" name="busuu" size="8" value="<%=RS("PR_busuu")%>">�� �i�����̔��s�V�X�e�������p�̍ۂ́A���v�������L�����Ă��������j</td>
    </tr>
    <tr>
      <td width="201">�����s�p�x</td>
      <td width="449"><input type="text" name="hindo" size="30" value="<%=RS("PR_hindo")%>"><br>�i�s������s�̕������������̕p�x�����������������E�E�E�Ⴆ�Ό��ɂP�`�Q��Ƃ��j</td>
    </tr>
    <tr>
      <td width="201">�������}�K���s�X�^���h</td>
      <td width="449">
        <table border="0" cellspacing="0" cellpadding="0" width="100%" class="kihon">
          <tr>
            <td><input type="checkbox" name="mms_melonpan" value="1"<%=Checked(RS("PR_mms_melonpan"))%>>�߂��ς�</td>
            <td><input type="checkbox" name="mms_mag" value="1"<%=Checked(RS("PR_mms_mag"))%>>�܂��܂�</td>
            <td><input type="checkbox" name="mms_pubz" value="1"<%=Checked(RS("PR_mms_pubz"))%>>Pubzine</td>
            <td><input type="checkbox" name="mms_melma" value="1"<%=Checked(RS("PR_mms_melma"))%>>melma!</td>
          </tr>
          <tr>
            <td><input type="checkbox" name="mms_macky" value="1"<%=Checked(RS("PR_mms_macky"))%>>Macky</td>
            <td><input type="checkbox" name="mms_kapu" value="1"<%=Checked(RS("PR_mms_kapu"))%>>�J�v���C�g</td>
            <td><input type="checkbox" name="mms_emag" value="1"<%=Checked(RS("PR_mms_emag"))%>>E-MAGAZINE</td>
            <td><input type="checkbox" name="mms_tengoku" value="1"<%=Checked(RS("PR_mms_tengoku"))%>>�����}�K�V��</td>
          </tr>
          <tr>
            <td><input type="checkbox" name="mms_tiara" value="1"<%=Checked(RS("PR_mms_tiara"))%>>�e�B�A���I�����C��</td>
            <td><input type="checkbox" name="mms_merubox" value="1"<%=Checked(RS("PR_mms_merubox"))%>>MERU-BOX</td>
            <td><input type="checkbox" name="mms_dokuji" value="1"<%=Checked(RS("PR_mms_dokuji"))%>>�Ǝ��z�M</td>
            <td><input type="checkbox" name="mms_other" value="1"<%=Checked(RS("PR_mms_other"))%>>���̑�</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td width="201">���߂��ς�̃}�K�W��ID�i�߂��ς�Ŕz�M���Ă���ꍇ�̂݁j</td>
      <td width="449"><input type="text" name="melonpan_id" maxlength="10" value="<%=RS("PR_melonpan_id")%>"></td>
    </tr>
    <tr>
      <td width="201">���܂��܂��̃}�K�W��ID�i�܂��܂��Ŕz�M���Ă���ꍇ�̂݁j</td>
      <td width="449"><input type="text" name="mgmg_id" maxlength="10" value="<%=RS("PR_mgmg_id")%>">�i�P�O���ł��L�����������j</td>
    </tr>
    <tr>
      <td width="201">���L���r��</td>
      <td>
        <select name="keisen_id" style="font-family:�l�r �S�V�b�N;font-size:9pt">
<%
Dim RS2
SQL = "SELECT KE_keisen_id,KE_start_line FROM T_KEISEN ORDER BY KE_keisen_id"
Set RS2 = DB.Execute(SQL)

Writeln "<option value='' selected>���D�݂̍L���r����I�����Ă�������</option>"
Do Until RS2.EOF
  Writeln "<option value='" & RS2("KE_keisen_id") & "'>" & ReplaceTag(ZenkakuRight(RS2("KE_start_line"), 30)) & "</option>"
  RS2.MoveNext
Loop
RS2.Close
%>
        </select>
      </td>
    </tr>
    <tr>
      <td width="201">���P�s�̕�����</td>
      <td width="449">
        <select name="moji_suu">
          <option value="35">�S�p35����</option>
          <option value="36">�S�p36����</option>
          <option value="37">�S�p37����</option>
          <option value="38">�S�p38����</option>
        </select> �i���[���}�K�W���̂P�s�̑S�p��������I�����Ă��������j
      </td>
    </tr>
<%
If IsNull(mel_id) Then
%>
    <tr>
      <td>�������}�K�̏Љ<br>�@�i�S�p250�����܂Łj</td>
      <td>
        <textarea name="shoukai_text" cols=60 rows=4></textarea>
      </td>
    </tr>
    <tr>
      <td>���w�Ǘp�z�[���y�[�W�̂t�q�k</td>
      <td>
        <input type="text" name="koudoku_url" size="62" value="http://">
      </td>
    </tr>
    <tr>
      <td>�������}�K�ڍא����p�̂t�q�k</td>
      <td>
        <input type="text" name="shousai_url" size="62" value="http://">
      </td>
    </tr>
    <tr>
      <td>���ǎҌ����Љ�y�[�W�ւ̌f��</td>
      <td>
        <input type="radio" name="shoukai_flag1" value="1" checked>��]����@<input type="radio" name="shoukai_flag1" value="0">��]���Ȃ�
        <br>�i�f�ڂ���]����ꍇ�́A�Љ�ƍw�ǗpURL��K�����͂��Ă��������j
      </td>
    </tr>
<%
End If
%>
  </table>
    	
  <P>
  <table border="1" width="650" bordercolor="#FF80C0" bordercolorlight="#FF80C0" bordercolordark="#FF80C0" class="kihon">
    <tr>
      <td align="center" colspan="2" width="600">�����Ȃ��̃����}�K�̂����悻�̓ǎґw�������Ă��������B</td>
    </tr>
      	
    <tr>
      <td width="120">�����}�K�̃J�e�S��<br>�i�Q�ȓ��őI�����Ă��������j</td>
      <td width="530">
        <table border="0" cellspacing="0" cellpadding="0" width="100%" class="kihon">
          <tr>
            <td><input type="checkbox" name="cat_int" value="1"<%=Checked(RS("PR_cat_int"))%>>�C���^�[�l�b�g</td>
            <td><input type="checkbox" name="cat_pcc" value="1"<%=Checked(RS("PR_cat_pcc"))%>>�p�\�R��/�R���s���[�^�S��</td>
            <td><input type="checkbox" name="cat_soft" value="1"<%=Checked(RS("PR_cat_soft"))%>>�\�t�g�E�F�A/�v���O���~���O</td>
          </tr>
          <tr>
            <td><input type="checkbox" name="cat_mvl" value="1"<%=Checked(RS("PR_cat_mvl"))%>>�g��/���o�C��</td>
            <td><input type="checkbox" name="cat_bns" value="1"<%=Checked(RS("PR_cat_bns"))%>>�r�W�l�X�S��</td>
            <td><input type="checkbox" name="cat_seiji" value="1"<%=Checked(RS("PR_cat_seiji"))%>>����/�o��</td>
          </tr>
          <tr>
            <td><input type="checkbox" name="cat_kabu" value="1"<%=Checked(RS("PR_cat_kabu"))%>>����/����</td>
            <td><input type="checkbox" name="cat_fsn" value="1"<%=Checked(RS("PR_cat_fsn"))%>>�t�@�b�V����/���e</td>
            <td><input type="checkbox" name="cat_lif" value="1"<%=Checked(RS("PR_cat_lif"))%>>��炵/���L</td>
          </tr>
          <tr>
            <td><input type="checkbox" name="cat_shumi" value="1"<%=Checked(RS("PR_cat_shumi"))%>>�</td>
            <td><input type="checkbox" name="cat_res" value="1"<%=Checked(RS("PR_cat_res"))%>>�O����/���X�g����</td>
            <td><input type="checkbox" name="cat_tvl" value="1"<%=Checked(RS("PR_cat_tvl"))%>>���s/�n��</td>
          </tr>
          <tr>
            <td><input type="checkbox" name="cat_spt" value="1"<%=Checked(RS("PR_cat_spt"))%>>�X�|�[�c/���W���[</td>
            <td><input type="checkbox" name="cat_gamble" value="1"<%=Checked(RS("PR_cat_gamble"))%>>�M�����u��</td>
            <td><input type="checkbox" name="cat_hlt" value="1"<%=Checked(RS("PR_cat_hlt"))%>>���N/���/�玙</td>
          </tr>
          <tr>
            <td><input type="checkbox" name="cat_art" value="1"<%=Checked(RS("PR_cat_art"))%>>�A�[�g/���|</td>
            <td><input type="checkbox" name="cat_shp" value="1"<%=Checked(RS("PR_cat_shp"))%>>�V���b�s���O</td>
            <td><input type="checkbox" name="cat_ent" value="1"<%=Checked(RS("PR_cat_ent"))%>>�G���^�[�e�C�����g</td>
          </tr>
          <tr>
            <td><input type="checkbox" name="cat_game" value="1"<%=Checked(RS("PR_cat_game"))%>>�Q�[��/�N�C�Y</td>
            <td><input type="checkbox" name="cat_movie" value="1"<%=Checked(RS("PR_cat_movie"))%>>�f��/���y/�|�\</td>
            <td><input type="checkbox" name="cat_uranai" value="1"<%=Checked(RS("PR_cat_uranai"))%>>�肢</td>
          </tr>
          <tr>
            <td><input type="checkbox" name="cat_std" value="1"<%=Checked(RS("PR_cat_std"))%>>�w�K/����</td>
            <td><input type="checkbox" name="cat_shikaku" value="1"<%=Checked(RS("PR_cat_shikaku"))%>>���i</td>
            <td><input type="checkbox" name="cat_gogaku" value="1"<%=Checked(RS("PR_cat_gogaku"))%>>��w</td>
          </tr>
          <tr>
            <td><input type="checkbox" name="cat_nws" value="1"<%=Checked(RS("PR_cat_nws"))%>>�j���[�X</td>
            <td><input type="checkbox" name="cat_zatsu" value="1"<%=Checked(RS("PR_cat_zatsu"))%>>�G�w</td>
            <td><input type="checkbox" name="cat_kenshou" value="1"<%=Checked(RS("PR_cat_kenshou"))%>>����/�v���[���g</td>
          </tr>
          <tr>
            <td><input type="checkbox" name="cat_homepage" value="1"<%=Checked(RS("PR_cat_homepage"))%>>�z�[���y�[�W�Љ�</td>
            <td><input type="checkbox" name="cat_hitokoto" value="1"<%=Checked(RS("PR_cat_hitokoto"))%>>����ЂƂ��ƌn</td>
            <td><input type="checkbox" name="cat_other" value="1"<%=Checked(RS("PR_cat_other"))%>>���̑�</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td width="120">�N��y�ѐ��� ������������Ȑl�|�Ƃ������ƂŃ`�F�b�N���Ă��������B</td>
      <td width="530">
        <table border="0" cellspacing="0" cellpadding="0" width="100%" class="kihon">
          <tr>
          	<td><input type="checkbox" name="age_10m" value="1"<%=Checked(RS("PR_age_10m"))%>>10��j</td>
          	<td><input type="checkbox" name="age_20m" value="1"<%=Checked(RS("PR_age_20m"))%>>20��j</td>
          	<td><input type="checkbox" name="age_30m" value="1"<%=Checked(RS("PR_age_30m"))%>>30��j</td>
          	<td><input type="checkbox" name="age_40m" value="1"<%=Checked(RS("PR_age_40m"))%>>40��j</td>
          	<td><input type="checkbox" name="age_50m" value="1"<%=Checked(RS("PR_age_50m"))%>>50��`�j</td>
          </tr>
          <tr>
          	<td><input type="checkbox" name="age_10f" value="1"<%=Checked(RS("PR_age_10f"))%>>10�㏗</td>
          	<td><input type="checkbox" name="age_20f" value="1"<%=Checked(RS("PR_age_20f"))%>>20�㏗</td>
          	<td><input type="checkbox" name="age_30f" value="1"<%=Checked(RS("PR_age_30f"))%>>30�㏗</td>
          	<td><input type="checkbox" name="age_40f" value="1"<%=Checked(RS("PR_age_40f"))%>>40�㏗</td>
          	<td><input type="checkbox" name="age_50f" value="1"<%=Checked(RS("PR_age_50f"))%>>50��`��</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td width="120">�E�Ɓ@������������Ȑl�|�Ƃ������ƂŃ`�F�b�N���Ă��������B</td>
      <td width="530">
        <table border="0" cellspacing="0" cellpadding="0" width="100%" class="kihon">
          <tr>
          	<td><input type="checkbox" name="shk_stu" value="1"<%=Checked(RS("PR_shk_stu"))%>>�w��</td>
          	<td><input type="checkbox" name="shk_enp" value="1"<%=Checked(RS("PR_shk_enp"))%>>�Љ�l</td>
          	<td><input type="checkbox" name="shk_syf" value="1"<%=Checked(RS("PR_shk_syf"))%>>��w</td>
          	<td><input type="checkbox" name="shk_other" value="1"<%=Checked(RS("PR_shk_other"))%>>���̑�</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td width="120">�n��@������������ȏ��|�Ƃ������ƂŃ`�F�b�N���Ă��������B�킩��Ȃ���ΑS�����B</td>
      <td width="530">
        <table border="0" cellspacing="0" cellpadding="0" width="100%" class="kihon">
          <tr>
          	<td><input type="checkbox" name="are_ht" value="1"<%=Checked(RS("PR_are_ht"))%>>�k�C��/���k</td>
          	<td><input type="checkbox" name="are_kt" value="1"<%=Checked(RS("PR_are_kt"))%>>�֓�</td>
          	<td><input type="checkbox" name="are_cb" value="1"<%=Checked(RS("PR_are_cb"))%>>����</td>
          	<td><input type="checkbox" name="are_kk" value="1"<%=Checked(RS("PR_are_kk"))%>>�ߋE</td>
          </tr>
          <tr>
          	<td><input type="checkbox" name="are_cs" value="1"<%=Checked(RS("PR_are_cs"))%>>����/�l��</td>
          	<td><input type="checkbox" name="are_ks" value="1"<%=Checked(RS("PR_are_ks"))%>>��B</td>
          	<td><input type="checkbox" name="are_fr" value="1"<%=Checked(RS("PR_are_fr"))%>>�C�O</td>
          	<td><input type="checkbox" name="are_zk" value="1"<%=Checked(RS("PR_are_zk"))%>>�S��</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
<%
If IsNull(mel_id) Then
%>
  <p>
  <table border="1" width="650" bordercolor="#FF80C0" bordercolorlight="#FF80C0" bordercolordark="#FF80C0" class="kihon">
    <tr>
      <td align="center" colspan="2" width="600">���p�X���[�h��ݒ肵�Ă��������B</td>
    </tr>
    <tr>
      <td width="120">���s�҃p�X���[�h</td>
      <td width="530"><input type="password" name="password" size="10" maxlength="10" onChange="changePasswd()">�i�U�����ȏ�̔��p�p�����Őݒ肵�Ă��������j</td>
    </tr>
    <tr>
      <td width="120">�p�X���[�h�̍ē���</td>
      <td width="530"><input type="password" name="password2" size="10" maxlength="10"></td>
    </tr>
  </table>
<%
End If
%>

  <p>
  <table border="1" width="650" bordercolor="#FF80C0" bordercolorlight="#FF80C0" bordercolordark="#FF80C0" class="kihon">
    <tr>
      <td align="center" colspan="2" width="600">���U���݋�s����</td>
    </tr>
<%
If IsNull(mel_id) Then
%>
    <tr>
      <td width="120">��s�E�M�p���ɖ�</td>
      <td width="530"><input type="text" name="bk_nm" size="80" maxlength="100"></td>
    </tr>
    <tr>
      <td width="120">�x�X��</td>
      <td width="530"><input type="text" name="bk_st" size="80" maxlength="100"></td>
    </tr>
    <tr>
      <td width="120">�a���������</td>
      <td width="530"><input type="radio" name="bk_knd" value=1 checked>���� <input type="radio" name="bk_knd" value=2>���� <input type="radio" name="bk_knd" value=3>���~</td>
    </tr>
    <tr>
      <td width="120">�����ԍ�</td>
      <td width="530"><input type="text" name="bk_acc" size="80" maxlength="100"></td>
    </tr>
    <tr>
      <td width="120">�������`�l��</td>
      <td width="530"><input type="text" name="bk_knm" size="80" maxlength="100"><br>�i�K���S�p�J�i�ł��L�����������j</td>
    </tr>
<%
Else
%>
    <tr>
      <td width="120">��s�E�M�p���ɖ�</td>
      <td width="530"><%=RS("MM_bk_nm")%></td>
    </tr>
    <tr>
      <td width="120">�x�X��</td>
      <td width="530"><%=RS("MM_bk_st")%></td>
    </tr>
    <tr>
      <td width="120">�a���������</td>
      <td width="530">
<%
  Select Case RS("MM_bk_knd")
  Case 1
    Response.Write "����"
  Case 2
    Response.Write "����"
  Case 3
    Response.Write "���~"
  Case Else
    Response.Write "�s��"
  End Select
%>
      </td>
    </tr>
    <tr>
      <td width="120">�����ԍ�</td>
      <td width="530"><%=RS("MM_bk_acc")%></td>
    </tr>
    <tr>
      <td width="120">�������`�l��</td>
      <td width="530"><%=RS("MM_bk_knm")%></td>
    </tr>
<%
End If
%>
  </table>
  <p><input type="submit" value="�{�o�^"></p>
</form>
	
</center>
<%
End If
%>
</body>
</html>
