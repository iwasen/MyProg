/************************************************************************
 *									*
 *		ＯＳ／２　オンラインユーザサポートシステム		*
 *			ＰＣ－ＮＥＴ／２				*
 *									*
 *		名称		: ユーザ対話処理			*
 *		ファイル名	: operate.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include "pcnet.h"
#include <stdlib.h>
#include <string.h>
#include <setjmp.h>
#include <io.h>
#include <sys\types.h>
#include <sys\stat.h>

#define	KWD_DISPLAY	1
#define	KWD_CLS		2
#define	KWD_MENU	3
#define	KWD_ENDMENU	4
#define	KWD_BBS		5
#define	KWD_QUESTION	6
#define	KWD_ANSWER	7
#define	KWD_PASSWORD	8
#define	KWD_CATALOG	9
#define	KWD_SMAIL	10
#define	KWD_RMAIL	11
#define	KWD_END		12
#define	KWD_NAME	13
#define	KWD_STR		14
#define	KWD_RMCHECK	15
#define	KWD_EOF		-1

typedef	struct	keyword	{
	SHORT	keyWord;
	VOID	*ptr;
	struct	keyword	*nextp;
} KEYWORD;

typedef	struct	menu	{
	SHORT	menuNo;
	CHAR	level;
	CHAR	name[21];
	struct	menu	*nextp;
} MENU;

typedef	struct	opdata	{
	CHAR	name[21];
	KEYWORD	*ptr;
	struct	opdata	*nextp;
} OPDATA;

typedef	struct	group	{
	CHAR	name[9];
	OPDATA	*op;
	struct	group	*nextp;
} GROUP;

typedef	struct	{
	CHAR	*keyWord;
	SHORT	keyCode;
} KEYTBL;

/*--------------------------------------*/
/*		内部関数		*/
/*--------------------------------------*/
static	KEYWORD	*GetParam(FILE *);
static	MENU	*GetMenu(FILE *);
static	SHORT	GetStr(FILE *, CHAR *, SHORT);
static	SHORT	fgetchr(FILE *);
static	VOID	Operation(USHORT, CHAR *, BOOL);
static	BOOL	Menu(USHORT, CHAR *, MENU *, BOOL);
static	VOID	BBS(USHORT, CHAR *, CHAR *);
static	VOID	BBSText(USHORT, CHAR *, CHAR *, BBSMDBF *);
static	VOID	ReceiveTextBBSFile(USHORT, CHAR *, DB *);
static	VOID	BBSBinary(USHORT, CHAR *, CHAR *, BBSMDBF *);
static	VOID	ReceiveBinaryBBSFile(USHORT, CHAR *, DB *);
static	VOID	DeleteBBSFile(USHORT, CHAR *, DB *, SHORT);
static	VOID	Question(USHORT, CHAR *);
static	VOID	QuestionSend(USHORT);
static	VOID	QuestionRead(USHORT, CHAR *, SHORT, BOOL);
static	SHORT	QuestionList(USHORT, CHAR *, SHORT, SHORT *);
static	BOOL	QuestionDelete(USHORT, CHAR *, SHORT);
static	VOID	Answer(USHORT, CHAR *);
static	VOID	AnswerRead(USHORT, CHAR *, SHORT, BOOL);
static	SHORT	AnswerList(USHORT, CHAR *, SHORT, SHORT *);
static	BOOL	AnswerDelete(USHORT, CHAR *, SHORT);
static	VOID	SndMail(USHORT, CHAR *);
static	VOID	MailSend(USHORT);
static	VOID	SndMailRead(USHORT, CHAR *, SHORT, BOOL);
static	SHORT	SndMailList(USHORT, CHAR *, SHORT, SHORT *);
static	BOOL	SndMailDelete(USHORT, CHAR *, SHORT);
static	VOID	RcvMail(USHORT, CHAR *);
static	VOID	RcvMailRead(USHORT, CHAR *, SHORT, BOOL);
static	SHORT	RcvMailList(USHORT, CHAR *, SHORT, SHORT *);
static	BOOL	RcvMailDelete(USHORT, CHAR *, SHORT);
static	VOID	PassWord(USHORT, CHAR *);
static	VOID	Catalog(USHORT, CHAR *, CHAR *);
static	VOID	RcvMsg(USHORT, CHAR *, CHAR *, CHAR *, SHORT);
static	SHORT	DspBBSList(USHORT, DB *, SHORT, BOOL);
static	SHORT	DspPGMList(USHORT, DB *, SHORT, BOOL);
static	BOOL	ReceiveTextFile(USHORT, CHAR *, CHAR *, CHAR *);
static	BOOL	ReceiveBinaryFile(USHORT, CHAR *);
static	VOID	SendBBSFile(USHORT, CHAR *, DB *, SHORT, BOOL);
static	VOID	SendTextFile(USHORT, CHAR *, BOOL);
static	VOID	SendXmodem(USHORT, CHAR *, DB *, SHORT, BOOL);
static	SHORT	GetNo(CHAR *);
static	BOOL	ReadBBSData(DB *, SHORT, BBSDBF *);
static	VOID	DspHelpMsg(USHORT, CHAR *);
static	BOOL	YESorNO(USHORT, CHAR *);
static	VOID	ReceiveStr(USHORT, CHAR *, USHORT);
static	VOID	SendStr(USHORT, CHAR *, USHORT);
static	VOID	SendInputErrMsg(USHORT);
static	VOID	SendSysErrMsg(USHORT);
static	FILE	*OpenFile(USHORT, CHAR *, CHAR *);
static	VOID	CloseFile(USHORT);
static	DB	*OpenBBS(USHORT, CHAR *);
static	VOID	CloseBBS(USHORT);
static	VOID	RcvMailCheck(USHORT, CHAR *);
static	VOID	Quit(SHORT);

/*--------------------------------------*/
/*		内部変数		*/
/*--------------------------------------*/
static	GROUP	*group;
static	OPDATA	*opdata[N_CHANNEL];
static	BOOL	unGetFlag;
static	CHAR	lineBuf[256];
static	USHORT	lineNo;
static	jmp_buf	jmpBuf[N_CHANNEL];
static	FILE	*openFile[N_CHANNEL];
static	DB	*openBBS[N_CHANNEL];
static	KEYTBL	keyTbl[] = {
	{"@DISPLAY",	KWD_DISPLAY},
	{"@CLS",	KWD_CLS},
	{"@MENU",	KWD_MENU},
	{"@ENDMENU",	KWD_ENDMENU},
	{"@BBS",	KWD_BBS},
	{"@QUESTION",	KWD_QUESTION},
	{"@ANSWER",	KWD_ANSWER},
	{"@PASSWORD",	KWD_PASSWORD},
	{"@CATALOG",	KWD_CATALOG},
	{"@SMAIL",	KWD_SMAIL},
	{"@RMAIL",	KWD_RMAIL},
	{"@END",	KWD_END},
	{"@RMCHECK",	KWD_RMCHECK}
};

/*=======================================================================
 |
 |		操作定義ファイル読み込み処理
 |
 |	BOOL	ReadOperationFile()
 |
 |		BOOL	返値		TRUE ：正常
 |					FALSE：操作定義ファイルに誤りがある
 |
 =======================================================================*/
BOOL	ReadOperationFile(VOID)
{
	FILE	*fp;
	GROUP	**gpp, *gp;
	OPDATA	**opp, *op;
	BOOL	st;
	SHORT	keyWord;
	SHORT	i;
	HDIR	hdir = 0xffff;
	FILEFINDBUF	findBuf;
	USHORT	count = 1;
	CHAR	buf[21];
	CHAR	msg[80];

	st = TRUE;
	gpp = &group;
	DosFindFirst(fnOperation, &hdir, FILE_NORMAL, &findBuf,
				sizeof(findBuf), &count, 0);
	while (count != 0) {
		if ((fp = fopen(findBuf.achName, "r")) != NULL) {
			gp = malloc(sizeof(GROUP));
			for (i = 0; i < 8; i++) {
				if (findBuf.achName[i] == '.')
					break;
				gp->name[i] = findBuf.achName[i];
			}
			gp->name[i] = '\0';

			opp = &gp->op;
			unGetFlag = FALSE;
			lineNo = 0;
			for (;;) {
				if ((keyWord = GetStr(fp, buf, sizeof(buf)))
								== KWD_EOF)
					break;
				else if (keyWord != KWD_NAME) {
					st = FALSE;
					break;
				}
				op = malloc(sizeof(OPDATA));
				strcpy(op->name, buf);
				if ((op->ptr = GetParam(fp)) == NULL) {
					st = FALSE;
					break;
				}
				op->nextp = NULL;
				*opp = op;
				opp = &op->nextp;
			}
			fclose(fp);
			if (st == FALSE)
				break;
			gp->nextp = NULL;
			*gpp = gp;
			gpp = &gp->nextp;
		}
		DosFindNext(hdir, &findBuf, sizeof(findBuf), &count);
	}
	DosFindClose(hdir);

	if (st == FALSE) {
		sprintf(msg,
		"操作定義ファイル[%s]の%d行目に誤りがあります。",
					findBuf.achName, lineNo);
		WinMessageBox(HWND_DESKTOP, hwndMain,
			msg,
			"操作定義エラー！",
			ID_MB,
			MB_OK | MB_ICONEXCLAMATION |
			MB_APPLMODAL);
	}

	return(st);
}

/*=======================================================================
 |
 |		キーワードのパラメータ読み込み
 |
 |	KEYWORD	*GetParam(fp)
 |
 |		FILE	*fp;		ファイルポインタ
 |
 |		KEYWORD	*返値		キーワードデータ
 |
 =======================================================================*/
static	KEYWORD	*GetParam(FILE *fp)
{
	SHORT	keyWord;
	KEYWORD	*kwp, **kwpp, *mp;
	CHAR	buf[256];

	kwp = NULL;
	kwpp = &kwp;
	for (;;) {
		if ((keyWord = GetStr(fp, buf, sizeof(buf))) == KWD_EOF)
			break;
		else if (keyWord == KWD_NAME) {
			unGetFlag = TRUE;
			break;
		}
		mp = malloc(sizeof(KEYWORD));
		mp->keyWord = keyWord;
		mp->ptr = NULL;
		mp->nextp = NULL;
		*kwpp = mp;
		kwpp = &mp->nextp;
		switch (keyWord) {
		case KWD_DISPLAY:
			if (GetStr(fp, buf, sizeof(buf)) == KWD_STR) {
				mp->ptr = malloc(strlen(buf) + 1);
				strcpy(mp->ptr, buf);
			} else {
				mp->ptr = malloc(1);
				((char *)(mp->ptr))[0] = '\0';
				unGetFlag = TRUE;
			}
			break;
		case KWD_BBS:
		case KWD_CATALOG:
		case KWD_RMCHECK:
			if (GetStr(fp, buf, sizeof(buf)) == KWD_STR) {
				mp->ptr = malloc(strlen(buf) + 1);
				strcpy(mp->ptr, buf);
			} else
				return(NULL);
			break;
		case KWD_MENU:
			if ((mp->ptr = GetMenu(fp)) == NULL)
				return(NULL);
			break;
		case KWD_CLS:
		case KWD_QUESTION:
		case KWD_ANSWER:
		case KWD_PASSWORD:
		case KWD_SMAIL:
		case KWD_RMAIL:
		case KWD_END:
			break;
		default:
			return(NULL);
		}
	}
	return(kwp);
}

