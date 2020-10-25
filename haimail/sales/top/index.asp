<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net営業用ページ
' Content:営業メニュー画面処理
'******************************************************

'=== ログイン名取得 ===
Public Function GetLoginName()
	Dim sql, ds

	Select Case g_login_type
	Case LOGIN_CENTER
		sql = "SELECT CN_user_name FROM T_CENTER WHERE CN_user_id='" & g_login_id & "'"
	Case LOGIN_AGENT
		sql = "SELECT DA_name FROM T_OPERATOR,T_DAIRITEN WHERE OP_operator_id='" & g_login_id & "' AND OP_dairiten_id=DA_dairiten_id"
	Case LOGIN_CLIENT
		sql = "SELECT TS_name1,TS_name2 FROM T_TANTOUSHA WHERE TS_tantousha_id='" & g_login_id & "'"
	Case LOGIN_GUEST
		GetLoginName = "ゲスト"
		Exit Function
	End Select

	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		GetLoginName = ds(0).Value
	End If
End Function

'=== メイン処理 ===
Dim title

Select Case g_login_type
Case LOGIN_AGENT
	title = ReplaceTag(GetLoginName) & "様 専用マーケティングインターフェース"
Case LOGIN_CLIENT
	title = ReplaceTag(GetLoginName) & "様 専用マーケティングインターフェース"
Case LOGIN_GUEST
	Response.Redirect "../search/index.asp"
End Select

