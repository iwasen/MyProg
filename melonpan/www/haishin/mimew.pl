package MIME;
# Copyright (C) 1993-94,1997 Noboru Ikuta <noboru@ikuta.ichihara.chiba.jp>
#
# mimew.pl: MIME encoder library Ver.2.02 (1997/12/30)

$main'mimew_version = "2.02";

# ���󥹥ȡ��� : @INC �Υǥ��쥯�ȥ���̾�� /usr/local/lib/perl�ˤ˥��ԡ�
#                ���Ʋ�������
#
# ������1 : require 'mimew.pl';
#           $from = "From: ���� �� <noboru\@ikuta.ichihara.chiba.jp>";
#           print &mimeencode($from);
#
# ������2 : # UNIX��Base64���󥳡��ɤ�����
#           require 'mimew.pl';
#           undef $/;
#           $body = <>;
#           print &bodyencode($body);
#           print &benflush;
#
# &bodyencode($data,$coding):
#   �ǡ�����Base64�����ޤ���Quoted-Printable�����ǥ��󥳡��ɤ��롣
#   ��2�ѥ�᡼����"qp"�ޤ���"b64"����ꤹ�뤳�Ȥˤ�ꥳ���ǥ��󥰷���
#   ��ؼ����뤳�Ȥ��Ǥ��롣��2�ѥ�᡼�����ά�����Base64�����ǥ���
#   �����ɤ��롣
#   Base64�����Υ��󥳡��ɤξ��ϡ�$foldcol*3/4 �Х���ñ�̤��Ѵ�����
#   �Τǡ��Ϥ��줿�ǡ����Τ���Ⱦü����ʬ�ϥХåե�����¸���켡�˸ƤФ�
#   ���Ȥ��˽�������롣�Ǹ�˥Хåե��˻Ĥä��ǡ�����&benflush��Ƥ�
#   ���Ȥˤ���������Хåե����饯�ꥢ����롣
#   Quoted-Printable�����Υ��󥳡��ɤξ��ϡ���ñ�̤��Ѵ����뤿�ᡢ
#   �ǡ����κǸ�˲���ʸ����̵����硢�Ǹ�β���ʸ���θ��Υǡ�����
#   �Хåե�����¸���졢���˸ƤФ줿�Ȥ��˽�������롣�Ǹ�˥Хåե�
#   �˻Ĥä��ǡ�����&benflush("qp")��Ƥ֤��Ȥˤ���������Хåե�
#   ���饯�ꥢ����롣
#
# &benflush($coding):
#   ��1�ѥ�᡼����"b64"�ޤ���"qp"����ꤹ�뤳�Ȥˤ�ꡢ���줾��Base64
#   �����ޤ���Quoted-Printable�����Υ��󥳡��ɤ���ꤹ�뤳�Ȥ��Ǥ��롣
#   ��1�ѥ�᡼���˲�����ꤷ�ʤ����Base64�����ǥ��󥳡��ɤ���롣
#   Base64�Υ��󥳡��ɤξ�硢&bodyencode���������Ĥ����ǡ����������
#   padʸ������Ϥ��롣Quoted-Printable�ξ�硢��ñ�̤Ǥʤ��֥�å�ñ
#   �̤�&bodyencode��Ƥ־�硢&bodyencode���������Ĥ����ǡ������⤷
#   �Хåե��˻ĤäƤ���Ф����������롣
#   ��ĤΥǡ�����(1��ޤ��ϲ��󤫤�ʬ����)&bodyencode�������ɬ��1��
#   �Ƥ�ɬ�פ����롣
#
# &mimeencode($text):
#   ��1�ѥ�᡼�������ܸ�ʸ�����ޤ�Ǥ���С�������ʬ��ISO-2022-JP��
#   �Ѵ��������ȡ�MIME encoded-word(RFC2047����)���Ѵ����롣ɬ�פ˱���
#   ��encoded-word��ʬ���encoded-word������Ǥι�ʬ���Ԥ���
#
#   ʸ�������ɤμ�ưȽ��ϡ�Ʊ��Ԥ�ShiftJIS��EUC�����ߤ��Ƥ������
#   �����ƴ��������ɤκ��ߤˤ��б����Ƥ��롣ShiftJIS��EUC���ɤ����Ƥ�
#   Ƚ�ǤǤ��ʤ��Ȥ���$often_use_kanji�����ꤵ��Ƥ��륳���ɤ�Ƚ�ꤹ�롣
#   ISO-2022-JP�Υ��������ץ������󥹤�$jis_in��$jis_out�����ꤹ�뤳��
#   �ˤ���ѹ���ǽ�Ǥ��롣

