<table cellspacing=0 cellpadding=0 width="100%">
	<tr>
		<td align="left" width="10%" valign="top"><small><B><nobr><%
Select Case g_login_type
Case LOGIN_AGENT
	Response.Write "はいめーる・net 代理店様用ページ"
Case LOGIN_CLIENT
	Response.Write "はいめーる・net クライアント様用ページ"
Case LOGIN_GUEST
	Response.Write "はいめーる・net ゲスト用ページ"
Case LOGIN_CENTER
	Response.Write "はいめーる・net 事務局用ページ"
End Select
%></nobr></B></small></td>
		<td align="center" width="40%"><h1 class="title"><nobr><%=title_text%></nobr></h1></td>
		<td align="right" width="10%" valign="top"><nobr><%=top_link%></nobr></td>
	</tr>
</table>
<hr class="header<%=g_login_type%>">