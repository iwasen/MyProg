<!--#INCLUDE FILE="../melmel_inc.asp"-->
<%
If Session("mel_id") = "" then
	Response.Redirect "./false.html"
Else
	Dim DB, RS, SQL, vPOINT, hknm, syori

	Set DB = OpenDB()
	SQL = "SELECT * FROM T_MELMAG WHERE MG_mel_id = '" & Session("mel_id") & "' AND MG_mag_id = '" & Request.QueryString("p") & "' AND (MG_status=0 OR MG_status=1)"

	Set RS = Server.CreateObject("ADODB.RecordSet")
	RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly

	If RS.EOF Then
	'--- No Entry ---
		Response.Redirect "./false.html"
	Else
	Session("mag_id") = Request.QueryString("p")

%>
<html>
<head>
<title>�߂�߂�Enet �����}�K���̍X�V</title>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<LINK rel="stylesheet" href="../melmel.css">
<script language="JavaScript">
<!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v3.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function OnSubmit_form1(form) {
  var cnt = 0;
  if (form.cat_int.checked)
    cnt++;
  if (form.cat_pcc.checked)
    cnt++;
  if (form.cat_soft.checked)
    cnt++;
  if (form.cat_mvl.checked)
    cnt++;
  if (form.cat_bns.checked)
    cnt++;
  if (form.cat_seiji.checked)
    cnt++;
  if (form.cat_kabu.checked)
    cnt++;
  if (form.cat_fsn.checked)
    cnt++;
  if (form.cat_lif.checked)
    cnt++;
  if (form.cat_shumi.checked)
    cnt++;
  if (form.cat_res.checked)
    cnt++;
  if (form.cat_tvl.checked)
    cnt++;
  if (form.cat_spt.checked)
    cnt++;
  if (form.cat_gamble.checked)
    cnt++;
  if (form.cat_hlt.checked)
    cnt++;
  if (form.cat_art.checked)
    cnt++;
  if (form.cat_shp.checked)
    cnt++;
  if (form.cat_ent.checked)
    cnt++;
  if (form.cat_game.checked)
    cnt++;
  if (form.cat_movie.checked)
    cnt++;
  if (form.cat_uranai.checked)
    cnt++;
  if (form.cat_std.checked)
    cnt++;
  if (form.cat_shikaku.checked)
    cnt++;
  if (form.cat_gogaku.checked)
    cnt++;
  if (form.cat_nws.checked)
    cnt++;
  if (form.cat_zatsu.checked)
    cnt++;
  if (form.cat_kenshou.checked)
    cnt++;
  if (form.cat_homepage.checked)
    cnt++;
  if (form.cat_hitokoto.checked)
    cnt++;
  if (form.cat_other.checked)
    cnt++;
  if (cnt > 2) {
    alert("�����}�K�J�e�S���͂Q�ȓ��őI�����Ă��������B")
    return false;
  }
  if (form.status.selectedIndex == 2)
    return confirm("�p����I������Ƃ��̃����}�K�͓o�^�𖕏�����A�Ȍ�ꗗ�ɂ��\������Ȃ��Ȃ�܂��B��낵���ł����H");
  else
    return true;
}
//-->
</script>
</head>
<body bgcolor="#ff9999" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="MM_preloadImages('../images/tab_koukoku1b.gif','../images/tab_top1.gif')">
<TABLE width="600" border="0" cellspacing="0" cellpadding="0" align="center">
  <TR>
    <TD colspan="3">
      <TABLE bgcolor="#ff9999" border="0" cellpadding="0" cellspacing="0" width="600" align="center">
        <TR> <!-- Shim row, height 1. -->
          <TD><IMG src="../images/shim.gif" width="252" height="1" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="348" height="1" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="1" height="1" border="0"></TD>
        </TR>
        <TR valign="top"><!-- row 1 -->
          <TD rowspan="3"><A HREF="../index.html"><IMG name="head_logo" src="../images/head_logo.gif" width="252" height="96" border="0" alt="�߂�߂�Enet ���S"></A></TD>
          <TD><IMG src="../images/shim.gif" width="348" height="26" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="1" height="26" border="0"></TD>
        </TR>
        <TR valign="top"><!-- row 2 -->
          <TD><IMG name="head_t1" src="../images/head_t1.gif" width="348" height="52" border="0" alt="���s�Ҍ����y�[�W"></TD>
          <TD><IMG src="../images/shim.gif" width="1" height="52" border="0"></TD>
        </TR>
        <TR valign="top"><!-- row 3 -->
          <TD><IMG src="../images/shim.gif" width="348" height="18" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="1" height="18" border="0"></TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