$often_use_kanji = 'EUC'; # or 'SJIS'

$jis_in  = "\x1b\$B"; # ESC-$-B ( or ESC-$-@ )
$jis_out = "\x1b\(B"; # ESC-(-B ( or ESC-(-J )

# ���۾�� : ������������ޤ��󤬡����ۡ����Ѥϼ�ͳ�Ȥ��ޤ������Ѥ���
#            ���ۤ�����ϡ����ꥸ�ʥ�Ȱۤʤ뤳�Ȥ������������ꥸ�ʥ�
#            �ΥС������ʥ�С��˲����ǥС������ʥ�С����ղä�����
#            �㤨�� Ver.2.02-XXXXX �Τ褦�ʥС������ʥ�С����դ��Ʋ�
#            �������ʤ���Copyrightɽ�����ѹ����ʤ��Ǥ���������
#
# ��� : &mimeencode��jperl1.X(��2�Х���ʸ���б��⡼��)�ǻ��Ѥ���ȡ�SJIS
#        ��EUC�򤦤ޤ�7bit JIS(ISO-2022-JP)���Ѵ��Ǥ��ޤ���
#        ���Ϥ˴ޤޤ��ʸ����7bit JIS(ISO-2022-JP)��ASCII�ΤߤǤ��뤳��
#        ���ݾڤ���Ƥ�����������ɬ��original�αѸ��Ǥ�perl�ʤޤ���
#        jperl1.4�ʾ�� -Llatin ���ץ�����դ��ˤ�ư�����Ƥ���������
#        �ʤ���Perl5�б���jperl�ϻ�����Ȥ��ʤ��ΤǤɤΤ褦��ư��ˤʤ�
#        ���狼��ޤ���
#
# ���� : RFC1468, RFC2045, RFC2047

## MIME base64 ����ե��٥åȥơ��֥��RFC2045����
%mime = (
"000000", "A",  "000001", "B",  "000010", "C",  "000011", "D",
"000100", "E",  "000101", "F",  "000110", "G",  "000111", "H",
"001000", "I",  "001001", "J",  "001010", "K",  "001011", "L",
"001100", "M",  "001101", "N",  "001110", "O",  "001111", "P",
"010000", "Q",  "010001", "R",  "010010", "S",  "010011", "T",
"010100", "U",  "010101", "V",  "010110", "W",  "010111", "X",
"011000", "Y",  "011001", "Z",  "011010", "a",  "011011", "b",
"011100", "c",  "011101", "d",  "011110", "e",  "011111", "f",
"100000", "g",  "100001", "h",  "100010", "i",  "100011", "j",
"100100", "k",  "100101", "l",  "100110", "m",  "100111", "n",
"101000", "o",  "101001", "p",  "101010", "q",  "101011", "r",
"101100", "s",  "101101", "t",  "101110", "u",  "101111", "v",
"110000", "w",  "110001", "x",  "110010", "y",  "110011", "z",
"110100", "0",  "110101", "1",  "110110", "2",  "110111", "3",
"111000", "4",  "111001", "5",  "111010", "6",  "111011", "7",
"111100", "8",  "111101", "9",  "111110", "+",  "111111", "/",
);

## JIS������(byte��)��encoded-word ��ʸ�����б�
%mimelen = (
 8,30, 10,34, 12,34, 14,38, 16,42,
18,42, 20,46, 22,50, 24,50, 26,54,
28,58, 30,58, 32,62, 34,66, 36,66,
38,70, 40,74, 42,74,
);

## �إå����󥳡��ɻ��ιԤ�Ĺ��������
$limit=74; ## ����ա� $limit��75����礭�����������ꤷ�ƤϤ����ʤ���

## �ܥǥ�base64���󥳡��ɻ��ιԤ�Ĺ��������
$foldcol=72; ## ����ա� $foldcol��76�ʲ���4���ܿ������ꤹ�뤳�ȡ�

