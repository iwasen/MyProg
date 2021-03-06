10 ' SAVE "ZAIKO",A
20 '*********************************************************************
30 '*
40 '*	バンパー完成品在庫推移シミュレーション結果表示プログラム
50 '*
60 '*		FILE_NAME : ZAIKO.BAS
70 '*		AUTHOR    : S.AIZAWA
80 '*                       M.HAMA
90 '*           DATE      : 90/09/19
100 '*********************************************************************
110 '
120 '--------------------------
130 '  初期設定
140 '--------------------------
141 DEFINT A-Z
150 'KEY 6,"SAVE"+CHR$(&H22)+"ZAIKO"+CHR$(&H22)+",A"+CHR$(13)
160 'KEY 7,"SYSTEM"+CHR$(13)
170 ON STOP GOSUB *OWARI:STOP ON
180 DIM TDLL$(35),TSHASHU$(35),TFR$(35),TSHIYOU$(35)
190 DIM TCCODE$(22),TCNAME$(22)
200 GOSUB *SETTBL
210 SCREEN 3,0:CONSOLE ,,0,1:COLOR 7,5,0,1,2
220 COLOR=(1,&H459):COLOR=(5,&H99A):COLOR=(4,&HD4A):CLS 3
230 '--------------------------
240 '  処理メニュー
250 '--------------------------
260 *MENU
270 CLS
280 'COLOR=(3,&H1CC)
290 LINE(8*6,16*3)-(8*74,16*23),4,BF,1
300 LOCATE 11,1:PRINT "《《《　バンパー完成品在庫推移シミュレーション表示　》》》";
310 LOCATE 71,0:PRINT DATE$;
320 LOCATE 24,4:PRINT "＊＊＊　処理メニュー　＊＊＊";
330 LOCATE 28,8:PRINT "１．一覧表示";
340 LOCATE 28,10:PRINT "２．データ受信";
350 LOCATE 28,12:PRINT "３．終了";
360 LOCATE 20,18:PRINT "処理を選択して下さい";
370 SEL=1
380 LOCATE 42,18:PRINT CHR$(SEL+&H30);
390 LINE (26*8,(SEL*2+6)*16)-(45*8,(SEL*2+7)*16),4,BF
400 LOCATE 42,18:K$=INPUT$(1)
410 LINE (26*8,(SEL*2+6)*16)-(45*8,(SEL*2+7)*16),1,BF
420 IF K$="1" THEN SEL=1
430 IF K$="2" THEN SEL=2
440 IF K$="3" THEN SEL=3
450 IF K$=CHR$(&H1F) THEN SEL=SEL+1:IF SEL=4 THEN SEL=1
460 IF K$=CHR$(&H1E) THEN SEL=SEL-1:IF SEL=0 THEN SEL=3
470 IF K$=CHR$(&HD) THEN ON SEL GOSUB *ICHIRAN,*JUSHIN,*OWARI:GOTO *MENU
480 GOTO 380
490 '--------------------------
500 '  終了処理
510 '--------------------------
520 *OWARI
530 COLOR 7,0:CLS 3
540 END
550 '--------------------------
560 '  データ受信処理
570 '--------------------------
580 *JUSHIN
590 CLS
600 'COLOR=(3,&HCCC)
610 LOCATE 11,1:PRINT "《《《　バンパー完成品在庫推移シミュレーション表示　》》》";
620 LOCATE 71,0:PRINT DATE$;
630 LOCATE 24,4:PRINT "＊＊＊　データ受信　＊＊＊";
640 LOCATE 25,16:PRINT "（中止はＥＳＣキー）";
650 LOCATE 18,12:PRINT "準備ができたらリターンキーを押して下さい  ";
660 K$=INPUT$(1)
670 IF K$=CHR$(&H1B) THEN RETURN
680 IF K$<>CHR$(&HD) THEN 660
690 LOCATE 18,12:PRINT SPC(40);
700 OPEN "COM:N81X" AS #1
710 OPEN "DSFINV.DAT" FOR OUTPUT AS #2
720 LOCATE 32,12:PRINT "受信待ち";
730 IF INKEY$=CHR$(&H1B) THEN CLOSE:RETURN
740 IF LOC(1)<>0 THEN RCV$=INPUT$(1,#1):IF RCV$>="0" AND RCV$<="9" THEN PRINT #2,RCV$;:GOTO 760
750 GOTO 730
760 LOCATE 32,12:PRINT "受信中  ";
790 IF LOC(1)<>0 THEN RCV$=INPUT$(1,#1):IF RCV$=CHR$(&H1A) THEN CLOSE:GOSUB *CODECNV:RETURN ELSE PRINT #2,RCV$;
820 IF INKEY$=CHR$(&H1B) THEN CLOSE:RETURN
830 GOTO 790
840 '--------------------------
850 '  コード変換処理
860 '--------------------------
870 *CODECNV
880 LOCATE 30,12:PRINT "コード変換中";
890 OPEN "DSFINV.DAT" FOR INPUT AS #1
900 OPEN "ZAIKO.DAT" FOR OUTPUT AS #2:CLOSE #2
910 OPEN "ZAIKO.DAT" AS #2
920 FIELD #2,3 AS FSHASHU$,2 AS FSHIYOU$,1 AS FFR$,3 AS FCOLOR$,2 AS FKOSUU$,4 AS FMAX$,4 AS FMIN$,84 AS FZAIKO$
930 WHILE EOF(1)=0
940   LINE INPUT #1,DT$
945   IF LEN(DT$)=80 THEN LINE INPUT #1,DT2$:DT$=DT$+DT2$
950   DLL$=LEFT$(DT$,3):DCOLOR$=MID$(DT$,4,3):DZAIKO$=MID$(DT$,7)
955   DNUM=(LEN(DT$)-6)/4
960   LSET FSHASHU$="???":LSET FSHIYOU$="??":LSET FFR$="?":LSET FCOLOR$="???"
970   FOR I=1 TO DLLTBLN
980     IF DLL$=TDLL$(I) THEN LSET FSHASHU$=TSHASHU$(I):LSET FSHIYOU$=TSHIYOU$(I):LSET FFR$=TFR$(I):I=DLLTBLN
990   NEXT
1000   MID$(DCOLOR$,1,1)="0"
1010   FOR I=1 TO COLTBLN
1020     IF DCOLOR$=TCCODE$(I) THEN LSET FCOLOR$=TCNAME$(I):I=COLTBLN
1030   NEXT
1040   ZMIN=999:ZMAX=-999
1050   FOR I=1 TO DNUM
1060     ZAIKO=VAL(MID$(DZAIKO$,(I-1)*4+1,4))
1070     IF ZAIKO>ZMAX THEN ZMAX=ZAIKO
1080     IF ZAIKO<ZMIN THEN ZMIN=ZAIKO
1090   NEXT
1095   RSET FKOSUU$=RIGHT$(STR$(DNUM),2)
1100   RSET FMAX$=STR$(ZMAX):RSET FMIN$=STR$(ZMIN)
1110   LSET FZAIKO$=DZAIKO$
1120   PUT #2
1130 WEND
1140 CLOSE
1150 RETURN
1160 '-----------------------------
1170 '  コード変換テーブル読み込み
1180 '-----------------------------
1190 *SETTBL
1200 RESTORE *DLLTBL
1210 I=1
1220 READ TDLL$(I),TSHASHU$(I),TFR$(I),TSHIYOU$(I)
1230 IF TDLL$(I)<>"" THEN I=I+1:GOTO 1220
1240 DLLTBLN=I
1250 RESTORE *COLORTBL
1260 I=1
1270 READ TCCODE$(I),TCNAME$(I)
1280 IF TCCODE$(I)<>"" THEN I=I+1:GOTO 1270
1290 COLTBLN=I
1300 RETURN
1310 '-----------------------------
1320 '  ドーリーコードテーブル
1330 '-----------------------------
1340 *DLLTBL
1350 DATA 100,B13,F,22
1360 DATA 101,B13,R,22
1370 DATA 120,B13,F,77
1380 DATA 121,B13,R,77
1390 DATA 140,B13,F,66
1400 DATA 141,B13,R,66
1410 DATA 144,B13,F,44
1420 DATA 145,B13,R,44
1430 DATA 150,B13,F,CC
1440 DATA 151,B13,R,CC
1450 DATA 160,B13,F,GP
1460 DATA 161,B13,R,GP
1470 DATA 164,B13,F,RO
1480 DATA 165,B13,R,RO
1490 DATA 180,B13,F,SS
1500 DATA 181,B13,R,SS
1510 DATA 184,B13,F,TW
1520 DATA 185,B13,R,TW
1530 DATA 200,R10,F,55
1540 DATA 201,R10,R,55
1550 DATA 204,R10,F,A5
1560 DATA 220,R10,F,85
1570 DATA 224,R10,F,Y5
1580 DATA 300,B12,F,?S
1590 DATA 301,B12,R,?S
1600 DATA 340,B12,F,?H
1610 DATA 341,B12,R,?H
1620 DATA 400,722,F,55
1630 DATA 401,722,R,55
1640 DATA 420,722,F,?1
1650 DATA 421,722,R,?1
1660 DATA 441,722,R,88
1670 DATA 460,722,F,EJ
1680 DATA 461,722,R,EJ
1690 DATA "","","",""
1700 '-----------------------------
1710 '  色コードテーブル
1720 '-----------------------------
1730 *COLORTBL
1740 DATA 001,426
1750 DATA 002,449
1760 DATA 003,463
1770 DATA 004,465
1780 DATA 005,505
1790 DATA 006,531
1800 DATA 007,549
1810 DATA 008,628
1820 DATA 009,AG2
1830 DATA 010,AH3
1840 DATA 011,BG3
1850 DATA 012,BG6
1860 DATA 013,CJ7
1870 DATA 014,DH3
1880 DATA 015,DJ3
1890 DATA 016,EH7
1900 DATA 017,KH3
1910 DATA 018,KJ1
1920 DATA 019,KJ5
1930 DATA 020,LJ0
1940 DATA 021,TH1
1950 DATA "",""
1960 '---------------------
1970 '  一覧表示処理
1980 '---------------------
1990 *ICHIRAN
2000 CLS
2010 DIM REC$(300),RECD$(300)
2020 LOCATE 25,1:PRINT "《《《　在庫一覧表示  》》》"
2030 LOCATE 71,0:PRINT DATE$
2040 OPEN "ZAIKO.DAT" AS #1
2050 FIELD #1,3 AS SYASYU$,2 AS SHIYOU$,1 AS FR$,3 AS IROCD$,2 AS KOSUU$,4 AS ZMAX$,4 AS ZMIN$,84 AS ZAIKO$
2060 CNT = 1:FCNT = LOF(1)
2070 WHILE NOT FCNT = 0
2080   LOCATE 30,11:PRINT "データ読み込み中"
2090   GET #1,CNT
2100   REC$(CNT-1) = SPACE$(9)+SYASYU$+SPACE$(7)+SHIYOU$+SPACE$(6)+FR$+SPACE$(8)+IROCD$+SPACE$(8)+ZMAX$+SPACE$(7)+ZMIN$+SPACE$(8)+KOSUU$
2110   RECD$(CNT-1) = ZAIKO$
2120   CNT = CNT + 1:FCNT = FCNT - 1
2130 WEND
2140 CLOSE #1
2150 IF CNT <= 1 THEN GOTO *MODORI
2160 CNTAMARI = CNT MOD 19
2170 CNTSYO = CNT \ 19
2180 IF CNTSYO = 0 THEN HYO = CNT - 1 ELSE HYO = 18
2190 CURCNT = 1:RECCNT = 1:HYF = 0
2200 '---------------------
2210 '  表示処理
2220 '---------------------
2230 *HYOUJI
2240 LOCATE 30,11:PRINT "                "
2250 LOCATE 6,23
2260 PRINT "リターンキー／グラフ表示　↑／上移動　↓／下移動　ＥＳＣ／終了";
2270 LINE(8 *6,16*3)-(8 *74,16*23),4,BF,1
2280 LINE(8 *6,16*4)-(8 *74,16*4),4
2290 LOCATE 8,3
2300 PRINT "車 種     仕様   Ｆ／Ｒ   色コード      最大     　最少      件数";
2310 FOR HYOI = HYF TO HYO
2320   LOCATE 0,4+HYOI-HYF
2330   PRINT REC$(HYOI);
2340 NEXT HYOI
2350 LINE(8*6+1,16*(3 + CURCNT)+1)-(8*74-1,16*(4+CURCNT)-1),4,BF
2360 '---------------------
2370 '  キー入力処理
2380 '---------------------
2390 *KEYIN
2400 INBUF$ = "":INBUF$ = INKEY$:IF INBUF$ = "" THEN *KEYIN
2410 IF INBUF$ = CHR$(&HD) THEN GOSUB *GRAPH:GOTO *HYOUJI
2420 IF INBUF$ = CHR$(&H1B) THEN GOTO *MODORI
2430 IF INBUF$ = CHR$(&H1E) THEN GOSUB *UE:GOTO *KEYIN
2440 IF INBUF$ = CHR$(&H1F) THEN GOSUB *SITA:GOTO *KEYIN
2450 BEEP:GOTO *KEYIN
2460 '---------------------
2470 '  リターンＭＥＮＵ
2480 '---------------------
2490 *MODORI
2500 ERASE REC$,RECD$
2510 CLS 3:RETURN:'END:'RUN "MENU.BAS"
2520 '---------------------
2530 '  上矢印キー処理
2540 '---------------------
2550 *UE
2560 IF RECCNT = 1 AND CURCNT = 1 THEN BEEP:RETURN
2570 IF RECCNT > 1 AND CURCNT = 1 THEN GOSUB *UEROLL:RETURN
2580 LINE(8*6+1,16*(3 + CURCNT)+1)-(8*74-1,16*(4+CURCNT)-1),,BF
2590 CURCNT = CURCNT - 1:RECCNT = RECCNT - 1
2600 LINE(8*6+1,16*(3 + CURCNT)+1)-(8*74-1,16*(4+CURCNT)-1),4,BF  
2610 RETURN
2620 '---------------------
2630 '  ＲＯＬＬＵＰ処理
2640 '---------------------
2650 *UEROLL
2660 FOR HYOI = RECCNT-2 TO RECCNT+16
2670   LOCATE 0,4+(HYOI-RECCNT+2)
2680   PRINT REC$(HYOI);
2690 NEXT HYOI
2700 RECCNT = RECCNT - 1
2710 RETURN
2720 '---------------------
2730 '  下矢印処理
2740 '---------------------
2750 *SITA
2760 IF CNTSYO = 0 AND CURCNT = CNTAMARI THEN BEEP:RETURN
2770 IF RECCNT = CNT-1 THEN BEEP:RETURN
2780 IF RECCNT < CNT-1 AND CURCNT = 19 THEN *SITAROLL:RETURN
2790 LINE(8*6+1,16*(3 + CURCNT)+1)-(8*74-1,16*(4+CURCNT)-1),,BF
2800 CURCNT = CURCNT + 1:RECCNT = RECCNT + 1
2810 LINE(8*6+1,16*(3 + CURCNT)+1)-(8*74-1,16*(4+CURCNT)-1),4,BF
2820 RETURN
2830 '---------------------
2840 '  ＲＯＬＬＤＯＷＮ処理
2850 '---------------------
2860 *SITAROLL
2870 FOR HYOI = RECCNT-18 TO RECCNT
2880   LOCATE 0,4+(HYOI-RECCNT+18)
2890   PRINT REC$(HYOI);
2900 NEXT HYOI
2910 RECCNT = RECCNT + 1
2920 RETURN
2930 *GRAPH
2940 '----------------------------
2950 'グラフ表示
2960 '----------------------------
2970 CLS 3
2980 LOCATE 25,0:PRINT "《《《　在庫グラフ表示  》》》"
2990 LOCATE 71,0:PRINT DATE$
3000 LOCATE 3,1:PRINT "何かキーを押して下さい。"
3010 LINE(8 *3,16*2)-(8 *77,16*23),4,BF,1
3020 LINE(8*10,16*3-8)-(8*10,16*23-10),7
3030 LINE(8*10,16*21)-(8*76,16*21),7
3040 FOR LN = 0 TO 8
3050   LINE(8*10,16*(3+LN*2))-(8*74,16*(3+LN*2)),7,,&H8888
3060 NEXT LN
3070 FOR LN = 0 TO 20
3080   LINE(8*(13+LN*3),16*3-5)-(8*(13+LN*3),16*23-12),7,,&H8888
3090 NEXT LN
3100 DAISU = 90
3110 FOR LN = 0 TO 8
3120   LOCATE 6,2+LN*2:PRINT DAISU;:DAISU=DAISU-10
3130 NEXT LN
3140 LOCATE 8,20:PRINT "0";:LOCATE 10,21:PRINT "0";
3150 FOR LN = 1 TO 10
3160   LOCATE 9+LN*6,21:PRINT LN;
3170 NEXT LN
3180 LOCATE 3,3:PRINT "(台)";:LOCATE 70,22:PRINT "(ｈ)";
3190 SVGX = 13*8:SVGY= 21*16-18*16*VAL(MID$(RECD$(RECCNT-1),1,4)) / 90
3200 FOR LN = 2 TO VAL(RIGHT$(REC$(RECCNT-1),2))
3210   GX = SVGX + 3*8
3220   GY = 21*16-18*16*VAL(MID$(RECD$(RECCNT-1),LN*4-3,4)) / 90
3230   LINE(SVGX,SVGY)-(GX,GY),6
3240   SVGX = GX:SVGY = GY
3250 NEXT LN
3260 LOCATE 6,23
3270 PRINT "車種："+MID$(REC$(RECCNT-1),10,3)+"   仕様："+MID$(REC$(RECCNT-1),20,2)+"  Ｆ／Ｒ："+MID$(REC$(RECCNT-1),28,1)+"  色コード："+MID$(REC$(RECCNT-1),37,3)+"  件数："+MID$(REC$(RECCNT-1),71,2);
3280 '---------------------
3290 '  キー入力待ち処理
3300 '---------------------
3310 *WAITKEY
3320 INBUF$ = "":INBUF$ = INKEY$:IF INBUF$ = "" THEN *WAITKEY
3330 CLS 3
3340 LOCATE 25,1:PRINT "《《《　在庫一覧表示  》》》"
3350 LOCATE 71,0:PRINT DATE$
3360 IF CNTSYO = 0 THEN HYF = 0:HYO = CNT-1:GOTO *RT
3370 HYF = RECCNT-CURCNT:HYO = HYF + 18
3380 *RT
3390 RETURN
