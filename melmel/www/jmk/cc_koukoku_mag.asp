<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:�����}�K�����ʃN���b�N���\��
'******************************************************

SetHeaderTitle "�����}�K�����ʃN���b�N��", TC_KOUKOKU
Response.CacheControl = "no-cache"
%>
<%
Dim DB, RS, SQL
Dim koukoku_id, koukoku_name

koukoku_id = Request.Form("sel")
If IsEmpty(koukoku_id) Then
  Response.Redirect("cc_koukoku.asp")
End If

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")

SQL = "SELECT KK_koukoku_name FROM V_KOUKOKU WHERE KK_koukoku_id='" & koukoku_id & "'"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
If Not RS.EOF Then
  koukoku_name = RS("KK_koukoku_name")
End If
RS.Close

SQL = "SELECT" & vbCrLf & _
			"COUNT(NULLIF(MG_mms_melonpan, 0)) AS mms_melonpan," & vbCrLf & _
			"COUNT(NULLIF(MG_mms_mag, 0)) AS mms_mag," & vbCrLf & _
			"COUNT(NULLIF(MG_mms_pubz, 0)) AS mms_pubz," & vbCrLf & _
			"COUNT(NULLIF(MG_mms_macky, 0)) AS mms_macky," & vbCrLf & _
			"COUNT(NULLIF(MG_mms_kapu, 0)) AS mms_kapu," & vbCrLf & _
			"COUNT(NULLIF(MG_mms_emag, 0)) AS mms_emag," & vbCrLf & _
			"COUNT(NULLIF(MG_mms_melma, 0)) AS mms_melma," & vbCrLf & _
			"COUNT(NULLIF(MG_mms_tiara, 0)) AS mms_tiara," & vbCrLf & _
			"COUNT(NULLIF(MG_mms_merubox, 0)) AS mms_merubox," & vbCrLf & _
			"COUNT(NULLIF(MG_mms_dokuji, 0)) AS mms_dokuji," & vbCrLf & _
			"COUNT(NULLIF(MG_mms_tengoku, 0)) AS mms_tengoku," & vbCrLf & _
			"COUNT(NULLIF(MG_mms_other, 0)) AS mms_other," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_int, 0)) AS cat_int," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_pcc, 0)) AS cat_pcc," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_soft, 0)) AS cat_soft," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_mvl, 0)) AS cat_mvl," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_bns, 0)) AS cat_bns," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_seiji, 0)) AS cat_seiji," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_kabu, 0)) AS cat_kabu," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_fsn, 0)) AS cat_fsn," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_lif, 0)) AS cat_lif," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_shumi, 0)) AS cat_shumi," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_res, 0)) AS cat_res," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_tvl, 0)) AS cat_tvl," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_spt, 0)) AS cat_spt," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_gamble, 0)) AS cat_gamble," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_hlt, 0)) AS cat_hlt," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_art, 0)) AS cat_art," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_shp, 0)) AS cat_shp," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_ent, 0)) AS cat_ent," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_game, 0)) AS cat_game," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_movie, 0)) AS cat_movie," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_uranai, 0)) AS cat_uranai," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_std, 0)) AS cat_std," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_shikaku, 0)) AS cat_shikaku," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_gogaku, 0)) AS cat_gogaku," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_nws, 0)) AS cat_nws," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_zatsu, 0)) AS cat_zatsu," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_kenshou, 0)) AS cat_kenshou," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_homepage, 0)) AS cat_homepage," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_hitokoto, 0)) AS cat_hitokoto," & vbCrLf & _
			"COUNT(NULLIF(MG_cat_other, 0)) AS cat_other," & vbCrLf & _
			"COUNT(NULLIF(MG_age_10m, 0)) AS age_10m," & vbCrLf & _
			"COUNT(NULLIF(MG_age_10f, 0)) AS age_10f," & vbCrLf & _
			"COUNT(NULLIF(MG_age_20m, 0)) AS age_20m," & vbCrLf & _
			"COUNT(NULLIF(MG_age_20f, 0)) AS age_20f," & vbCrLf & _
			"COUNT(NULLIF(MG_age_30m, 0)) AS age_30m," & vbCrLf & _
			"COUNT(NULLIF(MG_age_30f, 0)) AS age_30f," & vbCrLf & _
			"COUNT(NULLIF(MG_age_40m, 0)) AS age_40m," & vbCrLf & _
			"COUNT(NULLIF(MG_age_40f, 0)) AS age_40f," & vbCrLf & _
			"COUNT(NULLIF(MG_age_50m, 0)) AS age_50m," & vbCrLf & _
			"COUNT(NULLIF(MG_age_50f, 0)) AS age_50f," & vbCrLf & _
			"COUNT(NULLIF(MG_shk_stu, 0)) AS shk_stu," & vbCrLf & _
			"COUNT(NULLIF(MG_shk_enp, 0)) AS shk_enp," & vbCrLf & _
			"COUNT(NULLIF(MG_shk_syf, 0)) AS shk_syf," & vbCrLf & _
			"COUNT(NULLIF(MG_shk_other, 0)) AS shk_other," & vbCrLf & _
			"COUNT(NULLIF(MG_are_ht, 0)) AS are_ht," & vbCrLf & _
			"COUNT(NULLIF(MG_are_kt, 0)) AS are_kt," & vbCrLf & _
			"COUNT(NULLIF(MG_are_cb, 0)) AS are_cb," & vbCrLf & _
			"COUNT(NULLIF(MG_are_kk, 0)) AS are_kk," & vbCrLf & _
			"COUNT(NULLIF(MG_are_cs, 0)) AS are_cs," & vbCrLf & _
			"COUNT(NULLIF(MG_are_ks, 0)) AS are_ks," & vbCrLf & _
			"COUNT(NULLIF(MG_are_fr, 0)) AS are_fr," & vbCrLf & _
			"COUNT(NULLIF(MG_are_zk, 0)) AS are_zk" & vbCrLf & _
			"FROM T_CLICKLOG INNER JOIN T_MELMAG ON CL_mag_id=MG_mag_id" & vbCrLf & _
			"WHERE CL_koukoku_id='" & koukoku_id & "'"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
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
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<form method="post" name="form1" style="margin:0">
<table align="center" width="80%">
  <tr>
    <td align="left">��<%=koukoku_id%>&nbsp;<%=koukoku_name%></td>
    <td align="right"><input type="button" value=" �߂� " onClick="history.back()" id=button1 name=button1></td>
  </tr>
