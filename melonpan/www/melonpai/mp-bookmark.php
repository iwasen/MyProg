<?php
include ("../inc/com_path.inc");
include ("$compath/com_db_func.inc");
include ("inc/mex_passchk.inc");
include("tmpl_html/mp-bookmark.html");
include ("../sub/head.inc");

/****************************************************************
 Main
****************************************************************/
print "<form name=\"form2\" action=\"mitu-return.php\" target=\"input\">\n";

//$conn = db_conxxxt();
mex_passchk($conn,$melonpai_id,$melonpai_pswd);

$sql = "SELECT * FROM M_BK_CATEGORY WHERE melonpai_id = $melonpai_id order by category_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$catg_id = $fetch->catg_id;
	$melonpai_id = $fetch->melonpai_id;
	$category_id = $fetch->category_id;
	$category_name = $fetch->categiry_name;

	print "<b><font color=\"#003399\"><span class=\"text9ptnoColor\">\n";
	print "【".$category_name."】\n";
	print "</span></font></b>\n";
	print "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\">\n";
	print "<tr class=\"text9ptnoColor\"> \n";
	print "<td bgcolor=\"#99CCFF\" width=\"20%\">\n";
	print "<div align=\"center\"><font color=\"#003399\" size=\"-2\">メルマガID</font></div>\n";
	print "</td>\n";
	print "<td bgcolor=\"#99CCFF\" width=\"64%\"> \n";
	print "<div align=\"center\"><b><font color=\"#003399\">メルマガ名</font></b></div>\n";
	print "</td>\n";
	print "<td width=\"19%\"></td>\n";
	print "</tr>\n";

	$sql2 = "SELECT B.BM_ID,B.MELONPAI_ID,B.CATEGORY_ID,B.MAG_ID,B.PUBLISHER_ID,M.MAG_NM ";
	$sql2 .= " FROM T_BOOKMARK B,M_MAGAZINE M ";
	$sql2 .= " WHERE B.melonpai_id = $melonpai_id and B.category_id = $category_id ";
	$sql2 .= " and B.mag_id = M.mag_id order by modify_dt";
	$result2 = db_exec($sql2);
	$nrow2 = pg_numrows($result2);
	for ($j = 0; $j < $nrow2; $j++) {
		$fetch = pg_fetch_object($result2, $j);
		$bm_id = $fetch->bm_id;
		$melonpai_id = $fetch->melonpai_id;
		$category_id = $fetch->category_id;
		$mag_id = $fetch->mag_id;
		$publisher_id = $fetch->publisher_id;
		$mag_nm = $fetch->mag_nm;

		print "<tr> \n";
		print "<td class=\"text9ptnoColor\" width=\"17%\">$mag_id</td>\n";
		print "<td class=\"text9ptnoColor\" width=\"64%\">".trim($mag_nm)."</td>\n";
		print "<td width=\"19%\"> \n";
		print "<div align=\"center\"> \n";
		print "<input type=\"hidden\" name=\"mag_id\" value=\"$mag_id\">\n";
		$mag_nm = trim($mag_nm);
		print "<input type=\"checkbox\" name=\"input_mag\" value=\"";
		print $mag_id."：".$mag_nm;
		print "\">\n";
		print "</div>\n";
		print "</td>\n";
		print "</tr>\n";
	}
/*
	print "<tr> \n";
	print "<td width=\"17%\">　</td>\n";
	print "<td width=\"64%\">　</td>\n";
	print "<td width=\"19%\"> \n";
	print "<div align=\"center\"> \n";
	print "<input type=\"hidden\" name=\"body\" value=\"\">\n";
	print "<input type=\"hidden\" name=\"header\" value=\"\">\n";
	print "<input type=\"hidden\" name=\"footer\" value=\"\">\n";
	print "<input type=\"hidden\" name=\"subject\" value=\"\">\n";
	print "<input type=\"hidden\" name=\"job_flg\" value=\"add\">\n";
	print "<input type=\"hidden\" name=\"melonpai_id\" value=\"$melonpai_id\">\n";
	print "<input type=\"hidden\" name=\"melonpai_pswd\" value=\"$melonpai_pswd\">\n";
	print "<input type=\"hidden\" name=\"cho_id\" value=\"$cho_id\">\n";
	print "<input type=\"button\" name=\"button\" value=\"追加\" onClick=\"data_input(this.form)\">\n";
	print "</div>\n";
	print "</td>\n";
	print "</tr>\n";
*/
	print "</table>\n";
}
print "<br>\n";
print "<input type=\"hidden\" name=\"body\" value=\"\">\n";
print "<input type=\"hidden\" name=\"header\" value=\"\">\n";
print "<input type=\"hidden\" name=\"footer\" value=\"\">\n";
print "<input type=\"hidden\" name=\"subject\" value=\"\">\n";
print "<input type=\"hidden\" name=\"job_flg\" value=\"add\">\n";
print "<input type=\"hidden\" name=\"melonpai_id\" value=\"$melonpai_id\">\n";
print "<input type=\"hidden\" name=\"melonpai_pswd\" value=\"$melonpai_pswd\">\n";
print "<input type=\"hidden\" name=\"cho_id\" value=\"$cho_id\">\n";
print "<input type=\"button\" name=\"button\" value=\" 追加 \" onClick=\"data_input(this.form)\"> \n";
print "<input type=\"reset\" value=\"ﾘｾｯﾄ\">\n";

?>
</form>
</td>
</tr>
<tr><td><a href="#"  class="text9ptnoColor" onClick="MM_openBrWindow('../sample2/sample.html','','scrollbars=yes,resizable=yes,width=700,height=400')">[他のメルマガを参考にしてみる]</a></td></tr>
</table>
</body>
</html>
