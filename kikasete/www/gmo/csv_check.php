<?php

$top = '../admin';
$inc = "../inc";
include("$inc/common_gmo.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/error_msg.php");

session_start();

define("UPDIR"    , "/tmp");
define("ERRDIR"   , "errcsv");
define("ERRCSV"   , "ERROR.csv");
define("PROGRE"   , 500);
define("ERRHEAD"  , "モニターＩＤ,生年月日,性別,未既婚,居住地域コード,職業コード,退会フラグ,元ファイル行番号,通番,エラー情報,エラーカラム\r\n");
define("DEBUG"    , false);
define("DEBUGFILE", "/tmp/debuginf");

/******************************************************
' System :きかせて・net事務局用ページ
' Content:モニターＣＳＶインポートクラス
'******************************************************/
class CsvUpload {
    var $data = array();
    var $csvdata = array();
    var $updata = array();
    /*
    **  CsvUpload
    **  戻値      ：なし
    **  引数      ：なし
    **  機能説明  ：コンストラクタ
    */
    function CsvUpload() {
        $this->init();
    }
    /*
     **  getInstance
     **  戻値     ：なし
     **  引数     ：なし
     **  機能説明  ：インスタンス取得
     */
    function &getInstance() {
        static $singleton_;
        if ($singleton_ == null) {
            $singleton_ = new CsvUpload();
        }
        return $singleton_;
    }

