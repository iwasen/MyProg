<!--#INCLUDE FILE="../melmel_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�L��������y�[�W
' Content:�L�����e�m�F���
'******************************************************

Response.CacheControl = "no-cache"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title>�L�����e�m�F</title>
<LINK rel="stylesheet" href="../melmel.css">
<link rel="stylesheet" type="text/css" href="client.css">
</head>
<body>

<%
Dim DB, RS, SQL

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")

SQL = "SELECT *" & vbCrLf & _
      "FROM V_KOUKOKU" & vbCrLf & _
      "WHERE KK_koukoku_id='" & Request.QueryString("id") & "'"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly

If Not RS.EOF Then
%>
<center>
<form>
  <table border=0 cellspacing=0 cellpadding=0 width='650' class="kihon">
    <tr>
      <td>���L�����</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width='100%' class="kihon">
          <tr>
            <td class="m2" width="22%">�L��ID</td>
            <td class="n2"><%=RS("KK_koukoku_id")%></td>
          </tr>
          <tr>
            <td class="m2">�L����</td>
            <td class="n2"><%=ReplaceTag(RS("KK_koukoku_name"))%></td>
          </tr>
          <tr>
            <td class="m2">�L���f�ڏT</td>
            <td class="n2"><%=RS("KK_koukoku_shuu")%></td>
          </tr>
          <tr>
            <td class="m2">�z�M�J�n��</td>
            <td class="n2"><%=DateFormat(RS("KK_pub_start"))%></td>
          </tr>
          <tr>
            <td class="m2">�z�M�I����</td>
            <td class="n2"><%=DateFormat(RS("KK_pub_end"))%></td>
          </tr>
          <tr>
            <td class="m2" width=160>�񍐊���</td>
            <td class="n2"><%=DateFormat(RS("KK_limit_date"))%></td>
          <tr>
            <td class="m2">���s����</td>
            <td class="n2"><%=NumFormat(RS("KK_busuu"))%></td>
          </tr>
          <tr>
            <td class="m2" width=160>�L���^�C�v</td>
            <td class="n2">�^�C�v<%=RS("KK_koukoku_type")%>-<%=RS("KK_koukoku_line")%>�s</td>
          </tr>
          <tr>
            <td class="m2">�L����</td>
            <td class="n2"><font class="np"><%=ReplaceTag(RS("KK_koukoku_msg"))%></font></td>
          </tr>
          <tr>
            <td class="m2">���s�V�X�e��</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=130><input type="checkbox" <%=Checked(RS("KK_mms_melonpan"))%>>�߂��ς�</td>
              <td class="n2c" width=130><input type="checkbox" <%=Checked(RS("KK_mms_mag"))%>>�܂��܂�</td>
              <td class="n2c" width=130><input type="checkbox" <%=Checked(RS("KK_mms_pubz"))%>>Pubzine</td>
              <td class="n2c" width=130><input type="checkbox" <%=Checked(RS("KK_mms_melma"))%>>melma!</td>
              </tr>
              <tr>
              <td class="n2c" width=130><input type="checkbox" <%=Checked(RS("KK_mms_macky"))%>>Macky</td>
              <td class="n2c" width=130><input type="checkbox" <%=Checked(RS("KK_mms_kapu"))%>>�J�v���C�g</td>
              <td class="n2c" width=130><input type="checkbox" <%=Checked(RS("KK_mms_emag"))%>>E-MAGAZINE</td>
              <td class="n2c" width=130><input type="checkbox" <%=Checked(RS("KK_mms_tengoku"))%>>�����}�K�V��</td>
              </tr>
              <tr>
              <td class="n2c" width=130><input type="checkbox" <%=Checked(RS("KK_mms_tiara"))%>>�e�B�A���I�����C��</td>
              <td class="n2c" width=130><input type="checkbox" <%=Checked(RS("KK_mms_merubox"))%>>MERU-BOX</td>
              <td class="n2c" width=130><input type="checkbox" <%=Checked(RS("KK_mms_dokuji"))%>>�Ǝ��z�M</td>
              <td class="n2c" width=130><input type="checkbox" <%=Checked(RS("KK_mms_other"))%>>���̑�</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m2">�����}�K�J�e�S��</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_int"))%>>�C���^�[�l�b�g</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_pcc"))%>>�p�\�R��/�R���s���[�^�S��</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_soft"))%>>�\�t�g�E�F�A/�v���O���~���O</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_mvl"))%>>�g��/���o�C��</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_bns"))%>>�r�W�l�X�S��</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_seiji"))%>>����/�o��</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_kabu"))%>>����/����</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_fsn"))%>>�t�@�b�V����/���e</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_lif"))%>>��炵/���L</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_shumi"))%>>�</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_res"))%>>�O����/���X�g����</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_tvl"))%>>���s/�n��</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_spt"))%>>�X�|�[�c/���W���[</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_gamble"))%>>�M�����u��</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_hlt"))%>>���N/���/�玙</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_art"))%>>�A�[�g/���|</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_shp"))%>>�V���b�s���O</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_ent"))%>>�G���^�[�e�C�����g</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_game"))%>>�Q�[��/�N�C�Y</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_movie"))%>>�f��/���y/�|�\</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_uranai"))%>>�肢</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_std"))%>>�w�K/����</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_shikaku"))%>>���i</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_gogaku"))%>>��w</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_nws"))%>>�j���[�X</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_zatsu"))%>>�G�w</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_kenshou"))%>>����/�v���[���g</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_homepage"))%>>�z�[���y�[�W�Љ�</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_hitokoto"))%>>����ЂƂ��ƌn</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_other"))%>>���̑�</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m2">�ǎґw�i�N��j</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=80><input type="checkbox"<%=Checked(RS("KK_age_10m"))%>>10��j</td>
              <td class="n2c" width=80><input type="checkbox"<%=Checked(RS("KK_age_20m"))%>>20��j</td>
              <td class="n2c" width=80><input type="checkbox"<%=Checked(RS("KK_age_30m"))%>>30��j</td>
              <td class="n2c" width=80><input type="checkbox"<%=Checked(RS("KK_age_40m"))%>>40��j</td>
              <td class="n2c" width=80><input type="checkbox"<%=Checked(RS("KK_age_50m"))%>>50��ȏ�j</td>
              </tr>
              <tr>
              <td class="n2c" width=80><input type="checkbox"<%=Checked(RS("KK_age_10f"))%>>10�㏗</td>
              <td class="n2c" width=80><input type="checkbox"<%=Checked(RS("KK_age_20f"))%>>20�㏗</td>
              <td class="n2c" width=80><input type="checkbox"<%=Checked(RS("KK_age_30f"))%>>30�㏗</td>
              <td class="n2c" width=80><input type="checkbox"<%=Checked(RS("KK_age_40f"))%>>40�㏗</td>
              <td class="n2c" width=80><input type="checkbox"<%=Checked(RS("KK_age_50f"))%>>50��ȏ㏗</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m2">�ǎґw�i�E�Ɓj</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=100><input type="checkbox"<%=Checked(RS("KK_shk_stu"))%>>�w��</td>
              <td class="n2c" width=100><input type="checkbox"<%=Checked(RS("KK_shk_enp"))%>>�Љ�l</td>
              <td class="n2c" width=100><input type="checkbox"<%=Checked(RS("KK_shk_syf"))%>>��w</td>
              <td class="n2c" width=100><input type="checkbox"<%=Checked(RS("KK_shk_other"))%>>���̑�</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m2">�ǎґw�i�G���A�j</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=100><input type="checkbox"<%=Checked(RS("KK_are_ht"))%>>�k�C��/���k</td>
              <td class="n2c" width=100><input type="checkbox"<%=Checked(RS("KK_are_kt"))%>>�֓�</td>
              <td class="n2c" width=100><input type="checkbox"<%=Checked(RS("KK_are_cb"))%>>����</td>
              <td class="n2c" width=100><input type="checkbox"<%=Checked(RS("KK_are_kk"))%>>�ߋE</td>
              </tr>
              <tr>
              <td class="n2c" width=100><input type="checkbox"<%=Checked(RS("KK_are_cs"))%>>����/�l��</td>
              <td class="n2c" width=100><input type="checkbox"<%=Checked(RS("KK_are_ks"))%>>��B</td>
              <td class="n2c" width=100><input type="checkbox"<%=Checked(RS("KK_are_fr"))%>>�C�O</td>
              <td class="n2c" width=100><input type="checkbox"<%=Checked(RS("KK_are_zk"))%>>�S��</td>
              </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
</center>
<%
End If
RS.Close
%>
</body>
</html>
