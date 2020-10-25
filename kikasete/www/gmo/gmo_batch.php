<?php

$inc = "../../kikasete/inc";
include("$inc/database.php");

//-----------------------------------------------------------------------------
// 宣言
//-----------------------------------------------------------------------------
//define("FTP_SERVER","192.168.100.102");
define("FTP_SERVER","211.133.248.151");
define("FTP_USER","namiki");
define("FTP_PWD","linuxmuzui");
define("FTP_REMOTE_PATH","/tmp");
define("FTP_LOCAL_PATH","/tmp");
define("FTP_LOCAL_FILE","batch.csv");
define("GETCMD_FILE","GETCMD_FILE");
define("FTP_RET","ftp_ret.tmp");

define("DEST_PATH", "./tmp");
define("EXEC_RESULT", "gmo_batch.ret");

$errmsg = '';
$table_created = false;
//-----------------------------------------------------------------------------

function getmicrotime() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

// 実行結果ファイル
$fp = fopen (EXEC_RESULT, "w");

// ファイル有無チェック
if ( !file_exists(FTP_LOCAL_PATH."/".FTP_LOCAL_FILE) ) {
    fwrite($fp, FTP_LOCAL_PATH."/".FTP_LOCAL_FILE." ファイルが見つかりません。\n");
    fclose($fp);
    exit;
}


//-----------------------------------------------------------------------------
// FTP 接続
//-----------------------------------------------------------------------------

if( file_exists(FTP_RET) ) unlink(FTP_RET);

$ftp_server      = FTP_SERVER;
$ftp_user        = FTP_USER;
$ftp_pwd         = FTP_PWD;
$ftp_remote_path = FTP_REMOTE_PATH;
$ftp_local_path  = FTP_LOCAL_PATH;
$ftp_local_file  = FTP_LOCAL_FILE;

$tmp_fp = fopen (GETCMD_FILE, "w");
fwrite($tmp_fp, "user $ftp_user $ftp_pwd \n");
fwrite($tmp_fp, "cd $ftp_remote_path \n");
fwrite($tmp_fp, "lcd $ftp_local_path \n");
fwrite($tmp_fp, "bin \n");
fwrite($tmp_fp, "put $ftp_local_file \n");
fwrite($tmp_fp, "quit \n");
fclose($tmp_fp);

system("scp -i /usr/local/apache2/kikasete/gmo/gmoimport.ppk /tmp/batch.csv progmo@211.133.248.151:/tmp/");
//-----------------------------------------------------------------------------
// ＤＢ 処理
//-----------------------------------------------------------------------------
fwrite($fp, "DB INSERT処理 開始時間 ".date("Y:m:d H:i:s")."\n\n");

$sql  = "DELETE FROM g_monitor";
$result = db_exec($sql);
if ( pg_result_status($result) != PGSQL_COMMAND_OK ) {
    $errmsg = "$sql\n 処理ができませんでした。バッチ処理を終了します。\n";
}else {
    fwrite($fp, "$sql\nを処理しました。\n");

}

if( $errmsg == '' ){
    // CREATE a gmtp table
    $sql = "SELECT count(*) FROM pg_class WHERE relname='gtmp'";
    $result = db_fetch1($sql);
    if( $result == 1 ) {
        $sql = "DROP TABLE gtmp";
        $result = @db_exec($sql);
    }

    $sql  = "CREATE TABLE gtmp (";
    $sql .= "mn_monitor_id int,";
    $sql .= "mn_birthday date,";
    $sql .= "mn_sex smallint,";
    $sql .= "mn_mikikon smallint,";
    $sql .= "mn_jitaku_area smallint,";
    $sql .= "mn_shokugyou_cd smallint";
    $sql .= ")";
    $result = db_exec($sql);
    if ( pg_result_status($result) != PGSQL_COMMAND_OK ) {
        $errmsg = "$sql\n 処理ができませんでした。バッチ処理を終了します。\n";
    }
    else {
        fwrite($fp, "$sql\nを処理しました。\n");
        $table_created = true;
    }
}

if( $errmsg == '' ){
    $sql = "copy gtmp from '".FTP_REMOTE_PATH."/".FTP_LOCAL_FILE."' using delimiters ','";
    $result = db_exec($sql);
    if ( pg_result_status($result) != PGSQL_COMMAND_OK ) {
        $errmsg  = "\n$sql\n 処理ができませんでした。バッチ処理を終了します。\n";
    }
    else {
        fwrite($fp, "$sql\nを処理しました。\n");
    }
}

if( $errmsg == '' ){
    $sql = "INSERT INTO g_monitor SELECT mn_monitor_id,0,NULL,NULL,1,NULL,NULL,NULL,NULL,mn_birthday,mn_sex,mn_mikikon,NULL,NULL,NULL,NULL,mn_jitaku_area,NULL,NULL,NULL,NULL,NULL,NULL,mn_shokugyou_cd,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL FROM gtmp";
    $result = db_exec($sql);
    if ( pg_result_status($result) != PGSQL_COMMAND_OK ) {
        $errmsg = "$sql\n 処理ができませんでした。バッチ処理を終了します。\n";
    }
    else {
        fwrite($fp, "$sql\nを処理しました。\n");
    }
}

if( $table_created ){
    $sql = "DROP TABLE gtmp";
    $result = db_exec($sql);
    if ( pg_result_status($result) != PGSQL_COMMAND_OK ) {
        $errmsg = "$sql\n 処理ができませんでした。バッチ処理を終了します。\n";
    }
    else {
        fwrite($fp, "$sql\nを処理しました。\n");
    }
}
//-----------------------------------------------------------------------------

if( $errmsg != '' ) {
    fwrite($fp, $errmsg);
}

fwrite($fp, "\nDB INSERT処理 終了時間 ".date("Y:m:d H:i:s")."\n");
fclose($fp);

// ファイルパックアップ
if( !is_dir(DEST_PATH) ) {
    mkdir (DEST_PATH, 0700);
}
if( $errmsg == '' ){
    system("mv -f ".FTP_LOCAL_PATH."/".FTP_LOCAL_FILE." ".DEST_PATH."/".date("m").".csv");
}

//-------------------------------------------------------------------------------
//sum_dataの更新
$sql = "SELECT COUNT(*) FROM g_monitor WHERE mn_status=0";
$result = db_exec($sql);
if (pg_numrows($result)) {
		$fetch = pg_fetch_row($result, 0);
		$num = $fetch[0];
		$sql = "UPDATE t_sum_data SET su_date = NOW(), su_sum_data = $num WHERE su_sum_id=3";
		db_exec($sql);
}
system('echo "The import number is ' . $num . '"');
?>
