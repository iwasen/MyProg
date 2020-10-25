//*****************************************************************************************************
//  1. ファイル名
//		GraphView.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バイナリデータグラフのウィンドウクラスの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "MMA_G.h"
#include "GraphView.h"
#include "GraphFrm.h"
#include "Define.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#endif

// マウス移動モード
#define MOUSE_MOVE_NONE		0
#define MOUSE_MOVE_SELECT	1
#define MOUSE_MOVE_SCROLL	2

// スクロールバーの最大値
#define SCROLL_MAX		0x7ffffffe

// 縦軸のスクロールバーのID
#define IDC_SCROLL_X	200
#define IDC_SCROLL_Y	201
#define IDC_SCROLL_Z	202

// CGraphView

//*****************************************************************************************************
//  1. 関数名
//		CGraphView::CGraphView
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
CGraphView::CGraphView()
{
	// メンバ変数初期化
	m_sizeBitmap = 0;
	m_bSetBitmap = FALSE;
	m_nMouseMoveMode = MOUSE_MOVE_NONE;
	m_bAutoScroll = FALSE;

	// リソースからカーソル取得
	m_hCursorHandClose = AfxGetApp()->LoadCursor(IDC_HANDCLOSE);
}


BEGIN_MESSAGE_MAP(CGraphView, CWnd)
	ON_WM_PAINT()
	ON_WM_HSCROLL()
	ON_WM_VSCROLL()
	ON_WM_SIZE()
	ON_WM_CREATE()
	ON_WM_LBUTTONDOWN()
	ON_WM_LBUTTONUP()
	ON_WM_MOUSEMOVE()
	ON_WM_RBUTTONDOWN()
	ON_WM_RBUTTONUP()
END_MESSAGE_MAP()


