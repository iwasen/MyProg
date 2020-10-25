/************************************************************************
 *									*
 *		グラフィックウィンドウライブラリ			*
 *									*
 *		名称		: 共通データ定義			*
 *		ファイル名	: dlib.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include "gwlib.h"

WINDOW	std_win;	/* 全画面用ウィンドウ */
WINDOW	*scrp = &std_win;	/* 全画面ポインタ */
int	func_line = 23;	/* ファンクションキー表示行 */
WINDOW	*csr_wp;	/* カーソルの存在するウィンドウポインタ */
int	csr_sx;		/* カーソルＸ座標 */
int	csr_sy;		/* カーソルＹ座標 */
int	csr_flag;	/* カーソル表示フラグ */
int	g_fcolor = C_WHITE;	/* 文字色 */
int	g_bcolor = C_BLACK;	/* 背景色 */
int	g_kcolor = C_BLUE;	/* 漢字入力行の色 */
int	g_fkcolor = C_BLUE;	/* ファンクションキー選択時の色 */
int	m_gx;		/* マウスＸ座標（グラフィック座標） */
int	m_gy;		/* マウスＹ座標（グラフィック座標） */
int	m_sel;		/* マウス選択項目番号 */
int	m_csr_flag;	/* マウスカーソル表示フラグ */
int	mouse_drv;	/* マウスドライバ種別 （０：ＮＥＣ　１：アスキー） */
char	ank_font[256][16];	/* ＡＮＫフォントバッファ */
void	(far interrupt *m_intp)();
struct key_buf key_data = {	/* キーコード */
	{
		{0}, {0}, {0}, {0}, {0}, {0}, {0}, {0}, {0}, {0},
		{0}, {0}, {0}, {0}, {0}, {0}, {0}, {0}, {0}, {0}
	},
	{
		{0}, {0}, {KEY_INS}, {KEY_DEL},
		{KEY_UP}, {KEY_LEFT}, {KEY_RIGHT}, {KEY_DOWN},
		{KEY_CLR}, {KEY_HELP}, {KEY_HOME}
	}
};