/*=======================================================================
 |
 |		メニューデータ読み込み
 |
 |	MENU	*GetMenu(fp)
 |
 |		FILE	*fp;		ファイルポインタ
 |
 |		MENU	*返値		メニューデータ
 |
 =======================================================================*/
static	MENU	*GetMenu(FILE *fp)
{
	MENU	*menup, *mp, **mpp;
	CHAR	buf[21];
	SHORT	keyWord;

	menup = NULL;
	mpp = &menup;

	for (;;) {
		if ((keyWord = GetStr(fp, buf, sizeof(buf))) == KWD_EOF)
			break;
		else if (keyWord == KWD_ENDMENU)
			break;
		else if (keyWord != KWD_STR)
			return(NULL);
		mp = malloc(sizeof(MENU));
		mp->menuNo = atoi(buf);

		if ((keyWord = GetStr(fp, buf, sizeof(buf))) == KWD_EOF)
			return(NULL);
		else if (keyWord != KWD_STR)
			return(NULL);
		mp->level = buf[0];

		if ((keyWord = GetStr(fp, buf, sizeof(buf))) == KWD_EOF)
			return(NULL);
		else if (keyWord != KWD_NAME)
			return(NULL);
		strcpy(mp->name, buf);

		mp->nextp = NULL;
		*mpp = mp;
		mpp = &mp->nextp;
	}

	return(menup);
}

/*=======================================================================
 |
 |		文字列読み込み
 |
 |	SHORT	GetStr(fp, bufp, bufSize)
 |
 |		FILE	*fp;		ファイルポインタ
 |		CHAR	*bufp;		文字列格納バッファ
 |		USHORT	bufSize;	バッファサイズ
 |
 |		SHORT	返値		キーワード
 |
 =======================================================================*/
static	SHORT	GetStr(FILE *fp, CHAR *bufp, SHORT bufSize)
{
	SHORT	mode, ch, count;
	BOOL	loopSW;
	SHORT	keyWord;
	SHORT	i;
	static	CHAR	svBuf[256];
	static	SHORT	svKeyWord;

	if (unGetFlag) {
		unGetFlag = FALSE;
		strcpy(bufp, svBuf);
		return(svKeyWord);
	}

	mode = 0;
	count = 0;
	loopSW = TRUE;
	while (loopSW) {
		if (count+1 >= bufSize)
			break;
		ch = fgetchr(fp);
		switch (ch) {
		case EOF:
			svKeyWord = KWD_EOF;
			return(EOF);
		case ' ':
		case '\t':
		case '\n':
			if (mode == 1)
				loopSW = FALSE;
			else if (mode == 2)
				bufp[count++] = (CHAR)ch;
			break;
		case ',':
			if (mode == 0 || mode == 1)
				loopSW = FALSE;
			else if (mode == 2)
				bufp[count++] = (CHAR)ch;
			break;
		case '\"':
			if (mode == 0 || mode == 1)
				mode = 2;
			else if (mode == 2)
				mode = 1;
			break;
		default:
			if (mode == 0)
				mode = 1;
			bufp[count++] = (CHAR)ch;
			break;
		}
	}

	bufp[count] = '\0';

	for (i = 0; i < sizeof(keyTbl)/sizeof(KEYTBL); i++) {
		if (strcmp(bufp, keyTbl[i].keyWord) == 0) {
			keyWord = keyTbl[i].keyCode;
			break;
		}
	}
	if (i == sizeof(keyTbl)/sizeof(KEYTBL)) {
		if (bufp[0] == '[' && bufp[count-1] == ']') {
			memcpy(bufp, bufp + 1, count - 2);
			bufp[count - 2] = '\0';
			keyWord = KWD_NAME;
		} else
			keyWord = KWD_STR;
	}

	strcpy(svBuf, bufp);
	svKeyWord = keyWord;

	return(keyWord);
}

/*=======================================================================
 |
 |		１文字読み込み（コメント読みとばし）
 |
 |	SHORT	fgetchr(fp)
 |
 |		FILE	*fp;		ファイルポインタ
 |
 |		SHORT	返値		読み込み文字
 |
 =======================================================================*/
static	SHORT	fgetchr(FILE *fp)
{
	static	CHAR	*p;
	SHORT	ch, mode;

	mode = 0;
	for (;;) {
		if (lineNo == 0 || *p == '\0') {
			if (fgets(lineBuf, sizeof(lineBuf), fp) == NULL)
				return(EOF);
			p = lineBuf;
			lineNo++;
			continue;
		}
		ch = *p++;
			
		if (mode == 0 && ch == '/')
			mode = 1;
		else if (mode == 1 && ch == '*')
			mode = 2;
		else if (mode == 2 && ch == '*')
			mode = 3;
		else if (mode == 3 && ch == '/')
			mode = 0;
		else if (mode == 0)
			break;
		else if (mode == 1) {
			p--;
			ch = '/';
			break;
		} else if (mode == 3)
			mode = 2;
	}

	return(ch);
}

/*=======================================================================
 |
 |		オンライン処理
 |
 |	VOID	OnLine(chNo)
 |
 |		USHORT	chNo;		チャネル番号
 |
 =======================================================================*/
VOID	OnLine(USHORT chNo)
{
	GROUP	*gp;

	if (setjmp(jmpBuf[chNo]) != 0) {
		CloseFile(chNo);
		CloseBBS(chNo);
		return;
	}

	SendTextFile(chNo, fnLoginMsg, FALSE);

	for (gp = group; gp != NULL; gp = gp->nextp) {
		if (strcmp(gp->name, cGroup[chNo]) == 0) {
			opdata[chNo] = gp->op;
			Operation(chNo, gp->op->name, TRUE);
			break;
		}
	}
}

/*=======================================================================
 |
 |		ユーザ対話処理
 |
 |	VOID	Operation(chNo, name, top)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*name;		コーナー名
 |		BOOL	top;		TRUE :最上位メニュー
 |					FALSE:サブメニュー
 |
 =======================================================================*/
static	VOID	Operation(USHORT chNo, CHAR *name, BOOL top)
{
	OPDATA	*op;
	KEYWORD	*kp;

	for (op = opdata[chNo]; op != NULL; op = op->nextp) {
		if (strcmp(name, op->name) == 0)
			break;
	}
	if (op == NULL)
		return;
restart:
	for (kp = op->ptr; kp != NULL; kp = kp->nextp) {
		switch (kp->keyWord) {
		case KWD_DISPLAY:
			SendStr(chNo, kp->ptr, 1);
			break;
		case KWD_CLS:
			SendStr(chNo, "\x0c", 0);
			break;
		case KWD_MENU:
			if (Menu(chNo, op->name, kp->ptr, top))
				goto restart;
			break;
		case KWD_BBS:
			BBS(chNo, op->name, kp->ptr);
			break;
		case KWD_QUESTION:
			Question(chNo, op->name);
			break;
		case KWD_ANSWER:
			Answer(chNo, op->name);
			break;
		case KWD_PASSWORD:
			PassWord(chNo, op->name);
			break;
		case KWD_CATALOG:
			Catalog(chNo, op->name, kp->ptr);
			break;
		case KWD_SMAIL:
			SndMail(chNo, op->name);
			break;
		case KWD_RMAIL:
			RcvMail(chNo, op->name);
			break;
		case KWD_END:
			longjmp(jmpBuf[chNo], 1);
			break;
		case KWD_RMCHECK:
			RcvMailCheck(chNo, kp->ptr);
			break;
		}
	}
}

/*=======================================================================
 |
 |		メニュー処理
 |
 |	BOOL	Menu(chNo, name, menup, top)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*name;		コーナー名
 |		MENU	*menup;		メニューデータポインタ
 |		BOOL	top;		TRUE ：最上位メニュー
 |					FALSE：サブメニュー
 |
 |		BOOL	返値		TRUE ：継続
 |					FALSE：終了
 |
 =======================================================================*/
static	BOOL	Menu(USHORT chNo, CHAR *name, MENU *menup, BOOL top)
{
	CHAR	buf[3];
	MENU	*mp;
	SHORT	menuNo;

	for (;;) {
		RcvMsg(chNo, top ?
			"番号またはコマンド(H,M,Q)を入力して下さい。" :
			"番号またはコマンド(E,H,M,Q)を入力して下さい。",
			name, buf, sizeof(buf));
		switch (toupper(buf[0])) {
		case 'E':
			if (top)
				SendInputErrMsg(chNo);
			else
				return(FALSE);
			break;
		case 'H':
			DspHelpMsg(chNo, top ? "0HMQ" : "0EHMQ");
			break;
		case 'M':
			return(TRUE);
		case 'Q':
			Quit(chNo);
			break;
		default:
			menuNo = GetNo(buf);
			for (mp = menup; mp != NULL; mp = mp->nextp) {
				if (mp->menuNo == menuNo) {
					if (mp->level > cUserLevel[chNo]) {
						SendStr(chNo,
						"この機能はご利用できません。",
							2);
					} else {
						Operation(chNo,
							mp->name, FALSE);
						return(TRUE);
					}
					break;
				}
			}
			if (mp == NULL)
				SendInputErrMsg(chNo);
			break;
		}
	}
}

/*=======================================================================
 |
 |		掲示板処理
 |
 |	VOID	BBS(chNo, name, BBSName)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*name;		コーナー名
 |		CHAR	*BBSName;	掲示板識別名
 |
 =======================================================================*/
static	VOID	BBS(USHORT chNo, CHAR *name, CHAR *BBSName)
{
	BBSMDBF	BBSMData;

	if (GetBBSM(BBSName, &BBSMData) == TRUE) {
		if (BBSMData.type[0] == 'T')
			BBSText(chNo, name, BBSName, &BBSMData);
		else if (BBSMData.type[0] == 'B')
			BBSBinary(chNo, name, BBSName, &BBSMData);
	}
}

/*=======================================================================
 |
 |		テキスト形式掲示板処理
 |
 |	VOID	BBSText(chNo, name, BBSName, BBSMData)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*name;		コーナー名
 |		CHAR	*BBSName;	掲示板識別名
 |		BBSMDBF	*BBSMData;	掲示板データ
 |
 =======================================================================*/
