<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table width="760" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="100" align="left"><img src="images/txt_kikan_shitei.gif"></td>
<td width="80" align="left"><img src="images/txt_nichi2.gif"></td>
<td width="55" align="left">
<%= DateSelect.getSelectYear(db, "DATE", SelectCondBeanId.getDayFromY(), "dayFromY", 55) %>
</td>
<td width="24" align="left"><img src="images/txt_nen.gif"></td>
<td width="40" align="left">
<%= DateSelect.getSelectMonth(SelectCondBeanId.getDayFromM(), "dayFromM", 40) %>
</td>
<td width="24" align="left"><img src="images/txt_tsuki1.gif"></td>
<td width="40" align="left">
<%= DateSelect.getSelectDay(SelectCondBeanId.getDayFromD(), "dayFromD", 40) %>
</td>
<td width="24" align="left"><img src="images/txt_nichi1.gif"></td>
<td width="24" align="left"><img src="images/txt_kara.gif"></td>
<td width="55" align="left">
<%= DateSelect.getSelectYear(db, "DATE", SelectCondBeanId.getDayToY(), "dayToY", 55) %>
</td>
<td width="24" align="left"><img src="images/txt_nen.gif"></td>
<td width="40" align="left">
<%= DateSelect.getSelectMonth(SelectCondBeanId.getDayToM(), "dayToM", 40) %>
</td>
<td width="24" align="left"><img src="images/txt_tsuki1.gif"></td>
<td width="40" align="left">
<%= DateSelect.getSelectDay(SelectCondBeanId.getDayToD(), "dayToD", 40) %>
</td>
<td width="24" align="left"><img src="images/txt_nichi1.gif"></td>
<td width="72" class="size09" align="right">・・・・・・・・・・・・</td>
<td align="right" width="70">
<input type="submit" name="ToDay" value="日で表示" style="width:70" onClick="return checkDay()">
</td>
</tr>
</table>
