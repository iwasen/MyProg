<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<!--#INCLUDE FILE="waku_kanri_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:�󂫘g�󋵈ꗗ�\��
'******************************************************

SetHeaderTitle "�󂫘g�󋵊m�F", TC_KOUKOKU
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="admin.css">
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<%
mms_melonpan = Request.Form("mms_melonpan")
mms_mag = Request.Form("mms_mag")
mms_pubz = Request.Form("mms_pubz")
mms_melma = Request.Form("mms_melma")
mms_macky = Request.Form("mms_macky")
mms_kapu = Request.Form("mms_kapu")
mms_emag = Request.Form("mms_emag")
mms_tengoku = Request.Form("mms_tengoku")
mms_tiara = Request.Form("mms_tiara")
mms_merubox = Request.Form("mms_merubox")
mms_dokuji = Request.Form("mms_dokuji")
mms_other = Request.Form("mms_other")
cat_int = Request.Form("cat_int")
cat_pcc = Request.Form("cat_pcc")
cat_soft = Request.Form("cat_soft")
cat_mvl = Request.Form("cat_mvl")
cat_bns = Request.Form("cat_bns")
cat_seiji = Request.Form("cat_seiji")
cat_kabu = Request.Form("cat_kabu")
cat_fsn = Request.Form("cat_fsn")
cat_lif = Request.Form("cat_lif")
cat_shumi = Request.Form("cat_shumi")
cat_res = Request.Form("cat_res")
cat_tvl = Request.Form("cat_tvl")
cat_spt = Request.Form("cat_spt")
cat_gamble = Request.Form("cat_gamble")
cat_hlt = Request.Form("cat_hlt")
cat_art = Request.Form("cat_art")
cat_shp = Request.Form("cat_shp")
cat_ent = Request.Form("cat_ent")
cat_game = Request.Form("cat_game")
cat_movie = Request.Form("cat_movie")
cat_uranai = Request.Form("cat_uranai")
cat_std = Request.Form("cat_std")
cat_shikaku = Request.Form("cat_shikaku")
cat_gogaku = Request.Form("cat_gogaku")
cat_nws = Request.Form("cat_nws")
cat_zatsu = Request.Form("cat_zatsu")
cat_kenshou = Request.Form("cat_kenshou")
cat_homepage = Request.Form("cat_homepage")
cat_hitokoto = Request.Form("cat_hitokoto")
cat_other = Request.Form("cat_other")
age_10m = Request.Form("age_10m")
age_10f = Request.Form("age_10f")
age_20m = Request.Form("age_20m")
age_20f = Request.Form("age_20f")
age_30m = Request.Form("age_30m")
age_30f = Request.Form("age_30f")
age_40m = Request.Form("age_40m")
age_40f = Request.Form("age_40f")
age_50m = Request.Form("age_50m")
age_50f = Request.Form("age_50f")
shk_stu = Request.Form("shk_stu")
shk_enp = Request.Form("shk_enp")
shk_syf = Request.Form("shk_syf")
shk_other = Request.Form("shk_other")
are_ht = Request.Form("are_ht")
are_kt = Request.Form("are_kt")
are_cb = Request.Form("are_cb")
are_kk = Request.Form("are_kk")
are_cs = Request.Form("are_cs")
are_ks = Request.Form("are_ks")
are_fr = Request.Form("are_fr")
are_zk = Request.Form("are_zk")
%>
<center>
<table width="75%">
  <tr>
    <td align="left">���󂫘g�󋵈ꗗ</td>
  </tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="75%" align="center">
  <tr bgcolor="#FFE0E0">
    <th>�T�R�[�h</th>
    <th>�J�n��</th>
    <th>����</th>
    <th>�`�E�p�^�C�v��g</th>
    <th>�a�E�q�^�C�v��g</th>
  </tr>

<%
Dim DB, RS, SQL, line
Dim shisuu, type_a, type_b

Set DB = OpenDB()

Call GetTotalBusuu(shisuu, type_a, type_b)
type_a = type_a * 2
type_b = type_b * 2

Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = GetUmariWaku()
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly

line = 1
Do Until RS.EOF
  If line Mod 2 = 0 Then
    Writeln "<tr bgcolor='#FFFFFF'>"
  Else
    Writeln "<tr bgcolor='#E0FFE0'>"
  End If
  Writeln "<td align='center'>" & RS("WE_shuu_code") & "</td>"
  Writeln "<td align='center'>" & RS("WE_start_date") & "</td>"
  Writeln "<td align='right'>" & NumFormat(shisuu - NVL(RS("shisuu"), 0)) & "</td>"
  Writeln "<td align='right'>" & NumFormat(type_a - NVL(RS("type_a"), 0)) & "</td>"
  Writeln "<td align='right'>" & NumFormat(type_b - NVL(RS("type_b"), 0)) & "</td>"
  Writeln "</tr>"

  RS.MoveNext
  line = line + 1
Loop
RS.close
%>
</table>
<form method="post" name="form1" action="akiwaku.asp">
<%
If IsEmpty(Request("jouken")) Then
%>
<input type="submit" name="jouken" value="�����w��">
<%
Else
%>
<input type="submit" name="jouken" value=" �ĕ\�� ">
<table border=0 cellspacing=0 cellpadding=0>
  <tr>
    <td>�������ݒ�</td>
  </tr>
  <tr>
    <td>
      <table border=0 cellspacing=2 cellpadding=3 width='700'>
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
<%
End If
%>
</form>
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
