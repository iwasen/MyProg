/************************************************************************
 *									*
 *		ＯＳ／２　オンラインユーザサポートシステム		*
 *			ＰＣ－ＮＥＴ／２				*
 *									*
 *		名称		: 環境データ設定処理			*
 *		ファイル名	: settei.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include "pcnet.h"
#include <stdlib.h>

/*--------------------------------------*/
/*		内部関数		*/
/*--------------------------------------*/
static	VOID	SetComParam(HWND);
static	VOID	ClearComParam(HWND);
static	VOID	GetComParam(HWND);
MRESULT CALLBACK SetModemDlg(HWND, USHORT, MPARAM, MPARAM);

/*--------------------------------------*/
/*		内部変数		*/
/*--------------------------------------*/
static	COMPARAM comParam[N_CHANNEL];		/* 通信パラメータ */
static	SHORT	currentPort;			/* カレントポート */
static	USHORT	portTbl[N_CHANNEL]= {
	0, IDD_PORT_COM1, IDD_PORT_COM2, IDD_PORT_COM3
};
static	USHORT	conxxxtTbl[3]= {
	IDD_PORT_MODEM, IDD_PORT_DIRECT, IDD_PORT_NC
};
static	USHORT	bltTbl[6] = {
	IDD_PORT_B300,  IDD_PORT_B600,  IDD_PORT_B1200,
	IDD_PORT_B2400, IDD_PORT_B4800, IDD_PORT_B9600
};
static	USHORT	chrTbl[2] = {
	IDD_PORT_CHR7, IDD_PORT_CHR8
};
static	USHORT	stpTbl[3] = {
	IDD_PORT_STP1, IDD_PORT_STP15, IDD_PORT_STP2
};
static	USHORT	parityTbl[3] = {
	IDD_PORT_PNONE, IDD_PORT_PEVEN, IDD_PORT_PODD
};

/*=======================================================================
 |
 |		通信ポート設定ダイアログプロシージャ
 |
 |	MRESULT CALLBACK SetPortDlg(hwnd, msg, mp1, mp2)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		USHORT	msg;		メッセージ識別子
 |		MPARAM	mp1;		メッセージパラメータ１
 |		MPARAM	mp2;		メッセージパラメータ２
 |
 =======================================================================*/
MRESULT CALLBACK SetPortDlg(HWND hwnd, USHORT msg, MPARAM mp1, MPARAM mp2)
{
	USHORT	chNo;

	switch (msg) {
	case WM_INITDLG:
		for (chNo = CH1; chNo <= CH3; chNo++)
			comParam[chNo] = sv.comParam[chNo];
		currentPort = CH1;
		SetComParam(hwnd);
		CenterDlgBox(hwnd);
		break;
	case WM_COMMAND:
		switch(SHORT1FROMMP(mp1)) {
		case DID_OK:
			GetComParam(hwnd);
			for (chNo = CH1; chNo <= CH3; chNo++)
				sv.comParam[chNo] = comParam[chNo];
			WriteEnvData();
			for (chNo = CH1; chNo <= CH3; chNo++) {
				if (portOpenFlag[chNo] == FALSE)
					DosResumeThread(threadID[chNo]);
				else {
					if (comParam[chNo].conxxxt !=
								PORT_NOTUSE)
						SetPortParameter(chNo);
				}
			}
			break;
		case DID_CANCEL:
			break;
		case IDD_CMD_MODEM:
			WinDlgBox(HWND_DESKTOP, hwnd, SetModemDlg,
						0, IDD_MODEM, NULL);
			return(0);
		}
		break;
	case WM_CONTROL:
		switch (SHORT1FROMMP(mp1)) {
		case IDD_PORT_COM1:
			GetComParam(hwnd);
			ClearComParam(hwnd);
			currentPort = CH1;
			SetComParam(hwnd);
			break;
		case IDD_PORT_COM2:
			GetComParam(hwnd);
			ClearComParam(hwnd);
			currentPort = CH2;
			SetComParam(hwnd);
			break;
		case IDD_PORT_COM3:
			GetComParam(hwnd);
			ClearComParam(hwnd);
			currentPort = CH3;
			SetComParam(hwnd);
			break;
		case IDD_PORT_MODEM:
			WinEnableWindow(WinWindowFromID(hwnd,
						IDD_CMD_MODEM), TRUE);
			break;
		case IDD_PORT_NC:
		case IDD_PORT_DIRECT:
			WinEnableWindow(WinWindowFromID(hwnd,
						IDD_CMD_MODEM), FALSE);
			break;
		}
	}
	return(WinDefDlgProc(hwnd, msg, mp1, mp2));
}

