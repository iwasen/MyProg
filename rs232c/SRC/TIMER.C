/************************************************************************
 *									*
 *		ＲＳ－２３２Ｃライブラリ				*
 *									*
 *		名称		: タイマー制御				*
 *		ファイル名	: timer.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include	<stddef.h>
#include	<stdlib.h>
#include	<dos.h>
#include	<conio.h>
#include	"rs232c.h"

/*	タイマーワークエリア定義	*/
struct	timer	{
	int	no;
	int	counter;
	struct	timer	*chain;
};
typedef	struct	timer	TIMER;

/*	内部関数	*/
static	void	interrupt far	tm_intr(void);

/*	スタティック変数	*/
static	int	open_flag;
static	TIMER	*tm_top;
static	void	(interrupt far *tm_vect)();

/*	１０ｍｓカウンター */
long	tm_counter;

#pragma	check_stack-		/* スタックチェック無し */

/*=======================================================================
 |
 |		タイマー使用準備処理
 |
 |	int	tm_open()
 |
 |		返値			０：正常　　１：オープン済み
 |
 =======================================================================*/
int	tm_open(void)
{
	char	far *fptr;

	if (open_flag != 0)
		return(1);

	/* 割り込みベクタセット */
	tm_vect = _dos_getvect(0x08);
	_dos_setvect(0x08, tm_intr);

	/* タイマー起動 */
	FP_SEG(fptr) = 0;
	FP_OFF(fptr) = 0x501;
	(void)outp(IOP_TMC_CWW, 0x36);
	(void)outp(IOP_TMC_C0L, 0x00);
	(void)outp(IOP_TMC_C0L, *fptr & 0x80 ? 0x4e : 0x60);
	(void)outp(IOP_ICM_IMR, inp(IOP_ICM_IMR) & 0xfe);

	tm_counter = 0L;	/* １０ｍｓカウンタークリア */

	open_flag = 1;		/* オープンフラグセット */

	return(0);
}

/*=======================================================================
 |
 |		タイマーセット
 |
 |	int	tm_set(no, tm)
 |
 |		int	no;		タイマー番号
 |		int	tm;		タイマー値（１０ｍｓ単位）
 |
 |		返値			０：正常
 |					１：未オープン
 |					２：ワークエリア確保失敗
 |
 =======================================================================*/
int	tm_set(int no, int tm)
{
	TIMER	*tp;

	if (open_flag == 0)
		return(1);

	_disable();

	/* ワークエリアをサーチする */
	for (tp = tm_top; tp != NULL; tp = tp->chain) {
		if (tp->no == no)
			break;
	}

	if (tp == NULL) {
		/* ワークエリア確保 */
		if ((tp = malloc(sizeof(TIMER))) == NULL) {
			_enable();
			return(2);
		}

		/* ワークエリアにセット */
		tp->no = no;
		tp->chain = tm_top;
		tm_top = tp;
	}

	tp->counter = tm;

	_enable();

	return(0);
}

/*=======================================================================
 |
 |		タイムアウトチェック
 |
 |	int	tm_check(no)
 |
 |		int	no;		タイマー番号
 |
 |		返値			０：タイムアウトしていない
 |					１：タイムアウトした
 |					　　（タイマーがセットされていない）
 |
 =======================================================================*/
int	tm_check(int no)
{
	TIMER	*tp;

	/* ワークエリアをサーチし */
	for (tp = tm_top; tp != NULL; tp = tp->chain) {
		if (tp->no == no) {
			if (tp->counter == 0) {
				tm_stop(no);
				return(1);
			} else
				return(0);
		}
	}

	return(1);
}

/*=======================================================================
 |
 |		タイマー停止
 |
 |	void	tm_stop(no)
 |
 |		int	no;		タイマー番号
 |
 =======================================================================*/
void	tm_stop(int no)
{
	TIMER	*tp, **tpp;

	/* ワークエリアをサーチし、解放する */
	_disable();
	for (tpp = &tm_top; *tpp != 0; tpp = &(*tpp)->chain) {
		if ((*tpp)->no == no) {
			tp = *tpp;
			*tpp = (*tpp)->chain;
			free(tp);
			break;
		}
	}
	_enable();
}

/*=======================================================================
 |
 |		タイマー使用終了処理
 |
 |	void	tm_close()
 |
 =======================================================================*/
void	tm_close(void)
{
	if (open_flag == 0)
		return;

	/* 全てのタイマーを終了させる */
	while (tm_top != NULL)
		tm_stop(tm_top->no);

	/* 割り込みベクタを元に戻す */
	_disable();
	(void)outp(IOP_ICM_IMR, inp(IOP_ICM_IMR) | 0x01);
	_dos_setvect(0x08, tm_vect);
	_enable();

	open_flag = 0;
}

/*=======================================================================
 |
 |		タイマー割り込み処理
 |
 |	void	interrupt far	tm_intr()
 |
 =======================================================================*/
static	void	interrupt far	tm_intr(void)
{
	TIMER	*tp;

	for (tp = tm_top; tp != NULL; tp = tp->chain) {
		if (tp->counter != 0)
			tp->counter--;	/* タイマーカウントダウン */
	}

	tm_counter++;		/* １０ｍｓカウンター更新 */

	/* 割り込み終了通知 */
	(void)outp(IOP_ICM_EOI, 0x20);
}