<!--  <TR>
    <TD valign="top" colspan="3" align="left">
      <TABLE width="405" border="0" cellspacing="0" cellpadding="0">
        <TR>
          <TD><A href="../index.html" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image45','','../images/tab_top1.gif',1)"><IMG name="Image45" border="0" src="../images/tab_top1b.gif" width="130" height="25" alt="�g�b�v�y�[�W"></A></TD>
          <TD><A href="pubtop.asp"><IMG src="../images/tab_hakko1b.gif" width="130" height="25" alt="���s�Ҍ����g�b�v�y�[�W" border="0"></A></TD>
          <TD><A href="../agenttop.html" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image44','','../images/tab_koukoku1b.gif',1)"><IMG name="Image44" border="0" src="../images/tab_koukoku1.gif" width="130" height="25" alt="�L���㗝�X�����g�b�v�y�[�W"></A></TD>
          <TD><IMG src="../images/tab_tale1.gif" width="15" height="25"></TD>
        </TR>
      </TABLE>
    </TD>
  </TR>-->
  <TR>
    <TD valign="top" bgcolor="#FFFF00"><IMG src="../images/shim.gif" width="130" height="10"></TD>
    <TD valign="top" bgcolor="#FFFF00"><IMG src="../images/shim.gif" width="461" height="10"></TD>
    <TD valign="top"><IMG src="../images/shadow5.gif" width="9" height="10"></TD>
  </TR>
  <TR>
    <TD valign="top" bgcolor="#FFFF00" width="130">
      <TABLE width="130" border="0" cellspacing="0" cellpadding="0">
        <TR align="center">
          <TD><IMG src="images/menu.gif" width="130" height="20" alt="MENU"></TD>
        </TR>
        <TR align="center">
          <TD><A href="pubtop.asp"><IMG src="images/b_memb2.gif" width="112" height="34" alt="�|�C���g���Z�m�F" border="0"></A></TD>
        </TR>
        <tr align="center">
          <td><a href="pointhst.asp"><img src="images/b_memb10.gif" width="112" height="34" alt="�|�C���g����" border="0"></a></td>
        </tr>
        <TR align="center">
          <TD><A href="regedit.asp"><IMG src="images/b_memb1.gif" width="112" height="34" alt="�o�^���C��" border="0"></A></TD>
        </TR>
        <TR align="center">
          <TD><IMG src="images/b_memb3b.gif" width="112" height="34" alt="�����}�K���C��"></TD>
        </TR>
        <TR align="center">
          <TD><A href="rexxxheck.asp"><IMG src="images/b_memb4.gif" width="112" height="34" alt="�x��\������" border="0"></A></TD>
        </TR>
        <TR align="center">
          <TD><A href="backcheck.asp"><IMG src="images/b_memb5.gif" width="112" height="34" alt="����\������" border="0"></A></TD>
        </TR>
        <TR align="center">
          <TD><A href="resign.asp"><IMG src="images/b_memb6.gif" width="112" height="34" alt="�މ�\������" border="0"></A></TD>
        </TR>
        <tr align="center">
          <td><a href="../mpub/provreg_1.asp?id=<%=Session("mel_id")%>"><img src="images/b_memb7.gif" width="112" height="34" alt="���o�^�\������" border="0"></a></td>
        </tr>
        <TR align="center">
          <TD height = "34" ><A href="index.asp"><img src="images/b_memb8.gif" width="112" height="34" alt="���O�C���y�[�W" border="0"></a></TD>
        </TR>
        <TR align="center">
          <TD height = "34" ><A href="http://www.melmel.net/"><img src="images/b_memb9.gif" width="112" height="34" alt="�g�b�v�y�[�W" border="0"></a></TD>
        </TR>
      </TABLE>
    </TD>
    <TD valign="top" bgcolor="#FFFFFF">