//*****************************************************************************************************
//  1. 関数名
//		CGraphView::PreCreateWindow
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ウィンドウ作成前処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CREATESTRUCT		&cs			[I] ウィンドウ作成情報構造体
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE：正常　FALSE：エラー
//*****************************************************************************************************
BOOL CGraphView::PreCreateWindow(CREATESTRUCT& cs) 
{
	if (!CWnd::PreCreateWindow(cs))
		return FALSE;

	cs.dwExStyle |= WS_EX_CLIENTEDGE;
	cs.style &= ~WS_BORDER;
	cs.style |= WS_HSCROLL | WS_CLIPCHILDREN;
	cs.lpszClass = AfxRegisterWndClass(CS_HREDRAW|CS_DBLCLKS, 
		::LoadCursor(NULL, IDC_ARROW), reinterpret_cast<HBRUSH>(COLOR_WINDOW+1), NULL);

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphView::OnPaint
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
void CGraphView::OnPaint() 
{
	CPaintDC dc(this);

	if (m_bSetBitmap) {
		CGraphFrame *pGraphFrame = (CGraphFrame *)GetParent();

		// メモリデバイスコンテキストにビットマップを設定
		CBitmap *pBitmapOrg = m_dcGraph.SelectObject(&m_bmpGraph);

		// グラフのビットマップを画面に転送
		dc.BitBlt(0, 0, m_sizeBitmap.cx, m_sizeBitmap.cy, &m_dcGraph, 0, 0, SRCCOPY);

		// 時間カーソルを描画
		m_cDrawGraph.DrawTimeCursor(dc, pGraphFrame->m_cGraphData, pGraphFrame->m_nGraphKind);

		// メモリデバイスコンテキストからビットマップを削除
		m_dcGraph.SelectObject(pBitmapOrg);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphView::OnHScroll
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
void CGraphView::OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar)
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
		CGraphFrame *pGraphFrame = (CGraphFrame *)GetParent();
		CGraphData &cGraphData = pGraphFrame->m_cGraphData;

		// 横軸をスクロール
		cGraphData.m_stTimeRange.fBegin = cGraphData.m_stTimeRange.fMin + (int)nPos * cGraphData.m_stTimeRange.fSpan / si.nMax;
		pGraphFrame->UpdateGraph();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphView::OnVScroll
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		垂直スクロール処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nSBCode			[I] スクロール要求コード
//		UINT		nPos			[I] スクロールボックスの位置
//		CScrollBar	*pScrollBar		[I] スクロールバーコントロールへのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphView::OnVScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar)
{
	SCROLLINFO si;

	// 現在のスクロール情報を取得
	pScrollBar->GetScrollInfo(&si);
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
		CGraphFrame *pGraphFrame = (CGraphFrame *)GetParent();
		CGraphData &cGraphData = pGraphFrame->m_cGraphData;

		// 縦軸をスクロール
		SDispRange *pLevelRange;
		switch (pScrollBar->GetDlgCtrlID()) {
		case IDC_SCROLL_X:
			pLevelRange = &cGraphData.m_stLevelRangeX;
			break;
		case IDC_SCROLL_Y:
			pLevelRange = &cGraphData.m_stLevelRangeY;
			break;
		case IDC_SCROLL_Z:
			pLevelRange = &cGraphData.m_stLevelRangeZ;
			break;
		}
		pLevelRange->fBegin = pLevelRange->fMin + (int)(si.nMax - si.nPage - nPos) * pLevelRange->fSpan / si.nMax;
		pGraphFrame->UpdateGraph();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphView::OnSize
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ウィンドウサイズ変更処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT	nType			[I] サイズ変更のタイプ
//		int		cx				[I] ウィンドウの幅
//		int		cy				[I] ウィンドウの高さ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphView::OnSize(UINT nType, int cx, int cy)
{
	CWnd::OnSize(nType, cx, cy);

	// グラフ更新
	if (cx != 0 && cy != 0) {
		CGraphFrame *pGraphFrame = (CGraphFrame *)GetParent();
		pGraphFrame->UpdateGraph();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphView::UpdateGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフ表示更新処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphView::UpdateGraph()
{
	CGraphFrame *pGraphFrame = (CGraphFrame *)GetParent();
	CGraphData &cGraphData = pGraphFrame->m_cGraphData;

	// クライアント領域のサイズを取得
	CRect rectClinet;
	GetClientRect(rectClinet);
	CSize sizeView(rectClinet.Width(), rectClinet.Height());

	// ビットマップ作成
	CreateBitmap(sizeView);

	// メモリデバイスコンテキストにビットマップを設定
	CBitmap *pBitmapOrg = m_dcGraph.SelectObject(&m_bmpGraph);

	// グラフを描画
	m_cDrawGraph.DrawGraph(m_dcGraph, rectClinet, cGraphData, TRUE, pGraphFrame->m_nGraphKind);

	// メモリデバイスコンテキストからビットマップを解除
	m_dcGraph.SelectObject(pBitmapOrg);

	// ビットマップ設定フラグ設定
	m_bSetBitmap = TRUE;

	// スクロールバー設定
	SetScrollBar();

	// クライアント領域を無効化
	Invalidate(FALSE);

	// ウィンドウ更新
	UpdateWindow();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphView::CreateBitmap
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ビットマップ作成処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CSize	sizeBitmap			[I] ビットマップのサイズ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphView::CreateBitmap(CSize sizeBitmap)
{
	// クライアント領域のサイズに合わせてビットマップを作成
	if (m_sizeBitmap != sizeBitmap) {
		m_sizeBitmap = sizeBitmap;

		CDC *pDC = GetDC();
		m_bmpGraph.DeleteObject();
		m_bmpGraph.CreateCompatibleBitmap(pDC, m_sizeBitmap.cx, m_sizeBitmap.cy);
		ReleaseDC(pDC);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphView::SetScrollBar
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		スクロールバー設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphView::SetScrollBar()
{
	CGraphFrame *pGraphFrame = (CGraphFrame *)GetParent();
	CGraphData &cGraphData = pGraphFrame->m_cGraphData;
	SCROLLINFO si;

	// 水平スクロールバー設定
	MakeScrollInfo(cGraphData.m_stTimeRange, si, FALSE);
	SetScrollInfo(SB_HORZ, &si);

	// Ｘ軸垂直スクロールバー設定
	MakeScrollInfo(cGraphData.m_stLevelRangeX, si, TRUE);
	m_cScrollBarX.SetScrollInfo(&si);

	// Ｙ軸垂直スクロールバー設定
	MakeScrollInfo(cGraphData.m_stLevelRangeY, si, TRUE);
	m_cScrollBarY.SetScrollInfo(&si);

	// Ｚ軸垂直スクロールバー設定
	MakeScrollInfo(cGraphData.m_stLevelRangeZ, si, TRUE);
	m_cScrollBarZ.SetScrollInfo(&si);

	m_cDrawGraph.DispScrollBar(m_cScrollBarX, m_cScrollBarY, m_cScrollBarZ);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphView::MakeScrollInfo
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		スクロール情報設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SDispRange	&stDispRange		[I] 表示範囲情報
//		SCROLLINFO	&si					[O] スクロール情報
//		BOOL		bDir				[I] FASLE:水平スクロール　TRUE：垂直スクロール
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphView::MakeScrollInfo(const SDispRange &stDispRange, SCROLLINFO &si, BOOL bDir)
{
	memset(&si, 0, sizeof(si));
	si.cbSize = sizeof(si);
	si.fMask = SIF_PAGE | SIF_POS | SIF_RANGE;
	si.nMax = SCROLL_MAX;
	si.nMin = 0;
	si.nPage = stDispRange.fDisp <= stDispRange.fSpan ? (int)(SCROLL_MAX * stDispRange.fDisp / stDispRange.fSpan) : SCROLL_MAX;
	if (!bDir)
		si.nPos = (int)(SCROLL_MAX * (stDispRange.fBegin - stDispRange.fMin) / stDispRange.fSpan);
	else
		si.nPos = si.nMax - si.nPage - (int)(SCROLL_MAX * (stDispRange.fBegin - stDispRange.fMin) / stDispRange.fSpan);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphView::OnCreate
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ウィンドウ作成処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCREATESTRUCT		lpCreateStruct			[I] ウィンドウ作成情報構造体へのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		int		0：正常　-1：エラー
//*****************************************************************************************************
int CGraphView::OnCreate(LPCREATESTRUCT lpCreateStruct)
{
	if (CWnd::OnCreate(lpCreateStruct) == -1)
		return -1;

	// メモリデバイスコンテキストを作成
	CDC *pDC = GetDC();
	m_dcGraph.CreateCompatibleDC(pDC);
	ReleaseDC(pDC);

	// 縦軸のスクロールバーを作成
	CGraphFrame *pGraphFrame = (CGraphFrame *)GetParent();
	CRect rect(0, 0, 0, 0);
	m_cScrollBarX.Create(SBS_VERT | WS_CHILD | WS_VISIBLE, rect, this, IDC_SCROLL_X);
	if (pGraphFrame->m_nGraphKind == GRAPH_ACCEL || pGraphFrame->m_nGraphKind == GRAPH_ZERO_OFFSET) {
		m_cScrollBarY.Create(SBS_VERT | WS_CHILD | WS_VISIBLE, rect, this, IDC_SCROLL_Y);
		m_cScrollBarZ.Create(SBS_VERT | WS_CHILD | WS_VISIBLE, rect, this, IDC_SCROLL_Z);
	}

	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphView::OnLButtonDown
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
void CGraphView::OnLButtonDown(UINT nFlags, CPoint point)
{
	CGraphFrame *pGraphFrame = (CGraphFrame *)GetParent();
	CGraphData &cGraphData = pGraphFrame->m_cGraphData;

	// 時間カーソル位置設定
	pGraphFrame->SetTimeCursor(cGraphData.m_stTimeRange.fBegin + cGraphData.m_stTimeRange.fDisp * (point.x - m_cDrawGraph.m_rectScale.left) / m_cDrawGraph.m_rectScale.Width());

	if (cGraphData.m_fTimeCursor <= cGraphData.m_stTimeRange.fMin + cGraphData.m_fTimeDispEnd) {
		// 選択範囲を初期化
		m_fSelectStartTime = cGraphData.m_fSelectBegin = cGraphData.m_fSelectEnd = cGraphData.m_fTimeCursor;
		pGraphFrame->UpdateSelectArea();

		// マウスをキャプチャ
		SetCapture();

		// マウス移動モード設定
		m_nMouseMoveMode = MOUSE_MOVE_SELECT;
	}

	CWnd::OnLButtonDown(nFlags, point);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphView::OnLButtonUp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		左マウスボタンアップ処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nFlags			[I] 押されている仮想キー
//		CPoint		point			[I] カーソルの座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphView::OnLButtonUp(UINT nFlags, CPoint point)
{
	// マウスをリリース
	if (GetCapture()->GetSafeHwnd() == m_hWnd)
		ReleaseCapture();

	// マウス移動モード解除
	m_nMouseMoveMode = MOUSE_MOVE_NONE;

	CWnd::OnLButtonUp(nFlags, point);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphView::OnMouseMove
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		マウス移動処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nFlags			[I] 押されている仮想キー
//		CPoint		point			[I] カーソルの座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphView::OnMouseMove(UINT nFlags, CPoint point)
{
	CGraphFrame *pGraphFrame = (CGraphFrame *)GetParent();
	CGraphData &cGraphData = pGraphFrame->m_cGraphData;
	double fSelect;
	SDispRange *pLevelRange;

	switch (m_nMouseMoveMode) {
	case MOUSE_MOVE_SELECT:		// 選択中
		switch (pGraphFrame->m_nGraphKind) {
		case GRAPH_ACCEL:
			fSelect = cGraphData.m_stTimeRange.fBegin + cGraphData.m_stTimeRange.fDisp * (point.x - m_cDrawGraph.m_rectScale.left) / m_cDrawGraph.m_rectScale.Width();
			if (fSelect < cGraphData.m_stTimeRange.fBegin)
				fSelect = cGraphData.m_stTimeRange.fBegin;
			else if (fSelect > cGraphData.m_stTimeRange.fBegin + cGraphData.m_stTimeRange.fDisp)
				fSelect = cGraphData.m_stTimeRange.fBegin + cGraphData.m_stTimeRange.fDisp;
			if (fSelect > m_fSelectStartTime) {
				cGraphData.m_fSelectBegin = m_fSelectStartTime;
				cGraphData.m_fSelectEnd = fSelect;
			} else {
				cGraphData.m_fSelectBegin = fSelect;
				cGraphData.m_fSelectEnd = m_fSelectStartTime;
			}
			pGraphFrame->UpdateSelectArea();
			break;
		}
		break;
	case MOUSE_MOVE_SCROLL:		// スクロール中
		// スクロール中ならマウスの位置に合わせてウィンドウをスクロール
		CGraphFrame *pGraphFrame = (CGraphFrame *)GetParent();
		cGraphData.m_stTimeRange.fBegin = cGraphData.m_stTimeRange.fBegin + cGraphData.m_stTimeRange.fDisp * (m_pointScroll.x - point.x) / m_cDrawGraph.m_rectScale.Width();
		switch (m_nScroolGraph) {
		case 1:
			pLevelRange = &cGraphData.m_stLevelRangeX;
			break;
		case 2:
			pLevelRange = &cGraphData.m_stLevelRangeY;
			break;
		case 3:
			pLevelRange = &cGraphData.m_stLevelRangeZ;
			break;
		}
		pLevelRange->fBegin = pLevelRange->fBegin - pLevelRange->fDisp * (m_pointScroll.y - point.y) / m_cDrawGraph.m_rectScale.Height();
		pGraphFrame->UpdateGraph();
		m_pointScroll = point;
		break;
	}

	// 時間カーソル位置設定
	if (!m_bAutoScroll)
		pGraphFrame->SetTimeCursor(cGraphData.m_stTimeRange.fBegin + cGraphData.m_stTimeRange.fDisp * (point.x - m_cDrawGraph.m_rectScale.left) / m_cDrawGraph.m_rectScale.Width());

	CWnd::OnMouseMove(nFlags, point);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphView::OnRButtonDown
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		右マウスボタンダウン処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nFlags			[I] 押されている仮想キー
//		CPoint		point			[I] カーソルの座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphView::OnRButtonDown(UINT nFlags, CPoint point)
{
	m_nScroolGraph = m_cDrawGraph.CheckGraphPoint(point);
	if (m_nScroolGraph != 0) {
		// マウスポインタ位置を保存
		m_pointScroll = point;

		// マウスをキャプチャ
		SetCapture();

		// マウス移動モード設定
		m_nMouseMoveMode = MOUSE_MOVE_SCROLL;

		// マウスカーソルを設定
		m_hCursorSave = ::SetCursor(m_hCursorHandClose);
	}

	CWnd::OnRButtonDown(nFlags, point);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphView::OnRButtonUp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		右マウスボタンアップ処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nFlags			[I] 押されている仮想キー
//		CPoint		point			[I] カーソルの座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphView::OnRButtonUp(UINT nFlags, CPoint point)
{
	// マウスをリリース
	if (GetCapture()->GetSafeHwnd() == m_hWnd)
		ReleaseCapture();

	// マウスカーソルを戻す
	if (m_nMouseMoveMode == MOUSE_MOVE_SCROLL) {
		::SetCursor(m_hCursorHandClose);
		m_nMouseMoveMode = MOUSE_MOVE_NONE;
	}

	CWnd::OnRButtonUp(nFlags, point);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphView::DispSelectArea
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		選択範囲表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphView::DispSelectArea()
{
	CGraphFrame *pGraphFrame = (CGraphFrame *)GetParent();
	CGraphData &cGraphData = pGraphFrame->m_cGraphData;

	// 選択範囲を描画
	CBitmap *pBitmapOrg = m_dcGraph.SelectObject(&m_bmpGraph);
	m_cDrawGraph.DrawSelect(m_dcGraph, cGraphData, pGraphFrame->m_nGraphKind);
	m_dcGraph.SelectObject(pBitmapOrg);

	// クライアント領域を無効化
	Invalidate(FALSE);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphView::OnMouseWheel
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		マウスホイール処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nFlags			[I] 押されている仮想キー
//		short		zDelta			[I] 回転距離
//		CPoint		pt				[I] マウスの座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
BOOL CGraphView::OnMouseWheel(UINT nFlags, short zDelta, CPoint pt)
{
	CGraphFrame *pGraphFrame = (CGraphFrame *)GetParent();
	CGraphData &cGraphData = pGraphFrame->m_cGraphData;
	SDispRange *pLevelRange = NULL;

	ScreenToClient(&pt);
	switch (m_cDrawGraph.CheckGraphPoint(pt)) {
	case 1:
		pLevelRange = &cGraphData.m_stLevelRangeX;
		break;
	case 2:
		pLevelRange = &cGraphData.m_stLevelRangeY;
		break;
	case 3:
		pLevelRange = &cGraphData.m_stLevelRangeZ;
		break;
	}

	if (pLevelRange != NULL) {
		pLevelRange->fBegin = pLevelRange->fBegin + pLevelRange->fDisp * (zDelta / WHEEL_DELTA * 10) / m_cDrawGraph.m_rectScale.Height();
		pGraphFrame->UpdateGraph();
	}

	return TRUE;
}
