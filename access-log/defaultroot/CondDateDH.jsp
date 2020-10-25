<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="100" align="left"><img src="images/txt_kikan_shitei.gif"> </td>
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
<td class="size09" align="right">・・・・・・・・・・・・・・・・・・・・・・・・・</td>
<td align="right" width="72">
<input type="submit" name="ToDay" value="日で表示" style="width:70" onClick="return checkDay()">
</td>
</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="5"> </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="100" align="left">　 </td>
<td width="80" align="left"><img src="images/txt_ji2.gif"></td>
<td width="55" align="left">
<%= DateSelect.getSelectYear(db, "DATE", SelectCondBeanId.getHourFromY(), "hourFromY", 55) %>
</td>
<td width="24" align="left"><img src="images/txt_nen.gif"></td>
<td width="40" align="left">
<%= DateSelect.getSelectMonth(SelectCondBeanId.getHourFromM(), "hourFromM", 40) %>
</td>
<td width="24" align="left"><img src="images/txt_tsuki1.gif"></td>
<td width="40" align="left">
<%= DateSelect.getSelectDay(SelectCondBeanId.getHourFromD(), "hourFromD", 40) %>
</td>
<td width="24" align="left"><img src="images/txt_nichi1.gif"></td>
<td width="40" align="left">
<%= DateSelect.getSelectHour(SelectCondBeanId.getHourFromH(), "hourFromH", 40) %>
</td>
<td width="24" align="left"><img src="images/txt_ji1.gif"></td>
<td width="24" align="left"><img src="images/txt_kara.gif"></td>
<td width="55" align="left">
<%= DateSelect.getSelectYear(db, "DATE", SelectCondBeanId.getHourToY(), "hourToY", 55) %>
</td>
<td width="24" align="left"><img src="images/txt_nen.gif"></td>
<td width="40" align="left">
<%= DateSelect.getSelectMonth(SelectCondBeanId.getHourToM(), "hourToM", 40) %>
</td>
<td width="24" align="left"><img src="images/txt_tsuki1.gif"></td>
<td width="40" align="left">
<%= DateSelect.getSelectDay(SelectCondBeanId.getHourToD(), "hourToD", 40) %>
</td>
<td width="24" align="left"><img src="images/txt_nichi1.gif"></td>
<td width="40" align="left">
<%= DateSelect.getSelectHour(SelectCondBeanId.getHourToH(), "hourToH", 40) %>
</td>
<td width="24" align="left"><img src="images/txt_ji1.gif"></td>
<td class="size09" align="right">・・・・</td>
<td align="right" width="70"><input type="submit" name="ToHour" value="時間で表示" style="width:70" onClick="return checkHour()"></td>
</tr>
</table>
