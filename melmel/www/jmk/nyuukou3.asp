<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<!--#INCLUDE FILE="waku_kanri_inc.asp"-->
<%
'******************************************************
' System :ÇﬂÇÈÇﬂÇÈÅEnetéññ±ã«ópÉyÅ[ÉW
' Content:çLçêàÀóäêUÇËï™ÇØèàóù
'******************************************************

Dim sort

Sub TableHeader(text, sno)
	If sno = 0 Then
    Writeln "<th>" & text & "</th>"
  ELseIf sno = sort Then
    Writeln "<th><font color='brown'>" & text & "</font></th>"
  Else
    Writeln "<th class='sort' onClick='OnClick_sort(" & sno & ")'>" & text & "</th>"
  End If
End Sub

SetHeaderTitle "çLçêàÀóäêUÇËï™ÇØ", TC_KOUKOKU
%>
<html>
<HEAD>
<META HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<META name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<LINK rel="stylesheet" type="text/css" href="admin.css">
<SCRIPT LANGUAGE=javascript>
<!--
var total = 0;
function OnClick_irai(irai,busuu) {
  if (irai)
    total += busuu;
  else
    total -= busuu;
  ShowTotal();
}
function ShowTotal() {
  var i, t1, t2, s;
  t1 = total.toString();
  t2 = "";
  for (i = 0; i < t1.length; i++) {
    if (i != 0 && i % 3 == 0)
      t2 = "," + t2;
    t2 = t1.charAt(t1.length - i - 1) + t2;
  }
  s = "çáåvàÀóäïîêîÅF" + t2 + " (" + Math.round(total / Number(document.form1.busuu.value) * 100) + "%)";
  if (navigator.appName.charAt(0) == "M")
    document.all.total.innerHTML = s;
  window.status = s;
}
function OnClick_mag_id(id) {
  var win;
  win = window.open("mag_info.asp?id=" + id, "mag_info", "resizable=yes,scrollbars=yes,width=740");
  win.focus();
}
function OnClick_tsuikakouho() {
  document.form1.sort.value = 0;
  document.form1.action = "nyuukou3.asp";
  document.form1.submit();
}
function OnClick_sort(sort) {
  document.form1.sort.value = sort;
  document.form1.action = "nyuukou3.asp";
  document.form1.submit();
}
function OnSubmit_form1() {
  return confirm("çLçêÇìoò^ÇµÇ‹Ç∑ÅBÇÊÇÎÇµÇ¢Ç≈Ç∑Ç©ÅH");
}
//-->
</SCRIPT>
</HEAD>
<body onLoad="ShowTotal()">

<!--#INCLUDE FILE="admin_header.asp"-->

<%
Dim koukoku_id, koukoku_name, client_name, company, tantousha, mail_adr, tel_no, koukoku_shuu, pub_start, pub_end, busuu, keisai_irai, koukoku_msg, limit_date, koukoku_type, tsuika, keisen_id, tsuikakouho, jogai_id, koukoku_url, client_id, mktemp

