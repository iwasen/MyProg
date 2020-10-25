//*****************************************************************************************************
//  1. ファイル名
//		FftSpectrogramView.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		スペクトログラム解析グラフのウィンドウクラスの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "MMA_G.h"
#include "FftSpectrogramDlg.h"
#include "FftSpectrogramView.h"
#include "Define.h"

// スクロールバーの最大値
#define SCROLL_MAX		0x7ffffffe

// CFftSpectrogramView

IMPLEMENT_DYNAMIC(CFftSpectrogramView, CWnd)

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramView::CFftSpectrogramView
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		コンストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
CFftSpectrogramView::CFftSpectrogramView()
{
	// メンバ変数を初期化
	m_sizeBitmap = 0;
	m_bDispGraph = FALSE;
	m_nCursorX = 0;
	m_nCursorY = 0;
	m_bTrackMouse = FALSE;
	m_penCursor.CreatePen(PS_DOT, 1, COLOR_GRAPH_CURSOR);
	m_bAutoScroll = FALSE;
}


BEGIN_MESSAGE_MAP(CFftSpectrogramView, CWnd)
	ON_WM_ERASEBKGND()
	ON_WM_PAINT()
	ON_WM_MOUSEMOVE()
	ON_WM_HSCROLL()
	ON_WM_NCHITTEST()
	ON_WM_NCLBUTTONDOWN()
	ON_WM_NCLBUTTONUP()
	ON_WM_LBUTTONDOWN()
END_MESSAGE_MAP()


