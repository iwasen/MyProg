<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:���o�^���Q�Ɖ��
'******************************************************

SetHeaderTitle "���o�^���", TC_HAKKOUSHA
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
Dim DB, RS, SQL, seq_no

seq_no = Request.QueryString("id")
If IsEmpty(seq_no) Then
  Writeln "�w�肵�����o�^�h�c�̃f�[�^���݂���܂���B"
Else
  Set DB = OpenDB()
  Set RS = Server.CreateObject("ADODB.RecordSet")
  SQL = "SELECT * FROM T_PROVREG WHERE PR_seq_no=" & CLng(seq_no)
  RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
%>
<center>
<form>
  <table border=0 cellspacing=2 cellpadding=3 width="700">
    <tr>
      <td class="m1" width="23%">���s�Җ�</td>
      <td class="n1"><%=RS("PR_h_name")%></td>
    </tr>
    <tr>
      <td class="m1" width="23%">e-mail�A�h���X</td>
      <td class="n1"><%=RS("PR_mail_adr")%></td>
    </tr>
    <tr>
      <td class="m1">�����}�K��</td>
      <td class="n1"><%=ReplaceTag(RS("PR_m_name"))%></td>
    </tr>
    <tr>
      <td class="m1">���s�p�x</td>
      <td class="n1"><%=RS("PR_hindo")%></td>
    </tr>
    <tr>
      <td class="m1">���s����</td>
      <td class="n1"><%=NumFormat(RS("PR_busuu"))%></td>
    </tr>
    <tr>
      <td class="m1">�߂��ς�ID</td>
      <td class="n1"><%=RS("PR_melonpan_id")%></td>
    </tr>
    <tr>
      <td class="m1">���s�V�X�e��</td>
      <td class="n1">
        <table border=0 cellspacing=0 cellpadding=0>
        <tr>
        <td class="n1c" width=130><input type="checkbox" name="mms_melonpan"<%=Checked(RS("PR_mms_melonpan"))%> onclick="return false">�߂��ς�</td>
        <td class="n1c" width=130><input type="checkbox" name="mms_mag"<%=Checked(RS("PR_mms_mag"))%> onclick="return false">�܂��܂�</td>
        <td class="n1c" width=130><input type="checkbox" name="mms_pubz"<%=Checked(RS("PR_mms_pubz"))%> onclick="return false">Pubzine</td>
        <td class="n1c" width=130><input type="checkbox" name="mms_melma"<%=Checked(RS("PR_mms_melma"))%> onclick="return false">melma!</td>
        </tr>
        <tr>
        <td class="n1c" width=130><input type="checkbox" name="mms_macky"<%=Checked(RS("PR_mms_macky"))%> onclick="return false">Macky</td>
        <td class="n1c" width=130><input type="checkbox" name="mms_kapu"<%=Checked(RS("PR_mms_kapu"))%> onclick="return false">�J�v���C�g</td>
        <td class="n1c" width=130><input type="checkbox" name="mms_emag"<%=Checked(RS("PR_mms_emag"))%> onclick="return false">E-MAGAZINE</td>
        <td class="n1c" width=130><input type="checkbox" name="mms_tengoku"<%=Checked(RS("PR_mms_tengoku"))%> onclick="return false">�����}�K�V��</td>
        </tr>
        <tr>
        <td class="n1c" width=130><input type="checkbox" name="mms_tiara"<%=Checked(RS("PR_mms_tiara"))%> onclick="return false">�e�B�A���I�����C��</td>
        <td class="n1c" width=130><input type="checkbox" name="mms_merubox"<%=Checked(RS("PR_mms_merubox"))%> onclick="return false">MERU-BOX</td>
        <td class="n1c" width=130><input type="checkbox" name="mms_dokuji"<%=Checked(RS("PR_mms_dokuji"))%> onclick="return false">�Ǝ��z�M</td>
        <td class="n1c" width=130><input type="checkbox" name="mms_other"<%=Checked(RS("PR_mms_other"))%> onclick="return false">���̑�</td>
        </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td class="m1">�����}�K�J�e�S��</td>
      <td class="n1">
        <table border=0 cellspacing=0 cellpadding=0>
        <tr>
        <td class="n1c" width=170><input type="checkbox" name="cat_int"<%=Checked(RS("PR_cat_int"))%> onclick="return false">�C���^�[�l�b�g</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_pcc"<%=Checked(RS("PR_cat_pcc"))%> onclick="return false">�p�\�R��/�R���s���[�^�S��</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_soft"<%=Checked(RS("PR_cat_soft"))%> onclick="return false">�\�t�g�E�F�A/�v���O���~���O</td>
        </tr>
        <tr>
        <td class="n1c" width=170><input type="checkbox" name="cat_mvl"<%=Checked(RS("PR_cat_mvl"))%> onclick="return false">�g��/���o�C��</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_bns"<%=Checked(RS("PR_cat_bns"))%> onclick="return false">�r�W�l�X�S��</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_seiji"<%=Checked(RS("PR_cat_seiji"))%> onclick="return false">����/�o��</td>
        </tr>
        <tr>
        <td class="n1c" width=170><input type="checkbox" name="cat_kabu"<%=Checked(RS("PR_cat_kabu"))%> onclick="return false">����/����</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_fsn"<%=Checked(RS("PR_cat_fsn"))%> onclick="return false">�t�@�b�V����/���e</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_lif"<%=Checked(RS("PR_cat_lif"))%> onclick="return false">��炵/���L</td>
        </tr>
        <tr>
        <td class="n1c" width=170><input type="checkbox" name="cat_shumi"<%=Checked(RS("PR_cat_shumi"))%> onclick="return false">�</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_res"<%=Checked(RS("PR_cat_res"))%> onclick="return false">�O����/���X�g����</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_tvl"<%=Checked(RS("PR_cat_tvl"))%> onclick="return false">���s/�n��</td>
        </tr>
        <tr>
        <td class="n1c" width=170><input type="checkbox" name="cat_spt"<%=Checked(RS("PR_cat_spt"))%> onclick="return false">�X�|�[�c/���W���[</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_gamble"<%=Checked(RS("PR_cat_gamble"))%> onclick="return false">�M�����u��</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_hlt"<%=Checked(RS("PR_cat_hlt"))%> onclick="return false">���N/���/�玙</td>
        </tr>
        <tr>
        <td class="n1c" width=170><input type="checkbox" name="cat_art"<%=Checked(RS("PR_cat_art"))%> onclick="return false">�A�[�g/���|</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_shp"<%=Checked(RS("PR_cat_shp"))%> onclick="return false">�V���b�s���O</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_ent"<%=Checked(RS("PR_cat_ent"))%> onclick="return false">�G���^�[�e�C�����g</td>
        </tr>
        <tr>
        <td class="n1c" width=170><input type="checkbox" name="cat_game"<%=Checked(RS("PR_cat_game"))%> onclick="return false">�Q�[��/�N�C�Y</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_movie"<%=Checked(RS("PR_cat_movie"))%> onclick="return false">�f��/���y/�|�\</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_uranai"<%=Checked(RS("PR_cat_uranai"))%> onclick="return false">�肢</td>
        </tr>
        <tr>
        <td class="n1c" width=170><input type="checkbox" name="cat_std"<%=Checked(RS("PR_cat_std"))%> onclick="return false">�w�K/����</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_shikaku"<%=Checked(RS("PR_cat_shikaku"))%> onclick="return false">���i</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_gogaku"<%=Checked(RS("PR_cat_gogaku"))%> onclick="return false">��w</td>
        </tr>
        <tr>
        <td class="n1c" width=170><input type="checkbox" name="cat_nws"<%=Checked(RS("PR_cat_nws"))%> onclick="return false">�j���[�X</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_zatsu"<%=Checked(RS("PR_cat_zatsu"))%> onclick="return false">�G�w</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_kenshou"<%=Checked(RS("PR_cat_kenshou"))%> onclick="return false">����/�v���[���g</td>
        </tr>
        <tr>
        <td class="n1c" width=170><input type="checkbox" name="cat_homepage"<%=Checked(RS("PR_cat_homepage"))%> onclick="return false">�z�[���y�[�W�Љ�</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_hitokoto"<%=Checked(RS("PR_cat_hitokoto"))%> onclick="return false">����ЂƂ��ƌn</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_other"<%=Checked(RS("PR_cat_other"))%> onclick="return false">���̑�</td>
        </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td class="m1">�ǎґw�i�N��j</td>
      <td class="n1">
        <table border=0 cellspacing=0 cellpadding=0>
        <tr>
        <td class="n1c" width=90><input type="checkbox" name="age_10m"<%=Checked(RS("PR_age_10m"))%> onclick="return false">10��j</td>
        <td class="n1c" width=90><input type="checkbox" name="age_20m"<%=Checked(RS("PR_age_20m"))%> onclick="return false">20��j</td>
        <td class="n1c" width=90><input type="checkbox" name="age_30m"<%=Checked(RS("PR_age_30m"))%> onclick="return false">30��j</td>
        <td class="n1c" width=90><input type="checkbox" name="age_40m"<%=Checked(RS("PR_age_40m"))%> onclick="return false">40��j</td>
        <td class="n1c" width=90><input type="checkbox" name="age_50m"<%=Checked(RS("PR_age_50m"))%> onclick="return false">50��ȏ�j</td>
        </tr>
        <tr>
        <td class="n1c" width=90><input type="checkbox" name="age_10f"<%=Checked(RS("PR_age_10f"))%> onclick="return false">10�㏗</td>
        <td class="n1c" width=90><input type="checkbox" name="age_20f"<%=Checked(RS("PR_age_20f"))%> onclick="return false">20�㏗</td>
        <td class="n1c" width=90><input type="checkbox" name="age_30f"<%=Checked(RS("PR_age_30f"))%> onclick="return false">30�㏗</td>
        <td class="n1c" width=90><input type="checkbox" name="age_40f"<%=Checked(RS("PR_age_40f"))%> onclick="return false">40�㏗</td>
        <td class="n1c" width=90><input type="checkbox" name="age_50f"<%=Checked(RS("PR_age_50f"))%> onclick="return false">50��ȏ㏗</td>
        </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td class="m1">�ǎґw�i�E�Ɓj</td>
      <td class="n1">
        <table border=0 cellspacing=0 cellpadding=0>
        <tr>
        <td class="n1c" width=100><input type="checkbox" name="shk_stu"<%=Checked(RS("PR_shk_stu"))%> onclick="return false">�w��</td>
        <td class="n1c" width=100><input type="checkbox" name="shk_enp"<%=Checked(RS("PR_shk_enp"))%> onclick="return false">�Љ�l</td>
        <td class="n1c" width=100><input type="checkbox" name="shk_syf"<%=Checked(RS("PR_shk_syf"))%> onclick="return false">��w</td>
        <td class="n1c" width=100><input type="checkbox" name="shk_other"<%=Checked(RS("PR_shk_other"))%> onclick="return false">���̑�</td>
        </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td class="m1">�ǎґw�i�G���A�j</td>
      <td class="n1">
        <table border=0 cellspacing=0 cellpadding=0>
        <tr>
        <td class="n1c" width=100><input type="checkbox" name="are_ht"<%=Checked(RS("PR_are_ht"))%> onclick="return false">�k�C��/���k</td>
        <td class="n1c" width=100><input type="checkbox" name="are_kt"<%=Checked(RS("PR_are_kt"))%> onclick="return false">�֓�</td>
        <td class="n1c" width=100><input type="checkbox" name="are_cb"<%=Checked(RS("PR_are_cb"))%> onclick="return false">����</td>
        <td class="n1c" width=100><input type="checkbox" name="are_kk"<%=Checked(RS("PR_are_kk"))%> onclick="return false">�ߋE</td>
        </tr>
        <tr>
        <td class="n1c" width=100><input type="checkbox" name="are_cs"<%=Checked(RS("PR_are_cs"))%> onclick="return false">����/�l��</td>
        <td class="n1c" width=100><input type="checkbox" name="are_ks"<%=Checked(RS("PR_are_ks"))%> onclick="return false">��B</td>
        <td class="n1c" width=100><input type="checkbox" name="are_fr"<%=Checked(RS("PR_are_fr"))%> onclick="return false">�C�O</td>
        <td class="n1c" width=100><input type="checkbox" name="are_zk"<%=Checked(RS("PR_are_zk"))%> onclick="return false">�S��</td>
        </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td class="m1" width="23%">�o�^��</td>
      <td class="n1"><%=DateFormat(RS("PR_regist_date"))%></td>
    </tr>
    <tr>
      <td class="m1" width="23%">�p�X���[�h</td>
      <td class="n1"><%=RS("PR_password")%></td>
    </tr>
  </table>
</form>
</center>

<%
  RS.Close
End If
%>

</body>
</html>
