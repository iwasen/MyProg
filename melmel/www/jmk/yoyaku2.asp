<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:�\����͉��
'******************************************************

SetHeaderTitle "�\��o�^", TC_KOUKOKU

Dim DB, RS, SQL
Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
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
function DateToString(date) {
  var month,day;
  month = "0" + (date.getMonth() + 1);
  day = "0" + date.getDate();
  return date.getFullYear() + "/" + month.substr(month.length-2,2) + "/" + day.substr(day.length-2,2);
}
function OnChange_client_id() {
    var client_id;
    if (document.form1.client_id.selectedIndex != 0) {
      client_id = document.form1.client_id.value.substr(0, 5);
      switch (client_id) {
<%
SQL = "SELECT CM_client_id,CM_client_name,CM_company,CM_tantousha,CM_mail_adr,CM_tel_no FROM T_CLIENT ORDER BY CM_client_id"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
Do Until RS.EOF
  Writeln "case '" & RS("CM_client_id") & "':"
  Writeln "document.form1.client_name.value = '" & RS("CM_client_name") & "';"
  Writeln "document.form1.company.value = '" & RS("CM_company") & "';"
	Writeln "document.form1.tantousha.value = '" & RS("CM_tantousha") & "';"
	Writeln "document.form1.mail_adr.value = '" & RS("CM_mail_adr") & "';"
	Writeln "document.form1.tel_no.value = '" & RS("CM_tel_no") & "';"
  Writeln "break;"
  RS.MoveNext
Loop
RS.Close
%>
      }
    }
}
function OnChange_koukoku_shuu() {
  with (document.form1) {
    var date;
    if (koukoku_shuu.value != "") {
      date = new Date(koukoku_shuu.value.substr(6));
      pub_start.value = DateToString(date);
      date.setTime(date.getTime() + 6*24*60*60*1000);
      pub_end.value = DateToString(date);
      date.setTime(date.getTime() + 1*24*60*60*1000);
      limit_date.value = DateToString(date);
      pub_start.focus();
    }
  }
}
function OnSubmit_form1() {
  with (document.form1) {
    if (client_name.value == "") {
      alert("�L���˗��喼����͂��Ă��������B");
      client_name.focus();
      return false;
    }
    if (koukoku_name.value == "") {
      alert("�L��������͂��Ă��������B");
      koukoku_name.focus();
      return false;
    }
    if (koukoku_shuu.value == "") {
      alert("�L���f�ڏT��I�����Ă��������B");
      koukoku_shuu.focus();
      return false;
    }
    if (busuu.value == "") {
      alert("���s��������͂��Ă��������B");
      busuu.focus();
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
Dim koukoku_id, koukoku_name, client_name, company, koukoku_shuu, pub_start, pub_end, busuu, keisai_irai, koukoku_msg, limit_date, koukoku_type, client_id
Dim mms_melonpan, mms_mag, mms_pubz, mms_melma, mms_macky, mms_kapu, mms_emag, mms_tengoku, mms_tiara, mms_merubox, mms_dokuji, mms_other
Dim cat_int, cat_pcc, cat_soft, cat_mvl, cat_bns, cat_seiji, cat_kabu, cat_fsn, cat_lif, cat_shumi, cat_res, cat_tvl, cat_spt, cat_gamble, cat_hlt, cat_art, cat_shp, cat_ent, cat_game, cat_movie, cat_uranai, cat_std, cat_shikaku, cat_gogaku, cat_nws, cat_zatsu, cat_kenshou, cat_homepage, cat_hitokoto, cat_other
Dim age_10m, age_10f, age_20m, age_20f, age_30m, age_30f, age_40m, age_40f, age_50m, age_50f
Dim shk_stu, shk_enp, shk_syf, shk_other
Dim are_ht, are_kt, are_cb, are_kk, are_cs, are_ks, are_fr, are_zk
Dim i, tantousha, mail_adr, tel_no

koukoku_id = Request.QueryString("id")
If IsEmpty(koukoku_id) Then
  koukoku_shuu = ""
  koukoku_type = ""
Else
  SQL = "SELECT * FROM V_KOUKOKU WHERE KK_koukoku_id='" & koukoku_id & "'"
  RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly

  If Not RS.EOF Then
    koukoku_name = RS("KK_koukoku_name")
    client_name = RS("KK_client_name")
    company = RS("KK_company")
    koukoku_shuu = RS("KK_koukoku_shuu")
    pub_start = DateFormat(RS("KK_pub_start"))
    pub_end = DateFormat(RS("KK_pub_end"))
    busuu = RS("KK_busuu")
    keisai_irai = RS("KK_keisai_irai")
    koukoku_msg = RS("KK_koukoku_msg")
		mms_melonpan = RS("KK_mms_melonpan")
		mms_mag = RS("KK_mms_mag")
		mms_pubz = RS("KK_mms_pubz")
		mms_melma = RS("KK_mms_melma")
		mms_macky = RS("KK_mms_macky")
		mms_kapu = RS("KK_mms_kapu")
		mms_emag = RS("KK_mms_emag")
		mms_tengoku = RS("KK_mms_tengoku")
		mms_tiara = RS("KK_mms_tiara")
		mms_merubox = RS("KK_mms_merubox")
		mms_dokuji = RS("KK_mms_dokuji")
		mms_other = RS("KK_mms_other")
		cat_int = RS("KK_cat_int")
		cat_pcc = RS("KK_cat_pcc")
		cat_soft = RS("KK_cat_soft")
		cat_mvl = RS("KK_cat_mvl")
		cat_bns = RS("KK_cat_bns")
		cat_seiji = RS("KK_cat_seiji")
		cat_kabu = RS("KK_cat_kabu")
		cat_fsn = RS("KK_cat_fsn")
		cat_lif = RS("KK_cat_lif")
		cat_shumi = RS("KK_cat_shumi")
		cat_res = RS("KK_cat_res")
		cat_tvl = RS("KK_cat_tvl")
		cat_spt = RS("KK_cat_spt")
		cat_gamble = RS("KK_cat_gamble")
		cat_hlt = RS("KK_cat_hlt")
		cat_art = RS("KK_cat_art")
		cat_shp = RS("KK_cat_shp")
		cat_ent = RS("KK_cat_ent")
		cat_game = RS("KK_cat_game")
		cat_movie = RS("KK_cat_movie")
		cat_uranai = RS("KK_cat_uranai")
		cat_std = RS("KK_cat_std")
		cat_shikaku = RS("KK_cat_shikaku")
		cat_gogaku = RS("KK_cat_gogaku")
		cat_nws = RS("KK_cat_nws")
		cat_zatsu = RS("KK_cat_zatsu")
		cat_kenshou = RS("KK_cat_kenshou")
		cat_homepage = RS("KK_cat_homepage")
		cat_hitokoto = RS("KK_cat_hitokoto")
		cat_other = RS("KK_cat_other")
    age_10m = RS("KK_age_10m")
    age_10f = RS("KK_age_10f")
    age_20m = RS("KK_age_20m")
    age_20f = RS("KK_age_20f")
    age_30m = RS("KK_age_30m")
    age_30f = RS("KK_age_30f")
    age_40m = RS("KK_age_40m")
    age_40f = RS("KK_age_40f")
    age_50m = RS("KK_age_50m")
    age_50f = RS("KK_age_50f")
    shk_stu = RS("KK_shk_stu")
    shk_enp = RS("KK_shk_enp")
    shk_syf = RS("KK_shk_syf")
    shk_other = RS("KK_shk_other")
    are_ht = RS("KK_are_ht")
    are_kt = RS("KK_are_kt")
    are_cb = RS("KK_are_cb")
    are_kk = RS("KK_are_kk")
    are_cs = RS("KK_are_cs")
    are_ks = RS("KK_are_ks")
    are_fr = RS("KK_are_fr")
    are_zk = RS("KK_are_zk")
    limit_date = DateFormat(RS("KK_limit_date"))
    koukoku_type = RS("KK_koukoku_type") & RS("KK_koukoku_line")
    client_id = RS("KK_client_id")
		tantousha = RS("KK_tantousha")
		mail_adr = RS("KK_mail_adr")
		tel_no = RS("KK_tel_no")
  End If
  RS.Close
End If
%>
<center>
<form method="post" action="yoyaku3.asp" name="form1" onSubmit="return OnSubmit_form1();">
  <table border=0 cellspacing=0 cellpadding=0 width='800'>
    <tr>
      <td>���L���˗�����</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width='100%'>
          <tr>
            <td class="m2" width="20%">�N���C�A���gID</td>
            <td class="n2">
              <select name="client_id" onChange="OnChange_client_id()">
<%
SQL = "SELECT CM_client_id,CM_client_name,CM_company FROM T_CLIENT ORDER BY CM_client_id"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly

Writeln "<option value=''" & Selected(client_id, "") & ">�I�����Ă�������</option>"
Do Until RS.EOF
  Writeln "<option value='" & RS("CM_client_id") & "'" & Selected(client_id, RS("CM_client_id")) & ">" & RS("CM_client_id") & " " & ReplaceTag(RS("CM_client_name")) & "</option>"
  RS.MoveNext
Loop
RS.Close
%>
              </select>
            </td>
          </tr>
          <tr>
            <td class="m2" width="20%">�L���˗��喼<font size="-1">�i�K�{�j</font></td>
            <td class="n2"><input type="text" name="client_name" size=70 maxlength=100 value="<%=client_name%>"></td>
          </tr>
          <tr>
            <td class="m2">�g�D��</td>
            <td class="n2"><input type="text" name="company" size=70 maxlength=100 value="<%=company%>"></td>
          </tr>
          <tr>
            <td class="m2">�S���Җ�</td>
            <td class="n2"><input type="text" name="tantousha" size=50 maxlength=50 value="<%=tantousha%>"></td>
          </tr>
          <tr>
            <td class="m2">�A����Ұٱ��ڽ</td>
            <td class="n2"><input type="text" name="mail_adr" size=70 maxlength=100 value="<%=mail_adr%>"></td>
          </tr>
          <tr>
            <td class="m2">�A����d�b�ԍ�</td>
            <td class="n2"><input type="text" name="tel_no" size=20 maxlength=20 value="<%=tel_no%>"></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td>���L�����</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width='100%'>
<%
If Not IsEmpty(koukoku_id) Then
%>
          <tr>
            <td class="m2">�L��ID</td>
            <td class="n2"><input type="hidden" name="koukoku_id" value="<%=koukoku_id%>"><%=koukoku_id%></td>
          </tr>
<%
Else
%>
          <input type="hidden" name="koukoku_id" value="">
<%
End If
%>
          <tr>
            <td class="m2" width="20%">�L����<font size="-1">�i�K�{�j</font></td>
            <td class="n2"><input type="text" name="koukoku_name" size="70" maxlength="100" value="<%=koukoku_name%>"></td>
          </tr>
          <tr>
            <td class="m2">�L���f�ڏT<font size="-1">�i�K�{�j</font></td>
            <td class="n2">
              <select name="koukoku_shuu" onChange="OnChange_koukoku_shuu()">
<%
Dim dt

SQL = "SELECT * FROM T_WEEK WHERE WE_start_date >= '" & Date() & "' ORDER BY WE_shuu_code"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly

Writeln "<option value=''" & Selected(koukoku_shuu, "") & ">�I�����Ă�������</option>"
dt = DateAdd("m", 6, date)
Do While True
  If RS.EOF Then
    Exit Do
  End If
  Writeln "<option value='" & RS("WE_shuu_code") & DateFormat(RS("WE_start_date")) & "'" & Selected(koukoku_shuu, RS("WE_shuu_code")) & ">" & RS("WE_shuu_code") & " (" & DateFormat(RS("WE_start_date")) & ")</option>"
  If RS("WE_start_date") > dt Then
    Exit Do
  End If
  RS.MoveNext
Loop
RS.Close
%>
              </select>
            </td>
          </tr>
          <tr>
            <td class="m2">�z�M�J�n��</td>
            <td class="n2"><input type="text" name="pub_start" size="20" value="<%=pub_start%>"></td>
          </tr>
          <tr>
            <td class="m2">�z�M�I����</td>
            <td class="n2"><input type="text" name="pub_end" size="20" value="<%=pub_end%>"></td>
          </tr>
          <tr>
            <td class="m2" width=160>�񍐊���</td>
            <td class="n2"><input type="text" name="limit_date" value="<%=limit_date%>"></td>
          </tr>
          <tr>
            <td class="m2">���s����<font size="-1">�i�K�{�j</font></td>
            <td class="n2"><input type="text" name="busuu" size="20" value="<%=busuu%>"></td>
          </tr>
          <tr>
            <td class="m2">�f�ڈ˗�����</td>
            <td class="n2"><input type="text" name="keisai_irai" size="20" value="<%=keisai_irai%>">%</td>
          </tr>
          <tr>
            <td class="m2" width=160>�L���^�C�v</td>
            <td class="n2">
              <select name="koukoku_type">
                <option value="">�I�����Ă�������</option>
                <option value="A5" <%=Selected(koukoku_type, "A5")%>>�^�C�v�`-�T�s</option>
                <option value="B5" <%=Selected(koukoku_type, "B5")%>>�^�C�v�a-�T�s</option>
                <option value="Q5" <%=Selected(koukoku_type, "Q5")%>>�^�C�v�p-�T�s</option>
                <option value="R5" <%=Selected(koukoku_type, "R5")%>>�^�C�v�q-�T�s</option>
                <option value="A10" <%=Selected(koukoku_type, "A10")%>>�^�C�v�`-�P�O�s</option>
                <option value="B10" <%=Selected(koukoku_type, "B10")%>>�^�C�v�a-�P�O�s</option>
                <option value="Q10" <%=Selected(koukoku_type, "Q10")%>>�^�C�v�p-�P�O�s</option>
                <option value="R10" <%=Selected(koukoku_type, "R10")%>>�^�C�v�q-�P�O�s</option>
              </select>
            </td>
          </tr>
         <tr>
            <td class="m2">�L����</td>
            <td class="n2"><textarea name="koukoku_msg" cols=80 rows=10 class="np"><%=vbCrLf & koukoku_msg%></textarea></td>
          </tr>
          <tr>
            <td class="m2">���s�V�X�e��</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=130><input type="checkbox" name="mms_melonpan" value="1" <%=Checked(mms_melonpan)%>>�߂��ς�</td>
              <td class="n2c" width=130><input type="checkbox" name="mms_mag" value="1" <%=Checked(mms_mag)%>>�܂��܂�</td>
              <td class="n2c" width=130><input type="checkbox" name="mms_pubz" value="1" <%=Checked(mms_pubz)%>>Pubzine</td>
              <td class="n2c" width=130><input type="checkbox" name="mms_melma" value="1" <%=Checked(mms_melma)%>>melma!</td>
              </tr>
              <tr>
              <td class="n2c" width=130><input type="checkbox" name="mms_macky" value="1" <%=Checked(mms_macky)%>>Macky</td>
              <td class="n2c" width=130><input type="checkbox" name="mms_kapu" value="1" <%=Checked(mms_kapu)%>>�J�v���C�g</td>
              <td class="n2c" width=130><input type="checkbox" name="mms_emag" value="1" <%=Checked(mms_emag)%>>E-MAGAZINE</td>
              <td class="n2c" width=130><input type="checkbox" name="mms_tengoku" value="1" <%=Checked(mms_tengoku)%>>�����}�K�V��</td>
              </tr>
              <tr>
              <td class="n2c" width=130><input type="checkbox" name="mms_tiara" value="1" <%=Checked(mms_tiara)%>>�e�B�A���I�����C��</td>
              <td class="n2c" width=130><input type="checkbox" name="mms_merubox" value="1" <%=Checked(mms_merubox)%>>MERU-BOX</td>
              <td class="n2c" width=130><input type="checkbox" name="mms_dokuji" value="1" <%=Checked(mms_dokuji)%>>�Ǝ��z�M</td>
              <td class="n2c" width=130><input type="checkbox" name="mms_other" value="1" <%=Checked(mms_other)%>>���̑�</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m2">�����}�K�J�e�S��</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=170><input type="checkbox" name="cat_int" value="1" <%=Checked(cat_int)%>>�C���^�[�l�b�g</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_pcc" value="1" <%=Checked(cat_pcc)%>>�p�\�R��/�R���s���[�^�S��</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_soft" value="1" <%=Checked(cat_soft)%>>�\�t�g�E�F�A/�v���O���~���O</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" name="cat_mvl" value="1" <%=Checked(cat_mvl)%>>�g��/���o�C��</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_bns" value="1" <%=Checked(cat_bns)%>>�r�W�l�X�S��</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_seiji" value="1" <%=Checked(cat_seiji)%>>����/�o��</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" name="cat_kabu" value="1" <%=Checked(cat_kabu)%>>����/����</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_fsn" value="1" <%=Checked(cat_fsn)%>>�t�@�b�V����/���e</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_lif" value="1" <%=Checked(cat_lif)%>>��炵/���L</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" name="cat_shumi" value="1" <%=Checked(cat_shumi)%>>�</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_res" value="1" <%=Checked(cat_res)%>>�O����/���X�g����</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_tvl" value="1" <%=Checked(cat_tvl)%>>���s/�n��</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" name="cat_spt" value="1" <%=Checked(cat_spt)%>>�X�|�[�c/���W���[</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_gamble" value="1" <%=Checked(cat_gamble)%>>�M�����u��</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_hlt" value="1" <%=Checked(cat_hlt)%>>���N/���/�玙</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" name="cat_art" value="1" <%=Checked(cat_art)%>>�A�[�g/���|</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_shp" value="1" <%=Checked(cat_shp)%>>�V���b�s���O</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_ent" value="1" <%=Checked(cat_ent)%>>�G���^�[�e�C�����g</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" name="cat_game" value="1" <%=Checked(cat_game)%>>�Q�[��/�N�C�Y</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_movie" value="1" <%=Checked(cat_movie)%>>�f��/���y/�|�\</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_uranai" value="1" <%=Checked(cat_uranai)%>>�肢</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" name="cat_std" value="1" <%=Checked(cat_std)%>>�w�K/����</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_shikaku" value="1" <%=Checked(cat_shikaku)%>>���i</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_gogaku" value="1" <%=Checked(cat_gogaku)%>>��w</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" name="cat_nws" value="1" <%=Checked(cat_nws)%>>�j���[�X</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_zatsu" value="1" <%=Checked(cat_zatsu)%>>�G�w</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_kenshou" value="1" <%=Checked(cat_kenshou)%>>����/�v���[���g</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" name="cat_homepage" value="1" <%=Checked(cat_homepage)%>>�z�[���y�[�W�Љ�</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_hitokoto" value="1" <%=Checked(cat_hitokoto)%>>����ЂƂ��ƌn</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_other" value="1" <%=Checked(cat_other)%>>���̑�</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m2">�ǎґw�i�N��j</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=90><input type="checkbox" name="age_10m" value="1" <%=Checked(age_10m)%>>10��j</td>
              <td class="n2c" width=90><input type="checkbox" name="age_20m" value="1" <%=Checked(age_20m)%>>20��j</td>
              <td class="n2c" width=90><input type="checkbox" name="age_30m" value="1" <%=Checked(age_30m)%>>30��j</td>
              <td class="n2c" width=90><input type="checkbox" name="age_40m" value="1" <%=Checked(age_40m)%>>40��j</td>
              <td class="n2c" width=90><input type="checkbox" name="age_50m" value="1" <%=Checked(age_50m)%>>50��ȏ�j</td>
              </tr>
              <tr>
              <td class="n2c" width=90><input type="checkbox" name="age_10f" value="1" <%=Checked(age_10f)%>>10�㏗</td>
              <td class="n2c" width=90><input type="checkbox" name="age_20f" value="1" <%=Checked(age_20f)%>>20�㏗</td>
              <td class="n2c" width=90><input type="checkbox" name="age_30f" value="1" <%=Checked(age_30f)%>>30�㏗</td>
              <td class="n2c" width=90><input type="checkbox" name="age_40f" value="1" <%=Checked(age_40f)%>>40�㏗</td>
              <td class="n2c" width=90><input type="checkbox" name="age_50f" value="1" <%=Checked(age_50f)%>>50��ȏ㏗</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m2">�ǎґw�i�E�Ɓj</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=100><input type="checkbox" name="shk_stu" value="1" <%=Checked(shk_stu)%>>�w��</td>
              <td class="n2c" width=100><input type="checkbox" name="shk_enp" value="1" <%=Checked(shk_enp)%>>�Љ�l</td>
              <td class="n2c" width=100><input type="checkbox" name="shk_syf" value="1" <%=Checked(shk_syf)%>>��w</td>
              <td class="n2c" width=100><input type="checkbox" name="shk_other" value="1" <%=Checked(shk_other)%>>���̑�</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m2">�ǎґw�i�G���A�j</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=100><input type="checkbox" name="are_ht" value="1" <%=Checked(are_ht)%>>�k�C��/���k</td>
              <td class="n2c" width=100><input type="checkbox" name="are_kt" value="1" <%=Checked(are_kt)%>>�֓�</td>
              <td class="n2c" width=100><input type="checkbox" name="are_cb" value="1" <%=Checked(are_cb)%>>����</td>
              <td class="n2c" width=100><input type="checkbox" name="are_kk" value="1" <%=Checked(are_kk)%>>�ߋE</td>
              </tr>
              <tr>
              <td class="n2c" width=100><input type="checkbox" name="are_cs" value="1" <%=Checked(are_cs)%>>����/�l��</td>
              <td class="n2c" width=100><input type="checkbox" name="are_ks" value="1" <%=Checked(are_ks)%>>��B</td>
              <td class="n2c" width=100><input type="checkbox" name="are_fr" value="1" <%=Checked(are_fr)%>>�C�O</td>
              <td class="n2c" width=100><input type="checkbox" name="are_zk" value="1" <%=Checked(are_zk)%>>�S��</td>
              </tr>
              </table>
            </td>
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

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
