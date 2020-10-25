<?
if($no!=""){
			$file= "?no=$no";
} elseif ($id!="") {
			$file= "?id=$id";
} else {
			$file= "";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Frameset//EN">
<HTML>
<HEAD>
	<TITLE>アンケートでポイントを[きかせて・net]モニターサイト</TITLE>
	<META http-equiv=Content-Type content="text/html; charset=EUC-JP">
	<META content="MSHTML 6.00.2800.1505" name=GENERATOR>
</HEAD>
	<FRAMESET border=0 frameSpacing=0 rows=70,*,40 frameBorder=NO>
		<FRAME name=topframe src="./../header.php" noResize scrolling=no>
		<FRAME name=mainframe src="./pastenqd_main.php<?=$file?>">
		<FRAME name=bottomframe src="./../footer.php" noResize scrolling=no>
	</FRAMESET>
</HTML>
<?
@session_destroy();
?>