static	VOID	BBSText(USHORT chNo, CHAR *name, CHAR *BBSName,
							BBSMDBF *BBSMData)
{
	DB	*dbp;
	CHAR	cmd[6];
	BOOL	loopSW;
	SHORT	seqNo;
	SHORT	listNo;
	BOOL	fWrite, fListSeq;

	if ((dbp = OpenBBS(chNo, BBSName)) == NULL) {
		SendSysErrMsg(chNo);
		return;
	}

	fWrite = (BBSMData->userWrite[0] != ' ');
	fListSeq = (BBSMData->listSeq[0] == 'O');

	listNo = DspBBSList(chNo, dbp, -1, fListSeq);

	loopSW = TRUE;
	while (loopSW) {
		RcvMsg(chNo, fWrite ?
		"番号またはコマンド(D,E,H,L[n],Q,Rn,S,Xn)を入力して下さい。" :
		"番号またはコマンド(E,H,L[n],Q,Rn,Xn)を入力して下さい。",
			name, cmd, sizeof(cmd));
		switch (toupper(cmd[0])) {
		case 'D':
			if (fWrite) {
				seqNo = GetNo(cmd+1);
				if (seqNo >= 1 && seqNo <=
						(SHORT)DBRecCount(dbp)) {
					DeleteBBSFile(chNo, BBSName, dbp,
								seqNo);
					listNo = seqNo;
				} else
					SendInputErrMsg(chNo);
			} else
				SendInputErrMsg(chNo);
			break;
		case 'E':
			loopSW = FALSE;
			break;
		case 'H':
			DspHelpMsg(chNo, fWrite ? "1DEHLRQSX" : "1EHLQRX");
			break;
		case 'L':
			if (cmd[1] != '\0') {
				seqNo = GetNo(cmd+1);
				if (seqNo >= 1 &&
					    seqNo <= (SHORT)DBRecCount(dbp))
					listNo = DspBBSList(chNo, dbp, seqNo,
								fListSeq);
				else
					SendInputErrMsg(chNo);
			} else
				listNo = DspBBSList(chNo, dbp, -1, fListSeq);
			break;
		case 'Q':
			Quit(chNo);
			break;
		case 'R':
			seqNo = GetNo(cmd+1);
			if (seqNo >= 1 && seqNo <= (SHORT)DBRecCount(dbp)) {
				SendBBSFile(chNo, BBSName, dbp, seqNo, FALSE);
				listNo = seqNo;
			} else
				SendInputErrMsg(chNo);
			break;
		case 'S':
			if (fWrite)
				ReceiveTextBBSFile(chNo, BBSName, dbp);
			else
				SendInputErrMsg(chNo);
			break;
		case 'X':
			seqNo = GetNo(cmd+1);
			if (seqNo >= 1 && seqNo <= (SHORT)DBRecCount(dbp)) {
				SendXmodem(chNo, BBSName, dbp, seqNo, FALSE);
				listNo = seqNo;
			} else
				SendInputErrMsg(chNo);
			break;
		case '\0':
			listNo = DspBBSList(chNo, dbp, listNo, fListSeq);
			break;
		default:
			seqNo = GetNo(cmd);
			if (seqNo >= 1 && seqNo <= (SHORT)DBRecCount(dbp)) {
				SendBBSFile(chNo, BBSName, dbp, seqNo, TRUE);
				listNo = seqNo;
			} else
				SendInputErrMsg(chNo);
			break;
		}
	}

	CloseBBS(chNo);
}

/*=======================================================================
 |
 |		テキスト掲示ファイル受信処理
 |
 |	VOID	ReceiveTextBBSFile(chNo, BBSName, dbp)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*BBSName;	掲示板識別名
 |		DB	*dbp;		データベースポインタ
 |
 =======================================================================*/
static	VOID	ReceiveTextBBSFile(USHORT chNo, CHAR *BBSName, DB *dbp)
{
	CHAR	fileName[13], pathName[40], title[41];
	BBSDBF	BBSData;
	DATETIME	dateTime;
	CHAR	date[18];
	struct	stat	st;
	CHAR	strBuf[9];

	MakeBBSFile(BBSName, fileName, pathName);
	if (ReceiveTextFile(chNo, pathName, title,
				"メッセージを入力して下さい。")) {
		CopyToDBF(BBSData.fileName, fileName,
					sizeof(BBSData.fileName));
		DosGetDateTime(&dateTime);
		sprintf(date, "%2u/%02u/%02u %2u:%02u:%02u",
					dateTime.year % 100,
					dateTime.month,
					dateTime.day,
					dateTime.hours,
					dateTime.minutes,
					dateTime.seconds);
		CopyToDBF(BBSData.date, date, sizeof(BBSData.date));
		CopyToDBF(BBSData.title, title, sizeof(BBSData.title));
		BBSData.userLevel[0] = '0';
		if (stat(pathName, &st) == 0) {
			sprintf(strBuf, "%8ld", st.st_size);
			CopyToDBF(BBSData.size, strBuf, sizeof(BBSData.size));
		}
		CopyToDBF(BBSData.userID, cUserID[chNo],
					sizeof(BBSData.userID));
		DBLock(dbp);
		DBStore(dbp, &BBSData);
		DBRelease(dbp);
	} else
		DosDelete(pathName, 0L);
}

/*=======================================================================
 |
 |		バイナリ形式掲示板処理
 |
 |	VOID	BBSBinary(chNo, name, BBSName, BBSMData)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*name;		コーナー名
 |		CHAR	*BBSName;	掲示板識別名
 |		BBSMDBF	*BBSMData;	掲示板データ
 |
 =======================================================================*/
static	VOID	BBSBinary(USHORT chNo, CHAR *name, CHAR *BBSName,
							BBSMDBF *BBSMData)
{
	DB	*dbp;
	CHAR	cmd[6];
	BOOL	loopSW;
	SHORT	seqNo;
	SHORT	listNo;
	BOOL	fWrite, fListSeq;

	if ((dbp = OpenBBS(chNo, BBSName)) == NULL) {
		SendSysErrMsg(chNo);
		return;
	}

	fWrite = (BBSMData->userWrite[0] != ' ');
	fListSeq = (BBSMData->listSeq[0] == 'O');

	listNo = DspPGMList(chNo, dbp, -1, fListSeq);

	loopSW = TRUE;
	while (loopSW) {
		RcvMsg(chNo, fWrite ?
		"番号またはコマンド(D,E,H,L[n],Q,U)を入力して下さい。" :
		"番号またはコマンド(E,H,L[n],Q)を入力して下さい。",
			name, cmd, sizeof(cmd));
		switch (toupper(cmd[0])) {
		case 'D':
			if (fWrite) {
				seqNo = GetNo(cmd+1);
				if (seqNo >= 1 && seqNo <=
						(SHORT)DBRecCount(dbp)) {
					DeleteBBSFile(chNo, BBSName, dbp,
								seqNo);
					listNo = seqNo;
				} else
					SendInputErrMsg(chNo);
			} else
				SendInputErrMsg(chNo);
			break;
		case 'E':
			loopSW = FALSE;
			break;
		case 'H':
			DspHelpMsg(chNo, fWrite ? "2DEHLQU" : "2DEHLQ");
			break;
		case 'L':
			if (cmd[1] != '\0') {
				seqNo = GetNo(cmd+1);
				if (seqNo >= 1 &&
					    seqNo <= (SHORT)DBRecCount(dbp))
					listNo = DspPGMList(chNo, dbp, seqNo,
								fListSeq);
				else
					SendInputErrMsg(chNo);
			} else
				listNo = DspPGMList(chNo, dbp, -1, fListSeq);
			break;
		case 'Q':
			Quit(chNo);
			break;
		case 'U':
			if (fWrite)
				ReceiveBinaryBBSFile(chNo, BBSName, dbp);
			else
				SendInputErrMsg(chNo);
			break;
		case '\0':
			listNo = DspPGMList(chNo, dbp, listNo, fListSeq);
			break;
		default:
			seqNo = GetNo(cmd);
			if (seqNo >= 1 && seqNo <= (SHORT)DBRecCount(dbp)) {
				SendXmodem(chNo, BBSName, dbp, seqNo, TRUE);
				listNo = seqNo;
			} else
				SendInputErrMsg(chNo);
			break;
		}
	}

	CloseBBS(chNo);
}

/*=======================================================================
 |
 |		バイナリ掲示ファイル受信処理
 |
 |	VOID	ReceiveBinaryBBSFile(chNo, BBSName, dbp)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*BBSName;	掲示板識別名
 |		DB	*dbp;		データベースポインタ
 |
 =======================================================================*/
static	VOID	ReceiveBinaryBBSFile(USHORT chNo, CHAR *BBSName, DB *dbp)
{
	CHAR	name[13], fileName[13], pathName[26], title[41];
	BBSDBF	BBSData;
	DATETIME	dateTime;
	CHAR	date[18];
	struct	stat	st;
	CHAR	strBuf[9];

retry:
	SendStr(chNo, "アップロードするファイル名を入力して下さい。", 1);
	SendStr(chNo, "（中止はリターンのみ入力して下さい）", 2);
	ReceiveStr(chNo, name, sizeof(name));
	SendCRLF(chNo, 1);
	if (name[0] == '\0')
		return;

	if (CheckFileName(name) == FALSE) {
		SendStr(chNo, "ファイル名に誤りがあります。", 2);
		goto retry;
	}

	SendStr(chNo, "タイトルを入力して下さい。", 1);
	SendStr(chNo,
	    "（半角４０字／全角２０字以内、中止はリターンのみ入力して下さい）",
		2);
	ReceiveStr(chNo, title, sizeof(title));
	SendCRLF(chNo, 1);
	if (title[0] == '\0')
		return;

	MakeBBSFile(BBSName, fileName, pathName);
	if (ReceiveBinaryFile(chNo, pathName)) {
		CopyToDBF(BBSData.userID, cUserID[chNo],
					sizeof(BBSData.userID));
		CopyToDBF(BBSData.name, name, sizeof(BBSData.name));
		DosGetDateTime(&dateTime);
		sprintf(date, "%2u/%02u/%02u %2u:%02u:%02u",
					dateTime.year % 100,
					dateTime.month,
					dateTime.day,
					dateTime.hours,
					dateTime.minutes,
					dateTime.seconds);
		CopyToDBF(BBSData.date, date, sizeof(BBSData.date));
		CopyToDBF(BBSData.title, title, sizeof(BBSData.title));
		BBSData.userLevel[0] = '0';
		if (stat(pathName, &st) == 0) {
			sprintf(strBuf, "%8ld", st.st_size);
			CopyToDBF(BBSData.size, strBuf, sizeof(BBSData.size));
		}
		CopyToDBF(BBSData.fileName, fileName,
				sizeof(BBSData.fileName));
		DBLock(dbp);
		DBStore(dbp, &BBSData);
		DBRelease(dbp);
	}
}

/*=======================================================================
 |
 |		掲示板ファイル削除処理
 |
 |	VOID	DeleteBBSFile(chNo, BBSName, dbp, seqNo)
 |
 =======================================================================*/
static	VOID	DeleteBBSFile(USHORT chNo, CHAR *BBSName, DB *dbp, SHORT seqNo)
{
	CHAR	fileName[30];
	BBSDBF	BBSData;
	CHAR	name[13];
	CHAR	userID[10];
	CHAR	msg[80];

	if (ReadBBSData(dbp, seqNo, &BBSData) == FALSE)
		return;

	CopyToDBF(userID, cUserID[chNo], 8);
	if (cUserLevel[chNo] != '9' &&
				strncmp(userID, BBSData.userID, 8) != 0) {
		SendStr(chNo, "このファイルを削除することはできません。", 2);
		return;
	}

	sprintf(msg, "[%04d] を削除しますか？ (Y/N)", seqNo);
	if (YESorNO(chNo, msg) == FALSE)
		return;

	CopyFromDBF(name, BBSData.fileName, sizeof(BBSData.fileName));
	sprintf(fileName, "%s.BBS\\%s", BBSName, name);
	DosDelete(fileName, 0L);

	DBDelete2(dbp);

	SendStr(chNo, "削除しました。", 2);
}

/*=======================================================================
 |
 |		質問処理
 |
 |	VOID	Question(chNo, name)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*name;		コーナー名
 |
 =======================================================================*/
