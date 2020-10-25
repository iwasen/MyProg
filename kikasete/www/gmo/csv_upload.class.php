<?php

define("UPDIR"    , "/tmp");
define("FILENAME" , "batch.csv");
define("ERRDIR"   , "errcsv");
define("ERRCSV"   , "ERROR.csv");
define("ERRHEAD"  , "モニターＩＤ,生年月日,性別,未既婚,居住地域コード,職業コード,元ファイル行番号,通番,エラー情報,エラーカラム\r\n");
define("DEBUG"    , false);
define("DEBUGFILE", "/tmp/debuginf");
define("PROGRESS", "10000");
define("ENCODING"  , "EUC_JP");
define("S_ENCODING", "auto");
define("MAX_ERRMSG_SIZE", 1000);
/******************************************************
' System :きかせて・net事務局用ページ
' Content:モニターＣＳＶインポートクラス
'******************************************************/
class CsvUpload {
    var $data = array();
    var $csvdata = array();
    var $fdata = array();
    var $file_size = 0;
    var $from = 0;
    var $to = 0;
    var $max_errmsg_size = 0;

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
        if ( file_exists( ERRDIR."/".ERRCSV )) unlink( ERRDIR."/".ERRCSV );

        //  ----- ファイル名をセッションに格納 -----
        $this->data["upfile"]["fullname"]   = $_FILES["upfile"]["fullname"];
        $this->data["upfile"]["name"]       = $_FILES["upfile"]["name"];
        $this->data["upfile"]["type"]       = $_FILES["upfile"]["type"];
        $this->data["upfile"]["size"]       = $_FILES["upfile"]["size"];
        $this->data["upfile"]["tmp_name"]   = $_FILES["upfile"]["tmp_name"];
        $this->data["upfile"]["error"]      = $_FILES["upfile"]["error"];
        $this->data["chkflg"]               = $_REQUEST["chkflg"];
        $this->data["upfile"]["error"]      = $_FILES["upfile"]["error"];

        $filename = UPDIR."/".$_FILES["upfile"]["name"];
        if (move_uploaded_file($_FILES["upfile"]["tmp_name"], $filename) == false ) {
            $_SESSION["upload_filename"]         = "";
            $_SESSION["upload_errfilename"]      = "";
            $_SESSION["upload_plainfilename"]    = "";
            $_SESSION["upload_plainerrfilename"] = "";
            $errmsg = "ＣＳＶファイルアップロードに失敗しました。";
        } else {
            $_SESSION["upload_filename"]         = $filename;
            $_SESSION["upload_plainfilename"]    = substr( strrchr( $filename, "/" ), 1);
            $_SESSION["upload_errfilename"]      = ERRDIR."/".ERRCSV;
            $_SESSION["upload_plainerrfilename"] = ERRCSV;
        }

        $_SESSION["upload_filecount"] = 0;
        $_SESSION["upload_okcnt"]     = 0;
        $_SESSION["upload_ngcnt"]     = 0;

