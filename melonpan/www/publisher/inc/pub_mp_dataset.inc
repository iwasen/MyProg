<?php
function pub_mp_initdata () {
?>
	<input type="hidden" value="<? print pub_make_id ("GET_SQU_M_PUBLISHER"); ?>" name="publisher_id">
	<input type="hidden" value=0 name="pt_melonpan">
	<input type="hidden" value=0 name="pt_melmel">
<?
}

function pub_mp_dataset () {
?>
	<input type="hidden" value=<? echo $publisher_id; ?> name="publisher_id">
	<input type="hidden" value=<? echo $mail_add_main; ?> name="mail_add_main">
	<input type="hidden" value=<? echo $mail_add_sub; ?> name="mail_add_sub">
	<input type="hidden" value=<? echo $owner_nm_kanj; ?> name="owner_nm_kanj">
	<input type="hidden" value=<? echo $owner_nm_kana; ?> name="owner_nm_kana">
	<input type="hidden" value=<? echo $owner_zip; ?> name="owner_zip">
	<input type="hidden" value=<? echo $owner_prefec; ?> name="owner_prefec">
	<input type="hidden" value=<? echo $owner_add; ?> name="owner_add">
	<input type="hidden" value=<? echo $owner_tel; ?> name="owner_tel">
	<input type="hidden" value=<? echo $owner_pswd; ?> name="owner_pswd">
	<input type="hidden" value=<? echo $pt_melonpan; ?> name="pt_melonpan">
	<input type="hidden" value=<? echo $pt_melmel; ?> name="pt_melmel">
<?
}
?>