static	VOID	Question(USHORT chNo, CHAR *name)
{
	CHAR	userID[10];
	BOOL	loopSW;
	CHAR	cmd[6];
	SHORT	nRec, seqNo, listNo;

	CopyToDBF(userID, cUserID[chNo], 8);
	userID[8] = 'Q';
	userID[9] = ' ';

	listNo = QuestionList(chNo, userID, 0, &nRec);

	loopSW = TRUE;
	while (loopSW) {
		RcvMsg(chNo,
		    "番号またはコマンド(Dn,E,H,L,Q,Rn,S)を入力して下さい。",
			name, cmd, sizeof(cmd));
		switch (toupper(cmd[0])) {
		case 'D':
			seqNo = GetNo(cmd+1);
			if (seqNo >= 1 && seqNo <= nRec) {
				if (QuestionDelete(chNo, userID,
							seqNo) == TRUE)
					listNo = QuestionList(chNo, userID,
								0, &nRec);
			} else
				SendInputErrMsg(chNo);
			break;
		case 'E':
			loopSW = FALSE;
			break;
		case 'H':
			DspHelpMsg(chNo, "1DEHLQRS");
			break;
		case 'L':
			if (cmd[1] != '\0') {
				seqNo = GetNo(cmd+1);
				if (seqNo >= 1 && seqNo <= nRec)
					listNo = QuestionList(chNo, userID,
								seqNo, &nRec);
				else
					SendInputErrMsg(chNo);
			} else
				listNo = QuestionList(chNo, userID, 0, &nRec);
			break;
		case 'Q':
			Quit(chNo);
			break;
		case 'R':
			seqNo = GetNo(cmd+1);
			if (seqNo >= 1 && seqNo <= nRec) {
				QuestionRead(chNo, userID, seqNo, FALSE);
				listNo = seqNo;
			} else
				SendInputErrMsg(chNo);
			break;
		case 'S':
			QuestionSend(chNo);
			listNo = QuestionList(chNo, userID, 0, &nRec);
			break;
		case '\0':
			if (listNo >= 1)
				listNo = QuestionList(chNo, userID,
							listNo, &nRec);
			break;
		default:
			seqNo = GetNo(cmd);
			if (seqNo >= 1 && seqNo <= nRec) {
				QuestionRead(chNo, userID, seqNo, TRUE);
				listNo = seqNo;
			} else
				SendInputErrMsg(chNo);
			break;
		}
	}
}

/*=======================================================================
 |
 |		質問送信処理
 |
 |	VOID	QuestionSend(chNo)
 |
 |		USHORT	chNo;		チャネル番号
 |
 =======================================================================*/
static	VOID	QuestionSend(USHORT chNo)
{
	CHAR	fileName[13], pathName[40], title[41];

	MakeMailFile(fileName, pathName);
	if (ReceiveTextFile(chNo, pathName, title,
				"質問内容を入力して下さい。"))
		SendMail(cUserID[chNo], sv.env.systemID, title, fileName, 'Q');
	else
		DosDelete(pathName, 0L);
}

/*=======================================================================
 |
 |		質問内容表示処理
 |
 |	VOID	QuestionRead(chNo, userID, No, flag)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*userID;	ユーザＩＤ
 |		SHORT	No;		表示質問番号
 |		BOOL	flag;		一括表示フラグ
 |
 =======================================================================*/
static	VOID	QuestionRead(USHORT chNo, CHAR *userID, SHORT No, BOOL flag)
{
	MAILDBF	mailData;
	CHAR	title[41], fileName[13], pathName[40], msg[80];

	DBLock(mailDBp);
	DBSearch(mailDBp, userID, 10, indexMailS2);
	DBSkip(mailDBp, (long)(No-1));
	DBRead(mailDBp, &mailData);
	DBRelease(mailDBp);

	CopyFromDBF(title, mailData.title, sizeof(mailData.title));
	sprintf(msg, "タイトル： %s", title);
	SendStr(chNo, msg, 2);

	CopyFromDBF(fileName, mailData.fileName, sizeof(mailData.fileName));
	sprintf(pathName, "%s\\%s", dnMAIL, fileName);
	SendTextFile(chNo, pathName, flag);
}

/*=======================================================================
 |
 |		質問一覧表示処理
 |
 |	SHORT	QuestionList(chNo, userID, listNo, nRec)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*userID;	ユーザＩＤ
 |		SHORT	lintNo;		表示先頭番号
 |		SHORT	*nRec;		質問件数
 |
 |		SHORT	返値		次回表示番号
 |
 =======================================================================*/
static	SHORT	QuestionList(USHORT chNo, CHAR *userID,
						SHORT listNo, SHORT *nRec)
{
	CHAR	msgBuf[82], title[41];
	MAILDBF	mailData;
	SHORT	i;

	DBLock(mailDBp);

	*nRec = (SHORT)DBCount(mailDBp, userID, 10, indexMailS2);
	if (*nRec == 0)
		SendStr(chNo, "送信済みの質問はありません。", 2);
	else {
		sprintf(msgBuf, "番号  送信日付  受信日付  タイトル    送信メール数： %d", *nRec);
		SendStr(chNo, msgBuf, 2);
		if (listNo == 0)
			listNo = *nRec;
		DBSearch(mailDBp, userID, 10, indexMailS2);
		DBSkip(mailDBp, (long)(listNo-1));
		for (i = 0; i < 17; i++) {
			if (listNo <= 0)
				break;
			if (DBBof(mailDBp))
				break;
			DBRead(mailDBp, &mailData);
			CopyFromDBF(title, mailData.title,
					sizeof(mailData.title));
			sprintf(msgBuf, "%04d  %.8s  %.8s  %s",
					listNo, mailData.sDate, mailData.rDate,
					title);
			SendStr(chNo, msgBuf, 1);
			DBSkip(mailDBp, -1L);
			listNo--;
		}
		SendCRLF(chNo, 1);
	}

	DBRelease(mailDBp);

	return(listNo);
}

/*=======================================================================
 |
 |		質問削除処理
 |
 |	BOOL	QuestionDelete(chNo, userID, No)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*userID;	ユーザＩＤ
 |		SHORT	No;		削除質問番号
 |
 |		BOOL	返値		TRUE ：削除した
 |					FALSE：削除中止
 |
 =======================================================================*/
static	BOOL	QuestionDelete(USHORT chNo, CHAR *userID, SHORT No)
{
	CHAR	msg[80];

	sprintf(msg, "[%04d] を削除しますか？ (Y/N)", No);
	if (YESorNO(chNo, msg) == FALSE)
		return(FALSE);

	DBLock(mailDBp);
	if (DBSearch(mailDBp, userID, 10, indexMailS2)) {
		DBSkip(mailDBp, (long)(No-1));
		DeleteSMail();
	}
	DBRelease(mailDBp);

	SendStr(chNo, "削除しました。", 2);

	return(TRUE);
}

/*=======================================================================
 |
 |		回答処理
 |
 |	VOID	Answer(chNo, name)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*name;		コーナー名
 |
 =======================================================================*/
static	VOID	Answer(USHORT chNo, CHAR *name)
{
	CHAR	userID[10];
	BOOL	loopSW;
	CHAR	cmd[6];
	SHORT	nRec, seqNo, listNo;

	CopyToDBF(userID, cUserID[chNo], 8);
	userID[8] = 'A';
	userID[9] = ' ';

	listNo = AnswerList(chNo, userID, 0, &nRec);

	loopSW = TRUE;
	while (loopSW) {
		RcvMsg(chNo,
			"番号またはコマンド(Dn,E,H,L,Q,Rn)を入力して下さい。",
			name, cmd, sizeof(cmd));
		switch (toupper(cmd[0])) {
		case 'D':
			seqNo = GetNo(cmd+1);
			if (seqNo >= 1 && seqNo <= nRec) {
				if (AnswerDelete(chNo, userID, seqNo) == TRUE)
					listNo = AnswerList(chNo, userID,
								0, &nRec);
			} else
				SendInputErrMsg(chNo);
			break;
		case 'E':
			loopSW = FALSE;
			break;
		case 'H':
			DspHelpMsg(chNo, "1DEHLQRS");
			break;
		case 'L':
			if (cmd[1] != '\0') {
				seqNo = GetNo(cmd+1);
				if (seqNo >= 1 && seqNo <= nRec)
					listNo = AnswerList(chNo, userID,
								seqNo, &nRec);
				else
					SendInputErrMsg(chNo);
			} else
				listNo = AnswerList(chNo, userID, 0, &nRec);
			break;
		case 'Q':
			Quit(chNo);
			break;
		case 'R':
			seqNo = GetNo(cmd+1);
			if (seqNo >= 1 && seqNo <= nRec) {
				AnswerRead(chNo, userID, seqNo, FALSE);
				listNo = seqNo;
			} else
				SendInputErrMsg(chNo);
			break;
		case '\0':
			if (listNo >= 1)
				listNo = AnswerList(chNo, userID,
							listNo, &nRec);
			break;
		default:
			seqNo = GetNo(cmd);
			if (seqNo >= 1 && seqNo <= nRec) {
				AnswerRead(chNo, userID, seqNo, TRUE);
				listNo = seqNo;
			} else
				SendInputErrMsg(chNo);
			break;
		}
	}
}

/*=======================================================================
 |
 |		回答内容表示処理
 |
 |	VOID	AnswerRead(chNo, userID, No, flag)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*userID;	ユーザＩＤ
 |		SHORT	No;		表示回答番号
 |		BOOL	flag;		一括表示フラグ
 |
 =======================================================================*/
static	VOID	AnswerRead(USHORT chNo, CHAR *userID, SHORT No, BOOL flag)
{
	MAILDBF	mailData;
	CHAR	title[41], fileName[13], pathName[40], msg[80];

	DBLock(mailDBp);
	DBSearch(mailDBp, userID, 10, indexMailR2);
	DBSkip(mailDBp, (long)(No-1));
	SetMailReadDate();
	DBRead(mailDBp, &mailData);
	DBRelease(mailDBp);

	CopyFromDBF(title, mailData.title, sizeof(mailData.title));
	sprintf(msg, "タイトル： %s", title);
	SendStr(chNo, msg, 2);

	CopyFromDBF(fileName, mailData.fileName, sizeof(mailData.fileName));
	sprintf(pathName, "%s\\%s", dnMAIL, fileName);
	SendTextFile(chNo, pathName, flag);
}

/*=======================================================================
 |
 |		回答一覧表示処理
 |
 |	SHORT	AnswerList(chNo, userID, listNo, nRec)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*userID;	ユーザＩＤ
 |		SHORT	lintNo;		表示先頭番号
 |		SHORT	*nRec;		回答件数
 |
 |		SHORT	返値		次回表示番号
 |
 =======================================================================*/
static	SHORT	AnswerList(USHORT chNo, CHAR *userID,
						SHORT listNo, SHORT *nRec)
{
	CHAR	msgBuf[82], title[41];
	MAILDBF	mailData;
	SHORT	i;

	DBLock(mailDBp);

	*nRec = (SHORT)DBCount(mailDBp, userID, 10, indexMailR2);
	if (*nRec == 0)
		SendStr(chNo, "回答はありません。", 2);
	else {
		sprintf(msgBuf, "番号  送信日付  受信日付  タイトル    受信メール数： %d", *nRec);
		SendStr(chNo, msgBuf, 2);
		if (listNo == 0)
			listNo = *nRec;
		DBSearch(mailDBp, userID, 10, indexMailR2);
		DBSkip(mailDBp, (long)(listNo-1));
		for (i = 0; i < 17; i++) {
			if (listNo <= 0)
				break;
			if (DBBof(mailDBp))
				break;
			DBRead(mailDBp, &mailData);
			CopyFromDBF(title, mailData.title,
					sizeof(mailData.title));
			sprintf(msgBuf, "%04d  %.8s  %.8s  %s",
					listNo, mailData.sDate, mailData.rDate,
					title);
			SendStr(chNo, msgBuf, 1);
			DBSkip(mailDBp, -1L);
			listNo--;
		}
		SendCRLF(chNo, 1);
	}
	DBRelease(mailDBp);

	return(listNo);
}

