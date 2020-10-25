************************************************************************
*									*
*		統合光波表示装置　Ｓ／Ｇ　ＣＰＵプログラム		*
*									*
*		名称		: ベクタテーブル			*
*		ファイル名	: vcttbl.s				*
*									*
************************************************************************/
*
	CHIP	68020
	SECTION	vcttbl
*
	XREF	_main
	XREF	_BUSERR
	XREF	_ADRERR
	XREF	_ILLINS
	XREF	_CHKINS
	XREF	_PRIVVIO
	XREF	_TRCEXC
	XREF	_L1010EMU
	XREF	_L1111EMU
	XREF	_INTERR
	XREF	_CPPVIO
	XREF	_FMTERR
	XREF	_SPURIOUS
	XREF	_intrs2
	XREF	_int10ms
	XREF	_intgsc
	XREF	_intmbi_rx
	XREF	_intmbi_tx
	XREF	_intmbi_bit
	XREF	_INTERR15
	XREF	_INTERR16
	XREF	_INTERR17
	XREF	_INTERR18
	XREF	_INTERR19
	XREF	_INTERR20
	XREF	_INTERR21
	XREF	_INTERR22
	XREF	_INTERR23
	XREF	_INTERR24
	XREF	_INTERR25
	XREF	_INTERR26
	XREF	_INTERR27
	XREF	_INTERR28
	XREF	_INTERR29
	XREF	_INTERR30
	XREF	_INTERR31
	XREF	_INTERR32
	XREF	_INTERR33
	XREF	_INTERR34
	XREF	_INTERR35
	XREF	_INTERR36
	XREF	_INTERR37
	XREF	_INTERR38
	XREF	_INTERR39
	XREF	_INTERR40
	XREF	_INTERR41
	XREF	_INTERR42
	XREF	_INTERR43
	XREF	_INTERR44
	XREF	_INTERR45
	XREF	_INTERR46
	XREF	_INTERR47
	XREF	_INTERR48
	XREF	_INTERR49
	XREF	_INTERR50
	XREF	_INTERR51
	XREF	_INTERR52
	XREF	_INTERR53
	XREF	_INTERR54
	XREF	_INTERR55
	XREF	_INTERR56
	XREF	_INTERR57
	XREF	_INTERR58
	XREF	_INTERR59
	XREF	_INTERR60
	XREF	_INTERR61
	XREF	_INTERR62
	XREF	_INTERR63
	XREF	_INTERR64
	XREF	_INTERR67
	XREF	_INTERR68
	XREF	_INTERR69
	XREF	_INTERR70
	XREF	_INTERR71
	XREF	_INTERR72
	XREF	_INTERR73
	XREF	_INTERR74
	XREF	_INTERR79
	XREF	_INTERR80
	XREF	_INTERR81
	XREF	_INTERR82
	XREF	_INTERR83
	XREF	_INTERR84
	XREF	_INTERR85
	XREF	_INTERR87
	XREF	_INTERR88
	XREF	_INTERR89
	XREF	_INTERR90
