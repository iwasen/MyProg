<?php

define("UPDIR"    , "/tmp");
define("FILENAME" , "batch.csv");
define("ERRDIR"   , "errcsv");
define("ERRCSV"   , "ERROR.csv");
define("ERRHEAD"  , "��˥����ɣ�,��ǯ����,����,̤����,�ｻ�ϰ襳����,���ȥ�����,���ե�������ֹ�,����,���顼����,���顼�����\r\n");
define("DEBUG"    , false);
define("DEBUGFILE", "/tmp/debuginf");
define("PROGRESS", "10000");
define("ENCODING"  , "EUC_JP");
define("S_ENCODING", "auto");
define("MAX_ERRMSG_SIZE", 1000);
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:��˥����ãӣ֥���ݡ��ȥ��饹
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
    **  ����      ���ʤ�
    **  ����      ���ʤ�
    **  ��ǽ����  �����󥹥ȥ饯��
    */
    function CsvUpload() {
        $this->init();
    }
    /*
     **  getInstance
     **  ����     ���ʤ�
     **  ����     ���ʤ�
     **  ��ǽ����  �����󥹥��󥹼���
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
    **  ����      ���ʤ�
    **  ����      ���ʤ�
    **  ��ǽ����  �����������ͼ���
    */
    function init() {
    }
    /*
     **  validate
     **  ����     ��1.tErrMsg   ���顼��å�����
     **  ����     ��1.tErrMsg   ���顼��å�����
     **  ��ǽ���� �����åץ��ɥե���������å�
     **  ����     ��
     */
    function validate(&$errmsg)
    {
        // ���顼CSV���
        if ( file_exists( ERRDIR."/".ERRCSV )) unlink( ERRDIR."/".ERRCSV );

        //  ----- �ե�����̾�򥻥å����˳�Ǽ -----
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
            $errmsg = "�ãӣ֥ե����륢�åץ��ɤ˼��Ԥ��ޤ�����";
        } else {
            $_SESSION["upload_filename"]         = $filename;
            $_SESSION["upload_plainfilename"]    = substr( strrchr( $filename, "/" ), 1);
            $_SESSION["upload_errfilename"]      = ERRDIR."/".ERRCSV;
            $_SESSION["upload_plainerrfilename"] = ERRCSV;
        }

        $_SESSION["upload_filecount"] = 0;
        $_SESSION["upload_okcnt"]     = 0;
        $_SESSION["upload_ngcnt"]     = 0;

        // ----- ��Ƭ�ԥ����ȥ��ڤ�ʬ���ե饰�򥻥å����˳�Ǽ -----
        if ( $this->data["chkflg"][0] =="1") {
            $_SESSION["upload_headerflg"] = "1";
        }else{
            $_SESSION["upload_headerflg"] = "0";
        }
        $this->DEBUGWRITE("headerflg:".$_SESSION["upload_headerflg"]);
        // ----- CSV�ե�����Ÿ�� -----

        //  ----- CSV�ե�����̾���� -----
        $openFile = $_SESSION["upload_filename"];
        if ( $openFile == "" ) {
            $errmsg .= $this->data["upfile"]["error"];
        }
        // ----- CSV�ե����륪���ץ� -----
        if ($errmsg == "") {
            $this->DEBUGWRITE("filename:".$openFile);
            if ( file_exists( $openFile ) == false ) {
                $errmsg = "CSV�ե�����Υ����ץ�˼��Ԥ��ޤ�����";
            }
        }
        if ( $errmsg == "" ) {
            // CSV�ե�����ǡ�������
            $this->fdata = file( $openFile );
            $this->file_size = sizeof( $this->fdata );
            if ( $this->file_size < 1 ) {
                $errmsg = "CSV�ե�����϶��Τ褦�Ǥ�";
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
                // ----- CSV���ƥ����å� -----
                $this->csvNaiyoCheck( $filecount, $errcount );
                //----- CSV�ǡ��������å���̤򥻥å����ؽ񤭽Ф� -----
                $this->putCsvCheckResult( $filecount, $errcount );

                //----- ���顼�ե�������� -----
                if ($errcount > 0) {
                    $this->createErrFile( $filecount );
                }
            }
        }

        if ($errmsg == "") {
            if( $_SESSION["upload_ngcnt"] > 0) {
                $_SESSION["upload_errtime"] = "(����������".date("Y/m/d G:i:s").")";
                // CSV�ե�������
                unlink($openFile);
            }else{
                $_SESSION["upload_errtime"] = "���顼��ͭ��ޤ���Ǥ�����";
                $_SESSION["upload_errfilename"]      = "";
                $_SESSION["upload_plainerrfilename"] = "";
            }
        }


        if ($errmsg == "") {
            // CSV�ե�������
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
     **  ����     ���ʤ�
     **  ����     ���ʤ�
     **  ��ǽ���� ��
     **  ����     ��
     */
    function csvArrayClear() {
    }
    /*
     **  csvOpen
     **  ����     ��1.errmsg        ���顼��å�����
     **             2.filecount     csv�ե�����ιԿ�
     **  ����     ��1.errmsg        ���顼��å�����
     **             2.filecount     �ɤ߼��Կ�
     **             3.headerflg ��Ƭ��Ƚ�̥ե饰�����ȥ�('1':�����ȥ롢¾:�󥿥��ȥ�)
     **  ��ǽ���� �����Ѽ�CSV�ե�����Ÿ��
     **  ����     ��
     */
    function csvOpen( $from, $to, &$filecount, $encoding = "EUC_JP", $s_encoding = "SJIS") {
        $filecount = 0;

        for ( $i = $from; $i < $to; $i++ ) {
            $this->fdata[$i] = ereg_replace( "\r\n$", "", $this->fdata[$i]);
            $this->fdata[$i] = ereg_replace( "\r$", "", $this->fdata[$i]);
            $this->fdata[$i] = ereg_replace( "\n$", "", $this->fdata[$i]);
            $this->fdata[$i] = mb_convert_encoding( $this->fdata[$i], $encoding, $s_encoding);
            if (( $_SESSION["upload_headerflg"] == "1" ) and ( $i == 0 )) {
                // ��Ƭ�Ԥ������ȥ�ξ��
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
     **  ����     ��2.errcount������)   CSV�ե����ޥåȥ��顼��
     **  ����     ��1.filecount         CSV�ե������ɼ�����ʥ����ȥ�Դޤޤ���
     **  ��ǽ���� ��CSV�ե���������ƥ����å�
     **  ����     ��
     */
    function csvNaiyoCheck($filecount, &$errcount){
        $errcount = 0;

        // �����ѹ������б�
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
                $this->AddErrClmNm("��˥����ɣ�", $i);
                $errcnt_for_line++;
            }
            // BIRTHDAY
            if (!ereg("^([0-9]{4})[/]([01]?[0-9])[/]([0123]?[0-9])$", $this->csvdata["birthday"][$i], $parts) ) {
                $this->AddErrClmNm("��ǯ����", $i);
                $errcnt_for_line++;
            }else if (!checkdate($parts[2], $parts[3], $parts[1])) {
                $this->AddErrClmNm("��ǯ����", $i);
                $errcnt_for_line++;
            }
            // SEX
//            if ($this->csvdata["sex"][$i] != 1 and $this->csvdata["sex"][$i] != 2) {
            if ($this->csvdata["sex"][$i] != 1 and $this->csvdata["sex"][$i] != 2 and $this->csvdata["sex"][$i] != 3) {
                $this->AddErrClmNm("����", $i);
                $errcnt_for_line++;
            }
            // MIKIKON
//            if ($this->csvdata["mikikon"][$i] != 1 and $this->csvdata["mikikon"][$i] != 2) {
            if ($this->csvdata["mikikon"][$i] != 1 and $this->csvdata["mikikon"][$i] != 2 and $this->csvdata["mikikon"][$i] != 3) {
                $this->AddErrClmNm("̤����", $i);
                $errcnt_for_line++;
            }
            // AREA
            if (is_numeric($this->csvdata["jitakuarea"][$i])){
                if ( !(1 <= $this->csvdata["jitakuarea"][$i] and $this->csvdata["jitakuarea"][$i] <=47)){
                    $this->AddErrClmNm("�ｻ�ϰ襳����", $i);
                    $errcnt_for_line++;
                }
//              $sql = 'SELECT ar_area_cd FROM m_area WHERE ar_area_cd = '.$this->csvdata["jitakuarea"][$i];
//              $result = db_exec($sql);
//              if (pg_numrows($result) == 0) {
//                  $this->AddErrClmNm("�ｻ�ϰ襳����", $i);
//                  $errcnt_for_line++;
//              }
            }else{
                $this->AddErrClmNm("�ｻ�ϰ襳����", $i);
                $errcnt_for_line++;
            }
            // SHOKUGYOU
            if (is_numeric($this->csvdata["shokugyou"][$i])){
                if ( !in_array($this->csvdata["shokugyou"][$i], $sg_shokugyou_cd) ) {
                  $this->AddErrClmNm("���ȥ�����", $i);
                  $errcnt_for_line++;
                }
//              $sql = 'SELECT sg_shokugyou_cd FROM m_shokugyou WHERE sg_shokugyou_cd = '.$this->csvdata["shokugyou"][$i];
//              $result = db_exec($sql);
//              if (pg_numrows($result) == 0) {
//                  $this->AddErrClmNm("���ȥ�����", $i);
//                  $errcnt_for_line++;
//              }
            }else{
                $this->AddErrClmNm("���ȥ�����", $i);
                $errcnt_for_line++;
            }
            // TAIKAI
//          if ($this->csvdata["taikai"][$i] != 0 and $this->csvdata["taikai"][$i] != 1) {
//              $this->AddErrClmNm("���ե饰", $i);
//              $errcnt_for_line++;
//          }

            if ( $this->csvdata["field_cnt_valid"][$i] ){
                $this->AddErrClmNm("���ܿ���¿�����ޤ�", $i);
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
     **  ����     ��
     **  ����     ���ʤ�
     **  ��ǽ���� ��CSV�ե���������å���̤Υ��å����ؤν񤭽Ф�
     **  ����     ��
     */
    function putCsvCheckResult($filecount, $errcount) {
        // ���å�������¸
        $_SESSION["upload_filecount"] += $filecount;
        $_SESSION["upload_okcnt"]     += $filecount - $errcount;
        $_SESSION["upload_ngcnt"]     += $errcount;
    }
    /**
     *     ���顼CSV�ե�������������
     */
    function createErrFile($filecount, $encoding = "EUC_JP", $s_encoding = "SJIS")
    {
        // ----- �ǥ��쥯�ȥ�̵����к�� -----
        if (!file_exists(ERRDIR)) {
            if (!mkdir(ERRDIR)) {
                return;
            }
        }
        // ----- �ե����륪���ץ� -----
        $fp = fopen( $_SESSION["upload_errfilename"], "a");
        if ($fp == 0) {
            return;
        }
        // ----- �إå����ν���� -----
        $tBuff = ERRHEAD;
        $tBuff = mb_convert_encoding($tBuff,$s_encoding,$encoding);
        fputs( $fp, $tBuff );

        // ----- ���Ƥν���� -----
        $nErrCnt = 0;
        // �ե�����쥳����ʬ�롼��
        for ($i = 0; $i < $filecount; $i++) {
            $tBuff  = "";
            if (!isset($this->csvdata["errid"][$i])) continue; // ����쥳����
            if ($this->csvdata["errid"][$i] == "")   continue; // ����쥳����
            $nErrCnt++;

            // MAX_ERRMSG_SIZE�ޤǥ�����ɽ����Ф�
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
                        ($i+1) . "," .              // ���ե�����ι��ֹ�
                        $nErrCnt . "," .            // ����
                        "�ãӣ֥ե���������Ƥ˸�꤬����ޤ���" . "," .
                        $errCol . // ���顼�����̾
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