/*=======================================================================
 |
 |		回答削除処理
 |
 |	BOOL	AnswerDelete(chNo, userID, No)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*userID;	ユーザＩＤ
 |		SHORT	No;		削除回答番号
 |
 |		BOOL	返値		TRUE ：削除した
 |					FALSE：削除中止
 |
 =======================================================================*/
static	BOOL	AnswerDelete(USHORT chNo, CHAR *userID, SHORT No)
{
	CHAR	msg[80];

	sprintf(msg, "[%04d] を削除しますか？ (Y/N)", No);
	if (YESorNO(chNo, msg) == FALSE)
		return(FALSE);

	DBLock(mailDBp);
	if (DBSearch(mailDBp, userID, 10, indexMailR2)) {
		DBSkip(mailDBp, (long)(No-1));
		DeleteRMail();
	}
	DBRelease(mailDBp);

	SendStr(chNo, "削除しました。", 2);

	return(TRUE);
}

/*=======================================================================
 |
 |		送信メール処理
 |
 |	VOID	SndMail(chNo, name)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*name;		コーナー名
 |
 =======================================================================*/
static	VOID	SndMail(USHORT chNo, CHAR *name)
{
	CHAR	userID[10];
	BOOL	loopSW;
	CHAR	cmd[6];
	SHORT	nRec, seqNo, listNo;

	CopyToDBF(userID, cUserID[chNo], 8);
	userID[8] = 'M';
	userID[9] = ' ';

	listNo = SndMailList(chNo, userID, 0, &nRec);

	loopSW = TRUE;
	while (loopSW) {
		RcvMsg(chNo,
		    "番号またはコマンド(Dn,E,H,L,Q,Rn,S)を入力して下さい。",
			name, cmd, sizeof(cmd));
		switch (toupper(cmd[0])) {
		case 'D':
			seqNo = GetNo(cmd+1);
			if (seqNo >= 1 && seqNo <= nRec) {
				if (SndMailDelete(chNo, userID,
							seqNo) == TRUE)
					listNo = SndMailList(chNo, userID,
								0, &nRec);
			} else
				SendInputErrMsg(chNo);
			break;
		case 'E':
			loopSW = FALSE;
			break;
		case 'H':
			DspHelpMsg(chNo, "1DEHLQRS");
			break;
		case 'L':
			if (cmd[1] != '\0') {
				seqNo = GetNo(cmd+1);
				if (seqNo >= 1 && seqNo <= nRec)
					listNo = SndMailList(chNo, userID,
								seqNo, &nRec);
				else
					SendInputErrMsg(chNo);
			} else
				listNo = SndMailList(chNo, userID, 0, &nRec);
			break;
		case 'Q':
			Quit(chNo);
			break;
		case 'R':
			seqNo = GetNo(cmd+1);
			if (seqNo >= 1 && seqNo <= nRec) {
				SndMailRead(chNo, userID, seqNo, FALSE);
				listNo = seqNo;
			} else
				SendInputErrMsg(chNo);
			break;
		case 'S':
			MailSend(chNo);
			listNo = SndMailList(chNo, userID, 0, &nRec);
			break;
		case '\0':
			if (listNo >= 1)
				listNo = SndMailList(chNo, userID,
							listNo, &nRec);
			break;
		default:
			seqNo = GetNo(cmd);
			if (seqNo >= 1 && seqNo <= nRec) {
				SndMailRead(chNo, userID, seqNo, TRUE);
				listNo = seqNo;
			} else
				SendInputErrMsg(chNo);
			break;
		}
	}
}

/*=======================================================================
 |
 |		メール送信処理
 |
 |	VOID	MailSend(chNo)
 |
 |		USHORT	chNo;		チャネル番号
 |
 =======================================================================*/
static	VOID	MailSend(USHORT chNo)
{
	CHAR	fileName[13], pathName[40], title[41];
	USERDBF	userData;
	CHAR	rUserID[9];
	CHAR	msgBuf[80];
	CHAR	name[17];
	CHAR	type[2];
	BOOL	sysID;

	sysID = !strcmp(cUserID[chNo], sv.env.systemID);

	for (;;) {
		SendStr(chNo, "宛先のユーザＩＤを入力して下さい。（中止はリターンのみ入力して下さい）", 1);
		SendStr(chNo, "==>", 0);
		ReceiveStr(chNo, rUserID, sizeof(rUserID));
		if (rUserID[0] == '\0')
			return;
		if (ReadUserData(rUserID, &userData) == FALSE) {
			sprintf(msgBuf, "[%s]は登録されていません。", rUserID);
			SendStr(chNo, msgBuf, 2);
		} else {
			CopyFromDBF(name, userData.name,
					sizeof(userData.name));
			sprintf(msgBuf, "宛先の名前は【%s】です。よろしいですか？ (Y/N)", name);
			if (YESorNO(chNo, msgBuf) == TRUE)
				break;
			else
				SendCRLF(chNo, 1);
		}
	}

	if (sysID) {
		for (;;) {
			SendStr(chNo, "種別を入力して下さい（M:電子メール，A:回答）==>", 0);
			ReceiveStr(chNo, type, sizeof(type));
			type[0] = (CHAR)toupper(type[0]);
			if (type[0] == '\0')
				return;
			if (type[0] == 'M' || type[0] == 'A')
				break;
		}
	} else
		type[0] = 'M';

	MakeMailFile(fileName, pathName);
	if (ReceiveTextFile(chNo, pathName, title,
				"送信する内容を入力して下さい。"))
		SendMail(cUserID[chNo], rUserID, title, fileName, type[0]);
	else
		DosDelete(pathName, 0L);
}

/*=======================================================================
 |
 |		送信メール内容表示処理
 |
 |	VOID	SndMailRead(chNo, userID, No, flag)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*userID;	ユーザＩＤ
 |		SHORT	No;		表示メール番号
 |		BOOL	flag;		一括表示フラグ
 |
 =======================================================================*/
static	VOID	SndMailRead(USHORT chNo, CHAR *userID, SHORT No, BOOL flag)
{
	MAILDBF	mailData;
	CHAR	title[41], fileName[13], pathName[40], msgBuf[80];
	CHAR	rUserID[9];
	CHAR	name[17];
	USERDBF	userData;
	BOOL	sysID;
	SHORT	slen;

	sysID = !strcmp(cUserID[chNo], sv.env.systemID);
	slen = sysID ? 8 : 10;

	DBLock(mailDBp);
	DBSearch(mailDBp, userID, slen, indexMailS2);
	DBSkip(mailDBp, (long)(No-1));
	DBRead(mailDBp, &mailData);
	DBRelease(mailDBp);

	sprintf(msgBuf, "送信日付 ： %.17s", mailData.sDate);
	SendStr(chNo, msgBuf, 1);
	sprintf(msgBuf, "受信日付 ： %.17s", mailData.rDate);
	SendStr(chNo, msgBuf, 2);

	CopyFromDBF(rUserID, mailData.rUserID, sizeof(mailData.rUserID));
	if (ReadUserData(rUserID, &userData) == TRUE)
		CopyFromDBF(name, userData.name, sizeof(userData.name));
	else
		name[0] = '\0';
	sprintf(msgBuf, "受信者   ： [%s] %s", rUserID, name);
	SendStr(chNo, msgBuf, 2);

	CopyFromDBF(title, mailData.title, sizeof(mailData.title));
	sprintf(msgBuf, "タイトル ： %s", title);
	SendStr(chNo, msgBuf, 2);

	CopyFromDBF(fileName, mailData.fileName, sizeof(mailData.fileName));
	sprintf(pathName, "%s\\%s", dnMAIL, fileName);
	SendTextFile(chNo, pathName, flag);
}

/*=======================================================================
 |
 |		送信メール一覧表示処理
 |
 |	SHORT	SndMailList(chNo, userID, listNo, nRec)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*userID;	ユーザＩＤ
 |		SHORT	lintNo;		表示先頭番号
 |		SHORT	*nRec;		送信メール件数
 |
 |		SHORT	返値		次回表示番号
 |
 =======================================================================*/
static	SHORT	SndMailList(USHORT chNo, CHAR *userID,
						SHORT listNo, SHORT *nRec)
{
	CHAR	msgBuf[82], title[41];
	MAILDBF	mailData;
	SHORT	i;
	BOOL	sysID;
	SHORT	slen;

	sysID = !strcmp(cUserID[chNo], sv.env.systemID);
	slen = sysID ? 8 : 10;

	DBLock(mailDBp);

	*nRec = (SHORT)DBCount(mailDBp, userID, slen, indexMailS2);
	if (*nRec == 0)
		SendStr(chNo, "送信済みのメールはありません。", 2);
	else {
		sprintf(msgBuf, "番号  送信日付  受信日付  宛先ＩＤ  タイトル    送信メール数： %d", *nRec);
		SendStr(chNo, msgBuf, 2);
		if (listNo == 0)
			listNo = *nRec;
		DBSearch(mailDBp, userID, slen, indexMailS2);
		DBSkip(mailDBp, (long)(listNo-1));
		for (i = 0; i < 17; i++) {
			if (listNo <= 0)
				break;
			if (DBBof(mailDBp))
				break;
			DBRead(mailDBp, &mailData);
			CopyFromDBF(title, mailData.title,
					sizeof(mailData.title));
			if (sysID)
				sprintf(msgBuf,
					"%04d  %.8s  %.8s  %.8s  %c%c %s",
					listNo, mailData.sDate, mailData.rDate,
					mailData.rUserID, mailData.type[0],
					mailData.sDelete[0], title);
			else
				sprintf(msgBuf,
					"%04d  %.8s  %.8s  %.8s  %s",
					listNo, mailData.sDate, mailData.rDate,
					mailData.rUserID, title);
			SendStr(chNo, msgBuf, 1);
			DBSkip(mailDBp, -1L);
			listNo--;
		}
		SendCRLF(chNo, 1);
	}

	DBRelease(mailDBp);

	return(listNo);
}

/*=======================================================================
 |
 |		送信メール削除処理
 |
 |	BOOL	SndMailDelete(chNo, userID, No)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*userID;	ユーザＩＤ
 |		SHORT	No;		削除送信メール番号
 |
 |		BOOL	返値		TRUE ：削除した
 |					FALSE：削除中止
 |
 =======================================================================*/
static	BOOL	SndMailDelete(USHORT chNo, CHAR *userID, SHORT No)
{
	CHAR	msg[80];
	BOOL	sysID;
	SHORT	slen;

	sysID = !strcmp(cUserID[chNo], sv.env.systemID);
	slen = sysID ? 8 : 10;

	sprintf(msg, "[%04d] を削除しますか？ (Y/N)", No);
	if (YESorNO(chNo, msg) == FALSE)
		return(FALSE);

	DBLock(mailDBp);
	if (DBSearch(mailDBp, userID, slen, indexMailS2)) {
		DBSkip(mailDBp, (long)(No-1));
		DeleteSMail();
	}
	DBRelease(mailDBp);

	SendStr(chNo, "削除しました。", 2);

	return(TRUE);
}

/*=======================================================================
 |
 |		受信メール処理
 |
 |	VOID	RcvMail(chNo, name)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*name;		コーナー名
 |
 =======================================================================*/