        // ----- 先頭行タイトル切り分けフラグをセッションに格納 -----
        if ( $this->data["chkflg"][0] =="1") {
            $_SESSION["upload_headerflg"] = "1";
        }else{
            $_SESSION["upload_headerflg"] = "0";
        }
        $this->DEBUGWRITE("headerflg:".$_SESSION["upload_headerflg"]);
        // ----- CSVファイル展開 -----

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
            $this->fdata = file( $openFile );
            $this->file_size = sizeof( $this->fdata );
            if ( $this->file_size < 1 ) {
                $errmsg = "CSVファイルは空のようです";
            }else {
                $this->from = 0;
                $this->to = 0;
            }
        }

        if ($errmsg == "") {
            while ( $this->to < $this->file_size ) {
                $this->from = $this->to;
                $this->to = $this->from + PROGRESS;
                if( $this->to > $this->file_size ) $this->to = $this->file_size;
                $this->csvOpen( $this->from, $this->to,$filecount,"EUC-JP","SJIS");
                // ----- CSV内容チェック -----
                $this->csvNaiyoCheck( $filecount, $errcount );
                //----- CSVデータチェック結果をセッションへ書き出し -----
                $this->putCsvCheckResult( $filecount, $errcount );

                //----- エラーファイル作成 -----
                if ($errcount > 0) {
                    $this->createErrFile( $filecount );
                }
            }
        }

        if ($errmsg == "") {
            if( $_SESSION["upload_ngcnt"] > 0) {
                $_SESSION["upload_errtime"] = "(作成日時：".date("Y/m/d G:i:s").")";
                // CSVファイル削除
                unlink($openFile);
            }else{
                $_SESSION["upload_errtime"] = "エラーは有りませんでした。";
                $_SESSION["upload_errfilename"]      = "";
                $_SESSION["upload_plainerrfilename"] = "";
            }
        }


        if ($errmsg == "") {
            // CSVファイル削除
            $s_filename = mb_convert_encoding($_SESSION["upload_filename"], ENCODING, S_ENCODING);

            if( $_SESSION["upload_headerflg"] == "1" ) {
                system("mv -f ".$s_filename." ".UPDIR."/tmp.csv");

                system("sed '1 d' ".UPDIR."/tmp.csv > ".UPDIR."/".FILENAME);
                unlink(UPDIR."/tmp.csv");
            }else {
                system("mv -f ".$s_filename." ".UPDIR."/".FILENAME);
            }

            system("chmod 755 ".UPDIR."/".FILENAME);
            redirect("imp_result.php");
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
    function csvOpen( $from, $to, &$filecount, $encoding = "EUC_JP", $s_encoding = "SJIS") {
        $filecount = 0;

        for ( $i = $from; $i < $to; $i++ ) {
            $this->fdata[$i] = ereg_replace( "\r\n$", "", $this->fdata[$i]);
            $this->fdata[$i] = ereg_replace( "\r$", "", $this->fdata[$i]);
            $this->fdata[$i] = ereg_replace( "\n$", "", $this->fdata[$i]);
            $this->fdata[$i] = mb_convert_encoding( $this->fdata[$i], $encoding, $s_encoding);
            if (( $_SESSION["upload_headerflg"] == "1" ) and ( $i == 0 )) {
                // 先頭行がタイトルの場合
            } else {
                $sdata = split(",",$this->fdata[$i]);


                $this->csvdata["monitor_id"][$filecount] = $sdata[0];
                $this->csvdata["birthday"  ][$filecount] = $sdata[1];
                $this->csvdata["sex"       ][$filecount] = $sdata[2];
                $this->csvdata["mikikon"   ][$filecount] = $sdata[3];
                $this->csvdata["jitakuarea"][$filecount] = $sdata[4];
                $this->csvdata["shokugyou" ][$filecount] = $sdata[5];
//                $this->csvdata["taikai"    ][$filecount] = $sdata[6];

                $this->csvdata["field_cnt_valid" ][$filecount] = false;
                if( count($sdata) > 6 ) {
                    $this->csvdata["field_cnt_valid"][$filecount] = true;
                }

                $filecount++;
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

            if ( $this->csvdata["field_cnt_valid"][$i] ){
                $this->AddErrClmNm("項目数が多すぎます", $i);
                $errcnt_for_line++;
            }

            if ($errcnt_for_line > 0) {
                $errcount++;
                $this->max_errmsg_size++;

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
        $_SESSION["upload_filecount"] += $filecount;
        $_SESSION["upload_okcnt"]     += $filecount - $errcount;
        $_SESSION["upload_ngcnt"]     += $errcount;
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
        $fp = fopen( $_SESSION["upload_errfilename"], "a");
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

            // MAX_ERRMSG_SIZEまでエーら表示を出す
            if(  $nErrCnt > MAX_ERRMSG_SIZE ) break;

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
//                        $this->csvdata["taikai"    ][$i] . "," .
                        ($i+1) . "," .              // 元ファイルの行番号
                        $nErrCnt . "," .            // 通番
                        "ＣＳＶファイルの内容に誤りがあります。" . "," .
                        $errCol . // エラーカラム名
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


?>