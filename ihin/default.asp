<html>

<head>
<meta http-equiv="Content-Language" content="ja">
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>��i�c�a�����y�[�W</title>





<meta name="Microsoft Theme" content="mstheme22243 011, default"><meta name="Microsoft Border" content="t, default"></head>

<body OnLoad="SetHref()" background="_themes/mstheme22243/sumtextb.jpg" bgcolor="#FFFFFF" text="#000066" link="#3333CC" vlink="#3333CC" alink="#990099"><!--msnavigation--><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><!--mstheme--><font face="�l�r �o�S�V�b�N">

<p align="left"><font size="6"><strong><img src="_derived/default.asp_cmp_mstheme22243010_bnr.gif" width="600" height="60" border="0" alt="��i�c�a�����y�[�W"></strong></font></p>

<!--mstheme--></font></td></tr><!--msnavigation--></table><!--msnavigation--><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><!--msnavigation--><td valign="top"><!--mstheme--><font face="�l�r �o�S�V�b�N">
  <br>
<!--mstheme--></font><table border="0" width="513" height="232" style="margin-left: 60">
    <tr>
      <td width="200" height="31"><!--mstheme--><font face="�l�r �o�S�V�b�N"><b>�啪�ގw��</b><!--mstheme--></font></td>
      <td width="297" height="31"><!--mstheme--><font face="�l�r �o�S�V�b�N"><b>���̑�����</b><!--mstheme--></font></td>
    </tr>
    <tr>
      <td width="200" height="193"><!--mstheme--><font face="�l�r �o�S�V�b�N">�P�D<a id="idGenkou">���e</a>
        <p>�Q�D<a id="idGeijutsu">�|�p�֌W</a></p>
        <p>�R�D<a id="idShokan">����</a></p>
        <p>�S�D<a id="idShashin">�ʐ^</a></p>
        <p>�T�D<a id="idSeikatsu">�����p�i</a></p>
        <p>�U�D<a id="idHitonomichi">�ЂƂ݂̂��֌W</a>
        <p>�V�D<a id="idZousho">����</a><!--mstheme--></font></td>
      <td width="297" height="193" valign="top"><!--mstheme--><font face="�l�r �o�S�V�b�N">
        <form name="ChuBunrui">
          <p><input type="radio" name="Keisai" checked onChange="zKeisai_Change()">�S��������<br>
          <input type="radio" name="Keisai" onChange="zKeisai_Change()">�������w��@<select size="1" name="Kansuu" onChange="zKanSuu_Change()">
          <option value default>
          <%SetChuBunrui%>
          </select><br>
          <input type="radio" name="Keisai" onChange="zKeisai_Change()">���f��</p>
        </form>
      <!--mstheme--></font></td>
    </tr>
  </table><!--mstheme--><font face="�l�r �o�S�V�b�N">
  <br>
<!--msthemeseparator--><p align="left"><img src="_themes/mstheme22243/sumhorsa.gif" width="600" height="10"></p>
  <img border="0" src="images/b11_036.gif" width="90" height="22">
  <br>
<!--mstheme--></font><!--msnavigation--></td></tr><!--msnavigation--></table></body>

<%
Sub SetChuBunrui
 	Set MyCon = Session("xxxNDB")
	Sql = "M_CYUBUNRUI"
	Set MyRS = MyCon.Execute(SQL)

	Do While (Not MyRS.Eof)
		If MyRS.Fields("�����ރR�[�h") <> 29 then
			Response.Write "<option value=" & MyRS.Fields("�����ރR�[�h") & ">" & MyRS.Fields("�����ޖ�") & "</option>" & vbcrlf
		End If
		MyRS.MoveNext
	Loop

	MyRS.Close
End Sub
%>

<script language="JavaScript">
function onLoad()
{
	SetHref();
}

/*
�����̍X�V��
*/
function zKanSuu_Change()
{
	document.ChuBunrui.Keisai[1].checked = 1;
	SetHref();
}

function zKeisai_Change()
{
	SetHref();
}

function SetHref()
{
	var chubunrui;

	if (document.ChuBunrui.Keisai[0].checked)
		chubunrui = "";
	else if (document.ChuBunrui.Keisai[1].checked)
		chubunrui = document.ChuBunrui.Kansuu.value;
	else
		chubunrui = "29";

	chubunrui = "?cb=" + chubunrui;

	idGenkou = "j_genkou.asp" + chubunrui;
	idGeijutsu = "j_geijutsu.asp" + chubunrui;
	idShokan = "j_shokan.asp" + chubunrui;
	idShashin = "j_shashin.asp" + chubunrui;
	idSeikatsu = "j_seikatsu.asp" + chubunrui;
	idHitonomichi = "j_hitonomichi.asp" + chubunrui;
	idZousho = "j_zousho.asp" + chubunrui;
}
</script>

</html>