<!------------------ �{��textbox�e�[�u���E�������� ------------------------------>
      <TABLE width="461" border="0" cellspacing="5" cellpadding="0">
        <TR>
          <TD>
            <DIV align="center">
              <P><IMG src="images/t_mgcheck.gif" width="196" height="24" alt="���[���}�K�W���o�^���"><BR>
                <IMG name="chara01" src="../images/chara01.gif" width="83" height="61" border="0" alt="�L�����N�^�[�C���[�W">
              </P>
              <FORM method="post" action="./mgcheck.asp" onSubmit="return OnSubmit_form1(this)">
                <TABLE width="451" border="0" cellspacing="0" cellpadding="3">
                  <TR>
                    <TD>
                      <TABLE width="451" border="0" cellspacing="0" cellpadding="0">
                        <TR>
                          <TD><font class="kihon">�������}�KID</FONT></TD>
                          <TD><font class="kihon"><%=RS("MG_mag_id")%></FONT></TD>
                        </TR>
                        <TR>
                          <TD><font class="kihon">�������}�K��</FONT></TD>
                          <TD><font class="kihon"><INPUT type="text" name="m_name" SIZE="30" VALUE="<%=RS("MG_m_name")%>" MAXLENGTH="100"></FONT></TD>
                        </TR>
                        <TR>
                          <TD><font class="kihon">�����s�p�x</FONT></TD>
                          <TD><font class="kihon"><INPUT type="text" name="hindo" SIZE="20" VALUE="<%=RS("MG_hindo")%>" MAXLENGTH="30"></FONT></TD>
                        </TR>
                        <TR>
                          <TD><font class="kihon">�����s����</FONT></TD>
                          <TD><font class="kihon"><INPUT type="text" name="busuu" SIZE="20" VALUE="<%=RS("MG_busuu")%>" MAXLENGTH="30">���܂��܂��̕����͏����Ă��������B</FONT></TD>
                        </TR>
                        <tr>
                          <TD><font class="kihon">���L���r��</FONT></TD>
                          <td>
                            <select name="keisen_id" style="font-family:�l�r �S�V�b�N;font-size:9pt">
<%
Dim RS2
SQL = "SELECT KE_keisen_id,KE_start_line FROM T_KEISEN ORDER BY KE_keisen_id"
Set RS2 = DB.Execute(SQL)

Do Until RS2.EOF
  Writeln "<option value='" & RS2("KE_keisen_id") & "'" & Selected(RS("MG_keisen_id"), RS2("KE_keisen_id")) & ">" & ReplaceTag(ZenkakuRight(RS2("KE_start_line"), 22)) & "</option>"
  RS2.MoveNext
Loop
RS2.Close
%>
                            </select>
                          </td>
                        </TR>
                        <TR>
                          <TD><font class="kihon">���P�s�̕�����</FONT></TD>
                          <TD><font class="kihon">
