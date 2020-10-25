<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table width="760" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="100" align="left"><img src="images/txt_kikan_shitei.gif"></td>
<td width="80" align="left"><img src="images/txt_tsuki2.gif"></td>
<td width="55" align="left">
<%= DateSelect.getSelectYear(db, "MONTH", SelectCondBeanId.getMonthFromY(), "monthFromY", 55) %>
</td>
<td width="24" align="left"><img src="images/txt_nen.gif"></td>
<td width="40" align="left">
<%= DateSelect.getSelectMonth(SelectCondBeanId.getMonthFromM(), "monthFromM", 40) %>
</td>
<td width="24" align="left"><img src="images/txt_tsuki1.gif"></td>
<td width="24" align="left"><img src="images/txt_kara.gif"></td>
<td width="55" align="left">
<%= DateSelect.getSelectYear(db, "MONTH", SelectCondBeanId.getMonthToY(), "monthToY", 55) %>
</td>
<td width="24" align="left"><img src="images/txt_nen.gif"></td>
<td width="40" align="left">
<%= DateSelect.getSelectMonth(SelectCondBeanId.getMonthToM(), "monthToM", 40) %>
</td>
<td width="24" align="left"><img src="images/txt_tsuki1.gif"></td>
<td width="200" class="size09" align="right">・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・</td>
<td align="right" width="70">
<input type="submit" name="ToMonth" value="月で表示" style="width:70" onClick="return checkMonth()">
</td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table width="760" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="100" align="left">　</td>
<td width="80" align="left"><img src="images/txt_syuu.gif"></td>
<td width="55" align="left">
<%= DateSelect.getSelectYear(db, "WEEK", SelectCondBeanId.getWeekFromY(), "weekFromY", 55) %>
</td>
<td width="24" align="left"><img src="images/txt_nen.gif"></td>
<td width="115" align="left">
<%= DateSelect.getSelectWeek(db, SelectCondBeanId.getWeekFromW(), "weekFromW", 115) %>
</td>
<td align="left" width="24"><img src="images/txt_kara.gif"></td>
<td align="left" width="55">
<%= DateSelect.getSelectYear(db, "WEEK", SelectCondBeanId.getWeekToY(), "weekToY", 55) %>
</td>
<td align="left" width="24"><img src="images/txt_nen.gif"></td>
<td align="left" width="115">
<%= DateSelect.getSelectWeek(db, SelectCondBeanId.getWeekToW(), "weekToW", 115) %>
</td>
<td class="size09" width="98" align="right">・・・・・・・・・・・・・・・</td>
<td align="right" width="70">
<input type="submit" name="ToWeek" value="週で表示" style="width:70" onClick="return checkWeek()">
</td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table width="760" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="100" align="left">　 </td>
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
