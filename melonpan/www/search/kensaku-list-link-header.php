<script LANGUAGE="javascript">
<!--
var click_flag = false;
function click_check(){
	var c = click_flag;
	click_flag = true;
	return !c;
}
window.onload = function() {
	var f = document.form_search;
	if (f.agreement1)
		f.agreement1.checked = false;
	if (f.agreement2)
		f.agreement2.checked = false;
	check_select();
}
function check_agreement() {
	var f = document.form_search;
	f.action_btn.disabled = !(f.agreement1.checked && f.agreement2.checked);
}
function check_select() {
	var f = document.form_search;
	var read_chk = f["read_chk[]"];
	if (read_chk) {
		var chk_count = read_chk.length;
		var on_count = 0;
		var of_count = 0;
		for (var i = 0; i < chk_count; i++) {
			if (read_chk[i].checked) {
				if (read_chk[i].value.substr(0, 2) == "on")
					on_count++;
				if (read_chk[i].value.substr(0, 2) == "of")
					of_count++;
			}
		}
		if (on_count != 0) {
			f.action_btn.disabled = !(f.agreement1.checked && f.agreement2.checked);
			document.getElementById("agreement").style.display = "";
		} else {
			f.action_btn.disabled = (of_count == 0);
			document.getElementById("agreement").style.display = "none";
		}
	}
}
-->
</script>
<form name="form_search" action="search_result.php" method="post" onsubmit="return click_check()">
<?php echo $input_read_chk; ?>
<table border="0" cellpadding="5" cellspacing="0" width="657">
  <tr align="left" valign="top" bgcolor="#91c741" height="25">
    <td bgcolor="#91c741" height="25" valign="middle"><img src="../search/img/yellow.gif" width="11" height="11"><br>
    </td>
    <td align="left" valign="middle" width="450" bgcolor="#91c741" height="26">
	  <font class="ub"><b><font color="#000000"><?php echo $kind; ?></font></b>
	  </font><font size="2" color="black"></font></td>
    <td align="right" valign="middle" width="240"><font size="2" color="black">
         </font>
         <?php echo $chg_page; ?>
    </td>
<!-- 追加 -->
  </tr>
                      <tr valign="top" bgcolor="#e2f0c9" height="25">
                        <td align="left">　</td>
                        <td align="left" valign="middle" colspan="2">
                          <table border="0" cellspacing="0" cellpadding="0" width="595">
                            <tr>
                              <td bgcolor="#e2f0c9" width="100" height="17"><font size="2">
                                　マークの見方：</font></td>
                              <td bgcolor="#e2f0c9" width="485" height="17">
                                <p><img src="common/spacer.gif" width="2" height="16"><img src="../img/shincyaku1.gif" width="91" height="16" vspace="3" hspace="3" alt="新着メルマガ"><img src="../img/kari1.gif" width="98" height="16" vspace="3" hspace="3" alt="仮登録メルマガ"><img src="../img/osusume1.gif" width="110" height="16" vspace="3" hspace="3" alt="オススメ！（現在）"><img src="../img/osusumekako1.gif" width="102" height="16" vspace="3" hspace="3" alt="オススメ！（過去）"><img src="../img/otanjobi1.gif" width="134" height="16" vspace="3" hspace="3"><img src="../img/original1.gif" width="132" height="16" vspace="3" hspace="3"><img src="../img/imode1.gif" width="59" height="16" vspace="3" hspace="3"><img src="../img/ribacya1.gif" width="119" height="16" vspace="3" hspace="3"></p>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
<!----- ---->
</table>
<BR>