</table>

<table border=1 cellspacing=2 cellpadding=0 width='80%' align="center">
  <tr align="center">
    <td bgcolor="#FFE0E0"><nobr><b>���s�V�X�e��</b></nobr></td>
    <td>
      <table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
        <tr bgcolor="#E0FFE0" align="center">
					<td width="33%">�߂��ς�</td>
					<td width="33%">�܂��܂�</td>
					<td width="33%">Pubzine</td>
        </tr>
        <tr align="right">
					<td><%=RS("mms_melonpan")%></td>
					<td><%=RS("mms_mag")%></td>
					<td><%=RS("mms_pubz")%></td>
        </tr>
        <tr bgcolor="#E0FFE0" align="center">
					<td width="33%">melma!</td>
					<td width="33%">Macky</td>
					<td width="33%">�J�v���C�g</td>
        </tr>
        <tr align="right">
					<td><%=RS("mms_melma")%></td>
					<td><%=RS("mms_macky")%></td>
					<td><%=RS("mms_kapu")%></td>
        </tr>
        <tr bgcolor="#E0FFE0" align="center">
					<td width="33%">E-MAGAZINE</td>
					<td width="33%">�����}�K�V��</td>
					<td width="33%">�e�B�A���I�����C��</td>
        </tr>
        <tr align="right">
					<td><%=RS("mms_emag")%></td>
					<td><%=RS("mms_tengoku")%></td>
					<td><%=RS("mms_tiara")%></td>
        </tr>
        <tr bgcolor="#E0FFE0" align="center">
					<td width="33%">MERU-BOX</td>
					<td width="33%">�Ǝ��z�M</td>
					<td width="33%">���̑�</td>
        </tr>
        <tr align="right">
					<td><%=RS("mms_merubox")%></td>
					<td><%=RS("mms_dokuji")%></td>
					<td><%=RS("mms_other")%></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr align="center">
    <td bgcolor="#FFE0E0"><nobr><b>�����}�K�J�e�S��</b></nobr></td>
    <td>
      <table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
        <tr bgcolor="#E0FFE0" align="center">
					<td width="33%">�C���^�[�l�b�g</td>
					<td width="33%">�p�\�R��/�R���s���[�^�S��</td>
					<td width="33%">�\�t�g�E�F�A/�v���O���~���O</td>
        </tr>
        <tr align="right">
					<td><%=RS("cat_int")%></td>
					<td><%=RS("cat_pcc")%></td>
					<td><%=RS("cat_soft")%></td>
        </tr>
        <tr bgcolor="#E0FFE0" align="center">
					<td width="33%">�g��/���o�C��</td>
					<td width="33%">�r�W�l�X�S��</td>
					<td width="33%">����/�o��</td>
        </tr>
        <tr align="right">
					<td><%=RS("cat_mvl")%></td>
					<td><%=RS("cat_bns")%></td>
					<td><%=RS("cat_seiji")%></td>
        </tr>
        <tr bgcolor="#E0FFE0" align="center">
					<td width="33%">����/����</td>
					<td width="33%">�t�@�b�V����/���e</td>
					<td width="33%">��炵/���L</td>
        </tr>
        <tr align="right">
					<td><%=RS("cat_kabu")%></td>
					<td><%=RS("cat_fsn")%></td>
					<td><%=RS("cat_lif")%></td>
        </tr>
        <tr bgcolor="#E0FFE0" align="center">
					<td width="33%">�</td>
					<td width="33%">�O����/���X�g����</td>
					<td width="33%">���s/�n��</td>
        </tr>
        <tr align="right">
					<td><%=RS("cat_shumi")%></td>
					<td><%=RS("cat_res")%></td>
					<td><%=RS("cat_tvl")%></td>
        </tr>
        <tr bgcolor="#E0FFE0" align="center">
					<td width="33%">�X�|�[�c/���W���[</td>
					<td width="33%">�M�����u��</td>
					<td width="33%">���N/���/�玙</td>
        </tr>
        <tr align="right">
					<td><%=RS("cat_spt")%></td>
					<td><%=RS("cat_gamble")%></td>
					<td><%=RS("cat_hlt")%></td>
        </tr>
        <tr bgcolor="#E0FFE0" align="center">
					<td width="33%">�A�[�g/���|</td>
					<td width="33%">�V���b�s���O</td>
					<td width="33%">�G���^�[�e�C�����g</td>
        </tr>
        <tr align="right">
					<td><%=RS("cat_art")%></td>
					<td><%=RS("cat_shp")%></td>
					<td><%=RS("cat_ent")%></td>
        </tr>
        <tr bgcolor="#E0FFE0" align="center">
					<td width="33%">�Q�[��/�N�C�Y</td>
					<td width="33%">�f��/���y/�|�\</td>
					<td width="33%">�肢</td>
        </tr>
        <tr align="right">
					<td><%=RS("cat_game")%></td>
					<td><%=RS("cat_movie")%></td>
					<td><%=RS("cat_uranai")%></td>
        </tr>
        <tr bgcolor="#E0FFE0" align="center">
					<td width="33%">�w�K/����</td>
					<td width="33%">���i</td>
					<td width="33%">��w</td>
        </tr>
        <tr align="right">
					<td><%=RS("cat_std")%></td>
					<td><%=RS("cat_shikaku")%></td>
					<td><%=RS("cat_gogaku")%></td>
        </tr>
        <tr bgcolor="#E0FFE0" align="center">
					<td width="33%">�j���[�X</td>
					<td width="33%">�G�w</td>
					<td width="33%">����/�v���[���g</td>
        </tr>
        <tr align="right">
					<td><%=RS("cat_nws")%></td>
					<td><%=RS("cat_zatsu")%></td>
					<td><%=RS("cat_kenshou")%></td>
        </tr>
        <tr bgcolor="#E0FFE0" align="center">
					<td width="33%">�z�[���y�[�W�Љ�</td>
					<td width="33%">����ЂƂ��ƌn</td>
					<td width="33%">���̑�</td>
        </tr>
        <tr align="right">
					<td><%=RS("cat_homepage")%></td>
					<td><%=RS("cat_hitokoto")%></td>
					<td><%=RS("cat_other")%></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr align="center">
    <td bgcolor="#FFE0E0"><nobr><b>�ǎґw�i�N��j</b></nobr></td>
    <td>
      <table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
        <tr bgcolor="#E0FFE0" align="center">
          <td width="20%">10��j</td>
          <td width="20%">20��j</td>
          <td width="20%">30��j</td>
          <td width="20%">40��j</td>
          <td width="20%">50��ȏ�j</td>
        </tr>
        <tr align="right">
          <td><%=RS("age_10m")%></td>
          <td><%=RS("age_20m")%></td>
          <td><%=RS("age_30m")%></td>
          <td><%=RS("age_40m")%></td>
          <td><%=RS("age_50m")%></td>
        </tr>
        <tr bgcolor="#E0FFE0" align="center">
          <td width="20%">10�㏗</td>
          <td width="20%">20�㏗</td>
          <td width="20%">30�㏗</td>
          <td width="20%">40�㏗</td>
          <td width="20%">50��ȏ㏗</td>
        </tr>
        <tr align="right">
          <td><%=RS("age_10f")%></td>
          <td><%=RS("age_20f")%></td>
          <td><%=RS("age_30f")%></td>
          <td><%=RS("age_40f")%></td>
          <td><%=RS("age_50f")%></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr align="center">
    <td bgcolor="#FFE0E0"><nobr><b>�ǎґw�i�E�Ɓj</b></nobr></td>
    <td>
      <table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
        <tr bgcolor="#E0FFE0" align="center">
          <td width="25%">�w��</td>
          <td width="25%">�Љ�l</td>
          <td width="25%">��w</td>
          <td width="25%">���̑�</td>
        </tr>
        <tr align="right">
          <td><%=RS("shk_stu")%></td>
          <td><%=RS("shk_enp")%></td>
          <td><%=RS("shk_syf")%></td>
          <td><%=RS("shk_other")%></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr align="center">
    <td bgcolor="#FFE0E0"><nobr><b>�ǎґw�i�G���A�j</b></nobr></td>
    <td>
      <table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
        <tr bgcolor="#E0FFE0" align="center">
          <td width="25%">�k�C��/���k</td>
          <td width="25%">�֓�</td>
          <td width="25%">����</td>
          <td width="25%">�ߋE</td>
        </tr>
        <tr align="right">
          <td><%=RS("are_ht")%></td>
          <td><%=RS("are_kt")%></td>
          <td><%=RS("are_kt")%></td>
          <td><%=RS("are_kk")%></td>
        </tr>
        <tr bgcolor="#E0FFE0" align="center">
          <td width="25%">����/�l��</td>
          <td width="25%">��B</td>
          <td width="25%">�C�O</td>
          <td width="25%">�S��</td>
        </tr>
        <tr align="right">
          <td><%=RS("are_cs")%></td>
          <td><%=RS("are_ks")%></td>
          <td><%=RS("are_fr")%></td>
          <td><%=RS("are_zk")%></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
