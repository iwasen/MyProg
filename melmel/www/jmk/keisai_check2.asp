<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="click_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:改ざんメール表示
'******************************************************
%>
<html>
<head>
<META HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<META name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title>広告内容</title>
<link rel="stylesheet" type="text/css" href="admin.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_OK() {
  opener.document.form1.hantei<%=Request.QueryString("line")%>[0].checked = true;
  window.close();
}
function OnClick_NG() {
  opener.document.form1.hantei<%=Request.QueryString("line")%>[1].checked = true;
  window.close();
}
//-->
</SCRIPT>
</head>
<body bgcolor="lightgrey">

<%
Dim DB, RS, RS2, SQL
Dim koukoku_id, mag_id, seq_no
Dim start_line, end_line, moji_suu, koukoku_msg

koukoku_id = Request.QueryString("koukoku_id")
mag_id = Request.QueryString("mag_id")
seq_no = Request.QueryString("seq_no")

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
%>
<form name="form1">
■オリジナルデータ<br><textarea cols=80 rows=12 readonly class="np"><%
SQL = "SELECT KK_koukoku_msg,KK_koukoku_url" & vbCrLf & _
      "FROM V_KOUKOKU" & vbCrLf & _
      "WHERE KK_koukoku_id='" & koukoku_id & "'"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
If Not RS.EOF Then
  '広告罫線取得
  SQL = "SELECT MG_moji_suu,KE_start_line,KE_end_line" & vbCrLf & _
        "FROM T_MELMAG INNER JOIN T_KEISEN ON MG_keisen_id=KE_keisen_id" & vbCrLf & _
        "WHERE MG_mag_id='" & mag_id & "'"
  Set RS2 = DB.Execute(SQL)
  If Not RS2.EOF Then
    moji_suu = NVL(RS2("MG_moji_suu"), 0)
    If moji_suu < MIN_MOJISUU Then
      moji_suu = MIN_MOJISUU
    End If
    start_line = ZenkakuRight(RS2("KE_start_line"), moji_suu)
    end_line = ZenkakuRight(RS2("KE_end_line"), moji_suu)
  Else
    moji_suu = MIN_MOJISUU
    start_line = sDefaultStartLine
    end_line = sDefaultEndLine
  End If
  RS2.Close

  Dim vArray, vLine, sSpace, i

  'クリックカウント用URL埋め込み
  koukoku_msg = RS(0)
  Call SetClickCountURL(koukoku_msg, koukoku_id, mag_id, RS(1))

  '広告本文センタリング
  If moji_suu > MIN_MOJISUU Then
      sSpace = Space(moji_suu - MIN_MOJISUU)
      vArray = Split(koukoku_msg, vbCrLf)
      For i = 0 To UBound(vArray)
          vArray(i) = sSpace & vArray(i)
      Next
      koukoku_msg = Join(vArray, vbCrLf)
  End If

  end_line = Replace(end_line, "KKKKKK", koukoku_id)
  end_line = Replace(end_line, "MMMMM", mag_id)

  Response.Write start_line & vbCrLf
  Response.Write koukoku_msg & vbCrLf
  Response.Write end_line & vbCrLf
End If
RS.Close
%></textarea>
<br>
<table border=0 cellpadding=0 cellspacing=0 width="100%">
  <tr>
    <td>■掲載データ</td>
    <td align="right">
      <input type="button" value=" ＯＫ " onClick="OnClick_OK()">
      <input type="button" value=" ＮＧ " onClick="OnClick_NG()">
    </td>
  </tr>
</table>
<textarea cols=80 rows=28 readonly class="np"><%
SQL = "SELECT HM_body FROM T_HMAILLOG WHERE HM_seq_no=" & seq_no
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
If Not RS.EOF Then
  Response.Write RS(0)
End If
RS.Close
%></textarea>
</form>
</body>
</html>
