/************************************************************************
 *
 *		名称		: RS232C　Win32　共通関数ヘッダファイル
 *		ファイル名	: rs.h
 *
 *		1998/11/19		M.HAMA
 *
 ***********************************************************************/

#ifdef __cplusplus
extern "C" {
#endif

/*	チャネル番号	*/
#define	COM1		0		/* チャネル１ */
#define	COM2		1		/* チャネル２ */
#define	COM3		2		/* チャネル３ */

/*	全／半二重モード	*/
#define	DUPLEX_FULL	0		/* 全二重 */
#define	DUPLEX_HALF	1		/* 半二重 */

/*	ＲＳ－２３２Ｃオープンパラメータ	*/
typedef struct	rsprm	{
	int	comno;		/* ＣＯＭ番号 */
	int	duplex;		/* 二重モード */
	int	blt;		/* ボーレート */
	int	parity;		/* パリティー */
	int	chr_len;	/* キャラクタ長 */
	int	stp_len;	/* ストップビット長 */
	int	buf_size;	/* 受信バッファサイズ */
} RSPRM, FAR * LPRSPRM;

/*	rs.c		*/
extern	int	RsOpen(LPSTR);
extern	VOID RsClose(VOID);
extern	DWORD	RsGet(VOID);
extern	BOOL RsPut(int);
extern	BOOL RsSend(LPSTR, int);
extern	VOID RsDtr(int);
extern	VOID RsRts(int);
extern	int RsDsr(VOID);
extern	int RsCd(VOID);
extern	int RsCts(VOID);
extern	VOID TmSet(int);
extern	BOOL TmCheck(VOID);
extern	VOID TmWait(int);

#ifdef __cplusplus
}
#endif