/*=======================================================================
 |
 |		通信ポート設定ダイアログに通信パラメータを設定
 |
 |	VOID	SetComParam(hwnd)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |
 =======================================================================*/
static	VOID	SetComParam(HWND hwnd)
{
	WinSendDlgItemMsg(hwnd, portTbl[currentPort],
				BM_SETCHECK, MPFROM2SHORT(TRUE, 0), 0L);
	WinSendDlgItemMsg(hwnd, conxxxtTbl[comParam[currentPort].conxxxt],
				BM_SETCHECK, MPFROM2SHORT(TRUE, 0), 0L);
	WinSendDlgItemMsg(hwnd, bltTbl[comParam[currentPort].baudRate],
				BM_SETCHECK, MPFROM2SHORT(TRUE, 0), 0L);
	WinSendDlgItemMsg(hwnd, chrTbl[comParam[currentPort].chDataBits],
				BM_SETCHECK, MPFROM2SHORT(TRUE, 0), 0L);
	WinSendDlgItemMsg(hwnd, stpTbl[comParam[currentPort].chStopBits],
				BM_SETCHECK, MPFROM2SHORT(TRUE, 0), 0L);
	WinSendDlgItemMsg(hwnd, parityTbl[comParam[currentPort].chParity],
				BM_SETCHECK, MPFROM2SHORT(TRUE, 0), 0L);
	WinEnableWindow(WinWindowFromID(hwnd, IDD_CMD_MODEM),
				comParam[currentPort].conxxxt == PORT_MODEM);
}

/*=======================================================================
 |
 |		通信ポート設定ダイアログの通信パラメータをクリア
 |
 |	VOID	ClearComParam(hwnd)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |
 =======================================================================*/
static	VOID	ClearComParam(HWND hwnd)
{
	WinSendDlgItemMsg(hwnd, conxxxtTbl[comParam[currentPort].conxxxt],
				BM_SETCHECK, MPFROM2SHORT(FALSE, 0), 0L);
	WinSendDlgItemMsg(hwnd, bltTbl[comParam[currentPort].baudRate],
				BM_SETCHECK, MPFROM2SHORT(FALSE, 0), 0L);
	WinSendDlgItemMsg(hwnd, chrTbl[comParam[currentPort].chDataBits],
				BM_SETCHECK, MPFROM2SHORT(FALSE, 0), 0L);
	WinSendDlgItemMsg(hwnd, stpTbl[comParam[currentPort].chStopBits],
				BM_SETCHECK, MPFROM2SHORT(FALSE, 0), 0L);
	WinSendDlgItemMsg(hwnd, parityTbl[comParam[currentPort].chParity],
				BM_SETCHECK, MPFROM2SHORT(FALSE, 0), 0L);
}

/*=======================================================================
 |
 |		通信ポート設定ダイアログから通信パラメータを取り出す
 |
 |	VOID	GetComParam(hwnd)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |
 =======================================================================*/
static	VOID	GetComParam(HWND hwnd)
{
	comParam[currentPort].conxxxt = SHORT1FROMMR(
				WinSendDlgItemMsg(hwnd,
				IDD_PORT_MODEM, BM_QUERYCHECKINDEX, 0L, 0L));
	comParam[currentPort].baudRate = SHORT1FROMMR(
				WinSendDlgItemMsg(hwnd,
				IDD_PORT_B300, BM_QUERYCHECKINDEX, 0L, 0L));
	comParam[currentPort].chDataBits = SHORT1FROMMR(
				WinSendDlgItemMsg(hwnd,
				IDD_PORT_CHR7, BM_QUERYCHECKINDEX, 0L, 0L));
	comParam[currentPort].chStopBits = SHORT1FROMMR(
				WinSendDlgItemMsg(hwnd,
				IDD_PORT_STP1, BM_QUERYCHECKINDEX, 0L, 0L));
	comParam[currentPort].chParity = SHORT1FROMMR(
				WinSendDlgItemMsg(hwnd,
				IDD_PORT_PNONE, BM_QUERYCHECKINDEX, 0L, 0L));
}

/*=======================================================================
 |
 |		モデム情報設定ダイアログプロシージャ
 |
 |	MRESULT CALLBACK SetModemDlg(hwnd, msg, mp1, mp2)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		USHORT	msg;		メッセージ識別子
 |		MPARAM	mp1;		メッセージパラメータ１
 |		MPARAM	mp2;		メッセージパラメータ２
 |
 =======================================================================*/
