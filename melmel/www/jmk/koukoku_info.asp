<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:�L�����e�m�F���
'******************************************************

SetHeaderTitle "�L�����e�m�F", TC_KOUKOKU
Response.CacheControl = "no-cache"
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
  <table border=0 cellspacing=0 cellpadding=0 width='800'>
    <tr>
      <td>���L���˗�����</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width='100%'>
          <tr>
            <td class="m2" width="20%">�N���C�A���gID</td>
            <td class="n2"><%=RS("KK_client_id")%></td>
          </tr>
          <tr>
            <td class="m2">�L���˗��喼</td>
            <td class="n2"><%=ReplaceTag(RS("KK_client_name"))%></td>
          </tr>
          <tr>
            <td class="m2">�g�D��</td>
            <td class="n2"><%=ReplaceTag(RS("KK_company"))%></td>
          </tr>
          <tr>
            <td class="m2">�S���Җ�</td>
            <td class="n2"><%=ReplaceTag(RS("KK_tantousha"))%></td>
          </tr>
          <tr>
            <td class="m2">�A����Ұٱ��ڽ</td>
            <td class="n2"><%=ReplaceTag(RS("KK_mail_adr"))%></td>
          </tr>
          <tr>
            <td class="m2">�A����d�b�ԍ�</td>
            <td class="n2"><%=RS("KK_tel_no")%></td>
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
					</tr>
          <tr>
            <td class="m2">���s����</td>
            <td class="n2"><%=NumFormat(RS("KK_busuu"))%></td>
          </tr>
          <tr>
            <td class="m2">�f�ڈ˗�����</td>
            <td class="n2"><%=RS("KK_keisai_irai")%></td>
          </tr>
          <tr>
            <td class="m2" width=160>�L���^�C�v</td>
            <td class="n2">�^�C�v<%=RS("KK_koukoku_type")%>-<%=RS("KK_koukoku_line")%>�s</td>
          </tr>
          <tr>
            <td class="m2">�L����</td>
            <td class="n2"><textarea name="koukoku_msg" cols=80 rows=<%=RS("KK_koukoku_line")%> class="np" readonly><%=RS("KK_koukoku_msg")%></textarea></td>
          </tr>
          <tr>
            <td class="m2">�L��URL</td>
            <td class="n2"><a href="<%=RS("KK_koukoku_url")%>" target="koukoku_url"><%=ReplaceTag(RS("KK_koukoku_url"))%></a></td>
          </tr>
          <tr>
            <td class="m2">���s�V�X�e��</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=130><input type="checkbox"<%=Checked(RS("KK_mms_melonpan"))%> onclick="return false">�߂��ς�</td>
              <td class="n2c" width=130><input type="checkbox"<%=Checked(RS("KK_mms_mag"))%> onclick="return false">�܂��܂�</td>
              <td class="n2c" width=130><input type="checkbox"<%=Checked(RS("KK_mms_pubz"))%> onclick="return false">Pubzine</td>
              <td class="n2c" width=130><input type="checkbox"<%=Checked(RS("KK_mms_melma"))%> onclick="return false">melma!</td>
              </tr>
              <tr>
              <td class="n2c" width=130><input type="checkbox"<%=Checked(RS("KK_mms_macky"))%> onclick="return false">Macky</td>
              <td class="n2c" width=130><input type="checkbox"<%=Checked(RS("KK_mms_kapu"))%> onclick="return false">�J�v���C�g</td>
              <td class="n2c" width=130><input type="checkbox"<%=Checked(RS("KK_mms_emag"))%> onclick="return false">E-MAGAZINE</td>
              <td class="n2c" width=130><input type="checkbox"<%=Checked(RS("KK_mms_tengoku"))%> onclick="return false">�����}�K�V��</td>
              </tr>
              <tr>
              <td class="n2c" width=130><input type="checkbox"<%=Checked(RS("KK_mms_tiara"))%> onclick="return false">�e�B�A���I�����C��</td>
              <td class="n2c" width=130><input type="checkbox"<%=Checked(RS("KK_mms_merubox"))%> onclick="return false">MERU-BOX</td>
              <td class="n2c" width=130><input type="checkbox"<%=Checked(RS("KK_mms_dokuji"))%> onclick="return false">�Ǝ��z�M</td>
              <td class="n2c" width=130><input type="checkbox"<%=Checked(RS("KK_mms_other"))%> onclick="return false">���̑�</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m2">�����}�K�J�e�S��</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_int"))%> onclick="return false">�C���^�[�l�b�g</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_pcc"))%> onclick="return false">�p�\�R��/�R���s���[�^�S��</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_soft"))%> onclick="return false">�\�t�g�E�F�A/�v���O���~���O</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_mvl"))%> onclick="return false">�g��/���o�C��</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_bns"))%> onclick="return false">�r�W�l�X�S��</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_seiji"))%> onclick="return false">����/�o��</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_kabu"))%> onclick="return false">����/����</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_fsn"))%> onclick="return false">�t�@�b�V����/���e</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_lif"))%> onclick="return false">��炵/���L</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_shumi"))%> onclick="return false">�</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_res"))%> onclick="return false">�O����/���X�g����</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_tvl"))%> onclick="return false">���s/�n��</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_spt"))%> onclick="return false">�X�|�[�c/���W���[</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_gamble"))%> onclick="return false">�M�����u��</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_hlt"))%> onclick="return false">���N/���/�玙</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_art"))%> onclick="return false">�A�[�g/���|</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_shp"))%> onclick="return false">�V���b�s���O</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_ent"))%> onclick="return false">�G���^�[�e�C�����g</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_game"))%> onclick="return false">�Q�[��/�N�C�Y</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_movie"))%> onclick="return false">�f��/���y/�|�\</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_uranai"))%> onclick="return false">�肢</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_std"))%> onclick="return false">�w�K/����</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_shikaku"))%> onclick="return false">���i</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_gogaku"))%> onclick="return false">��w</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_nws"))%> onclick="return false">�j���[�X</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_zatsu"))%> onclick="return false">�G�w</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_kenshou"))%> onclick="return false">����/�v���[���g</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_homepage"))%> onclick="return false">�z�[���y�[�W�Љ�</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_hitokoto"))%> onclick="return false">����ЂƂ��ƌn</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_other"))%> onclick="return false">���̑�</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m2">�ǎґw�i�N��j</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=90><input type="checkbox" <%=Checked(RS("KK_age_10m"))%> onclick="return false">10��j</td>
              <td class="n2c" width=90><input type="checkbox" <%=Checked(RS("KK_age_20m"))%> onclick="return false">20��j</td>
              <td class="n2c" width=90><input type="checkbox" <%=Checked(RS("KK_age_30m"))%> onclick="return false">30��j</td>
              <td class="n2c" width=90><input type="checkbox" <%=Checked(RS("KK_age_40m"))%> onclick="return false">40��j</td>
              <td class="n2c" width=90><input type="checkbox" <%=Checked(RS("KK_age_50m"))%> onclick="return false">50��ȏ�j</td>
              </tr>
              <tr>
              <td class="n2c" width=90><input type="checkbox" <%=Checked(RS("KK_age_10f"))%> onclick="return false">10�㏗</td>
              <td class="n2c" width=90><input type="checkbox" <%=Checked(RS("KK_age_20f"))%> onclick="return false">20�㏗</td>
              <td class="n2c" width=90><input type="checkbox" <%=Checked(RS("KK_age_30f"))%> onclick="return false">30�㏗</td>
              <td class="n2c" width=90><input type="checkbox" <%=Checked(RS("KK_age_40f"))%> onclick="return false">40�㏗</td>
              <td class="n2c" width=90><input type="checkbox" <%=Checked(RS("KK_age_50f"))%> onclick="return false">50��ȏ㏗</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m2">�ǎґw�i�E�Ɓj</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=100><input type="checkbox" <%=Checked(RS("KK_shk_stu"))%> onclick="return false">�w��</td>
              <td class="n2c" width=100><input type="checkbox" <%=Checked(RS("KK_shk_enp"))%> onclick="return false">�Љ�l</td>
              <td class="n2c" width=100><input type="checkbox" <%=Checked(RS("KK_shk_syf"))%> onclick="return false">��w</td>
              <td class="n2c" width=100><input type="checkbox" <%=Checked(RS("KK_shk_other"))%> onclick="return false">���̑�</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m2">�ǎґw�i�G���A�j</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=100><input type="checkbox" <%=Checked(RS("KK_are_ht"))%> onclick="return false">�k�C��/���k</td>
              <td class="n2c" width=100><input type="checkbox" <%=Checked(RS("KK_are_kt"))%> onclick="return false">�֓�</td>
              <td class="n2c" width=100><input type="checkbox" <%=Checked(RS("KK_are_cb"))%> onclick="return false">����</td>
              <td class="n2c" width=100><input type="checkbox" <%=Checked(RS("KK_are_kk"))%> onclick="return false">�ߋE</td>
              </tr>
              <tr>
              <td class="n2c" width=100><input type="checkbox" <%=Checked(RS("KK_are_cs"))%> onclick="return false">����/�l��</td>
              <td class="n2c" width=100><input type="checkbox" <%=Checked(RS("KK_are_ks"))%> onclick="return false">��B</td>
              <td class="n2c" width=100><input type="checkbox" <%=Checked(RS("KK_are_fr"))%> onclick="return false">�C�O</td>
              <td class="n2c" width=100><input type="checkbox" <%=Checked(RS("KK_are_zk"))%> onclick="return false">�S��</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m2">�˗����O�L��ID</td>
            <td class="n2"><%=RS("KK_jogai_id")%></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</center>
<%
End If
RS.Close
%>
</body>
</html>
