<?php
function pub_mm_initdata () {
?>
	<input type="hidden" value="<? echo pub_make_id ("GET_SQU_M_MAGAZINE"); ?>" name="mag_id">
	<input type="hidden" value="00" name="mag_status_flg">
	<input type="hidden" value="00" name="mag_pub_status_flg">
	<input type="hidden" value="00" name="mag_pub_stop_flg">

<?
}
?>
