/************************************************************************
 *
 *			ＰＣ－ＳＥＲＶＥ
 *
 *		名称		: カレンダー関連定義
 *		ファイル名	: pcscl.h
 *		作成者		: y.kaneko
 *
 ************************************************************************/

#define	FN_CLDBF	"_CALENDR.DBF"
#define	FN_CLNDX	"_CALENDR.NDX"


typedef	struct _CALENDR {
	char	Code[8];
	char	Year[4];
	char	Flag[366];
} CALENDR;

extern	SHORT	CLGetDate(CHAR *, PCLDATE, SHORT, PCLDATE);
extern	SHORT	CLGetCalendar(CHAR *, PCLDATE, CHAR *);