static	VOID	RcvMail(USHORT chNo, CHAR *name)
{
	CHAR	userID[10];
	BOOL	loopSW;
	CHAR	cmd[6];
	SHORT	nRec, seqNo, listNo;

	CopyToDBF(userID, cUserID[chNo], 8);
	userID[8] = 'M';
	userID[9] = ' ';

	listNo = RcvMailList(chNo, userID, 0, &nRec);

	loopSW = TRUE;
	while (loopSW) {
		RcvMsg(chNo,
			"番号またはコマンド(Dn,E,H,L,Q,Rn)を入力して下さい。",
			name, cmd, sizeof(cmd));
		switch (toupper(cmd[0])) {
		case 'D':
			seqNo = GetNo(cmd+1);
			if (seqNo >= 1 && seqNo <= nRec) {
				if (RcvMailDelete(chNo, userID, seqNo) == TRUE)
					listNo = RcvMailList(chNo, userID,
								0, &nRec);
			} else
				SendInputErrMsg(chNo);
			break;
		case 'E':
			loopSW = FALSE;
			break;
		case 'H':
			DspHelpMsg(chNo, "1DEHLQR");
			break;
		case 'L':
			if (cmd[1] != '\0') {
				seqNo = GetNo(cmd+1);
				if (seqNo >= 1 && seqNo <= nRec)
					listNo = RcvMailList(chNo, userID,
								seqNo, &nRec);
				else
					SendInputErrMsg(chNo);
			} else
				listNo = RcvMailList(chNo, userID, 0, &nRec);
			break;
		case 'Q':
			Quit(chNo);
			break;
		case 'R':
			seqNo = GetNo(cmd+1);
			if (seqNo >= 1 && seqNo <= nRec) {
				RcvMailRead(chNo, userID, seqNo, FALSE);
				listNo = seqNo;
			} else
				SendInputErrMsg(chNo);
			break;
		case '\0':
			if (listNo >= 1)
				listNo = RcvMailList(chNo, userID,
							listNo, &nRec);
			break;
		default:
			seqNo = GetNo(cmd);
			if (seqNo >= 1 && seqNo <= nRec) {
				RcvMailRead(chNo, userID, seqNo, TRUE);
				listNo = seqNo;
			} else
				SendInputErrMsg(chNo);
			break;
		}
	}
}

/*=======================================================================
 |
 |		受信メール内容表示処理
 |
 |	VOID	RcvMailRead(chNo, userID, No, flag)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*userID;	ユーザＩＤ
 |		SHORT	No;		表示メール番号
 |		BOOL	flag;		一括表示フラグ
 |
 =======================================================================*/
static	VOID	RcvMailRead(USHORT chNo, CHAR *userID, SHORT No, BOOL flag)
{
	MAILDBF	mailData;
	CHAR	title[41], fileName[13], pathName[40], msgBuf[80];
	CHAR	sUserID[9];
	CHAR	name[17];
	USERDBF	userData;
	BOOL	sysID;
	SHORT	slen;

	sysID = !strcmp(cUserID[chNo], sv.env.systemID);
	slen = sysID ? 8 : 10;

	DBLock(mailDBp);
	DBSearch(mailDBp, userID, slen, indexMailR2);
	DBSkip(mailDBp, (long)(No-1));
	SetMailReadDate();
	DBRead(mailDBp, &mailData);
	DBRelease(mailDBp);

	sprintf(msgBuf, "送信日付 ： %.17s", mailData.sDate);
	SendStr(chNo, msgBuf, 1);
	sprintf(msgBuf, "受信日付 ： %.17s", mailData.rDate);
	SendStr(chNo, msgBuf, 2);

	CopyFromDBF(sUserID, mailData.sUserID, sizeof(mailData.sUserID));
	if (ReadUserData(sUserID, &userData) == TRUE)
		CopyFromDBF(name, userData.name, sizeof(userData.name));
	else
		name[0] = '\0';
	sprintf(msgBuf, "送信者   ： [%s] %s", sUserID, name);
	SendStr(chNo, msgBuf, 2);

	CopyFromDBF(title, mailData.title, sizeof(mailData.title));
	sprintf(msgBuf, "タイトル ： %s", title);
	SendStr(chNo, msgBuf, 2);

	CopyFromDBF(fileName, mailData.fileName, sizeof(mailData.fileName));
	sprintf(pathName, "%s\\%s", dnMAIL, fileName);
	SendTextFile(chNo, pathName, flag);
}

/*=======================================================================
 |
 |		受信メール一覧表示処理
 |
 |	SHORT	RcvMailList(chNo, userID, listNo, nRec)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*userID;	ユーザＩＤ
 |		SHORT	lintNo;		表示先頭番号
 |		SHORT	*nRec;		受信メール件数
 |
 |		SHORT	返値		次回表示番号
 |
 =======================================================================*/
static	SHORT	RcvMailList(USHORT chNo, CHAR *userID,
						SHORT listNo, SHORT *nRec)
{
	CHAR	msgBuf[82], title[41];
	MAILDBF	mailData;
	SHORT	i;
	BOOL	sysID;
	SHORT	slen;

	sysID = !strcmp(cUserID[chNo], sv.env.systemID);
	slen = sysID ? 8 : 10;

	DBLock(mailDBp);

	*nRec = (SHORT)DBCount(mailDBp, userID, slen, indexMailR2);
	if (*nRec == 0)
		SendStr(chNo, "受信メールはありません。", 2);
	else {
		sprintf(msgBuf, "番号  送信日付  受信日付  送信者ＩＤ  タイトル    受信メール数： %d", *nRec);
		SendStr(chNo, msgBuf, 2);
		if (listNo == 0)
			listNo = *nRec;
		DBSearch(mailDBp, userID, slen, indexMailR2);
		DBSkip(mailDBp, (long)(listNo-1));
		for (i = 0; i < 17; i++) {
			if (listNo <= 0)
				break;
			if (DBBof(mailDBp))
				break;
			DBRead(mailDBp, &mailData);
			CopyFromDBF(title, mailData.title,
					sizeof(mailData.title));
			if (sysID)
				sprintf(msgBuf,
					"%04d  %.8s  %.8s   %.8s   %c%c %s",
					listNo, mailData.sDate, mailData.rDate,
					mailData.sUserID, mailData.type[0],
					mailData.rDelete[0], title);
			else
				sprintf(msgBuf,
					"%04d  %.8s  %.8s   %.8s   %s",
					listNo, mailData.sDate, mailData.rDate,
					mailData.sUserID, title);
			SendStr(chNo, msgBuf, 1);
			DBSkip(mailDBp, -1L);
			listNo--;
		}
		SendCRLF(chNo, 1);
	}
	DBRelease(mailDBp);

	return(listNo);
}

/*=======================================================================
 |
 |		受信メール削除処理
 |
 |	BOOL	RcvMailDelete(chNo, userID, No)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*userID;	ユーザＩＤ
 |		SHORT	No;		削除受信メール番号
 |
 |		BOOL	返値		TRUE ：削除した
 |					FALSE：削除中止
 |
 =======================================================================*/
static	BOOL	RcvMailDelete(USHORT chNo, CHAR *userID, SHORT No)
{
	CHAR	msg[80];
	BOOL	sysID;
	SHORT	slen;

	sysID = !strcmp(cUserID[chNo], sv.env.systemID);
	slen = sysID ? 8 : 10;

	sprintf(msg, "[%04d] を削除しますか？ (Y/N)", No);
	if (YESorNO(chNo, msg) == FALSE)
		return(FALSE);

	DBLock(mailDBp);
	if (DBSearch(mailDBp, userID, slen, indexMailR2)) {
		DBSkip(mailDBp, (long)(No-1));
		DeleteRMail();
	}
	DBRelease(mailDBp);

	SendStr(chNo, "削除しました。", 2);

	return(TRUE);
}

/*=======================================================================
 |
 |		パスワード変更処理
 |
 |	VOID	PassWord(chNo, name)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*name;		コーナー名
 |
 =======================================================================*/
static	VOID	PassWord(USHORT chNo, CHAR *name)
{
	CHAR	oldPassWord[9], newPassWord[9], chkPassWord[9];
	USERDBF	userData;

retry1:
	SendStr(chNo, "現在のパスワードを入力して下さい ==>", 0);
	ReceiveStr(chNo, oldPassWord, sizeof(oldPassWord));
	SendCRLF(chNo, 1);
	if (CheckPassWord(cUserID[chNo], oldPassWord) == FALSE) {
		SendStr(chNo, "パスワードが違います。", 2);
		goto retry1;
	}
retry2:
	SendStr(chNo, "新しいパスワードを入力して下さい ==>", 0);
	ReceiveStr(chNo, newPassWord, sizeof(newPassWord));
	SendCRLF(chNo, 1);
	SendStr(chNo, "もう一度新しいパスワードを入力して下さい ==>", 0);
	ReceiveStr(chNo, chkPassWord, sizeof(chkPassWord));
	SendCRLF(chNo, 1);
	if (strcmp(newPassWord, chkPassWord) != 0) {
		SendStr(chNo, "パスワードが違います。", 2);
		goto retry2;
	}

	DBLock(userDBp);
	if (ReadUserData(cUserID[chNo], &userData) != FALSE) {
		CopyToDBF(userData.password, newPassWord,
					sizeof(userData.password));
		DBUpdate(userDBp, (char *)&userData);
	}
	DBRelease(userDBp);

	SendStr(chNo, "パスワードを変更しました。", 2);
}

/*=======================================================================
 |
 |		資料請求処理
 |
 |	VOID	Catalog(chNo, name, title)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*name;		コーナー名
 |		CHAR	*title;		メールタイトル
 |
 =======================================================================*/
static	VOID	Catalog(USHORT chNo, CHAR *name, CHAR *title)
{
	static	CHAR	*msg[] = {
		"郵便番号", "住所    ", "会社名  ",
		"部課名  ", "氏名    "
	};
	CHAR	inputBuf[5][61], msgBuf[80], No[2];
	CHAR	fileName[13], pathName[40];
	SHORT	i;
	FILE	*fp;

	SendStr(chNo, "（中止は E を入力して下さい）", 2);

	for (i = 0; i < 5; i++) {
		sprintf(msgBuf, "%s ==>", msg[i]);
		SendStr(chNo, msgBuf, 0);
		ReceiveStr(chNo, inputBuf[i], 60);
		if (strcmp(inputBuf[i], "E") == 0
				|| strcmp(inputBuf[i], "e") == 0)
			return;
	}

	for (;;) {
		SendCRLF(chNo, 1);
		SendStr(chNo, "お送り先", 1);
		for (i = 0; i < 5; i++) {
			sprintf(msgBuf, "  %d.%s：%s",
						i+1, msg[i], inputBuf[i]);
			SendStr(chNo, msgBuf, 1);
		}

		SendCRLF(chNo, 1);
		SendStr(chNo, "以上でよろしいですか？", 1);
		SendStr(chNo, "修正がある場合はその番号を、無い場合は Y を入力して下さい ==>", 0);
		ReceiveStr(chNo, No, sizeof(No));
		if (No[0] == 'Y' || No[0] == 'y')
			break;
		else if (No[0] >= '1' && No[0] <= '5') {
			i = No[0] - '1';
			sprintf(msgBuf, "%s ==>", msg[i]);
			SendStr(chNo, msgBuf, 0);
			ReceiveStr(chNo, inputBuf[i], 60);
		} else if (No[0] == 'E' || No[0] == 'e')
			return;
	}

	MakeMailFile(fileName, pathName);
	if ((fp = OpenFile(chNo, pathName, "w")) == NULL) {
		SendSysErrMsg(chNo);
		return;
	}
	fprintf(fp, "%s [%s]\n\n", title, cUserID[chNo]);
	for (i = 0; i < 5; i++)
		fprintf(fp, "  %s：%s\n", msg[i], inputBuf[i]);
	CloseFile(chNo);
	SendMail(cUserID[chNo], sv.env.systemID, title, fileName, 'C');

	SendCRLF(chNo, 1);
	SendStr(chNo, "資料のご請求ありがとうございました。早速お送り致します。", 2);
}

