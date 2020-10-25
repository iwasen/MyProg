<?php
if ($id == "")
	$id = $QUERY_STRING;

if (strlen($id) == 6)
	header("Location: melonpa/mag-detail.php?mag_id=$id");
else
	header("Location: index.html");
exit;
?>