<%
Dim moji_suu
moji_suu = RS("MG_moji_suu")
%>
                            <select name="moji_suu">
                              <option value="35"<%=Selected(moji_suu, 35)%>>�S�p35����</option>
                              <option value="36"<%=Selected(moji_suu, 36)%>>�S�p36����</option>
                              <option value="37"<%=Selected(moji_suu, 37)%>>�S�p37����</option>
                              <option value="38"<%=Selected(moji_suu, 38)%>>�S�p38����</option>
                            </select>
                          </FONT></TD>
                        </TR>
                        <TR>
                          <TD><font class="kihon">�������}�K�Љ<br>�i�S�p250�����ȓ��j</FONT></TD>
                          <TD><font class="kihon"><textarea name="shoukai_text" cols=44 rows=5><%=RS("MG_shoukai_text")%></textarea></FONT></TD>
                        </TR>
                        <TR>
                          <TD><font class="kihon">���w�ǗpURL</FONT></TD>
                          <TD><font class="kihon"><INPUT type="text" name="koudoku_url" SIZE="50" VALUE="<%=RS("MG_koudoku_url")%>" MAXLENGTH="100"></FONT></TD>
                        </TR>
                        <TR>
                          <TD><font class="kihon">���ڍא����pURL</FONT></TD>
                          <TD><font class="kihon"><INPUT type="text" name="shousai_url" SIZE="50" VALUE="<%=RS("MG_shousai_url")%>" MAXLENGTH="100"></FONT></TD>
                        </TR>
                        <TR>
                          <TD><font class="kihon">���ǎҌ����Љ�</FONT></TD>
                          <TD><font class="kihon"><input type="radio" name="shoukai_flag1" value="1"<%=Checked(RS("MG_shoukai_flag1"))%>>�f�ڂ���]����@<input type="radio" name="shoukai_flag1" value="0"<%=NotChecked(RS("MG_shoukai_flag1"))%>>�f�ڂ���]���Ȃ�</FONT></TD>
                        </TR>
                        <TR>
                          <TD><font class="kihon">���߂��ς�ID</FONT></TD>
                          <TD><font class="kihon"><INPUT type="hidden" name="melonpan_id" VALUE="<%=RS("MG_melonpan_id")%>"><%=RS("MG_melonpan_id")%> �i�C�����ꂽ������<A HREF="mailto:office@melmel.net">������</A>�܂ł��₢���킹���������j</FONT></TD>
                        </TR>
                        <TR>
                          <TD><font class="kihon">���܂��܂�ID</FONT></TD>
                          <TD><font class="kihon"><INPUT type="hidden" name="mgmg_id" VALUE="<%=RS("MG_mgmg_id")%>"><%=RS("MG_mgmg_id")%> �i�C�����ꂽ������<A HREF="mailto:office@melmel.net">������</A>�܂ł��₢���킹���������j</FONT></TD>
                        </TR>
                      </TABLE>
                      <FONT class="kihon">�����s�X�^���h<BR></FONT>
                      <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 width="100%">
                        <TR>
                          <td width="25%" class="kihon"><input type="checkbox" name="mms_melonpan" value="1"<%=Checked(RS("MG_mms_melonpan"))%>>�߂��ς�</td>
                          <td width="25%" class="kihon"><input type="checkbox" name="mms_mag" value="1"<%=Checked(RS("MG_mms_mag"))%>>�܂��܂�</td>
                          <td width="25%" class="kihon"><input type="checkbox" name="mms_pubz" value="1"<%=Checked(RS("MG_mms_pubz"))%>>Pubzine</td>
                          <td width="25%" class="kihon"><input type="checkbox" name="mms_melma" value="1"<%=Checked(RS("MG_mms_melma"))%>>melma!</td>
                        </TR>
                        <TR>
                          <td width="25%" class="kihon"><input type="checkbox" name="mms_macky" value="1"<%=Checked(RS("MG_mms_macky"))%>>Macky</td>
                          <td width="25%" class="kihon"><input type="checkbox" name="mms_kapu" value="1"<%=Checked(RS("MG_mms_kapu"))%>>�J�v���C�g</td>
                          <td width="25%" class="kihon"><input type="checkbox" name="mms_emag" value="1"<%=Checked(RS("MG_mms_emag"))%>>E-MAGAZINE</td>
                          <td width="25%" class="kihon"><input type="checkbox" name="mms_tengoku" value="1"<%=Checked(RS("MG_mms_tengoku"))%>>�����}�K�V��</td>
                        </TR>
                        <TR>
                          <td width="25%" class="kihon"><input type="checkbox" name="mms_tiara" value="1"<%=Checked(RS("MG_mms_tiara"))%>>è�׵�ײ�</td>
                          <td width="25%" class="kihon"><input type="checkbox" name="mms_merubox" value="1"<%=Checked(RS("MG_mms_merubox"))%>>MERU-BOX</td>
                          <td width="25%" class="kihon"><input type="checkbox" name="mms_dokuji" value="1"<%=Checked(RS("MG_mms_dokuji"))%>>�Ǝ��z�M</td>
                          <td width="25%" class="kihon"><input type="checkbox" name="mms_other" value="1"<%=Checked(RS("MG_mms_other"))%>>���̑�</td>
                        </TR>
                      </TABLE><BR>
                      <font class="kihon">�������}�K�J�e�S���i�Q�ȓ��ɂ��Ă��������j<BR></FONT>
                      <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 width="100%">
                        <TR>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_int" value="1"<%=Checked(RS("MG_cat_int"))%>>�C���^�[�l�b�g</td>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_pcc" value="1"<%=Checked(RS("MG_cat_pcc"))%>><nobr>�p�\�R��/�R���s���[�^�S��</nobr></td>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_soft" value="1"<%=Checked(RS("MG_cat_soft"))%>><nobr>�\�t�g�E�F�A/�v���O���~���O</nobr></td>
                        </TR>
                        <TR>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_mvl" value="1"<%=Checked(RS("MG_cat_mvl"))%>>�g��/���o�C��</td>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_bns" value="1"<%=Checked(RS("MG_cat_bns"))%>>�r�W�l�X�S��</td>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_seiji" value="1"<%=Checked(RS("MG_cat_seiji"))%>>����/�o��</td>
                        </TR>
                        <TR>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_kabu" value="1"<%=Checked(RS("MG_cat_kabu"))%>>����/����</td>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_fsn" value="1"<%=Checked(RS("MG_cat_fsn"))%>>�t�@�b�V����/���e</td>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_lif" value="1"<%=Checked(RS("MG_cat_lif"))%>>��炵/���L</td>
                        </TR>
                        <TR>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_shumi" value="1"<%=Checked(RS("MG_cat_shumi"))%>>�</td>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_res" value="1"<%=Checked(RS("MG_cat_res"))%>>�O����/���X�g����</td>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_tvl" value="1"<%=Checked(RS("MG_cat_tvl"))%>>���s/�n��</td>
                        </TR>
                        <TR>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_spt" value="1"<%=Checked(RS("MG_cat_spt"))%>>�X�|�[�c/���W���[</td>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_gamble" value="1"<%=Checked(RS("MG_cat_gamble"))%>>�M�����u��</td>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_hlt" value="1"<%=Checked(RS("MG_cat_hlt"))%>>���N/���/�玙</td>
                        </TR>
                        <TR>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_art" value="1"<%=Checked(RS("MG_cat_art"))%>>�A�[�g/���|</td>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_shp" value="1"<%=Checked(RS("MG_cat_shp"))%>>�V���b�s���O</td>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_ent" value="1"<%=Checked(RS("MG_cat_ent"))%>>�G���^�[�e�C�����g</td>
                        </TR>
                        <TR>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_game" value="1"<%=Checked(RS("MG_cat_game"))%>>�Q�[��/�N�C�Y</td>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_movie" value="1"<%=Checked(RS("MG_cat_movie"))%>>�f��/���y/�|�\</td>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_uranai" value="1"<%=Checked(RS("MG_cat_uranai"))%>>�肢</td>
                        </TR>
                        <TR>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_std" value="1"<%=Checked(RS("MG_cat_std"))%>>�w�K/����</td>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_shikaku" value="1"<%=Checked(RS("MG_cat_shikaku"))%>>���i</td>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_gogaku" value="1"<%=Checked(RS("MG_cat_gogaku"))%>>��w</td>
                        </TR>
                        <TR>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_nws" value="1"<%=Checked(RS("MG_cat_nws"))%>>�j���[�X</td>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_zatsu" value="1"<%=Checked(RS("MG_cat_zatsu"))%>>�G�w</td>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_kenshou" value="1"<%=Checked(RS("MG_cat_kenshou"))%>>����/�v���[���g</td>
                        </TR>
                        <TR>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_homepage" value="1"<%=Checked(RS("MG_cat_homepage"))%>>�z�[���y�[�W�Љ�</td>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_hitokoto" value="1"<%=Checked(RS("MG_cat_hitokoto"))%>>����ЂƂ��ƌn</td>
                          <td width="33%" class="kihon"><input type="checkbox" name="cat_other" value="1"<%=Checked(RS("MG_cat_other"))%>>���̑�</td>
                        </TR>
                      </TABLE><BR>
                      <font class="kihon">���ǎґw�i�N��j<BR></FONT>
                      <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0>
                        <TR>
                          <td width=90 class="kihon"><INPUT TYPE="checkbox" NAME="age_10m" value="1"<%=Checked(RS("MG_age_10m"))%>>10��j</td>
                          <td width=90 class="kihon"><INPUT TYPE="checkbox" NAME="age_20m" value="1"<%=Checked(RS("MG_age_20m"))%>>20��j</td>
                          <td width=90 class="kihon"><INPUT TYPE="checkbox" NAME="age_30m" value="1"<%=Checked(RS("MG_age_30m"))%>>30��j</td>
                          <td width=90 class="kihon"><INPUT TYPE="checkbox" NAME="age_40m" value="1"<%=Checked(RS("MG_age_40m"))%>>40��j</td>
                          <td width=90 class="kihon"><INPUT TYPE="checkbox" NAME="age_50m" value="1"<%=Checked(RS("MG_age_50m"))%>>50��ȏ�j</td>
                        </TR>
                        <TR>
                          <td width=90 class="kihon"><INPUT TYPE="checkbox" NAME="age_10f" value="1"<%=Checked(RS("MG_age_10f"))%>>10�㏗</td>
                          <td width=90 class="kihon"><INPUT TYPE="checkbox" NAME="age_20f" value="1"<%=Checked(RS("MG_age_20f"))%>>20�㏗</td>
                          <td width=90 class="kihon"><INPUT TYPE="checkbox" NAME="age_30f" value="1"<%=Checked(RS("MG_age_30f"))%>>30�㏗</td>
                          <td width=90 class="kihon"><INPUT TYPE="checkbox" NAME="age_40f" value="1"<%=Checked(RS("MG_age_40f"))%>>40�㏗</td>
                          <td width=90 class="kihon"><INPUT TYPE="checkbox" NAME="age_50f" value="1"<%=Checked(RS("MG_age_50f"))%>>50��ȏ㏗</td>
                        </TR>
                      </TABLE><BR>
                      <font class="kihon">���ǎґw�i�E�Ɓj<BR></FONT>
                      <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0>
                        <TR>
                          <td width=100 class="kihon"><INPUT TYPE="checkbox" NAME="shk_stu" VALUE="1"<%=Checked(RS("MG_shk_stu"))%>>�w��</td>
                          <td width=100 class="kihon"><INPUT TYPE="checkbox" NAME="shk_enp" VALUE="1"<%=Checked(RS("MG_shk_enp"))%>>�Љ�l</td>
                          <td width=100 class="kihon"><INPUT TYPE="checkbox" NAME="shk_syf" VALUE="1"<%=Checked(RS("MG_shk_syf"))%>>��w</td>
                          <td width=100 class="kihon"><INPUT TYPE="checkbox" NAME="shk_other" VALUE="1"<%=Checked(RS("MG_shk_other"))%>>���̑�</td>
                        </TR>
                      </TABLE><BR>
                      <font class="kihon">���ǎґw�i�G���A�j<BR></FONT>
                      <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0>
                        <TR>
                          <td width=100 class="kihon"><INPUT TYPE="checkbox" NAME="are_ht" value="1"<%=Checked(RS("MG_are_ht"))%>>�k�C��/���k</td>
                          <td width=100 class="kihon"><INPUT TYPE="checkbox" NAME="are_kt" value="1"<%=Checked(RS("MG_are_kt"))%>>�֓�</td>
                          <td width=100 class="kihon"><INPUT TYPE="checkbox" NAME="are_cb" value="1"<%=Checked(RS("MG_are_cb"))%>>����</td>
                          <td width=100 class="kihon"><INPUT TYPE="checkbox" NAME="are_kk" value="1"<%=Checked(RS("MG_are_kk"))%>>�ߋE</td>
                        </TR>
                        <TR>
                          <td width=100 class="kihon"><INPUT TYPE="checkbox" NAME="are_cs" value="1"<%=Checked(RS("MG_are_cs"))%>>����/�l��</td>
                          <td width=100 class="kihon"><INPUT TYPE="checkbox" NAME="are_ks" value="1"<%=Checked(RS("MG_are_ks"))%>>��B</td>
                          <td width=100 class="kihon"><INPUT TYPE="checkbox" NAME="are_fr" value="1"<%=Checked(RS("MG_are_fr"))%>>�C�O</td>
                          <td width=100 class="kihon"><INPUT TYPE="checkbox" NAME="are_zk" value="1"<%=Checked(RS("MG_are_zk"))%>>�S��</td>
                        </TR>
                      </TABLE>
                     </TD>
                  </TR>
                  <TR>
                    <TD><font class="kihon">�������}�K�������</FONT>&nbsp;
                      <select name="status">