/*=======================================================================
 |
 |		メッセージ受信処理
 |
 |	VOID	RcvMsg(chNo, msg, name, buf, bufSize)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*msg;		送信メッセージ
 |		CHAR	*name;		コーナー名
 |		CHAR	*buf;		受信バッファ
 |		SHORT	bufSize;	受信バッファサイズ
 |
 =======================================================================*/
static	VOID	RcvMsg(USHORT chNo, CHAR *msg, CHAR *name,
						CHAR *buf, SHORT bufSize)
{
	CHAR	prompt[20];

	SendStr(chNo, msg, 1);
	sprintf(prompt, "[%s]", name);
	SendStr(chNo, prompt, 0);
	ReceiveStr(chNo, buf, bufSize);
	SendCRLF(chNo, 1);
}

/*=======================================================================
 |
 |		テキスト掲示ファイル一覧表示処理
 |
 |	SHORT	DspBBSList(chNo, dbp, listNo, fListSeq)
 |
 |		USHORT	chNo;		チャネル番号
 |		DB	*dbp;		データベースポインタ
 |		SHORT	listNo;		表示先頭番号
 |		BOOL	fListSeq;	表示順序
 |
 |		SHORT	返値		次回表示番号
 |
 =======================================================================*/
static	SHORT	DspBBSList(USHORT chNo, DB *dbp, SHORT listNo, BOOL fListSeq)
{
	CHAR	msgBuf[82], title[41];
	BBSDBF	BBSData;
	SHORT	i;

	if (listNo != -1) {
		if (fListSeq) {
			if (listNo > (SHORT)DBRecCount(dbp))
				return(listNo);
		} else {
			if (listNo == 0)
				return(listNo);
		}
	}

	sprintf(msgBuf, "番号  登録日付  タイトル      登録メッセージ数： %ld",
							DBRecCount(dbp));
	SendStr(chNo, msgBuf, 2);

	if (listNo == -1)
		listNo = fListSeq ? 1 : (SHORT)DBRecCount(dbp);

	DBLock(dbp);
	DBTop(dbp);
	DBSkip(dbp, listNo - 1);
	for (i = 0; i < 17; i++) {
		if (DBBof(dbp) || DBEof(dbp))
			break;
		DBRead(dbp, &BBSData);
		CopyFromDBF(title, BBSData.title, sizeof(BBSData.title));
		sprintf(msgBuf, "%04d  %.8s  %s",
					listNo, BBSData.date, title);
		SendStr(chNo, msgBuf, 1);
		if (fListSeq) {
			DBSkip(dbp, 1L);
			listNo++;
		} else {
			DBSkip(dbp, -1L);
			listNo--;
		}
	}
	DBRelease(dbp);

	SendCRLF(chNo, 1);

	return(listNo);
}

/*=======================================================================
 |
 |		バイナリ掲示ファイル一覧表示処理
 |
 |	SHORT	DspPGMList(chNo, dbp, listNo, fListSeq)
 |
 |		USHORT	chNo;		チャネル番号
 |		DB	*dbp;		データベースポインタ
 |		SHORT	listNo;		表示先頭番号
 |		BOOL	fListSeq;	表示順序
 |
 |		SHORT	返値		次回表示番号
 |
 =======================================================================*/
static	SHORT	DspPGMList(USHORT chNo, DB *dbp, SHORT listNo, BOOL fListSeq)
{
	CHAR	msgBuf[82], title[41];
	BBSDBF	BBSData;
	SHORT	i;

	if (listNo != -1) {
		if (fListSeq) {
			if (listNo > (SHORT)DBRecCount(dbp))
				return(listNo);
		} else {
			if (listNo == 0)
				return(listNo);
		}
	}

	sprintf(msgBuf, "番号  ファイル名    サイズ   登録日付  タイトル      登録ファイル数： %ld", DBRecCount(dbp));
	SendStr(chNo, msgBuf, 2);

	if (listNo == -1)
		listNo = fListSeq ? 1 : (SHORT)DBRecCount(dbp);

	DBLock(dbp);
	DBTop(dbp);
	DBSkip(dbp, listNo - 1);
	for (i = 0; i < 17; i++) {
		if (DBBof(dbp) || DBEof(dbp))
			break;
		DBRead(dbp, &BBSData);
		CopyFromDBF(title, BBSData.title, sizeof(BBSData.title));
		sprintf(msgBuf, "%04d  %.12s %.8s  %.8s  %s",
				listNo, BBSData.name, BBSData.size,
				BBSData.date, title);
		SendStr(chNo, msgBuf, 1);
		if (fListSeq) {
			DBSkip(dbp, 1L);
			listNo++;
		} else {
			DBSkip(dbp, -1L);
			listNo--;
		}
	}
	DBRelease(dbp);

	SendCRLF(chNo, 1);

	return(listNo);
}

/*=======================================================================
 |
 |		テキスト受信処理
 |
 |	BOOL	ReceiveTextFile(chNo, fileName, title, guideMsg)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*fileName;	格納ファイル名
 |		CHAR	*title;		タイトル
 |		CHAR	*guideMsg;	ガイドメッセージ
 |
 |		BOOL	返値		TRUE ：受信した
 |					FALSE：中止
 |
 =======================================================================*/
static	BOOL	ReceiveTextFile(USHORT chNo, CHAR *fileName, CHAR *title,
							CHAR *guideMsg)
{
	CHAR	rcvBuf[1024];
	FILE	*fp;

	SendStr(chNo, guideMsg, 1);
	SendStr(chNo, "（１行目はタイトル、終了は/Eを入力して下さい）", 2);

	ReceiveStr(chNo, title, 41);
	if (title[0] == '\0' ||
			strcmp(title, "/E") == 0 || strcmp(title, "/e") == 0)
		return(FALSE);

	if ((fp = OpenFile(chNo, fileName, "w")) == NULL) {
		SendSysErrMsg(chNo);
		return(FALSE);
	}

	for (;;) {
		ReceiveStr(chNo, rcvBuf, sizeof(rcvBuf));
		if (strcmp(rcvBuf, "/E") == 0 || strcmp(rcvBuf, "/e") == 0)
			break;
		fprintf(fp, "%s\n", rcvBuf);
	}

	CloseFile(chNo);

	for (;;) {
		SendCRLF(chNo, 1);
		SendStr(chNo, "１．送信する  ２．中止する ==>", 0);
		ReceiveStr(chNo, rcvBuf, 2);
		if (rcvBuf[0] == '1') {
			SendCRLF(chNo, 1);
			SendStr(chNo, "送信しました。", 2);
			return(TRUE);
		} else if (rcvBuf[0] == '2') {
			SendCRLF(chNo, 1);
			SendStr(chNo, "中止しました。", 2);
			return(FALSE);
		}
	}
}

/*=======================================================================
 |
 |		バイナリファイル受信処理
 |
 |	BOOL	ReceiveBinaryFile(chNo, fileName)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*fileName;	格納ファイル名
 |
 |		BOOL	返値		TRUE ：受信した
 |					FALSE：受信失敗
 |
 =======================================================================*/
static	BOOL	ReceiveBinaryFile(USHORT chNo, CHAR *fileName)
{
	FILE	*fp;

	SendStr(chNo, "ＸＭＯＤＥＭでファイルを送信して下さい。", 2);

	if ((fp = OpenFile(chNo, fileName, "wb")) == NULL) {
		SendSysErrMsg(chNo);
		return(FALSE);
	}

	if (RXmodem(chNo, fp, NULL) == 0) {
		CloseFile(chNo);
		DosSleep(1000);
		SendCRLF(chNo, 1);
		SendStr(chNo, "アップロードは正常終了しました。", 2);
		return(TRUE);
	} else {
		CloseFile(chNo);
		DosDelete(fileName, 0L);
		DosSleep(1000);
		SendCRLF(chNo, 1);
		SendStr(chNo, "アップロードは異常終了しました。", 2);
		return(FALSE);
	}
}

/*=======================================================================
 |
 |		掲示ファイル送信処理
 |
 |	VOID	SendBBSFile(chNo, BBSName, dbp, seqNo, flag)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*BBSName;	掲示板識別名
 |		DB	*dbp;		データベースポインタ
 |		SHORT	seqNo;		シーケンス番号
 |		BOOL	flag;		一括表示フラグ
 |
 =======================================================================*/
static	VOID	SendBBSFile(USHORT chNo, CHAR *BBSName, DB *dbp,
						SHORT seqNo, BOOL flag)
{
	CHAR	fileName[30];
	BBSDBF	BBSData;
	CHAR	title[41], name[13], msg[80];

	if (ReadBBSData(dbp, seqNo, &BBSData) == FALSE)
		return;

	if (cUserLevel[chNo] < BBSData.userLevel[0]) {
		SendStr(chNo, "このメッセージを見ることはできません。", 2);
		return;
	}

	CopyFromDBF(title, BBSData.title, sizeof(BBSData.title));
	sprintf(msg, "タイトル： %s", title);
	SendStr(chNo, msg, 2);

	CopyFromDBF(name, BBSData.fileName, sizeof(BBSData.fileName));
	sprintf(fileName, "%s.BBS\\%s", BBSName, name);
	SendTextFile(chNo, fileName, flag);
}

/*=======================================================================
 |
 |		テキストファイル送信処理
 |
 |	VOID	SendTextFile(chNo, fileName, flag)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*fileName;	ファイル名
 |		BOOL	flag;		一括表示フラグ
 |
 =======================================================================*/
static	VOID	SendTextFile(USHORT chNo, CHAR *fileName, BOOL flag)
{
	FILE	*fp;
	SHORT	lixxxounter;
	CHAR	rcvChar;
	int	len;
	CHAR	buf[1024];

	if ((fp = OpenFile(chNo, fileName, "r")) == NULL)
		return;

	lixxxounter = 2;
	while (fgets(buf, sizeof(buf), fp) != NULL) {
		if (flag == TRUE && ++lixxxounter == 20) {
			SendCRLF(chNo, 1);
			SendStr(chNo, "続きを見る場合はリターンキー、中止する場合はＥＳＣキーを押して下さい ==>", 0);
			for (;;) {
				if (ReceiveChar(chNo, &rcvChar) == FALSE)
					goto loop_end;
				if (rcvChar == 0x1b)
					goto loop_end;
				if (rcvChar == 0x0d)
					break;
			}
			SendCRLF(chNo, 2);
			lixxxounter = 0;
		}
		len = strlen(buf);
		if (len != 0 && buf[len-1] == '\n')
			buf[len-1] = '\0';
		SendStr(chNo, buf, 1);
	}
loop_end:
	SendCRLF(chNo, 2);

	CloseFile(chNo);
}