//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramView::PreSubclassWindow
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		サブクラス化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramView::PreSubclassWindow()
{
	// 初期化処理
	Initialize();

	CWnd::PreSubclassWindow();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramView::OnEraseBkgnd
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		背景描画処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC			*pDC			[I] デバイスコンテキストへのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE
//*****************************************************************************************************
BOOL CFftSpectrogramView::OnEraseBkgnd(CDC *pDC)
{
	// 何もしない
	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramView::OnPaint
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ペイント処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramView::OnPaint()
{
	CPaintDC dc(this);

	// クライアント領域のサイズを取得
	CRect rect;
	GetClientRect(&rect);

	if (m_bDispGraph) {
		// グラフのビットマップを画面に転送
		CBitmap *pBitmapOrg = m_dcGraph.SelectObject(&m_bmpGraph);
		dc.BitBlt(0, 0, rect.Width(), rect.Height(), &m_dcGraph, 0, 0, SRCCOPY);

		// カーソル描画
		if (m_nCursorX >= m_cFftSpectrogramDraw.m_rectScale.left
				&& m_nCursorX <= m_cFftSpectrogramDraw.m_rectScale.right
				&& m_nCursorY >= m_cFftSpectrogramDraw.m_rectScale.top
				&& m_nCursorY <= m_cFftSpectrogramDraw.m_rectScale.bottom) {
			CRect &rectScale = m_cFftSpectrogramDraw.m_rectScale;
			CPen *pPenOrg = dc.SelectObject(&m_penCursor);
			dc.MoveTo(m_nCursorX, rectScale.top);
			dc.LineTo(m_nCursorX, rectScale.bottom);
			dc.MoveTo(rectScale.left, m_nCursorY);
			dc.LineTo(rectScale.right, m_nCursorY);
			dc.SelectObject(pPenOrg);
		}

		m_dcGraph.SelectObject(pBitmapOrg);
	} else {
		// グレーで塗りつぶす
		CBrush brush;
		brush.CreateSysColorBrush(COLOR_MENU);
		dc.FillRect(&rect, &brush);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramView::Initialize
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		初期化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramView::Initialize()
{
	// メモリデバイスコンテキストを作成
	CDC *pDC = GetDC();
	m_dcGraph.CreateCompatibleDC(pDC);
	ReleaseDC(pDC);

	// 水平スクロールバー表示
	ShowScrollBar(SB_HORZ, TRUE);
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramView::DispGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフ表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		*pDataG				[I] 合力スペクトログラムデータへのポインタ
//		int			nFreqData			[I] 周波数軸の個数
//		int			nTimeData			[I] 時間軸の個数
//		double		fSampleRate			[I] サンプリングレート
//		double		fTimeMin			[I] 時間軸の最小値
//		double		fTimeMax			[I] 時間軸の最大値
//		double		fTimeOffset			[I] 時間軸のオフセット
//		double		fTimeZoom			[I] 時間軸のズーム比
//		double		fFreqMin			[I] 周波数軸の最小値
//		double		fFreqMax			[I] 周波数軸の最大値
//		BOOL		bFreqLog			[I] 周波数軸の対数スケールフラグ
//		double		fLevelMin			[I] レベル軸の最小値
//		double		fLevelMax			[I] レベル軸の最大値
//		BOOL		bLevelLog			[I] レベル軸の対数スケールフラグ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramView::DispGraph(const double *pDataG, int nFreqData, int nTimeData, double fSampleRate, double fTimeMin, double fTimeMax, double fTimeOffset, double fTimeZoom, double fFreqMin, double fFreqMax, BOOL bFreqLog, double fLevelMin, double fLevelMax, BOOL bLevelLog)
{
	if (m_hWnd == NULL)
		return;

	// クライアント領域の大きさに合わせてビットマップを作成
	CRect rectClinet;
	GetClientRect(&rectClinet);
	CSize sizeView(rectClinet.Width(), rectClinet.Height());
	if (m_sizeBitmap != sizeView) {
		m_sizeBitmap = sizeView;

		CDC *pDC = GetDC();
		m_bmpGraph.DeleteObject();
		m_bmpGraph.CreateCompatibleBitmap(pDC, m_sizeBitmap.cx, m_sizeBitmap.cy);
		ReleaseDC(pDC);
	}

	// メモリデバイスコンテキストにビットマップを設定
	CBitmap *pBitmapOrg = m_dcGraph.SelectObject(&m_bmpGraph);

	// グラフ描画
	m_cFftSpectrogramDraw.DrawGraph(m_dcGraph, rectClinet, pDataG, nFreqData, nTimeData, fSampleRate, fTimeMin, fTimeMax, fTimeOffset, fTimeZoom, fFreqMin, fFreqMax, bFreqLog, fLevelMin, fLevelMax, bLevelLog);

	// メモリデバイスコンテキストからビットマップを解放
	m_dcGraph.SelectObject(pBitmapOrg);

	// グラフ表示フラグセット
	m_bDispGraph = TRUE;

	// ウィンドウの無効化
	Invalidate(FALSE);

	// ウィンドウを更新
	UpdateWindow();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramView::HideGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフ消去処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramView::HideGraph()
{
	// グラフ表示フラグリセット
	m_bDispGraph = FALSE;

	// 画面描画
	Invalidate();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramView::OnMouseMove
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		マウス移動処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nFlags			[I] 押されている仮想キー
//		CPoint		point			[I] マウスの座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramView::OnMouseMove(UINT nFlags, CPoint point)
{
	if (!m_bAutoScroll) {
		CFftSpectrogramDlg *pWnd = (CFftSpectrogramDlg *)GetParent();

		// マウスカーソルがスケール内あればカーソル位置データを表示する
		CRect &rectScale = m_cFftSpectrogramDraw.m_rectScale;
		if (rectScale.PtInRect(point)) {
			if (!m_bTrackMouse) {
				TRACKMOUSEEVENT tme;
				tme.cbSize = sizeof(TRACKMOUSEEVENT);
				tme.dwFlags = TME_LEAVE;
				tme.hwndTrack = m_hWnd;
				::TrackMouseEvent(&tme);

				m_bTrackMouse = TRUE;
			}

			pWnd->SetCursor((double)(point.x - rectScale.left) / rectScale.Width(), (double)(rectScale.bottom - point.y) / rectScale.Height());
		} else {
			pWnd->SetCursor(0, 0);
		}
	}

	CWnd::OnMouseMove(nFlags, point);
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramView::OnMouseLeave
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		マウスカーソルがウィンドウ外へ移動
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		WPARAM		wParam			[I] WM_MOUSELEAVEメッセージパラメータ
//		LPARAM		lParam			[I] WM_MOUSELEAVEメッセージパラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		LRESULT		0
//*****************************************************************************************************
LRESULT CFftSpectrogramView::OnMouseLeave(WPARAM wParam, LPARAM lParam)
{
	CFftSpectrogramDlg *pWnd = (CFftSpectrogramDlg *)GetParent();

	// カーソル位置データを消去する
	pWnd->SetCursor(0, 0);
	m_bTrackMouse = FALSE;

	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramView::DispCursor
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		カーソル表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		fPosX			[I] 0～1に正規化されたカーソルのX座標
//		double		fPosY			[I] 0～1に正規化されたカーソルのY座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramView::DispCursor(double fPosX, double fPosY)
{
	int nCursorX = 0;
	int nCursorY = 0;

	// カーソルのX座標を計算
	CRect &rectScale = m_cFftSpectrogramDraw.m_rectScale;
	nCursorX = rectScale.left + (int)(rectScale.Width() * fPosX);

	// カーソルのY座標を計算
	if (fPosY != 0) {
		nCursorY = rectScale.bottom - (int)(rectScale.Height() * fPosY + 0.5);
		if (nCursorY < rectScale.top || nCursorY > rectScale.bottom)
			nCursorY = 0;
	}

	// 移動していたら再表示
	if (nCursorX != m_nCursorX || nCursorY != m_nCursorY) {
		m_nCursorX = nCursorX;
		m_nCursorY = nCursorY;
		Invalidate(FALSE);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramView::OnHScroll
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		水平スクロール処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nSBCode			[I] スクロール要求コード
//		UINT		nPos			[I] スクロールボックスの位置
//		CScrollBar	*pScrollBar		[I] スクロールバーコントロールへのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramView::OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar)
{
	SCROLLINFO si;

	// 現在のスクロール情報を取得
	GetScrollInfo(SB_HORZ, &si);
	int nPageSize = si.nPage;
	int nLineSize = nPageSize / 32;
	if (nLineSize == 0)
		nLineSize = 1;

	// スクロール位置を更新
	switch (nSBCode) {
	case SB_LEFT:
		nPos = 0;
		break;
	case SB_LINELEFT:
		nPos = si.nPos - nLineSize;
		break;
	case SB_LINERIGHT:
		nPos = si.nPos + nLineSize;
		break;
	case SB_PAGELEFT:
		nPos = si.nPos - nPageSize;
		break;
	case SB_PAGERIGHT:
		nPos = si.nPos + nPageSize;
		break;
	case SB_RIGHT:
		nPos = si.nMax - si.nPage;
		break;
	case SB_THUMBPOSITION:
	case SB_THUMBTRACK:
		nPos = si.nTrackPos;
		break;
	default:
		return;
	}

	if (nPos != si.nPos) {
		CFftSpectrogramDlg *pFftSpectrogramDlg = (CFftSpectrogramDlg *)GetParent();

		// 横軸をスクロール
		pFftSpectrogramDlg->TimeScroll((double)nPos / si.nMax);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramView::OnNcHitTest
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		非クライアント領域ヒットテスト処理（スクロールバーを有効にするために必要）
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CPoint		point			[I] カーソル位置
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		LRESULT		DefWindowProcの返り値
//*****************************************************************************************************
LRESULT CFftSpectrogramView::OnNcHitTest(CPoint point)
{
	return ::DefWindowProc(m_hWnd, WM_NCHITTEST, 0, MAKELPARAM(point.x, point.y));
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramView::OnNcLButtonDown（スクロールバーを有効にするために必要）
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		非クライアント領域マウスダウン処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nHitTest		[I] ヒットテスト
//		CPoint		point			[I] カーソル位置
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramView::OnNcLButtonDown(UINT nHitTest, CPoint point)
{
	::DefWindowProc(m_hWnd, WM_NCLBUTTONDOWN, nHitTest, MAKELPARAM(point.x, point.y));
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramView::OnNcLButtonUp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		非クライアント領域マウスアップ処理（スクロールバーを有効にするために必要）
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nHitTest		[I] ヒットテスト
//		CPoint		point			[I] カーソル位置
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramView::OnNcLButtonUp(UINT nHitTest, CPoint point)
{
	::DefWindowProc(m_hWnd, WM_NCLBUTTONUP, nHitTest, MAKELPARAM(point.x, point.y));
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramView::SetTimeScrollBar
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		スクロール情報設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		fTimeOffset			[I] 時間オフセット
//		double		fTimeZoom			[I] 時間ズーム
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramView::SetTimeScrollBar(double fTimeOffset, double fTimeZoom)
{
	SCROLLINFO si;

	memset(&si, 0, sizeof(si));
	si.cbSize = sizeof(si);
	si.fMask = SIF_PAGE | SIF_POS | SIF_RANGE;
	si.nMax = SCROLL_MAX;
	si.nMin = 0;
	si.nPage = (int)(SCROLL_MAX / fTimeZoom);
	if (si.nPage == 0)
		si.nPage = 1;
	si.nPos = (int)(SCROLL_MAX * fTimeOffset);

	SetScrollInfo(SB_HORZ, &si);
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramView::OnLButtonDown
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		左マウスボタンダウン処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nFlags			[I] 押されている仮想キー
//		CPoint		point			[I] カーソルの座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramView::OnLButtonDown(UINT nFlags, CPoint point)
{
	CFftSpectrogramDlg *pWnd = (CFftSpectrogramDlg *)GetParent();

	// マウスカーソルがスケール内あればカーソル位置データを表示する
	CRect &rectScale = m_cFftSpectrogramDraw.m_rectScale;
	if (rectScale.PtInRect(point))
		pWnd->SetCursor((double)(point.x - rectScale.left) / rectScale.Width(), (double)(rectScale.bottom - point.y) / rectScale.Height());
	else
		pWnd->SetCursor(0, 0);

	CWnd::OnLButtonDown(nFlags, point);
}