*
	DC.L	0ff080000H	*0	Initial interrupt stack pointer
	DC.L	_main		*1	Initial program counter
	DC.L	_BUSERR		*2	Bus error (vector No.2)
	DC.L	_ADRERR		*3	Address error (vector No.3)
	DC.L	_ILLINS		*4	Illegal instruction (vector No.4)
	DC.L	_intmbi_rx	*5	IRQ5 MBI RX
	DC.L	_CHKINS		*6	CHK,CHK2 instruction (vector No.6)
	DC.L	_intmbi_bit	*7	IRQ7 MBI BIT END
	DC.L	_PRIVVIO	*8	Privilege violation (vector No.8)
	DC.L	_TRCEXC		*9	Trace exception (vector No.9)
	DC.L	_L1010EMU	*10	Line 1010($A) emulator (vector No.10)
	DC.L	_L1111EMU	*11	Line 1111($F) emulator (vector No.11)
	DC.L	_INTERR		*12	Vector No.12
	DC.L	_CPPVIO		*13	Coprocessor protcol violation
	DC.L	_FMTERR		*14	Format error (vector No.14)
	DC.L	_INTERR15	*15	Uninitialized interrupt (vector No.15)
	DC.L	_INTERR16	*16	Vector No.16
	DC.L	_INTERR17	*17	Vector No.17
	DC.L	_INTERR18	*18	Vector No.18
	DC.L	_INTERR19	*19	Vector No.19
	DC.L	_INTERR20	*20	Vector No.20
	DC.L	_INTERR21	*21	Vector No.21
	DC.L	_INTERR22	*22	Vector No.22
	DC.L	_INTERR23	*23	Vector No.23
	DC.L	_SPURIOUS	*24	Spurious interrupt (vector No.24)
	DC.L	_INTERR25	*25	Vector No.25
	DC.L	_INTERR26	*26	Vector No.26
	DC.L	_INTERR27	*27	Vector No.27
	DC.L	_INTERR28	*28	Vector No.28
	DC.L	_INTERR29	*29	Vector No.29
	DC.L	_INTERR30	*30	Vector No.30
	DC.L	_INTERR31	*31	Vector No.31
	DC.L	_INTERR32	*32	Vector No.32
	DC.L	_INTERR33	*33	Vector No.33
	DC.L	_INTERR34	*34	Vector No.34
	DC.L	_INTERR35	*35	Vector No.35
	DC.L	_INTERR36	*36	Vector No.36
	DC.L	_INTERR37	*37	Vector No.37
	DC.L	_INTERR38	*38	Vector No.38
	DC.L	_INTERR39	*39	Vector No.39
	DC.L	_INTERR40	*40	Vector No.40
	DC.L	_INTERR41	*41	Vector No.41
	DC.L	_INTERR42	*42	Vector No.42
	DC.L	_INTERR43	*43	Vector No.43
	DC.L	_INTERR44	*44	Vector No.44
	DC.L	_INTERR45	*45	Vector No.45
	DC.L	_INTERR46	*46	Vector No.46
	DC.L	_INTERR47	*47	Vector No.47
	DC.L	_INTERR48	*48	Vector No.48
	DC.L	_INTERR49	*49	Vector No.49
	DC.L	_INTERR50	*50	Vector No.50
	DC.L	_INTERR51	*51	Vector No.51
	DC.L	_INTERR52	*52	Vector No.52
	DC.L	_INTERR53	*53	Vector No.53
	DC.L	_INTERR54	*54	Vector No.54
	DC.L	_INTERR55	*55	Vector No.55
	DC.L	_INTERR56	*56	Vector No.56
	DC.L	_INTERR57	*57	Vector No.57
	DC.L	_INTERR58	*58	Vector No.58
	DC.L	_INTERR59	*59	Vector No.59
	DC.L	_INTERR60	*60	Vector No.60
	DC.L	_INTERR61	*61	Vector No.61
	DC.L	_INTERR62	*62	Vector No.62
	DC.L	_INTERR63	*63	Vector No.63
	DC.L	_INTERR64	*64	Vector No.64
	DC.L	_intrs2		*65	LIRQ1 RS232C RX
	DC.L	_int10ms	*66	LIRQ2 10ms timer
	DC.L	_INTERR67	*67	LIRQ3 not use
	DC.L	_INTERR68	*68	LIRQ4 not use
	DC.L	_INTERR69	*69	LIRQ5 not use
	DC.L	_INTERR70	*70	LIRQ6 not use
	DC.L	_INTERR71	*71	LIRQ7 not use
	DC.L	_INTERR72	*72	IRQ1 not use
	DC.L	_INTERR73	*73	IRQ2 not use
	DC.L	_INTERR74	*74	IRQ3 not use
	DC.L	_intgsc		*75	IRQ4 GSC drawing end
	DC.L	_intmbi_rx	*76	IRQ5 MBI RX
	DC.L	_intmbi_tx	*77	IRQ6 MBI TX (not use)
	DC.L	_intmbi_bit	*78	IRQ7 MBI BIT END
	DC.L	_INTERR79	*79	Not use
	DC.L	_INTERR80	*80	Not use
	DC.L	_INTERR81	*81	Not use
	DC.L	_INTERR82	*82	Not use
	DC.L	_INTERR83	*83	Not use
	DC.L	_INTERR84	*84	Not use
	DC.L	_INTERR85	*85	Not use
	DC.L	_intgsc		*86	IRQ4 GSC drawing end
	DC.L	_INTERR87	*87	Not use
	DC.L	_INTERR88	*88	Not use
	DC.L	_INTERR89	*89	Not use
	DC.L	_INTERR90	*90	Not use
*
	DCB.L	165,_INTERR	*91-255	Not use