/*=======================================================================
 |
 |		ＸＭＯＤＥＭ送信処理
 |
 |	VOID	SendXmodem(chNo, BBSName, dbp, seqNo, flag)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*BBSName;	掲示板識別名
 |		DB	*dbp;		データベースポインタ
 |		SHORT	seqNo;		シーケンス番号
 |		BOOL	flag;		ファイル名表示フラグ
 |
 =======================================================================*/
static	VOID	SendXmodem(USHORT chNo, CHAR *BBSName, DB *dbp,
						SHORT seqNo, BOOL flag)
{
	FILE	*fp;
	CHAR	fileName[30];
	BBSDBF	BBSData;
	CHAR	title[41], name[13], msg[80];

	if (ReadBBSData(dbp, seqNo, &BBSData) == FALSE)
		return;

	if (cUserLevel[chNo] < BBSData.userLevel[0]) {
		SendStr(chNo,
			 "このファイルをダウンロードするはできません。", 2);
		return;
	}

	CopyFromDBF(name, BBSData.fileName, sizeof(BBSData.fileName));
	sprintf(fileName, "%s.BBS\\%s", BBSName, name);
	if ((fp = OpenFile(chNo, fileName, "rb")) == NULL)
		return;
	CopyFromDBF(title, BBSData.title, sizeof(BBSData.title));
	CopyFromDBF(name, BBSData.name, sizeof(BBSData.name));
	sprintf(msg, "【%s】をダウンロードしますか (Y/N)", title);
	if (YESorNO(chNo, msg) == TRUE) {
		if (flag)
			sprintf(msg, "ＸＭＯＤＥＭ（受信）を起動して下さい。ファイル名は %s です。", name);
		else
			strcpy(msg, "ＸＭＯＤＥＭ（受信）を起動して下さい。");
		SendStr(chNo, msg, 1);
		SendStr(chNo, "（中止は CTRL+X を押して下さい）", 1);
		if (SXmodem(chNo, fp, NULL) == 0) {
			DosSleep(1000);
			SendCRLF(chNo, 1);
			SendStr(chNo, "ダウンロードは正常終了しました。", 2);
		} else {
			DosSleep(1000);
			SendCRLF(chNo, 1);
			SendStr(chNo, "ダウンロードは異常終了しました。", 2);
		}
	}

	CloseFile(chNo);
}

/*=======================================================================
 |
 |		受信番号チェック処理
 |
 |	SHORT	GetNo(bufp)
 |
 |		CHAR	*bufp;		受信バッファ
 |
 |		SHORT	返値		受信番号
 |
 =======================================================================*/
static	SHORT	GetNo(CHAR *bufp)
{
	CHAR	*p;

	for (p = bufp; *p != '\0'; p++) {
		if (*p != ' ' && (*p < '0' || *p > '9'))
			return(-1);
	}
	return(atoi(bufp));
}

/*=======================================================================
 |
 |		掲示ファイル情報読み込み処理
 |
 |	BOOL	ReadBBSData(dbp, seqNo, BBSData)
 |
 |		DB	*dbp;		データベースポインタ
 |		SHORT	seqNo;		シーケンス番号
 |		BBSDBF	*BBSData;	掲示ファイル情報
 |
 |		BOOL	返値		TRUE ：読み込み正常
 |					FALSE：読み込み失敗
 |
 =======================================================================*/
static	BOOL	ReadBBSData(DB *dbp, SHORT seqNo, BBSDBF *BBSData)
{
	BOOL	st;

	if (seqNo <= 0)
		return(FALSE);

	DBLock(dbp);
	DBTop(dbp);
	DBSkip(dbp, seqNo - 1);
	if (DBEof(dbp) == FALSE) {
		DBRead(dbp, BBSData);
		st = TRUE;
	} else
		st = FALSE;
	DBRelease(dbp);

	return(st);
}

/*=======================================================================
 |
 |		ヘルプ表示処理
 |
 |	VOID	DspHelpMsg(chNo, help)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*help;		ヘルプ表示コマンド
 |
 =======================================================================*/
static	VOID	DspHelpMsg(USHORT chNo, CHAR *help)
{
	CHAR	*msg;

	SendStr(chNo, "        コマンド一覧", 2);
	while (*help != '\0') {
		switch (*help++) {
		case '0':
			msg = "  番号 ：指定した番号のコーナーへ移動する";
			break;
		case '1':
			msg = "  番号 ：指定した番号のメッセージを１ページ毎に表示する";
			break;
		case '2':
			msg = "  番号 ：指定した番号のファイルをＸＭＯＤＥＭで受信する";
			break;
		case 'D':
			msg = "  Ｄn  ：ｎ番の項目を削除する";
			break;
		case 'E':
			msg = "  Ｅ   ：このコーナーを終了する";
			break;
		case 'H':
			msg = "  Ｈ   ：コマンド一覧を表示する";
			break;
		case 'L':
			msg = "  Ｌ[n]：［ｎ番目から］項目の一覧を表示する";
			break;
		case 'M':
			msg = "  Ｍ   ：メニューを表示する";
			break;
		case 'Q':
			msg = "  Ｑ   ：通信を終了する";
			break;
		case 'R':
			msg = "  Ｒn  ：ｎ番のメッセージを一括表示する";
			break;
		case 'S':
			msg = "  Ｓ   ：メッセージを送信する";
			break;
		case 'U':
			msg = "  Ｕ   ：ファイルをＸＭＯＤＥＭでアップロードする";
			break;
		case 'X':
			msg = "  Ｘn  ：ｎ番のメッセージをＸＭＯＤＥＭで受信する";
			break;
		default:
			msg = "??????????";
			break;
		}
		SendStr(chNo, msg, 1);
	}
	SendCRLF(chNo, 1);
}

/*=======================================================================
 |
 |		Ｙｅｓ／Ｎｏ受信処理
 |
 |	BOOL	YESorNO(chNo, msg)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*msg;		表示メッセージ
 |
 |		BOOL	返値		TRUE ：Ｙ受信
 |					FALSE：Ｎ受信
 |
 =======================================================================*/
static	BOOL	YESorNO(USHORT chNo, CHAR *msg)
{
	CHAR	rcvChar;

	SendStr(chNo, msg, 0);
	for (;;) {
		if (ReceiveChar(chNo, &rcvChar) == FALSE)
			return(FALSE);
		if (rcvChar == 'Y' || rcvChar == 'y') {
			SendStr(chNo, "Y", 2);
			return(TRUE);
		} else if (rcvChar == 'N' || rcvChar == 'n') {
			SendStr(chNo, "N", 2);
			return(FALSE);
		}
	}
}

/*=======================================================================
 |
 |		文字列受信処理
 |
 |	VOID	ReceiveStr(chNo, rcvBuf, bufSize)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*rcvBuf;	受信バッファ
 |		USHORT	bufSize;	受信バッファサイズ
 |
 =======================================================================*/
static	VOID	ReceiveStr(USHORT chNo, CHAR *rcvBuf, USHORT bufSize)
{
	if (ReceiveLine(chNo, rcvBuf, bufSize) == FALSE)
		longjmp(jmpBuf[chNo], 1);
}

/*=======================================================================
 |
 |		文字列送信処理
 |
 |	VOID	SendStr(chNo, sendMsg, newLine)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*sendMsg;	送信メッセージ
 |		USHORT	newLine;	改行数
 |
 =======================================================================*/
static	VOID	SendStr(USHORT chNo, CHAR *sendMsg, USHORT newLine)
{
	if (SendLine(chNo, sendMsg, newLine) == FALSE)
		longjmp(jmpBuf[chNo], 1);
}

/*=======================================================================
 |
 |		入力エラーメッセージ送信処理
 |
 |	VOID	SendInputErrMsg(chNo)
 |
 |		USHORT	chNo;		チャネル番号
 |
 =======================================================================*/
static	VOID	SendInputErrMsg(USHORT chNo)
{
	SendStr(chNo, "番号またはコマンドが違います。", 2);
}

/*=======================================================================
 |
 |		システム障害メッセージ送信処理
 |
 |	VOID	SendSysErrMsg(chNo)
 |
 |		USHORT	chNo;		チャネル番号
 |
 =======================================================================*/
static	VOID	SendSysErrMsg(USHORT chNo)
{
	SendStr(chNo,
		"システム障害により、コマンドは実行出来ませんでした。", 2);
}

/*=======================================================================
 |
 |		ファイルオープン処理
 |
 |	FILE	*OpenFile(chNo, fileName, openMode)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*fileName;	ファイル名
 |		CHAR	*openMode;	オープンモード
 |
 |		FILE	*返値		ファイルポインタ
 |
 =======================================================================*/
static	FILE	*OpenFile(USHORT chNo, CHAR *fileName, CHAR *openMode)
{
	CloseFile(chNo);
	openFile[chNo] = fopen(fileName, openMode);
	return(openFile[chNo]);
}

/*=======================================================================
 |
 |		ファイルクローズ処理
 |
 |	VOID	CloseFile(chNo)
 |
 |		USHORT	chNo;		チャネル番号
 |
 =======================================================================*/
static	VOID	CloseFile(USHORT chNo)
{
	if (openFile[chNo] != NULL) {
		fclose(openFile[chNo]);
		openFile[chNo] = NULL;
	}
}

/*=======================================================================
 |
 |		掲示板オープン処理
 |
 |	DB	*OpenBBS(chNo, BBSName)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*BBSName;	掲示板識別名
 |
 |		DB	*返値		データベースポインタ
 |
 =======================================================================*/
static	DB	*OpenBBS(USHORT chNo, CHAR *BBSName)
{
	CloseBBS(chNo);
	openBBS[chNo] = OpenBBSDBF(BBSName, FALSE);
	return(openBBS[chNo]);
}

/*=======================================================================
 |
 |		掲示板クローズ処理
 |
 |	VOID	CloseBBS(chNo)
 |
 |		USHORT	chNo;		チャネル番号
 |
 =======================================================================*/
static	VOID	CloseBBS(USHORT chNo)
{
	if (openBBS[chNo] != NULL) {
		CloseBBSDBF(openBBS[chNo]);
		openBBS[chNo] = NULL;
	}
}

/*=======================================================================
 |
 |		メール受信チェック
 |
 |	VOID	RcvMailCheck(chNo, msg)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*msg;		受信メッセージ
 |
 =======================================================================*/
static	VOID	RcvMailCheck(USHORT chNo, CHAR *msg)
{
	MAILDBF	recBuf;
	BOOL	flag;
	CHAR	userID[10];

	CopyToDBF(userID, cUserID[chNo], 8);
	userID[8] = 'M';
	userID[9] = ' ';

	flag = FALSE;
	DBLock(mailDBp);
	DBSearch(mailDBp, userID, 10, indexMailR2);
	for (;;) {
		if (DBEof(mailDBp))
			break;
		if (IDXCompare(mailDBp->ip[indexMailR2], userID, 10) != 0)
			break;
		DBRead(mailDBp, &recBuf);
		if (recBuf.rDate[0] == '-') {
			flag = TRUE;
			break;
		}
		DBSkip(mailDBp, 1L);
	}
	DBRelease(mailDBp);

	if (flag)
		SendStr(chNo, msg, 2);
}

/*=======================================================================
 |
 |		通信終了処理
 |
 |	VOID	Quit(chNo)
 |
 |		USHORT	chNo;		チャネル番号
 |
 =======================================================================*/
static	VOID	Quit(SHORT chNo)
{
	if (YESorNO(chNo, "通信を終了しますか？ (Y/N)"))
		longjmp(jmpBuf[chNo], 1);
}
