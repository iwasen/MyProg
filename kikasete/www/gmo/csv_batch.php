<?php

$top = '../admin';
$inc = "../inc";
include("$inc/common_gmo.php");
include("$inc/database.php");

session_start();

define("UPDIR"    , "/tmp");
define("UPFILE"    , "gmo_tmp.csv");
define("SLOWMODE" , false);
define("SLEEP"    , 2);
define("DEBUG"    , false);
define("DEBUGFILE", "/usr/local/apache2/htdocs/kikasete/gmo/debuginf");

if( $_REQUEST["from"] == 0 ) {
//if( $argv[1] == 0 ) {
    $sql  = "DELETE FROM g_monitor";
    DEBUGWRITE($sql);
    @db_exec($sql);
}

$from = $_REQUEST["from"];
$to   = $_REQUEST["to"];

csvOpen($csvdata);

dbProc($errid, $csvdata, $from, $to);
//dbProc($errid, $csvdata, $argv[1], $argv[2]);

echo $errid;

function DEBUGWRITE($str){
    if ( DEBUG ) {
        $fp = fopen( DEBUGFILE, "a");
        fputs($fp, $str."\n");
        fclose($fp);

    }
}


function csvOpen(&$csvdata, $encoding = "EUC_JP", $s_encoding = "SJIS") {
    $errid = '';
    //  ----- CSVファイル名取得 -----
    $openFile = UPDIR."/".UPFILE;

    // CSVファイルデータ取得
    $fdata = file( $openFile );
    $filecount = sizeof($fdata);
    $csvdata = array();

    global $from,$to;
    for ( $i = 0; $i < $filecount; $i++ ) {
        // 先頭行がタイトルの場合
        if (( $_SESSION["upload_headerflg"] == "1" ) and ( $i == 0 )) continue;

        if ( $i < $from ) continue;
        if ( $i >= $to ) return;

        $fdata[$i] = ereg_replace( "\r\n$", "", $fdata[$i]);
        $fdata[$i] = ereg_replace( "\r$", "", $fdata[$i]);
        $fdata[$i] = ereg_replace( "\n$", "", $fdata[$i]);
        $fdata[$i] = mb_convert_encoding( $fdata[$i], $encoding, $s_encoding);

        $sdata[$i] = split(",",$fdata[$i]);
        $csvdata["monitor_id"][$i] = $sdata[$i][0];
        $csvdata["birthday"  ][$i] = $sdata[$i][1];
        $csvdata["sex"       ][$i] = $sdata[$i][2];
        $csvdata["mikikon"   ][$i] = $sdata[$i][3];
        $csvdata["jitakuarea"][$i] = $sdata[$i][4];
        $csvdata["shokugyou" ][$i] = $sdata[$i][5];
        $csvdata["taikai"    ][$i] = $sdata[$i][6];
    }

}

function dbProc(&$errid, &$csvdata, $from, $to) {
//        db_begin_trans();
    for($i=$from; $i < $to ; $i++) {
        // 先頭行がタイトルの場合
        if (( $_SESSION["upload_headerflg"] == "1" ) and ( $i == 0 )) continue;

        DEBUGWRITE("i:".$i);

        $sql  = "INSERT INTO g_monitor(mn_monitor_id, mn_birthday, mn_sex, mn_mikikon, ";
        $sql .= "mn_jitaku_area, mn_shokugyou_cd, mn_status, mn_type) ";
        $sql .= "VALUES (";
        $sql .= $csvdata["monitor_id"][$i] . ",'" . $csvdata["birthday"][$i]  . "'," ;
        $sql .= $csvdata["sex"][$i]        . ", " . $csvdata["mikikon"][$i]   . " ," ;
        $sql .= $csvdata["jitakuarea"][$i] . ", " . $csvdata["shokugyou"][$i] . " ," ;
        if($csvdata["taikai"][$i] == "1")   $sql .= "9" ;
        else                                $sql .= "0" ;
        $sql .= ',1 )';
        DEBUGWRITE($sql);
        $result = @db_exec($sql);

        if ( pg_result_status($result) != PGSQL_COMMAND_OK ) {
            $errid = $csvdata["monitor_id"][$i];
            return;
        }
    }
}
?>
