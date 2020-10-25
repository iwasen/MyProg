/************************************************************************
 *									*
 *		ＡＣＥＳ　Ｓ／Ｇ　ＣＰＵプログラム			*
 *									*
 *		名称		: １０ｍｓ定周期割り込み処理		*
 *		ファイル名	: int10ms.c				*
 *									*
 ************************************************************************/

#pragma	SECTION	PROG=int10ms

#include "sg_cpu.h"

/*	内部関数	*/
static	void	tmc_mbibit(void);
static	void	tmc_dustart(void);
static	void	tmc_update(void);
static	void	tmc_dudata(void);
static	void	tmc_duchar(void);
static	void	tmc_digital(void);
static	void	tmc_ibit(void);
static	void	tmc_gscbackup(void);
static	void	tmc_simulation(void);

/*=======================================================================
 |
 |		１０ｍｓ定周期割り込み処理
 |
 |	void	int10ms()
 |
 =======================================================================*/
#pragma	INTERRUPT
void	int10ms(void)
{
//	tmc_mbibit();	/* ＭＢＩボードＢＩＴ終了割り込みタイムアウト検出 */
	tmc_dustart();	/* Ｄ／Ｕ立ち上がり検出 */
	tmc_update();	/* データ更新周期の監視 */
	tmc_dudata();	/* Ｄ／Ｕへデータ送信及びデータ受信監視 */
	tmc_duchar();	/* Ｄ／Ｕからのデータの文字間タイムアウト検出 */
	tmc_digital();	/* デジタル表示の更新タイミングのカウント */
	tmc_ibit();	/* Ｉ－ＢＩＴタイマー */
	tmc_gscbackup();/* ＧＳＣ描画終了割り込み停止時のバックアップ処理 */
	tmc_simulation();/* デバッグ用入力データシミュレーション処理 */
}
#pragma	INTERRUPT-

/*=======================================================================
 |
 |		ＭＢＩボードＢＩＴ終了割り込みタイムアウト検出処理
 |
 |	void	tmc_mbibit()
 |
 =======================================================================*/
static	void	tmc_mbibit(void)
{
	if (tm_mbibit != TM_STOP) {
		if (--tm_mbibit == 0) {
			if (++counter_mbibit < 3)
				mbibit();/* ＭＢＩボードＢＩＴチェック再実行 */
			else {
/*@@@@@@@@@@@*/
extern	short	test_mbi_error;
test_mbi_error = 1;
/*@@@@@@@@@@@*/
//				halt_cpu("MBI BIT ERROR", 1);
			}
		}
	}
}

/*=======================================================================
 |
 |		Ｄ／Ｕ立ち上がり検出処理
 |
 |	void	tmc_dustart()
 |
 =======================================================================*/
static	void	tmc_dustart(void)
{
	if (tm_dustart != TM_STOP) {
		if (--tm_dustart == 0) {	/* ３０秒 */
			sg_mode = SGM_NORMAL;
			sg_bit_status |= BIT_SG_232T;
		}
	}
}

/*=======================================================================
 |
 |		データ更新周期の監視処理
 |
 |	void	tmc_update()
 |
 =======================================================================*/
static	void	tmc_update(void)
{
	if (tm_update != TM_STOP) {
		if (--tm_update == 0) {		/* １秒 */
			/* １５５３Ｂデータ更新レート保存 */
			rate_1553B = count_1553B;

			/* １５５３Ｂデータ受信カウンタクリア */
			count_1553B = 0;

			/* ＢＵＳ ＦＡＩＬチェック */
			if ((debug_flag & DBF_1553B) == 0) {
				if (rate_1553B == 0) {
					if (bus_fail == 0) {
						if (++count_nodata == 5)
							bus_fail = 1;
					}
				} else {
					count_nodata = 0;
					bus_fail = 0;
				}
			}

			/* 表示更新レート保存 */
			rate_gscint = count_gscint;

			/* 表示更新カウンタクリア */
			count_gscint = 0;

			/* タイマー再セット */
			tm_update = TM_UPDATE;
		}
	}
}

/*=======================================================================
 |
 |		Ｄ／Ｕへデータ送信及びデータ受信監視処理
 |
 |	void	tmc_dudata()
 |
 =======================================================================*/