client_id = Request.Form("client_id")
tsuika = Request.Form("tsuika")
tsuikakouho = Request.Form("tsuikakouho")
koukoku_id = Request.Form("koukoku_id")
koukoku_name = Request.Form("koukoku_name")
client_name = Request.Form("client_name")
company = Request.Form("company")
tantousha = Request.Form("tantousha")
mail_adr = Request.Form("mail_adr")
tel_no = Request.Form("tel_no")
koukoku_shuu = Left(Request.Form("koukoku_shuu"), 6)
pub_start = Request.Form("pub_start")
pub_end = Request.Form("pub_end")
busuu = Request.Form("busuu")
keisai_irai = CLng(Request.Form("keisai_irai"))
koukoku_msg = Request.Form("koukoku_msg")
keisen_id = Request.Form("keisen_id")
limit_date = Request.Form("limit_date")
koukoku_type = Request.Form("koukoku_type")
jogai_id = Request.Form("jogai_id")
koukoku_url = Request.Form("koukoku_url")
mms_melonpan = Checkbox(Request.Form("mms_melonpan").Item)
mms_mag = Checkbox(Request.Form("mms_mag").Item)
mms_pubz = Checkbox(Request.Form("mms_pubz").Item)
mms_melma = Checkbox(Request.Form("mms_melma").Item)
mms_macky = Checkbox(Request.Form("mms_macky").Item)
mms_kapu = Checkbox(Request.Form("mms_kapu").Item)
mms_emag = Checkbox(Request.Form("mms_emag").Item)
mms_tengoku = Checkbox(Request.Form("mms_tengoku").Item)
mms_tiara = Checkbox(Request.Form("mms_tiara").Item)
mms_merubox = Checkbox(Request.Form("mms_merubox").Item)
mms_dokuji = Checkbox(Request.Form("mms_dokuji").Item)
mms_other = Checkbox(Request.Form("mms_other").Item)
cat_int = Checkbox(Request.Form("cat_int").Item)
cat_pcc = Checkbox(Request.Form("cat_pcc").Item)
cat_soft = Checkbox(Request.Form("cat_soft").Item)
cat_mvl = Checkbox(Request.Form("cat_mvl").Item)
cat_bns = Checkbox(Request.Form("cat_bns").Item)
cat_seiji = Checkbox(Request.Form("cat_seiji").Item)
cat_kabu = Checkbox(Request.Form("cat_kabu").Item)
cat_fsn = Checkbox(Request.Form("cat_fsn").Item)
cat_lif = Checkbox(Request.Form("cat_lif").Item)
cat_shumi = Checkbox(Request.Form("cat_shumi").Item)
cat_res = Checkbox(Request.Form("cat_res").Item)
cat_tvl = Checkbox(Request.Form("cat_tvl").Item)
cat_spt = Checkbox(Request.Form("cat_spt").Item)
cat_gamble = Checkbox(Request.Form("cat_gamble").Item)
cat_hlt = Checkbox(Request.Form("cat_hlt").Item)
cat_art = Checkbox(Request.Form("cat_art").Item)
cat_shp = Checkbox(Request.Form("cat_shp").Item)
cat_ent = Checkbox(Request.Form("cat_ent").Item)
cat_game = Checkbox(Request.Form("cat_game").Item)
cat_movie = Checkbox(Request.Form("cat_movie").Item)
cat_uranai = Checkbox(Request.Form("cat_uranai").Item)
cat_std = Checkbox(Request.Form("cat_std").Item)
cat_shikaku = Checkbox(Request.Form("cat_shikaku").Item)
cat_gogaku = Checkbox(Request.Form("cat_gogaku").Item)
cat_nws = Checkbox(Request.Form("cat_nws").Item)
cat_zatsu = Checkbox(Request.Form("cat_zatsu").Item)
cat_kenshou = Checkbox(Request.Form("cat_kenshou").Item)
cat_homepage = Checkbox(Request.Form("cat_homepage").Item)
cat_hitokoto = Checkbox(Request.Form("cat_hitokoto").Item)
cat_other = Checkbox(Request.Form("cat_other").Item)
age_10m = Checkbox(Request.Form("age_10m").Item)
age_10f = Checkbox(Request.Form("age_10f").Item)
age_20m = Checkbox(Request.Form("age_20m").Item)
age_20f = Checkbox(Request.Form("age_20f").Item)
age_30m = Checkbox(Request.Form("age_30m").Item)
age_30f = Checkbox(Request.Form("age_30f").Item)
age_40m = Checkbox(Request.Form("age_40m").Item)
age_40f = Checkbox(Request.Form("age_40f").Item)
age_50m = Checkbox(Request.Form("age_50m").Item)
age_50f = Checkbox(Request.Form("age_50f").Item)
shk_stu = Checkbox(Request.Form("shk_stu").Item)
shk_enp = Checkbox(Request.Form("shk_enp").Item)
shk_syf = Checkbox(Request.Form("shk_syf").Item)
shk_other = Checkbox(Request.Form("shk_other").Item)
are_ht = Checkbox(Request.Form("are_ht").Item)
are_kt = Checkbox(Request.Form("are_kt").Item)
are_cb = Checkbox(Request.Form("are_cb").Item)
are_kk = Checkbox(Request.Form("are_kk").Item)
are_cs = Checkbox(Request.Form("are_cs").Item)
are_ks = Checkbox(Request.Form("are_ks").Item)
are_fr = Checkbox(Request.Form("are_fr").Item)
are_zk = Checkbox(Request.Form("are_zk").Item)
%>

<table width="100%" border=0 cellpadding=0 cellspacing=0><tr><td>
<form method="post" name="form1" action="nyuukou4.asp" onSubmit="return OnSubmit_form1();">

