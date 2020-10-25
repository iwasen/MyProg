/************************************************************************
 *									*
 *		統合光波表示装置　Ｓ／Ｇ　ＣＰＵプログラム		*
 *									*
 *		名称		: 不正割り込み処理			*
 *		ファイル名	: error.c				*
 *									*
 ************************************************************************/
#pragma	SECTION PROG=error

#include "sg_cpu.h"

void	BUSERR(void)
{
	abort("BUS ERROR");
}

void	ADRERR(void)
{
	abort("ADDRESS ERROR");
}

void	ILLINS(void)
{
	abort("ILLEGAL INSTRUCTION");
}

void	CHKINS(void)
{
	abort("CHK,CHK2 INSTRUCTION");
}

void	PRIVVIO(void)
{
	abort("PRIVILEGE VIOLATION");
}

void	TRCEXC(void)
{
	abort("TRACE EXCEPTION");
}

void	L1010EMU(void)
{
	abort("LINE 1010($A) EMULATOR");
}

void	L1111EMU(void)
{
	abort("LINE 1111($F) EMULATOR");
}

void	CPPVIO(void)
{
	abort("COPROCESSOR PROTCOL VIOLATION");
}

void	FMTERR(void)
{
	abort("FORMAT ERROR");
}

#pragma	INTERRUPT
void	SPURIOUS(void)
{
	msgout("SPURIOUS INT\x0d\x0a");
}
#pragma	INTERRUPT-

void	INTERR(void)
{
	abort("INT ERROR");
}

void	INTERR15()
{
	abort("INT15 ERROR");
}

void	INTERR16()
{
	abort("INT16 ERROR");
}

void	INTERR17()
{
	abort("INT17 ERROR");
}

void	INTERR18()
{
	abort("INT18 ERROR");
}

void	INTERR19()
{
	abort("INT19 ERROR");
}

void	INTERR20()
{
	abort("INT20 ERROR");
}

void	INTERR21()
{
	abort("INT21 ERROR");
}

void	INTERR22()
{
	abort("INT22 ERROR");
}

void	INTERR23()
{
	abort("INT23 ERROR");
}

void	INTERR24()
{
	abort("INT24 ERROR");
}

void	INTERR25()
{
	abort("INT25 ERROR");
}

void	INTERR26()
{
	abort("INT26 ERROR");
}

void	INTERR27()
{
	abort("INT27 ERROR");
}

void	INTERR28()
{
	abort("INT28 ERROR");
}

void	INTERR29()
{
	abort("INT29 ERROR");
}

void	INTERR30()
{
	abort("INT30 ERROR");
}

void	INTERR31()
{
	abort("INT31 ERROR");
}

void	INTERR32()
{
	abort("INT32 ERROR");
}

void	INTERR33()
{
	abort("INT33 ERROR");
}

void	INTERR34()
{
	abort("INT34 ERROR");
}

void	INTERR35()
{
	abort("INT35 ERROR");
}

void	INTERR36()
{
	abort("INT36 ERROR");
}

void	INTERR37()
{
	abort("INT37 ERROR");
}

void	INTERR38()
{
	abort("INT38 ERROR");
}

void	INTERR39()
{
	abort("INT39 ERROR");
}

void	INTERR40()
{
	abort("INT40 ERROR");
}

void	INTERR41()
{
	abort("INT41 ERROR");
}

void	INTERR42()
{
	abort("INT42 ERROR");
}

void	INTERR43()
{
	abort("INT43 ERROR");
}

void	INTERR44()
{
	abort("INT44 ERROR");
}

void	INTERR45()
{
	abort("INT45 ERROR");
}

void	INTERR46()
{
	abort("INT46 ERROR");
}

void	INTERR47()
{
	abort("INT47 ERROR");
}

void	INTERR48()
{
	abort("INT48 ERROR");
}

void	INTERR49()
{
	abort("INT49 ERROR");
}

void	INTERR50()
{
	abort("INT50 ERROR");
}

void	INTERR51()
{
	abort("INT51 ERROR");
}

void	INTERR52()
{
	abort("INT52 ERROR");
}

void	INTERR53()
{
	abort("INT53 ERROR");
}

void	INTERR54()
{
	abort("INT54 ERROR");
}

void	INTERR55()
{
	abort("INT55 ERROR");
}

void	INTERR56()
{
	abort("INT56 ERROR");
}

void	INTERR57()
{
	abort("INT57 ERROR");
}

void	INTERR58()
{
	abort("INT58 ERROR");
}

void	INTERR59()
{
	abort("INT59 ERROR");
}

void	INTERR60()
{
	abort("INT60 ERROR");
}

void	INTERR61()
{
	abort("INT61 ERROR");
}

void	INTERR62()
{
	abort("INT62 ERROR");
}

void	INTERR63()
{
	abort("INT63 ERROR");
}

void	INTERR64()
{
	abort("INT64 ERROR");
}

void	INTERR67()
{
	abort("INT67 ERROR");
}

void	INTERR68()
{
	abort("INT68 ERROR");
}

void	INTERR69()
{
	abort("INT69 ERROR");
}

void	INTERR70()
{
	abort("INT70 ERROR");
}

void	INTERR71()
{
	abort("INT71 ERROR");
}

void	INTERR72()
{
	abort("INT72 ERROR");
}

void	INTERR73()
{
	abort("INT73 ERROR");
}

void	INTERR74()
{
	abort("INT74 ERROR");
}

void	INTERR79()
{
	abort("INT79 ERROR");
}

void	INTERR80()
{
	abort("INT80 ERROR");
}

void	INTERR81()
{
	abort("INT81 ERROR");
}

void	INTERR82()
{
	abort("INT82 ERROR");
}

void	INTERR83()
{
	abort("INT83 ERROR");
}

void	INTERR84()
{
	abort("INT84 ERROR");
}

void	INTERR85()
{
	abort("INT85 ERROR");
}

void	INTERR87()
{
	abort("INT87 ERROR");
}

void	INTERR88()
{
	abort("INT88 ERROR");
}

void	INTERR89()
{
	abort("INT89 ERROR");
}

void	INTERR90()
{
	abort("INT90 ERROR");
}

void	abort(char *msg)
{
	int_disable();		/* 割り込み禁止 */

	/* メッセージ送信 */
	msgout(msg);

	for (;;);		/* 無限ループ（プログラム停止） */
}