MRESULT CALLBACK SetModemDlg(HWND hwnd, USHORT msg, MPARAM mp1, MPARAM mp2)
{
	static	MODEM	modem;
	static	ENTRY_TBL	fieldData[] = {
		{ET_STZEROFIELD, IDD_MDM_INITCMD, 20, modem.initCmd},
		{ET_RADIOBUTTON, IDD_MDM_AUTO,   'A', &modem.detectCall},
		{ET_RADIOBUTTON, IDD_MDM_RI,     'R', &modem.detectCall},
		{ET_RADIOBUTTON, IDD_MDM_MSG,    'M', &modem.detectCall},
		{ET_STZEROFIELD, IDD_MDM_RIMSG,    4, modem.ringMsg},
		{ET_STZEROFIELD, IDD_MDM_CNCMD,   10, modem.conxxxtCmd},
		{ET_RADIOBUTTON, IDD_MDM_DCC,    'C', &modem.disconxxxt},
		{ET_RADIOBUTTON, IDD_MDM_DTR,    'D', &modem.disconxxxt},
		{ET_STZEROFIELD, IDD_MDM_ESC,      4, modem.escCode},
		{ET_NUMBERFIELD, IDD_MDM_GUARD,    4, &modem.guardTime},
		{ET_STZEROFIELD, IDD_MDM_DCCMD,    4, modem.discCmd},
		{ET_NUMBERFIELD, IDD_MDM_DTROFF,   4, &modem.dtrOffTime}
	};
	static	MODEM	ATdefault = {
		"ATZ", 'R', "RING", "ATA", 'D', "+++", 2, "ATH", 3
	};
	static	MODEM	V25default = {
		"SET", 'R', "RING", "CIC", 'D', "", 0, "", 3
	};

	switch (msg) {
	case WM_INITDLG:
		modem = sv.modem[currentPort];
		SetEntryData(hwnd, fieldData,
				sizeof(fieldData) / sizeof(ENTRY_TBL));

		CenterDlgBox(hwnd);
		return(FALSE);
	case WM_COMMAND:
		switch(SHORT1FROMMP(mp1)) {
		case DID_OK:
			GetEntryData(hwnd, fieldData,
				sizeof(fieldData) / sizeof(ENTRY_TBL));
			sv.modem[currentPort] = modem;
			WriteEnvData();
			break;
		case DID_CANCEL:
			break;
		case IDD_CMD_AT:
			modem = ATdefault;
			SetEntryData(hwnd, fieldData,
					sizeof(fieldData) / sizeof(ENTRY_TBL));
			return(0);
		case IDD_CMD_V25:
			modem = V25default;
			SetEntryData(hwnd, fieldData,
					sizeof(fieldData) / sizeof(ENTRY_TBL));
			return(0);
		}
		break;
	}
	return(WinDefDlgProc(hwnd, msg, mp1, mp2));
}

/*=======================================================================
 |
 |		環境設定ダイアログプロシージャ
 |
 |	MRESULT CALLBACK SetEnvDlg(hwnd, msg, mp1, mp2)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		USHORT	msg;		メッセージ識別子
 |		MPARAM	mp1;		メッセージパラメータ１
 |		MPARAM	mp2;		メッセージパラメータ２
 |
 =======================================================================*/
MRESULT CALLBACK SetEnvDlg(HWND hwnd, USHORT msg, MPARAM mp1, MPARAM mp2)
{
	static	ENVDATA	env;
	static	ENTRY_TBL	fieldData[] = {
		{ET_STZEROFIELD, IDD_ENV_SYSID,    8, env.systemID},
		{ET_STZEROFIELD, IDD_ENV_EDITOR,  32, env.editor},
		{ET_STZEROFIELD, IDD_ENV_OPTION,  16, env.edOption},
		{ET_NUMBERFIELD, IDD_ENV_TIMER,    2, &env.timer},
		{ET_STZEROFIELD, IDD_ENV_PASSWD,   8, env.passWord},
		{ET_CHECKBUTTON, IDD_ENV_MAIL,   '*', &env.mailFlag},
		{ET_CHECKBUTTON, IDD_ENV_LOGSAVE,'*', &env.logSave}
	};

	switch (msg) {
	case WM_INITDLG:
		env = sv.env;
		SetEntryData(hwnd, fieldData,
				sizeof(fieldData) / sizeof(ENTRY_TBL));
		CenterDlgBox(hwnd);
		break;
	case WM_COMMAND:
		switch(SHORT1FROMMP(mp1)) {
		case DID_OK:
			GetEntryData(hwnd, fieldData,
				sizeof(fieldData) / sizeof(ENTRY_TBL));
			sv.env = env;
			WriteEnvData();
			break;
		case DID_CANCEL:
			break;
		}
		break;
	}
	return(WinDefDlgProc(hwnd, msg, mp1, mp2));
}