## �ܥǥ�Quoted-Printable���󥳡��ɻ��ιԤ�Ĺ��������
$qfoldcol=75; ## ����ա� $foldcol��76�ʲ������ꤹ�뤳�ȡ�

## null bit�������� padʸ���������Τ���Υơ��֥�
@zero = ( "", "00000", "0000", "000", "00", "0" );
@pad  = ( "", "===",   "==",   "=" );

## ASCII, 7bit JIS, Shift-JIS �ڤ� EUC �γơ��˥ޥå�����ѥ�����
$match_ascii = '\x1b\([BHJ]([\t\x20-\x7e]*)';
$match_jis = '\x1b\$[@B](([\x21-\x7e]{2})*)';
$match_sjis = '([\x81-\x9f\xe0-\xfc][\x40-\x7e\x80-\xfc])+';
$match_euc  = '([\xa1-\xfe]{2})+';

## MIME Part 2(charset=`ISO-2022-JP',encoding=`B') �� head �� tail
$mime_head = '=?ISO-2022-JP?B?';
$mime_tail = '?=';

## &bodyencode ���Ȥ������Ĥ��ǡ����ѥХåե�
$benbuf = "";

## &bodyencode �ν���ñ�̡ʥХ��ȡ�
$bensize = int($foldcol/4)*3;

## &mimeencode interface ##
sub main'mimeencode {
    local($_) = @_;
    s/$match_jis/$jis_in$1/go;
    s/$match_ascii/$jis_out$1/go;
############# ��¤2000/12/26 ����:EUC��ᤦ��######################
#    $kanji = &checkkanji;
    $kanji = 'EUC';