<table width="100%">
  <tr>
    <td align="left">Å°åfç⁄àÀóäåÛï‚ÉäÉXÉg<%If keisai_irai = 0 Then Response.Write " ÅiéËìÆäÑÇËìñÇƒÅj"%></td>
<%
sort = Request.Form("sort")
If IsEmpty(sort) Then
	If keisai_irai = 0 Then
		sort = 1
	Else
	  sort = 0
	End If
Else
  sort = CInt(sort)
End If

If keisai_irai > 0 Then
  If IsEmpty(tsuikakouho) Then
    tsuikakouho = 15
		mktemp = True
  Else
    tsuikakouho = CLng(tsuikakouho)
		mktemp = False
  End If
%>
    <td align="left">
      í«â¡åÛï‚<input type="text" name="tsuikakouho" value="<%=tsuikakouho%>" size=5>
      <input type="button" value="ïœçX" onClick="OnClick_tsuikakouho()">
    </td>
<%
End If
%>
    <td align="right"><span id="total"></span></td>
  </tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
  <tr bgcolor='#FFE0E0'>
<%
TableHeader "ÉÅÉãÉ}ÉKID", 1
TableHeader "ÉÅÉãÉ}ÉKñº", 2
TableHeader "î≠çsïîêî", 3
TableHeader "ä˙ë“ïîêî", 0
TableHeader "ó›êœïîêî", 0
TableHeader "î≠çsïpìx", 5
TableHeader "ç≈èIçXêVì˙", 8
TableHeader "àÀóä", 0
%>
  </tr>

<%
Dim DB, RS, SQL, line, ruiseki, check, irai_busuu, nc, hakkou_busuu, shuu_keisuu, kitai_keisuu, kitai_busuu, total
Dim mag_id, irai_mag_id, current_mag_id

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = KoukokuFuriwake(koukoku_shuu, koukoku_id, jogai_id, Left(koukoku_type, 1), keisai_irai, mktemp)

If sort = 0 Then
  SQL = SQL & "ORDER BY FT_order DESC"
Else
	If keisai_irai <> 0 Then
		current_mag_id = ""
		For Each mag_id IN Request.Form("mag_id")
			If current_mag_id <> "" Then
				current_mag_id  = current_mag_id & ","
			End If
			current_mag_id = current_mag_id & mag_id
		Next
		If current_mag_id <> "" Then
		  SQL = SQL & "AND MG_mag_id IN (" & current_mag_id & ")" & vbCrLf
		End If
	End If
	SQL = SQL & "ORDER BY " & sort
End If

RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly

ruiseki = 0
total = 0
nc = 0
irai_busuu = CLng(busuu) * keisai_irai / 100
line = 1
Do Until RS.EOF
	mag_id = RS("MG_mag_id")

  Select Case Left(koukoku_type, 1)
  Case "A", "Q"
    hakkou_busuu = RS("MG_new_busuu")
    shuu_keisuu = 1
  Case "B", "R"
    hakkou_busuu = RS("MG_new_busuu") * RS("MG_shuu_keisuu2")
    shuu_keisuu = RS("MG_shuu_keisuu2")
  End Select

  kitai_keisuu = (NVL(RS("keisai_count"), 0) + shuu_keisuu * 0.8) / (NVL(RS("irai_count"), 0) + 1)
  kitai_busuu = Int(RS("MG_new_busuu") * kitai_keisuu)

  If keisai_irai <> 0 Then
    If ruiseki <= irai_busuu Then
      check = " checked"
    Else
      nc = nc + 1
      If nc > tsuikakouho And sort = 0 Then
        Exit Do
      End If
      check = ""
    End If
    ruiseki = ruiseki + kitai_busuu
  End If

	If mktemp = False Then
    check = ""
  	For Each irai_mag_id IN Request.Form("irai")
			If mag_id = irai_mag_id Then
				check = " checked"
				Exit For
			End If
		Next
	End If

	If check <> "" Then
    total = total + kitai_busuu
	End If

  If line Mod 2 = 0 Then
    Writeln "<tr bgcolor='#FFFFFF'>"
  Else
    Writeln "<tr bgcolor='#E0FFE0'>"
  End If

  Writeln "<td align='center'><a href='JavaScript:OnClick_mag_id(""" & mag_id & """)'>" & mag_id & "</a><input type='hidden' name='mag_id' value='" & mag_id & "'></td>"
  Writeln "<td>" & ReplaceTag(RS("MG_m_name")) & "</td>"
  Writeln "<td align='right'>" & NumFormat(hakkou_busuu) & "</td>"
  Writeln "<td align='right'>" & NumFormat(kitai_busuu) & "<input type='hidden' name='kitai_busuu' value='" & kitai_busuu & "'></td>"
  If keisai_irai = 0 Then
    Writeln "<td align='right'><br></td>"
  Else
    Writeln "<td align='right'>" & NumFormat(ruiseki) & "</td>"
  End If
	Writeln "<td>" & ReplaceTag(RS("MG_hindo")) & "</td>"
	Writeln "<td align='center'>" & DateFormat(RS("MG_last_update")) & "</td>"
  Writeln "<td align='center'><input type='checkbox' name='irai' value='" & mag_id & "'" & check & " onClick='OnClick_irai(checked," & kitai_busuu & ")'></td>"
  Writeln "</tr>"

  RS.MoveNext
  line = line + 1
Loop
RS.close
%>
<SCRIPT LANGUAGE=javascript>
<!--
total = <%=total%>;
//-->
</SCRIPT>

</table>
<input type="hidden" name="line" value="<%=line-1%>">
<input type="hidden" name="client_id" value="<%=client_id%>">
<input type="hidden" name="tsuika" value="<%=tsuika%>">
<input type="hidden" name="koukoku_id" value="<%=koukoku_id%>">
<input type="hidden" name="koukoku_name" value="<%=koukoku_name%>">
<input type="hidden" name="client_name" value="<%=client_name%>">
<input type="hidden" name="company" value="<%=company%>">
<input type="hidden" name="tantousha" value="<%=tantousha%>">
<input type="hidden" name="mail_adr" value="<%=mail_adr%>">
<input type="hidden" name="tel_no" value="<%=tel_no%>">
<input type="hidden" name="koukoku_shuu" value="<%=koukoku_shuu%>">
<input type="hidden" name="pub_start" value="<%=pub_start%>">
<input type="hidden" name="pub_end" value="<%=pub_end%>">
<input type="hidden" name="busuu" value="<%=CLng(busuu)%>">
<input type="hidden" name="keisai_irai" value="<%=keisai_irai%>">
<input type="hidden" name="koukoku_msg" value="<%=Replace(koukoku_msg, """", "&quot;")%>">
<input type="hidden" name="keisen_id" value="<%=keisen_id%>">
<input type="hidden" name="limit_date" value="<%=limit_date%>">
<input type="hidden" name="koukoku_type" value="<%=koukoku_type%>">
<input type="hidden" name="jogai_id" value="<%=jogai_id%>">
<input type="hidden" name="koukoku_url" value="<%=koukoku_url%>">
<input type="hidden" name="sort" value="<%=sort%>">
<%
If mms_melonpan Then Writeln "<input type='hidden' name='mms_melonpan' value='1'>"
If mms_mag Then Writeln "<input type='hidden' name='mms_mag' value='1'>"
If mms_pubz Then Writeln "<input type='hidden' name='mms_pubz' value='1'>"
If mms_melma Then Writeln "<input type='hidden' name='mms_melma' value='1'>"
If mms_macky Then Writeln "<input type='hidden' name='mms_macky' value='1'>"
If mms_kapu Then Writeln "<input type='hidden' name='mms_kapu' value='1'>"
If mms_emag Then Writeln "<input type='hidden' name='mms_emag' value='1'>"
If mms_tengoku Then Writeln "<input type='hidden' name='mms_tengoku' value='1'>"
If mms_tiara Then Writeln "<input type='hidden' name='mms_tiara' value='1'>"
If mms_merubox Then Writeln "<input type='hidden' name='mms_merubox' value='1'>"
If mms_dokuji Then Writeln "<input type='hidden' name='mms_dokuji' value='1'>"
If mms_other Then Writeln "<input type='hidden' name='mms_other' value='1'>"
If cat_int Then Writeln "<input type='hidden' name='cat_int' value='1'>"
If cat_pcc Then Writeln "<input type='hidden' name='cat_pcc' value='1'>"
If cat_soft Then Writeln "<input type='hidden' name='cat_soft' value='1'>"
If cat_mvl Then Writeln "<input type='hidden' name='cat_mvl' value='1'>"
If cat_bns Then Writeln "<input type='hidden' name='cat_bns' value='1'>"
If cat_seiji Then Writeln "<input type='hidden' name='cat_seiji' value='1'>"
If cat_kabu Then Writeln "<input type='hidden' name='cat_kabu' value='1'>"
If cat_fsn Then Writeln "<input type='hidden' name='cat_fsn' value='1'>"
If cat_lif Then Writeln "<input type='hidden' name='cat_lif' value='1'>"
If cat_shumi Then Writeln "<input type='hidden' name='cat_shumi' value='1'>"
If cat_res Then Writeln "<input type='hidden' name='cat_res' value='1'>"
If cat_tvl Then Writeln "<input type='hidden' name='cat_tvl' value='1'>"
If cat_spt Then Writeln "<input type='hidden' name='cat_spt' value='1'>"
If cat_gamble Then Writeln "<input type='hidden' name='cat_gamble' value='1'>"
If cat_hlt Then Writeln "<input type='hidden' name='cat_hlt' value='1'>"
If cat_art Then Writeln "<input type='hidden' name='cat_art' value='1'>"
If cat_shp Then Writeln "<input type='hidden' name='cat_shp' value='1'>"
If cat_ent Then Writeln "<input type='hidden' name='cat_ent' value='1'>"
If cat_game Then Writeln "<input type='hidden' name='cat_game' value='1'>"
If cat_movie Then Writeln "<input type='hidden' name='cat_movie' value='1'>"
If cat_uranai Then Writeln "<input type='hidden' name='cat_uranai' value='1'>"
If cat_std Then Writeln "<input type='hidden' name='cat_std' value='1'>"
If cat_shikaku Then Writeln "<input type='hidden' name='cat_shikaku' value='1'>"
If cat_gogaku Then Writeln "<input type='hidden' name='cat_gogaku' value='1'>"
If cat_nws Then Writeln "<input type='hidden' name='cat_nws' value='1'>"
If cat_zatsu Then Writeln "<input type='hidden' name='cat_zatsu' value='1'>"
If cat_kenshou Then Writeln "<input type='hidden' name='cat_kenshou' value='1'>"
If cat_homepage Then Writeln "<input type='hidden' name='cat_homepage' value='1'>"
If cat_hitokoto Then Writeln "<input type='hidden' name='cat_hitokoto' value='1'>"
If cat_other Then Writeln "<input type='hidden' name='cat_other' value='1'>"
If age_10m Then Writeln "<input type='hidden' name='age_10m' value='1'>"
If age_10f Then Writeln "<input type='hidden' name='age_10f' value='1'>"
If age_20m Then Writeln "<input type='hidden' name='age_20m' value='1'>"
If age_20f Then Writeln "<input type='hidden' name='age_20f' value='1'>"
If age_30m Then Writeln "<input type='hidden' name='age_30m' value='1'>"
If age_30f Then Writeln "<input type='hidden' name='age_30f' value='1'>"
If age_40m Then Writeln "<input type='hidden' name='age_40m' value='1'>"
If age_40f Then Writeln "<input type='hidden' name='age_40f' value='1'>"
If age_50m Then Writeln "<input type='hidden' name='age_50m' value='1'>"
If age_50f Then Writeln "<input type='hidden' name='age_50f' value='1'>"
If shk_stu Then Writeln "<input type='hidden' name='shk_stu' value='1'>"
If shk_enp Then Writeln "<input type='hidden' name='shk_enp' value='1'>"
If shk_syf Then Writeln "<input type='hidden' name='shk_syf' value='1'>"
If shk_other Then Writeln "<input type='hidden' name='shk_other' value='1'>"
If are_ht Then Writeln "<input type='hidden' name='are_ht' value='1'>"
If are_kt Then Writeln "<input type='hidden' name='are_kt' value='1'>"
If are_cb Then Writeln "<input type='hidden' name='are_cb' value='1'>"
If are_kk Then Writeln "<input type='hidden' name='are_kk' value='1'>"
If are_cs Then Writeln "<input type='hidden' name='are_cs' value='1'>"
If are_ks Then Writeln "<input type='hidden' name='are_ks' value='1'>"
If are_fr Then Writeln "<input type='hidden' name='are_fr' value='1'>"
If are_zk Then Writeln "<input type='hidden' name='are_zk' value='1'>"
%>
<center>
<br>
<input type="submit" value="Å@ÇnÇjÅ@">
</center>

</form>
</td></tr></table>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