SetHeaderTitle title
top_link = "<small><font color='blue'>■</font><A href='logoff.asp'>ログオフ</A></small>"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function onMouseOver(c) {
	c.style.color="yellow";
}
function onMouseOut(c) {
	c.style.color="white";
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<table align="center" border=0 cellpadding=4 cellspacing=15 width="85%">
<%
If CheckPermission(PM_REGIST_AGENT) And g_login_type = LOGIN_AGENT Then
%>
	<tr>
		<a href="../agent/index.asp">
			<td width="30%" align="center" class="menu" onMouseOver="onMouseOver(this)" onMouseOut="onMouseOut(this)">代理店／担当者登録</td>
		</a>
		<td width="70%">代理店およびその担当者の登録・変更を行います。</td>
	</tr>
<%
End If

If CheckPermission(PM_REGIST_CLIENT) And g_login_type = LOGIN_AGENT Then
%>
	<tr>
		<a href="../client/index.asp">
			<td width="30%" align="center" class="menu" onMouseOver="onMouseOver(this)" onMouseOut="onMouseOut(this)">企業／担当者登録</td>
		</a>
		<td width="70%">発信企業およびその担当者の登録・変更を行います。</td>
	</tr>
<%
End If

If CheckPermission(PM_REGIST_OPERATOR) Then
%>
	<tr>
		<a href="../operator/index.asp">
			<td width="30%" align="center" class="menu" onMouseOver="onMouseOver(this)" onMouseOut="onMouseOut(this)">オペレータ登録</td>
		</a>
		<td width="70%">システムのオペレータを登録します。</td>
	</tr>
<%
End If
%>
	<tr>
		<a href="../project/pjt_go.asp">
			<td width="30%" align="center" class="menu" onMouseOver="onMouseOver(this)" onMouseOut="onMouseOut(this)">配信管理</td>
		</a>
		<td width="70%">プロジェクトのクリック率、返信状況をリアルタイムに閲覧できます。レポートのダウンロードはこちらから行ってください。</td>
	</tr>
	<tr>
		<a href="../search/index.asp">
			<td width="30%" align="center" class="menu" onMouseOver="onMouseOver(this)" onMouseOut="onMouseOut(this)">メンバー検索／配信予約</td>
		</a>
		<td width="70%">ご指定の条件に該当するメンバーの人数検索から配信予約までを行います。<br>※メール検索のご入稿はセンターまで、お問合せください。</td>
	</tr>
</table>

<p> </p>
<table width="75%" border="2" cellspacing="0" cellpadding="0" align="center" bordercolor="cd853f">
	<tr>
		<td>
			
      <table width="100%" border="0" cellspacing="10" cellpadding="0" align="center">
        <tr> 
          <td colspan="3" class="np"> 
            <div align="center"><b><font color="#336699">はいめーるセンターからのお知らせ</font></b></div>
          </td>
        </tr>
        <tr> 
          <td valign="top" class="info"> 
            <div align="right"><font color="#336699">■</font></div>
          </td>
          <td colspan="2" class="info">最低広告料金は５万円になります。<br>
            万が一、配信料金が５万円に満たない場合、最低広告料金を適用させていただいております。 あらかじめご了承くださいますようお願い申し上げます。</td>
        </tr>
        <tr> 
          <td valign="top" class="info"> 
            <div align="right"><font color="#336699">■</font></div>
          </td>
          <td colspan="2" class="info">原稿入稿は原則、<font color="red"><b>配信の３営業日１７:００ 迄</b></font>となっております</td>
        </tr>
        <tr> 
          <td valign="top" class="info"> 
            <div align="right"><font color="#336699">■</font></div>
          </td>
          <td colspan="2" class="info">はいめーる・netはオプトインメールであるため、メールコンテンツと興味ジャンル、属性等の内容の整合性を取らせて頂いております。<br>
            配信する内容とご指定項目の内容によりましては、ご入稿いただいた後にセンターより確認させていただくことがございます。あらかじめご了承ください。 
            <a href="http://manyu.haimail.net/sales/top/faq.html" target="_blank">（配信前に確認させていただくケース）</a> 
          </td>
        </tr>
        <tr> 
          <td valign="top" class="info"> 
            <div align="right"><font color="#336699">■</font></div>
          </td>
          <td colspan="2" class="info"> HTMLメンバーに絞ってのメンバー検索に限り、表示される検索メンバー数に比べ、実際の配信可能数が少なくなる場合がございます。<br>
			<font color="red"><b>HTMLメンバー検索をご希望の際には、お手数ですがはいめーるセンターまで別途検索をお申し付け下さい。</b></font></td>
        </tr>
        <tr> 
          <td valign="top" class="info"> 
            <div align="right"><font color="#336699">■</font></div>
          </td>
          <td colspan="2" class="info">はいめーる・netでは、キャンペーンを行っております。詳しくは<a href="http://www.haimail.net/6/download.html" target="_blank">媒体資料</a> 
            をご確認ください。 </td>
        </tr>
        <tr>
          <td valign="top" class="info">
            <div align="right"><font color="#336699">■</font></div>
          </td>
          <td colspan="2" class="info">はいめーる・netでは、業種規定により配信できない業種もございます。詳しくは<a href="http://www.haimail.net/6/download.html">媒体資料</a>をご確認下さい。</td>
        </tr>
        <tr> 
          <td valign="top" class="info"> 
            <div align="right"><font color="#336699">■</font></div>
          </td>
          <td colspan="2" class="info"><a href="manual_for_agencies.pdf">設定マニュアルダウンロード</a>（PDF形式）</td>
        </tr>
        <tr> 
          <td valign="top" class="info">　</td>
          <td class="info">PDFファイルをご覧になるには<a href="http://www.adobe.co.jp/products/acrobat/readstep.html" target="_blank">Adobe 
            Acrobat Reader</a>が必要です。<br>
            右のアイコンから無料でダウンロードできます。</td>
          <td width="20%" class="info"> 
            <div align="left"><a href="http://www.adobe.co.jp/products/acrobat/readstep.html" target="_blank"><img src="getacro.gif" width="88" height="31" border="0" alt="Acrobat Reader"></a></div>
          </td>
        </tr>
      </table>
		</td>
	</tr>
</table>

<p>　</p>
<table width="300" border="0" cellspacing="0" cellpadding="0" align="right">
	<tr>
		<td class="info"> お問合せは下記にて受け付けております。<br>
			株式会社　○○○○○○　はいめーるセンター<br>
      mail to <a href="mailto:ad@xxxxxxx.co.jp">ad@xxxxxxx.co.jp</a> <br>
			受付時間：１０：００～１８：００（土日祝のぞく） </td>
	</tr>
</table>
<p>　</p>
<p>　</p>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
