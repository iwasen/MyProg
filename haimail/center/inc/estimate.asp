<%
'=== �P�� ===
Function HaimailTanka(mail_format, mail_type)
	HaimailTanka = 0

	Select Case mail_format
	Case "0"	'�e�L�X�g
		Select Case mail_type
		Case "CR"
			HaimailTanka = 20
		Case "TG"
			HaimailTanka = 8
		End Select
	Case "1"	'�e�L�X�gHTML
		HaimailTanka = 25
	Case "2"	'HTML
		HaimailTanka = 30
	End Select
End Function

'=== ���ς���z�v�Z ===
Function Estimate(hasshin_suu, mail_format, mail_type)
	Estimate = HaimailTanka(mail_format, mail_type) * hasshin_suu
End Function
%>