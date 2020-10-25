//*****************************************************************************************************
//  1. ファイル名
//		FftSpectrumView.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		スペクトル解析グラフのウィンドウクラスの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "MMA_G.h"
#include "FftSpectrumDlg.h"
#include "FftSpectrumView.h"
#include "Define.h"


// CFftSpectrumView

IMPLEMENT_DYNAMIC(CFftSpectrumView, CWnd)

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrumView::CFftSpectrumView
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
CFftSpectrumView::CFftSpectrumView()
{
	// メンバ変数を初期化
	m_sizeBitmap = 0;
	m_bDispGraph = FALSE;
	m_nCursorX = 0;
	m_bTrackMouse = FALSE;
	m_penCursor.CreatePen(PS_DOT, 1, COLOR_GRAPH_CURSOR);
}


BEGIN_MESSAGE_MAP(CFftSpectrumView, CWnd)
	ON_WM_ERASEBKGND()
	ON_WM_PAINT()
	ON_WM_MOUSEMOVE()
	ON_MESSAGE(WM_MOUSELEAVE, OnMouseLeave)
END_MESSAGE_MAP()


//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrumView::PreSubclassWindow
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
void CFftSpectrumView::PreSubclassWindow()
{
	// 初期化処理
	Initialize();

	CWnd::PreSubclassWindow();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrumView::OnEraseBkgnd
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
BOOL CFftSpectrumView::OnEraseBkgnd(CDC *pDC)
{
	// 何もしない
	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrumView::OnPaint
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
void CFftSpectrumView::OnPaint()
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
		if (m_nCursorX != 0) {
			CRect &rectScale = m_cFftSpectrumDraw.m_rectScale;
			CPen *pPenOrg = dc.SelectObject(&m_penCursor);
			dc.MoveTo(m_nCursorX, rectScale.top);
			dc.LineTo(m_nCursorX, rectScale.bottom);
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
//		CFftSpectrumView::Initialize
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
void CFftSpectrumView::Initialize()
{
	// メモリデバイスコンテキストを作成
	CDC *pDC = GetDC();
	m_dcGraph.CreateCompatibleDC(pDC);
	ReleaseDC(pDC);
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrumView::DispGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフ表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		*pDataX				[I] X軸スペクトルデータへのポインタ
//		double		*pDataY				[I] Y軸スペクトルデータへのポインタ
//		double		*pDataZ				[I] Z軸スペクトルデータへのポインタ
//		double		*pDataG				[I] 合力スペクトルデータへのポインタ
//		int			nData				[I] データ数
//		double		fSampleRate			[I] サンプリングレート
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
void CFftSpectrumView::DispGraph(const double *pDataX, const double *pDataY, const double *pDataZ, const double *pDataG, int nData, double fSampleRate, double fFreqMin, double fFreqMax, BOOL bFreqLog, double fLevelMin, double fLevelMax, BOOL bLevelLog)
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
	m_cFftSpectrumDraw.DrawGraph(m_dcGraph, rectClinet, pDataX, pDataY, pDataZ, pDataG, nData, fSampleRate, fFreqMin, fFreqMax, bFreqLog, fLevelMin, fLevelMax, bLevelLog);

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
//		CFftSpectrumView::HideGraph
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
void CFftSpectrumView::HideGraph()
{
	// グラフ表示フラグリセット
	m_bDispGraph = FALSE;

	// 画面描画
	Invalidate();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrumView::OnMouseMove
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
void CFftSpectrumView::OnMouseMove(UINT nFlags, CPoint point)
{
	CFftSpectrumDlg *pWnd = (CFftSpectrumDlg *)GetParent();

	// マウスカーソルがスケール内あればカーソル位置データを表示する
	CRect &rectScale = m_cFftSpectrumDraw.m_rectScale;
	if (rectScale.PtInRect(point)) {
		if (!m_bTrackMouse) {
			TRACKMOUSEEVENT tme;
			tme.cbSize = sizeof(TRACKMOUSEEVENT);
			tme.dwFlags = TME_LEAVE;
			tme.hwndTrack = m_hWnd;
			::TrackMouseEvent(&tme);

			m_bTrackMouse = TRUE;
		}

		pWnd->DispCursorData((double)(point.x - rectScale.left) / rectScale.Width(), (double)(rectScale.bottom - point.y) / rectScale.Height());
	} else {
		pWnd->DispCursorData(0, 0);
	}

	CWnd::OnMouseMove(nFlags, point);
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrumView::OnMouseLeave
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
LRESULT CFftSpectrumView::OnMouseLeave(WPARAM wParam, LPARAM lParam)
{
	CFftSpectrumDlg *pWnd = (CFftSpectrumDlg *)GetParent();

	// カーソル位置データを消去する
	pWnd->DispCursorData(0, 0);
	m_bTrackMouse = FALSE;

	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrumView::DispCursor
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		カーソル表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		fPosX			[I] 0～1に正規化されたカーソルの位置
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrumView::DispCursor(double fPosX)
{
	int nCursorX = 0;

	if (fPosX != 0) {
		CRect &rectScale = m_cFftSpectrumDraw.m_rectScale;
		nCursorX = rectScale.left + (int)(rectScale.Width() * fPosX + 0.5);
		if (nCursorX < rectScale.left || nCursorX > rectScale.right)
			nCursorX = 0;
	}

	if (nCursorX != m_nCursorX) {
		m_nCursorX = nCursorX;
		Invalidate(FALSE);
	}
}