    /*
    **  init
    **  戻値      ：なし
    **  引数      ：なし
    **  機能説明  ：画面入力値取得
    */
    function init() {
    }
    /*
     **  validate
     **  戻値     ：1.tErrMsg   エラーメッセージ
     **  引数     ：1.tErrMsg   エラーメッセージ
     **  機能説明 ：アップロードファイルチェック
     **  備考     ：
     */
    function validate(&$errmsg)
    {
        // エラーCSV削除
        if ( file_exists( ERRDIR.ERRCSV )) unlink( ERRDIR.ERRCSV );

        // ----- 先頭行タイトル切り分けフラグをセッションに格納 -----
        $this->DEBUGWRITE("headerflg:".$_SESSION["upload_headerflg"]);
        // ----- CSVファイル展開 -----
        $this->csvOpen( $errmsg, $filecount );
        if ($errmsg == "") {
            // ----- CSV内容チェック -----
            $this->csvNaiyoCheck( $filecount, $errcount );
        }
        if ($errmsg == "") {
            //----- CSVデータチェック結果をセッションへ書き出し -----
            $this->putCsvCheckResult( $filecount, $errcount );
        }
        if ($errmsg == "") {
            //----- エラーファイル作成 -----
            if ($errcount > 0) {
                $this->createErrFile( $filecount );
                $_SESSION["upload_errtime"] = "(作成日時：".date("Y/m/d G:i:s").")";
                $errmsg = "error";
            }else{
                $_SESSION["upload_errtime"] = "エラーは有りませんでした。";
                $_SESSION["upload_errfilename"]      = "";
                $_SESSION["upload_plainerrfilename"] = "";
            }
        }
    }
    /*
     **  csvArrayClear
     **  戻値     ：なし
     **  引数     ：なし
     **  機能説明 ：
     **  備考     ：
     */
    function csvArrayClear() {
    }
    /*
     **  csvOpen
     **  戻値     ：1.errmsg        エラーメッセージ
     **             2.filecount     csvファイルの行数
     **  引数     ：1.errmsg        エラーメッセージ
     **             2.filecount     読み取り行数
     **             3.headerflg 先頭行判別フラグタイトル('1':タイトル、他:非タイトル)
     **  機能説明 ：利用者CSVファイル展開
     **  備考     ：
     */
    function csvOpen(&$errmsg, &$filecount, $encoding = "EUC_JP", $s_encoding = "SJIS") {
        $errmsg = '';
        $filecount = 0;
        //  ----- CSVファイル名取得 -----
        $openFile = $_SESSION["upload_filename"];
        if ( $openFile == "" ) {
            $errmsg .= $this->data["upfile"]["error"];
        }
        // ----- CSVファイルオープン -----
        if ($errmsg == "") {
            $this->DEBUGWRITE("filename:".$openFile);
            if ( file_exists( $openFile ) == false ) {
                $errmsg = "CSVファイルのオープンに失敗しました。";
            }
        }
        if ( $errmsg == "" ) {
            // CSVファイルデータ取得
            $fdata = file( $openFile );
            if ( sizeof( $fdata ) < 1 ) {
                $errmsg = "CSVファイルは空のようです";
            }
        }
        if ( $errmsg == "" ) {
            for ( $i = 0; $i < sizeof( $fdata ); $i++ ) {
                $fdata[$i] = ereg_replace( "\r\n$", "", $fdata[$i]);
                $fdata[$i] = ereg_replace( "\r$", "", $fdata[$i]);
                $fdata[$i] = ereg_replace( "\n$", "", $fdata[$i]);
                $fdata[$i] = mb_convert_encoding( $fdata[$i], $encoding, $s_encoding);
                if (( $_SESSION["upload_headerflg"] == "1" ) and ( $i == 0 )) {
                    // 先頭行がタイトルの場合
                } else {
                    $sdata[$i] = split(",",$fdata[$i]);
                    $this->csvdata["monitor_id"][$filecount] = $sdata[$i][0];
                    $this->csvdata["birthday"  ][$filecount] = $sdata[$i][1];
                    $this->csvdata["sex"       ][$filecount] = $sdata[$i][2];
                    $this->csvdata["mikikon"   ][$filecount] = $sdata[$i][3];
                    $this->csvdata["jitakuarea"][$filecount] = $sdata[$i][4];
                    $this->csvdata["shokugyou" ][$filecount] = $sdata[$i][5];
                    $this->csvdata["taikai"    ][$filecount] = $sdata[$i][6];
                    $filecount++;
                }
            }
        }
    }
    /*
     **  csvNaiyoCheck
     **  戻値     ：2.errcount（戻値)   CSVフォーマットエラー数
     **  引数     ：1.filecount         CSVファイル読取り件数（タイトル行含まず）
     **  機能説明 ：CSVファイルの内容チェック
     **  備考     ：
     */
    function csvNaiyoCheck($filecount, &$errcount){
        $errcount = 0;

        // 職業変更時に対応
        $sql = 'SELECT sg_shokugyou_cd FROM m_shokugyou';
        $result = db_exec($sql);
        $nrow  = pg_numrows($result);
        $sg_shokugyou_cd = array();
        for($i=0; $i<$nrow; $i++) {
            $fetch = pg_fetch_object($result, $i);
            $sg_shokugyou_cd[] = $fetch->sg_shokugyou_cd;
        }

        for ($i=0;$i<$filecount;$i++) {
            $this->csvdata["errclm"][$i] = "";
            $errcnt_for_line = 0;
            // MONITOR_ID
            if (!is_numeric($this->csvdata["monitor_id"][$i])) {
                $this->AddErrClmNm("モニターＩＤ", $i);
                $errcnt_for_line++;
            }
            // BIRTHDAY
            if (!ereg("^([0-9]{4})[/]([01]?[0-9])[/]([0123]?[0-9])$", $this->csvdata["birthday"][$i], $parts) ) {
                $this->AddErrClmNm("生年月日", $i);
                $errcnt_for_line++;
            }else if (!checkdate($parts[2], $parts[3], $parts[1])) {
                $this->AddErrClmNm("生年月日", $i);
                $errcnt_for_line++;
            }
            // SEX
//            if ($this->csvdata["sex"][$i] != 1 and $this->csvdata["sex"][$i] != 2) {
            if ($this->csvdata["sex"][$i] != 1 and $this->csvdata["sex"][$i] != 2 and $this->csvdata["sex"][$i] != 3) {
                $this->AddErrClmNm("性別", $i);
                $errcnt_for_line++;
            }
            // MIKIKON
//            if ($this->csvdata["mikikon"][$i] != 1 and $this->csvdata["mikikon"][$i] != 2) {
            if ($this->csvdata["mikikon"][$i] != 1 and $this->csvdata["mikikon"][$i] != 2 and $this->csvdata["mikikon"][$i] != 3) {
                $this->AddErrClmNm("未既婚", $i);
                $errcnt_for_line++;
            }
            // AREA
            if (is_numeric($this->csvdata["jitakuarea"][$i])){
                if ( !(1 <= $this->csvdata["jitakuarea"][$i] and $this->csvdata["jitakuarea"][$i] <=47)){
                    $this->AddErrClmNm("居住地域コード", $i);
                    $errcnt_for_line++;
                }
//              $sql = 'SELECT ar_area_cd FROM m_area WHERE ar_area_cd = '.$this->csvdata["jitakuarea"][$i];
//              $result = db_exec($sql);
//              if (pg_numrows($result) == 0) {
//                  $this->AddErrClmNm("居住地域コード", $i);
//                  $errcnt_for_line++;
//              }
            }else{
                $this->AddErrClmNm("居住地域コード", $i);
                $errcnt_for_line++;
            }
            // SHOKUGYOU
            if (is_numeric($this->csvdata["shokugyou"][$i])){
                if ( !in_array($this->csvdata["shokugyou"][$i], $sg_shokugyou_cd) ) {
                  $this->AddErrClmNm("職業コード", $i);
                  $errcnt_for_line++;
                }
//              $sql = 'SELECT sg_shokugyou_cd FROM m_shokugyou WHERE sg_shokugyou_cd = '.$this->csvdata["shokugyou"][$i];
//              $result = db_exec($sql);
//              if (pg_numrows($result) == 0) {
//                  $this->AddErrClmNm("職業コード", $i);
//                  $errcnt_for_line++;
//              }
            }else{
                $this->AddErrClmNm("職業コード", $i);
                $errcnt_for_line++;
            }
            // TAIKAI
//          if ($this->csvdata["taikai"][$i] != 0 and $this->csvdata["taikai"][$i] != 1) {
//              $this->AddErrClmNm("退会フラグ", $i);
//              $errcnt_for_line++;
//          }
            if ($errcnt_for_line > 0) {
                $errcount++;
                $this->csvdata["errid"][$i] = "110";
            }else{
                $this->csvdata["errid"][$i] = "";
            }
        }
    }
    function AddErrClmNm($clmnm, $i){
        if ($this->csvdata["errclm"][$i] != "") {
            $this->csvdata["errclm"][$i] .= ",";
        }
        $this->csvdata["errclm"][$i] .= $clmnm;
    }
    /*
     **  putCsvCheckResult
     **  戻値     ：
     **  引数     ：なし
     **  機能説明 ：CSVファイルチェック結果のセッションへの書き出し
     **  備考     ：
     */
    function putCsvCheckResult($filecount, $errcount) {
        // セッションに保存
        $_SESSION["upload_filecount"] = $filecount;
        $_SESSION["upload_okcnt"]     = $filecount - $errcount;
        $_SESSION["upload_ngcnt"]     = $errcount;
        if ($errcount == 0) {
            $_SESSION["upload_plainerrfilename"] = "";
        }
    }
    /**
     *     エラーCSVファイルを作成する
     */
    function createErrFile($filecount, $encoding = "EUC_JP", $s_encoding = "SJIS")
    {
        // ----- ディレクトリ無ければ作る -----
        if (!file_exists(ERRDIR)) {
            if (!mkdir(ERRDIR)) {
                return;
            }
        }
        // ----- ファイルオープン -----
        $fp = fopen( $_SESSION["upload_errfilename"], "w");
        if ($fp == 0) {
            return;
        }
        // ----- ヘッダーの書込み -----
        $tBuff = ERRHEAD;
        $tBuff = mb_convert_encoding($tBuff,$s_encoding,$encoding);
        fputs( $fp, $tBuff );

        // ----- 内容の書込み -----
        $nErrCnt = 0;
        // ファイルレコード分ループ
        for ($i = 0; $i < $filecount; $i++) {
            $tBuff  = "";
            if (!isset($this->csvdata["errid"][$i])) continue; // 正常レコード
            if ($this->csvdata["errid"][$i] == "")   continue; // 正常レコード
            $nErrCnt++;
            $errCol = "";
            if (isset($this->csvdata["errclm"][$i]) ) {
                $errCol = $this->csvdata["errclm"][$i];
            }
            $tBuff =    $this->csvdata["monitor_id"][$i] . "," .
                        $this->csvdata["birthday"  ][$i] . "," .
                        $this->csvdata["sex"       ][$i] . "," .
                        $this->csvdata["mikikon"   ][$i] . "," .
                        $this->csvdata["jitakuarea"][$i] . "," .
                        $this->csvdata["shokugyou" ][$i] . "," .
                        $this->csvdata["taikai"    ][$i] . "," .
                        ($i+1) . "," .              // 元ファイルの行番号
                        $nErrCnt . "," .            // 通番
                        "ＣＳＶファイルの内容に誤りがあります。" . "," .
                        $errCol  .                  // エラーカラム名
                        "\r\n";
            $tBuff = mb_convert_encoding($tBuff,$s_encoding,$encoding);
            fputs( $fp, $tBuff );
        }
        fclose($fp);
    }
    function DEBUGWRITE($str){
        if (DEBUG) {
            $fp = fopen( DEBUGFILE, "a");
            fputs($fp, $str."\n");
            fclose($fp);
        }
    }
}

//$_SESSION["upload_filename"] = $argv[1];

$up = &CsvUpload::getInstance();
$up->DEBUGWRITE("--------");

// CSVデータチェック
$up->DEBUGWRITE("VALIDATE");

$up->validate($errmsg);

echo $errmsg;

?>