<%
Dim status
status = RS("MG_status")
%>
                        <option value="0"<%=Selected(status, 0)%>>������</option>
                        <option value="1"<%=Selected(status, 1)%>>�x�~��</option>
                        <option value="2"<%=Selected(status, 2)%>>�p��</option>
                      </select>
                    </TD>
                  </TR>
                </TABLE>
                <BR>
                <font class="kihon">
                <INPUT type="submit" name="Submit" value=" �X�V ">
                <INPUT type="reset" name="Submit2" value="���Z�b�g">
                </FONT>
              </FORM>
            </DIV>
          </TD>
        </TR>
      </TABLE>
<!------------------ �{��textbox�e�[�u���E�����܂� ------------------------------>
      <P>�@ </P>
      </TD>
    <TD background="../images/shadow2.gif" ><IMG src="../images/shim.gif" width="9" height="300"></TD>
  </TR>
  <TR>
    <TD bgcolor="#FFFF00"><IMG src="../images/shim.gif" width="130" height="10"></TD>
    <TD bgcolor="#FFFF00"><IMG src="../images/shim.gif" width="461" height="10"></TD>
    <TD background="../images/shadow2.gif" ><IMG src="../images/shim.gif" width="9" height="10"></TD>
  </TR>
  <TR>
    <TD height="9" colspan="3"><IMG src="../images/shadow6.gif" width="600" height="9"></TD>
  </TR>
  <TR>
    <TD colspan="3">
      <TABLE bgcolor="#ff9999" border="0" cellpadding="0" cellspacing="0" width="600" align="center">
        <TR> <!-- Shim row, height 1. -->
          <TD><IMG src="../images/shim.gif" width="147" height="1" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="66" height="1" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="28" height="1" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="155" height="1" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="204" height="1" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="1" height="1" border="0"></TD>
        </TR>
        <TR valign="top"><!-- row 1 -->
          <TD colspan="3"><IMG src="../images/shim.gif" width="241" height="15" border="0"></TD>
          <TD rowspan="2"><IMG name="logo_s" src="../images/logo_s.gif" width="155" height="42" border="0" alt="�߂�߂�Enet ���S"></TD>
          <TD rowspan="2"><IMG src="../images/shim.gif" width="204" height="42" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="1" height="15" border="0"></TD>
        </TR>
        <TR valign="top"><!-- row 2 -->
          <TD rowspan="2"><IMG src="../images/shim.gif" width="147" height="49" border="0"></TD>
          <TD rowspan="2"><IMG name="cara_s" src="../images/cara_s.gif" width="66" height="49" border="0" alt="�߂�߂� �C���[�W"></TD>
          <TD><IMG src="../images/shim.gif" width="28" height="27" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="1" height="27" border="0"></TD>
        </TR>
        <TR valign="top"><!-- row 3 -->
          <TD colspan="3" height="22" valign="middle"> <FONT size="2" class="komoji">Copyright
            (C) <%=Year(Date)%> xx xxxxx Inc. All rights reserved.</FONT></TD>
          <TD><IMG src="../images/shim.gif" width="1" height="22" border="0"></TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
</TABLE>
</body>

</html>
<%
	End if
End if
%>