static	void	tmc_dudata(void)
{
	if (tm_dudata != TM_STOP) {
		if (--tm_dudata == 0) {
			/* データ受信チェック */
			if (du_rcvflag == 0) {
				if (sg_mode != SGM_PBIT)
					sg_bit_status |= BIT_SG_232T;

				/* ＤＵＡＲＴ初期化 */
				if (debug_mode != DBM_VT)
					set_DUART();
			} else
				sg_bit_status &= ~BIT_SG_232T;

			/* 送信バッファクリア */
			SET_WORD(du_send_data, 0, 3);

			/* 受信監視タイマーセット */
			tm_dudata = TM_DUDATA;

			switch (sg_mode) {
			case SGM_IBIT:
				switch (ibit_mode) {
				case IBIT_START:
					du_send_data[5] |= 0x01;
					ibit_status(BIT_OK);
					tm_dudata = TM_DUIBIT;
					break;
				case IBIT_DUBIT:
					ibit_status(BIT_NG);
					break;
				}
				break;
			case SGM_RBIT:
				if ((rbit_mode & RBIT_DUBIT) == 0) {
					du_send_data[5] |= 0x02;
					tm_dudata = TM_DURBIT;
					rbit_mode |= RBIT_DUBIT;
				} else {
					rbit_mode |= RBIT_DU_END;
				}
				if ((rbit_mode & (RBIT_SG_END | RBIT_DU_END))
						== (RBIT_SG_END | RBIT_DU_END))
					sg_mode = SGM_NORMAL;
				break;
			case SGM_NORMAL:
				switch (echo_mode) {
				case ECHO_START:
					du_send_data[4] |= 0x80;
					echo_mode = ECHO_WAIT;
					break;
				case ECHO_WAIT:
					echo_status = ECHO_NG;
					echo_mode = ECHO_END;
					break;
				}
				break;
			}

			/* データ送信 */
			rsa_send(du_send_data, 6);

			du_rcvflag = 0;
		}
	}
}

/*=======================================================================
 |
 |		Ｄ／Ｕからのデータの文字間タイムアウト検出処理
 |
 |	void	tmc_duchar()
 |
 =======================================================================*/
static	void	tmc_duchar(void)
{
	if (tm_duchar != TM_STOP) {
		if (--tm_duchar == 0) {
			rsa_rcvcounter = 0;	/* 受信データを捨てる */
		}
	}
}

/*=======================================================================
 |
 |		デジタル表示の更新タイミングのカウント処理
 |
 |	void	tmc_digital()
 |
 =======================================================================*/
static	void	tmc_digital(void)
{
	if (tm_digital != TM_STOP) {
		if (--tm_digital == 0) {
			/* デジタル表示更新フラグセット */
			digital_update_flag = 1;

			/* デジタル表示更新タイマー再セット */
			tm_digital = TM_DIGITAL;
		}
	}
}

/*=======================================================================
 |
 |		Ｉ－ＢＩＴタイマー
 |
 |	void	tmc_ibit()
 |
 =======================================================================*/
static	void	tmc_ibit(void)
{
	if (tm_ibit != TM_STOP) {
		if (--tm_ibit == 0) {
			switch (ibit_mode) {
			case IBIT_TPTN1:
			case IBIT_TPTN2:
			case IBIT_TPTN3:
			case IBIT_TPTN4:
				ibit_status(BIT_OK);
				break;
			case IBIT_SWITCH:
				sg_bit_status |= BIT_SG_SW;
				ibit_status(BIT_NG);
				break;
			case IBIT_RESULT:
				break;
			}
		}
	}
}

/*=======================================================================
 |
 |		ＧＳＣ描画終了割り込み停止時のバックアップ処理
 |
 |	void	tmc_gscbackup()
 |
 =======================================================================*/
static	void	tmc_gscbackup(void)
{
	if (tm_gscbackup != TM_STOP) {
		if (--tm_gscbackup == 0) {
			/* Ｄ／ＰをＲＡＰにセット */
			*(DP *)GSC_RAP_DP = dp_tmp;

			/* Ｐ／ＡビットをＲＡＰにセット */
			COPY_WORD(GSC_RAP_PA, pa_tmp, N_PABIT);

			/* 計算開始フラグセット */
			calc_flag = 1;

			/* ＧＳＣボード異常 */
			gsc_board_test = BIT_NG;

			tm_gscbackup = TM_GSCBACKUP;
		}
	}
}

/*=======================================================================
 |
 |		デバッグ用入力データシミュレーション処理
 |
 |	void	tmc_simulation()
 |
 =======================================================================*/
static	void	tmc_simulation(void)
{
	if (tm_simulation != TM_STOP) {
		if (--tm_simulation == 0) {
			debug_simulation();
			tm_simulation = TM_SIMULATION;
		}
	}
}
