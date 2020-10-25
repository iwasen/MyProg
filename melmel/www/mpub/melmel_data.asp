<%
Dim h_cnt, h_busuu, h_wbusuu, k_cnt

Call QueryData

Sub QueryData
  Dim DB, RS, SQL

  Set DB = OpenDB()
  Set RS = Server.CreateObject("ADODB.RecordSet")

  SQL = "SELECT COUNT(*),SUM(MG_new_busuu),SUM(MG_new_busuu * MG_shuu_keisuu)" & vbCrLf & _
        "FROM T_MELMAG INNER JOIN T_MELMEL ON MG_mel_id=MM_mel_id" & vbCrLf & _
        "WHERE MM_status=0 AND MG_status=0"
  RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
  If Not RS.EOF Then
    h_cnt = RS(0)
    h_busuu = RS(1)
    h_wbusuu = Int(RS(2))
  End If
  RS.Close

  SQL = "SELECT MAX(PR_seq_no) FROM T_PROVREG"
  RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
  If Not RS.EOF Then
    k_cnt = RS(0)
  End If
  RS.Close
End Sub
%>
<tr>
  <td width="38%" align="center" colspan=2><%=DateFormat(Date)%> 現在</td>
</tr>
<tr>
  <td width="38%" align="center">仮登録マガジン数</td>
  <td width="31%" align="right"><%=NumFormat(k_cnt)%> 誌</td>
</tr>
<tr>
  <td width="38%" align="center">本登録マガジン数</td>
  <td width="32%" align="right"><%=NumFormat(h_cnt)%> 誌</td>
</tr>
<tr>
  <td width="38%" align="center">総発行部数</td>
  <td width="32%" align="right"><%=NumFormat(h_busuu)%> 部</td>
</tr>
<tr>
  <td width="38%" align="center">１週間あたり発行部数</td>
  <td width="32%" align="right"><%=NumFormat(h_wbusuu)%> 部</td>
</tr>
