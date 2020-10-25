<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/database.php");
include ("../inc/admin_inc.php");
include ("../inc/com_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:
'******************************************************/

$title_text = "てすと";
$title_color = $TC_MASTER;
/*
function chk_date($s) {
  $err = false;

  $s_len = strlen($s);

  if ($s_len == 0) {
    // 未入力 
    $err = true;
  } else {
    for ($i=0;$i<$s_len;$i++) {
      $ASC = ord(substr($s,$i,1));
      if ( ( $ASC<ord("0") or $ASC>ord("9") )
       and ($ASC<ord("a") or $ASC>ord("z") )
       and ( $ASC<>ord("@") ) and ( $ASC<>ord(".") )
       and ( $ASC<>ord("_") ) and ( $ASC<>ord("-") ) ) {
        $err = true;
        break;
      }
    }

    if (!$err) {
      $POS = strpos($s,"@");
      if (($POS == false) or ($POS == 0)) {
        $err = true;
      } else {
        $NS = substr($s,$POS+1);
        if (checkdnsrr($NS,"ANY") == false) {
          $err = true;
        }
      }
    }
  }

  /* 戻り値のセット 
  if ($err) {
    return false;
  } else {
		return true;
  }
}

function CheckMail($mail) {

	// メールアドレス内の不正文字をチェック
	if (preg_match("/[^-._~@\w]+/", $mail))
		return 1;

	// メールアドレス内のホスト名の有効性をMXレコードからチェック
	$hostname = substr($mail, strpos($mail, "@")+1);

	$mxcheck = getmxrr($hostname, $mxhosts);
	$hoxxxheck = ($hostname != gethostbyname($hostname));
	if (!$mxcheck and !$hoxxxheck)
		return 2;
	return 0;
}
*/
function addr_check($mail_addr) {
	$mail_addr = trim($mail_addr);
	if (strrpos($mail_addr, " ")) {
		$err = true;
	} else {
		$mail_parts = explode("@", $mail_addr);
		if ($mail_parts[2]) {
			$err = true;
		}	elseif ($mail_parts[0] == "" || $mail_parts[1] == "") {
			$err = true;
		}	elseif (strpos($mail_parts[1], ".") == 0) {
			$err = true;
		}
	}
	if ($err) {
		return false;
	} else {
		return true;
	}

}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><? echo ("$title_text") ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<form method="post" name="form1">
<?

$sql = "SELECT mail_addr FROM X_MAILADDR";
$select = new select_sql($sql);

$i = 0;
$n = 0;
While ($select->fetch()) {
	$mail_addr = $select->field("MAIL_ADDR");
/*
$sql = "SELECT melonpai_mail_addr FROM M_MELONPAI";
$select = new select_sql($sql);
/
$i = 0;
While ($select->fetch()) {
	$mail_addr = $select->field("MELONPAI_MAIL_ADDR");
*/
//$mail_addr = " akiko @trrt.dd";
	if (addr_check($mail_addr)) {
//		print $mail_addr . " <font color='blue'>ok!</font><br>";
		$i++;
	} else {
		print $mail_addr . " <font color='red'>ng!</font><br>";
		$n++;
	}
}

print "ok " . $i . "件見つかりました。";
print "ok " . $n . "件見つかりました。";

?>
<form>

<? footer_jmk(0); ?>

</body>
</html>
