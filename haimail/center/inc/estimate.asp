<%
'=== 単価 ===
Function HaimailTanka(mail_format, mail_type)
	HaimailTanka = 0

	Select Case mail_format
	Case "0"	'テキスト
		Select Case mail_type
		Case "CR"
			HaimailTanka = 20
		Case "TG"
			HaimailTanka = 8
		End Select
	Case "1"	'テキストHTML
		HaimailTanka = 25
	Case "2"	'HTML
		HaimailTanka = 30
	End Select
End Function

'=== 見積り金額計算 ===
Function Estimate(hasshin_suu, mail_format, mail_type)
	Estimate = HaimailTanka(mail_format, mail_type) * hasshin_suu
End Function
%>