###################################################################
    s/$match_sjis/&s2j($&)/geo if ($kanji eq 'SJIS');
    s/$match_euc/&e2j($&)/geo if ($kanji eq 'EUC');
    s/(\x1b[\$\(][BHJ@])+/$1/g;
    1 while s/(\x1b\$[B@][\x21-\x7e]+)\x1b\$[B@]/$1/;
    1 while s/$match_jis/&mimeencode($&,$`,$')/eo;
    s/$match_ascii/$1/go;
    $_;
}

## &bodyencode interface ##
sub main'bodyencode {
    local($_,$coding) = @_;
    if (!defined($coding) || $coding eq "" || $coding eq "b64"){
	$_ = $benbuf . $_;
	local($cut) = int((length)/$bensize)*$bensize;
	$benbuf = substr($_, $cut+$[);
	$_ = substr($_, $[, $cut);
	$_ = &base64encode($_);
	s/.{$foldcol}/$&\n/g;
    }elsif ($coding eq "qp"){
	# $benbuf �����Ǥʤ���Хǡ����κǽ���ɲä���
	$_ = $benbuf . $_;

	# ����ʸ��������������
	s/\r\n/\n/g;
	s/\r/\n/g;

	# �ǡ������ñ�̤�ʬ�䤹��(�Ǹ�β���ʸ���ʹߤ� $benbuf ����¸����)
	@line = split(/\n/,$_,-1);
	$benbuf = pop(@line);

	local($result) = "";
	foreach (@line){
	    $_ = &qpencode($_);
	    $result .= $_ . "\n";
	}
	$_ = $result;
    }
    $_;
}

## &benflush interface ##
sub main'benflush {
    local($coding) = @_;
    local($ret) = "";
    if ((!defined($coding) || $coding eq "" || $coding eq "b64")
	&& $benbuf ne ""){
        $ret = &base64encode($benbuf) . "\n";
        $benbuf = "";
    }elsif ($coding eq "qp" && $benbuf ne ""){
	$ret = &qpencode($benbuf) . "\n";
	$benbuf = "";
    }
    $ret;
}

## MIME �إå����󥳡��ǥ���
sub mimeencode {
    local($_, $befor, $after) = @_;
    local($back, $forw, $blen, $len, $flen, $str);
    $blen = 0; $flen=0;
    $befor = substr($befor, rindex($befor, "\n")+1);
    $after = substr($after, 0, index($after, "\n")-$[);
    $back = " " unless ($befor eq ""
                     || $befor =~ /[ \t\(]$/);
    $forw = " " unless ($after =~ /^\x1b\([BHJ]$/
                     || $after =~ /^\x1b\([BHJ][ \t\)]/);
    $blen = length($befor);
    $flen = length($forw)+length($&)-3 if ($after =~ /^$match_ascii/o);
    $len = length($_);
    return "" if ($len <= 3);
    if ($len > 39 || $blen + $mimelen{$len+3} > $limit){
        if ($limit-$blen < 30){
            $len = 0;
        }else{
            $len = int(($limit-$blen-26)/4)*2+3;
        }
        if ($len >= 5){
            $str = substr($_, 0, $len).$jis_out;
            $str = &base64encode($str);
            $str = $mime_head.$str.$mime_tail;
            $back.$str."\n ".$jis_in.substr($_, $len);
        }else{
            "\n ".$_;
        }
    }else{
        $_ .= $jis_out;
        $_ = &base64encode($_);
#        $_ = $back.$mime_head.$_.$mime_tail;
        $_ = $mime_head.$_.$mime_tail;
#        if ($blen + (length) + $flen > $limit){
        if ($blen + (length) + $flen > $limit){
            $_."\n ";
        }else{
            $_.$forw;
        }
    }
}

## MIME base64 ���󥳡��ǥ���
sub base64encode {
    local($_) = @_;
    $_ = unpack("B".((length)<<3), $_);
    $_ .= $zero[(length)%6];
    s/.{6}/$mime{$&}/go;
    $_.$pad[(length)%4];
}

## Quoted-Printable ���󥳡��ǥ���
sub qpencode {
    local($_) = @_;

    # `=' ʸ����16��ɽ�����Ѵ�����
    s/=/=3D/g;

    # �����Υ��֤ȥ��ڡ�����16��ɽ�����Ѵ�����
    s/\t$/=09/;
    s/ $/=20/;

    # ������ǽʸ��(`!'��`~')�ʳ���ʸ����16��ɽ�����Ѵ�����
    s/([^!-~ \t])/&qphex($1)/ge;

    # 1�Ԥ�$qfoldcolʸ���ʲ��ˤʤ�褦�˥��եȲ��Ԥ򤤤��
    local($folded, $line) = "";
    while (length($_) > $qfoldcol){
	$line = substr($_, 0, $qfoldcol-1);
	if ($line =~ /=$/){
	    $line = substr($_, 0, $qfoldcol-2);
	    $_ = substr($_, $qfoldcol-2);
	}elsif ($line =~ /=[0-9A-Fa-f]$/){
	    $line = substr($_, 0, $qfoldcol-3);
	    $_ = substr($_, $qfoldcol-3);
	}else{
	    $_ = substr($_, $qfoldcol-1);
	}
	$folded .= $line . "=\n";
    }
    $folded . $_;
}

sub qphex {
    local($_) = @_;
    $_ = '=' . unpack("H2", $_);
    tr/a-f/A-F/;
    $_;
}

## Shift-JIS �� EUC �Τɤ���δ��������ɤ��ޤޤ�뤫������å�
sub checkkanji {
    local($sjis,$euc);
    $sjis = 0 ; $euc = 0;
    $sjis += length($&) while(/$match_sjis/go);
    $euc  += length($&) while(/$match_euc/go);
    return 'NONE' if ($sjis == 0 && $euc == 0);
    return 'SJIS' if ($sjis > $euc);
    return 'EUC'  if ($sjis < $euc);
    $often_use_kanji;
}

## EUC �� 7bit JIS ���Ѵ�
sub e2j {
    local($_) = @_;
    tr/\xa1-\xfe/\x21-\x7e/;
    $jis_in.$_.$jis_out;
}

## Shift-JIS �� 7bit JIS ���Ѵ�
sub s2j {
    local($string);
    local(@ch) = split(//, $_[0]);
    while(($j1,$j2)=unpack("CC",shift(@ch).shift(@ch))){
        if ($j2 > 0x9e){
            $j1 = (($j1>0x9f ? $j1-0xb1 : $j1-0x71)<<1)+2;
            $j2 -= 0x7e;
        }
        else{
            $j1 = (($j1>0x9f ? $j1-0xb1 : $j1-0x71)<<1)+1;
            $j2 -= ($j2>0x7e ? 0x20 : 0x1f);
        }
        $string .= pack("CC", $j1, $j2);
    }
    $jis_in.$string.$jis_out;
}
1;
