//*****************************************************************************************************
//  1. ファイル名
//		SchematicView.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		接続系統図・機能系統図・機能説明図のFlash表示用ビュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "stdafx.h"
#include "Schematic.h"
#include "SchematicFrm.h"
#include "SchematicView.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#endif

// CSchematicView

IMPLEMENT_DYNCREATE(CSchematicView, CWnd)

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::CSchematicView
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		コンストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
CSchematicView::CSchematicView()
{
	// メンバ変数を初期化する
	m_fZoomRatio = 1;
	m_bInScroll = FALSE;
	m_bScroll = FALSE;
	m_pMenuDlg = NULL;
	m_bQuickMode = FALSE;
	m_hCursorHandClose = AfxGetApp()->LoadCursor(IDC_HANDCLOSE);
}


BEGIN_MESSAGE_MAP(CSchematicView, CWnd)
	ON_WM_PAINT()
	ON_WM_SIZE()
	ON_WM_HSCROLL()
	ON_WM_VSCROLL()
	ON_WM_CREATE()
	ON_MESSAGE(WM_USER_ZOOM_IN, OnUserZoomIn)
	ON_MESSAGE(WM_USER_ZOOM_OUT, OnUserZoomOut)
	ON_MESSAGE(WM_USER_SIZE_RESET, OnUserSizeReset)
	ON_MESSAGE(WM_USER_PRINT, OnUserPrint)
	ON_MESSAGE(WM_USER_EXIT, OnUserExit)
	ON_MESSAGE(WM_USER_ACTION, OnUserAction)
	ON_MESSAGE(WM_USER_PAUSE, OnUserPause)
	ON_MESSAGE(WM_USER_SLOW, OnUserSlow)
	ON_MESSAGE(WM_USER_NORMAL, OnUserNormal)
	ON_WM_LBUTTONDOWN()
	ON_WM_LBUTTONUP()
	ON_WM_MOUSEMOVE()
#ifdef _DEVELOP
	ON_WM_RBUTTONUP()
	ON_COMMAND(IDM_QUICK_MODE, &CSchematicView::OnQuickMode)
	ON_COMMAND(IDM_SCREEN_COPY, &CSchematicView::OnScreenCopy)
	ON_COMMAND(IDM_START_ALL, &CSchematicView::OnStartAll)
#endif
END_MESSAGE_MAP()



// CSchematicView メッセージ ハンドラ

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::PreCreateWindow
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		コンストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		CREATESTRUCT&		cs			[I] ウィンドウ初期化パラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE
//*****************************************************************************************************
BOOL CSchematicView::PreCreateWindow(CREATESTRUCT& cs) 
{
	if (!CWnd::PreCreateWindow(cs))
		return FALSE;

	cs.dwExStyle |= WS_EX_CLIENTEDGE;
	cs.style &= ~WS_BORDER;
	cs.lpszClass = AfxRegisterWndClass(/*CS_HREDRAW|CS_VREDRAW|*/CS_DBLCLKS, 
		::LoadCursor(NULL, IDC_ARROW), reinterpret_cast<HBRUSH>(COLOR_WINDOW+1), NULL);

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::OnCreate
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		フレームウィンドウ作成時処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		LPCREATESTRUCT		lpCreateStruct			[I] ウィンドウ初期化パラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		int		0
//*****************************************************************************************************
int CSchematicView::OnCreate(LPCREATESTRUCT lpCreateStruct)
{
	if (CWnd::OnCreate(lpCreateStruct) == -1)
		return -1;

	// フレームウィンドウのポインタを保存
	m_pSchematicFrm = (CSchematicFrame *)GetParent();

	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::OnPaint
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ペイント(WM_PAINT)メッセージハンドラ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CSchematicView::OnPaint() 
{
	// 表示はFlashが行うので、ビュー自体は描画は行わない
	CPaintDC dc(this);
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::ShowSchematic
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		図表ウィンドウ表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		CRuntimeClass	*pMenuClass			[I] 操作メニュークラス
//		CString			&sFlashFileName		[I] Flashのファイル名
//		int				nActionCode			[I] アクションコード
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CSchematicView::ShowSchematic(CRuntimeClass *pMenuClass, const CString &sFlashFileName, int nActionCode)
{
	// Flashウィンドウの大きさを初期化
	InitFlashRect();

	// Flashウィンドウを作成
	if (m_cFlash.m_hWnd != NULL)
		m_cFlash.DestroyWindow();
	m_cFlash.Create(NULL, NULL, WS_CHILD, m_rectFlash, this, 0);

	// Flashウィンドウをスクロール
	ScrollFlashWindow();

	// swfファイルを表示
	CString sFullPath;
	_fullpath(sFullPath.GetBuffer(MAX_PATH), CSchematic::m_sFlashDir + "\\" + sFlashFileName, MAX_PATH);
	sFullPath.ReleaseBuffer();
	sFullPath += "?";
	m_cFlash.LoadMovie(0, sFullPath);

	// ステージのサイズを取得
	m_sizeStage.cx = atoi(m_cFlash.GetVariable("stageWidth"));
	m_sizeStage.cy = atoi(m_cFlash.GetVariable("stageHeight"));

	if (nActionCode != 0)
		FlashAction(nActionCode, NULL);
	m_cFlash.ShowWindow(SW_SHOW);

	// 操作メニューを表示
	if (m_pMenuDlg != NULL)
		m_pMenuDlg->DestroyWindow();
	if (pMenuClass != NULL) {
		m_pMenuDlg = (CMenuCommon *)pMenuClass->CreateObject();
		if (m_pMenuDlg != NULL)
			m_pMenuDlg->DoModeless(this);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::OnSize
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ウィンドウサイズ変更メッセージ(WM_SIZE)ハンドラ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		UINT	nType		[I] サイズ変更のタイプ
//		int		cx			[I] クライアント領域の幅
//		int		cy			[I] クライアント領域の高さ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CSchematicView::OnSize(UINT nType, int cx, int cy)
{
	CWnd::OnSize(nType, cx, cy);

	// 現在のビューサイズを保存
	m_sizeView.SetSize(cx, cy);

	// Flashウィンドウのスクロール処理
	if (m_cFlash.m_hWnd != NULL)
		ScrollFlashWindow();
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::OnHScroll
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		水平スクロールメッセージ(WM_HSCROLL)ハンドラ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		UINT		nSBCode			[I] スクロール要求コード
//		UINT		nPos			[I] スクロールボックスの位置
//		CScrollBar*	pScrollBar		[I] スクロールバーコントロール
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CSchematicView::OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar)
{
	// Flashウィンドウの座標を更新
	m_rectFlash.MoveToX(-GetNextScrollPos(SB_HORZ, nSBCode, nPos));

	// Flashウィンドウのスクロール処理
	ScrollFlashWindow();

	CWnd::OnHScroll(nSBCode, nPos, pScrollBar);
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::OnVScroll
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		垂直スクロールメッセージ(WM_VSCROLL)ハンドラ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		UINT		nSBCode			[I] スクロール要求コード
//		UINT		nPos			[I] スクロールボックスの位置
//		CScrollBar*	pScrollBar		[I] スクロールバーコントロール
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CSchematicView::OnVScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar)
{
	// Flashウィンドウの座標を更新
	m_rectFlash.MoveToY(-GetNextScrollPos(SB_VERT, nSBCode, nPos));

	// Flashウィンドウのスクロール処理
	ScrollFlashWindow();

	CWnd::OnVScroll(nSBCode, nPos, pScrollBar);
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::OnLButtonDown
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		左マウスボタンダウンメッセージ(WM_LBUTTONDOWN)ハンドラ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		UINT	nFlags			[I] 押されている仮想キー
//		CPoint	point			[I] マウスカーソルの座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CSchematicView::OnLButtonDown(UINT nFlags, CPoint point)
{
	if (GetStyle() & (WS_HSCROLL | WS_VSCROLL)) {
		// マウスポインタ位置を保存
		m_pointScroll = point;

		// マウスをキャプチャ
		SetCapture();
	}

	CWnd::OnLButtonDown(nFlags, point);
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::OnLButtonUp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		左マウスボタンアップメッセージ(WM_LBUTTONUP)ハンドラ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		UINT	nFlags			[I] 押されている仮想キー
//		CPoint	point			[I] マウスカーソルの座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CSchematicView::OnLButtonUp(UINT nFlags, CPoint point)
{
	// マウスカーソルを元に戻してスクロールを終了する
	if (GetCapture()->GetSafeHwnd() == m_hWnd) {
		::SetCursor(m_hCursorHandClose);
		m_bScroll = FALSE;
		ReleaseCapture();
	}

	CWnd::OnLButtonUp(nFlags, point);
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::OnMouseMove
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		マウス移動メッセージ(WM_MOUSEMOVE)ハンドラ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		UINT	nFlags			[I] 押されている仮想キー
//		CPoint	point			[I] マウスカーソルの座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CSchematicView::OnMouseMove(UINT nFlags, CPoint point)
{
	if (m_bScroll) {
		// スクロール中ならマウスの位置に合わせてウィンドウをスクロール
		m_rectFlash.MoveToX(-GetNextScrollPos(SB_HORZ, SB_THUMBTRACK, m_pointScroll.x - point.x - m_rectFlash.left));
		m_rectFlash.MoveToY(-GetNextScrollPos(SB_VERT, SB_THUMBTRACK, m_pointScroll.y - point.y - m_rectFlash.top));
		ScrollFlashWindow();
		m_cFlash.UpdateWindow();
		m_pointScroll = point;
	} else {
		// 左マウスボタンが押されていればマウスカーソルを変更し、スクロール中フラグをON
		if (GetCapture()->GetSafeHwnd() == m_hWnd) {
			m_bScroll = TRUE;
			m_hCursorSave = ::SetCursor(m_hCursorHandClose);
		}
	}

	CWnd::OnMouseMove(nFlags, point);
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::PreTranslateMessage
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ウィンドウメッセージのフィルタ処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		MSG*	pMsg			[I] メッセージデータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	CWnd::PreTranslateMessage(pMsg)
//*****************************************************************************************************
BOOL CSchematicView::PreTranslateMessage(MSG* pMsg)
{
	switch (pMsg->message) {
	case WM_LBUTTONDOWN:
	case WM_LBUTTONUP:
	case WM_RBUTTONDOWN:
	case WM_RBUTTONUP:
	case WM_MOUSEMOVE:
		// Flashウィンドウの下のビューウィンドウにマウスメッセージを転送する
		{
			char sClassName[256];
			GetClassName(pMsg->hwnd, sClassName, sizeof(sClassName));
			if (strcmp(sClassName, "MacromediaFlashPlayerActiveX") == 0) {
				if (pMsg->message == WM_RBUTTONDOWN)
					return TRUE;
				CPoint point((int)(pMsg->lParam & 0xFFFF), (int)((pMsg->lParam >> 16) & 0xFFFF));
				HWND hParent = ::GetParent(pMsg->hwnd);
				if (hParent != NULL) {
					::MapWindowPoints(pMsg->hwnd, hParent, &point, 1);
					::SendMessage(hParent, pMsg->message, pMsg->wParam, point.x | (point.y << 16));
				}
			}
		}
		break;
	}

	return CWnd::PreTranslateMessage(pMsg);
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::OnUserZoomIn
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		操作メニューの「拡大」ボタン処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		WPARAM	wParam			[I] メッセージパラメータ
//		LPARAM	lParam			[I] メッセージパラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		LRESULT		拡大してもFlashのサイズが4096以内ならばTRUE、超える場合はFALSE（4096が限度のため）
//*****************************************************************************************************
LRESULT CSchematicView::OnUserZoomIn(WPARAM wParam, LPARAM lParam)
{
	// 1.25倍に拡大する
	SetZoomRatio(m_fZoomRatio * 1.25);

	// 再度拡大可能かチェックする
	double fScaleX = m_rectFlash.Width() / (double)m_sizeStage.cx;
	double fScaleY = m_rectFlash.Height() / (double)m_sizeStage.cy;
	double fScale = min(fScaleX, fScaleY);
	int nRight = (m_rectFlash.Width() + (int)(m_sizeStage.cx * fScale)) / 2;
	int nBottom = (m_rectFlash.Height() + (int)(m_sizeStage.cy * fScale)) / 2;
	return max(nRight, nBottom) * 1.25 <= 4096;
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::OnUserZoomOut
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		操作メニューの「縮小」ボタン処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		WPARAM	wParam			[I] メッセージパラメータ
//		LPARAM	lParam			[I] メッセージパラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		LRESULT		拡大率が0.25以上ならTRUE、それ以外はFALSE
//*****************************************************************************************************
LRESULT CSchematicView::OnUserZoomOut(WPARAM wParam, LPARAM lParam)
{
	// 1/1.25に縮小する
	SetZoomRatio(m_fZoomRatio / 1.25);

	return m_fZoomRatio >= 0.25;
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::OnUserSizeReset
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		操作メニューの「サイズリセット」ボタン処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		WPARAM	wParam			[I] メッセージパラメータ
//		LPARAM	lParam			[I] メッセージパラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		LRESULT	0
//*****************************************************************************************************
LRESULT CSchematicView::OnUserSizeReset(WPARAM wParam, LPARAM lParam)
{
	// スクロールバーを消す
	m_bInScroll = TRUE;
	ShowScrollBar(SB_BOTH, FALSE);
	m_bInScroll = FALSE;

	// Flashウィンドウをスクロールする
	InitFlashRect();
	ScrollFlashWindow();

	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::OnUserPrint
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		操作メニューの「印刷」ボタン処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		WPARAM	wParam			[I] メッセージパラメータ
//		LPARAM	lParam			[I] メッセージパラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		LRESULT	0
//*****************************************************************************************************
LRESULT CSchematicView::OnUserPrint(WPARAM wParam, LPARAM lParam)
{
	CRect rectView(0, 0, m_sizeView.cx, m_sizeView.cy);
	double fStageWidth = m_sizeStage.cx;
	double fStageHeight = m_sizeStage.cy;
	double fRatio = min(m_rectFlash.Width() / fStageWidth, m_rectFlash.Height() / fStageHeight);
	CPoint pointCenter = m_rectFlash.CenterPoint();
	CPoint pointLeftTop(pointCenter.x - (int)(fStageWidth / 2 * fRatio), pointCenter.y - (int)(fStageHeight / 2 * fRatio));
	rectView.MoveToXY(-pointLeftTop);

	CString sTitle;
	GetParent()->GetWindowText(sTitle);

	CString sParam;
	sParam.Format("<arguments><number>%d</number><number>%d</number><number>%d</number><number>%d</number><string>%s</string></arguments>",
		(int)(rectView.left / fRatio),
		(int)(rectView.top / fRatio),
		(int)(rectView.right / fRatio),
		(int)(rectView.bottom / fRatio),
		sTitle);
	CallFlashFunction("extPrint", sParam);
/*
	CPrintDialog dlg(FALSE);

	// 用紙を横向きに設定
	dlg.GetDefaults();
	LPDEVMODE lpDevInfo = dlg.GetDevMode();
	lpDevInfo->dmOrientation = DMORIENT_LANDSCAPE;
    dlg.m_pd.Flags &= ~PD_RETURNDEFAULT;

	if(dlg.DoModal() == IDOK) {
		// 印刷用CDC取得
		CDC	dc;
		dc.Attach(dlg.GetPrinterDC());

		int nWidth = dc.GetDeviceCaps(HORZRES);
		int nHeight = dc.GetDeviceCaps(VERTRES);

		CDC dcMem;
		CBitmap bmpMem;
		dcMem.CreateCompatibleDC(&dc);
		bmpMem.CreateCompatibleBitmap(&dc, nWidth, nHeight);
		dcMem.SelectObject(&bmpMem);

		// DOCINFOを用意
		DOCINFO	docinfo;
		memset(&docinfo, 0, sizeof(docinfo));
		docinfo.cbSize  = sizeof(docinfo);
		docinfo.lpszDocName = "Test";

		// 印刷開始
		dc.StartDoc(&docinfo);

		// ページ印刷開始
		dc.StartPage();
		m_cFlash.PrintWindow(&dcMem, 0);
		dc.StretchBlt(0, 0, nWidth, nHeight, &dcMem, 0, 0, m_rectFlash.Width(), m_rectFlash.Height(), SRCCOPY);

		// ページ印刷終了
		dc.EndPage();

		// 印刷終了
		dc.EndDoc();
	}
*/
	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::OnUserExit
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		操作メニューの「終了」ボタン処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		WPARAM	wParam			[I] メッセージパラメータ
//		LPARAM	lParam			[I] メッセージパラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		LRESULT	0
//*****************************************************************************************************
LRESULT CSchematicView::OnUserExit(WPARAM wParam, LPARAM lParam)
{
	// 親ウィンドウ（フレームウィンドウ）を閉じる
	GetParent()->SendMessage(WM_CLOSE);

	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::OnUserAction
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		操作メニューからの各操作ボタン処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		WPARAM	wParam			[I] メッセージパラメータ
//		LPARAM	lParam			[I] メッセージパラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		LRESULT	0
//*****************************************************************************************************
LRESULT CSchematicView::OnUserAction(WPARAM wParam, LPARAM lParam)
{
	// Flashに動作指示コマンドを送信する
	FlashAction((int)wParam, (CUIntArray *)lParam);

	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::OnUserPause
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		操作メニューからの一時停止ボタン処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		WPARAM	wParam			[I] メッセージパラメータ
//		LPARAM	lParam			[I] メッセージパラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		LRESULT	0
//*****************************************************************************************************
LRESULT CSchematicView::OnUserPause(WPARAM wParam, LPARAM lParam)
{
	// Flashに動作指示コマンドを送信する
	CUIntArray aParams;
	aParams.Add((UINT)wParam);
	FlashAction(ACTION_PAUSE, &aParams);

	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::OnUserSlow
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		操作メニューからの低速ボタン処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		WPARAM	wParam			[I] メッセージパラメータ
//		LPARAM	lParam			[I] メッセージパラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		LRESULT	0
//*****************************************************************************************************
LRESULT CSchematicView::OnUserSlow(WPARAM wParam, LPARAM lParam)
{
	// Flashに動作指示コマンドを送信する
	FlashAction(ACTION_SLOW, NULL);

	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::OnUserNormal
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		操作メニューからの通常ボタン処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		WPARAM	wParam			[I] メッセージパラメータ
//		LPARAM	lParam			[I] メッセージパラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		LRESULT	0
//*****************************************************************************************************
LRESULT CSchematicView::OnUserNormal(WPARAM wParam, LPARAM lParam)
{
	// Flashに動作指示コマンドを送信する
	FlashAction(ACTION_NORMAL, NULL);

	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::FlashAction
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		Flashに動作指示コマンド送信処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		int			nActionCode			[I] アクションコード
//		CUIntArray	*pParams			[I] 付加パラメータの配列
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CSchematicView::FlashAction(int nActionCode, CUIntArray *pParams)
{
	CString sParams;
	CString sParam;

	// 動作コード
	sParams.Format("<number>%d</number>", nActionCode);

	// 付加パラメータ編集
	if (pParams != NULL) {
		for (int i = 0; i < pParams->GetSize(); i++) {
			sParam.Format("<number>%d</number>", pParams->ElementAt(i));
			sParams += sParam;
		}
	}

	// パラメータ
	sParams = "<arguments>" + sParams + "</arguments>";

	// Flash内の"extAction"関数を呼び出す
	CallFlashFunction("extAction", sParams);
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::PostNcDestroy
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ウィンドウ破棄の最終処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CSchematicView::PostNcDestroy()
{
	CWnd::PostNcDestroy();

	// 自分自身を削除
	delete this;
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::InitFlashRect
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		初期状態のウィンドウサイズを設定する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CSchematicView::InitFlashRect()
{
	// ズーム倍率を1にする
	m_fZoomRatio = 1;
	m_sizeDefault = m_sizeView;
	m_rectFlash.SetRect(0, 0, m_sizeDefault.cx, m_sizeDefault.cy);
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::GetNextScrollPos
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		スクロールバーを操作したときのスクロール位置を取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		int		nBar			[I] スクロールバーの種類（縦・横）
//		UINT	nSBCode			[I] スクロール要求コード
//		UINT	nPos			[I] スクロールボックスの位置
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		int		次のスクロールボックスの位置
//*****************************************************************************************************
int CSchematicView::GetNextScrollPos(int nBar, UINT nSBCode, UINT nPos)
{
	SCROLLINFO si;
	si.fMask = SIF_PAGE | SIF_POS | SIF_RANGE;
	GetScrollInfo(nBar, &si);
	int nNextPos = si.nPos;

	switch (nSBCode) {
	case SB_TOP:
		nNextPos = 0;
		break;
	case SB_BOTTOM:
		nNextPos = INT_MAX;
		break;
	case SB_LINEUP:
		nNextPos -= si.nMax / 100;
		break;
	case SB_LINEDOWN:
		nNextPos += si.nMax / 100;
		break;
	case SB_PAGEUP:
		nNextPos -= si.nMax / 10;
		break;
	case SB_PAGEDOWN:
		nNextPos += si.nMax / 10;
		break;
	case SB_THUMBTRACK:
		nNextPos = nPos;
		break;
	}

	if (nNextPos < si.nMin)
		nNextPos = si.nMin;
	if (nNextPos > si.nMax - (int)si.nPage)
		nNextPos = si.nMax - si.nPage;

	return nNextPos;
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::ScrollFlashWindow
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		Flashウィンドウをスクロールする
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CSchematicView::ScrollFlashWindow()
{
	// 再入を防ぐ
	if (m_bInScroll)
		return;

	m_bInScroll = TRUE;

	// 水平スクロールバーを設定
	if (0 > m_rectFlash.left || m_sizeView.cx < m_rectFlash.right) {
		if (m_sizeView.cx > m_rectFlash.right)
			m_rectFlash.MoveToX(m_rectFlash.left + m_sizeView.cx - m_rectFlash.right);

		SCROLLINFO si;
		si.fMask = SIF_PAGE | SIF_RANGE | SIF_POS;
		si.nMin = 0;
		si.nMax = m_rectFlash.Width();
		si.nPage = m_sizeView.cx;
		si.nPos = -m_rectFlash.left;
		SetScrollInfo(SB_HORZ, &si);
		ShowScrollBar(SB_HORZ, TRUE);
	} else
		ShowScrollBar(SB_HORZ, FALSE);

	// 垂直スクロールバーを設定
	if (0 > m_rectFlash.top || m_sizeView.cy < m_rectFlash.bottom) {
		if (m_sizeView.cy > m_rectFlash.bottom)
			m_rectFlash.MoveToY(m_rectFlash.top + m_sizeView.cy - m_rectFlash.bottom);

		SCROLLINFO si;
		si.fMask = SIF_PAGE | SIF_RANGE | SIF_POS;
		si.nMin = 0;
		si.nMax = m_rectFlash.Height();
		si.nPage = m_sizeView.cy;
		si.nPos = -m_rectFlash.top;
		SetScrollInfo(SB_VERT, &si);
		ShowScrollBar(SB_VERT, TRUE);
	} else
		ShowScrollBar(SB_VERT, FALSE);

	// Flashウィンドウをスクロール位置に移動する
	m_cFlash.MoveWindow(m_rectFlash, FALSE);
	m_cFlash.UpdateWindow();

	m_bInScroll = FALSE;
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::SetZoomRatio
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		指定したズーム倍率によりFlashウィンドウを拡大・縮小する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		double		fZoomRatio			[I] ズーム倍率
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CSchematicView::SetZoomRatio(double fZoomRatio)
{
	double fZoom = fZoomRatio / m_fZoomRatio;

	// ズーム倍率によりFlashウィンドウの大きさと位置を計算する
	m_rectFlash.left = (int)((m_rectFlash.left - m_sizeView.cx / 2) * fZoom) + m_sizeView.cx / 2;
	m_rectFlash.top = (int)((m_rectFlash.top - m_sizeView.cy / 2) * fZoom) + m_sizeView.cy / 2;
	m_rectFlash.right = m_rectFlash.left + (int)(m_sizeDefault.cx * fZoomRatio);
	m_rectFlash.bottom = m_rectFlash.top + (int)(m_sizeDefault.cy * fZoomRatio);

	m_fZoomRatio = fZoomRatio;

	ScrollFlashWindow();
}

// Flashからのイベントマップ
BEGIN_EVENTSINK_MAP(CSchematicView, CWnd)
	ON_EVENT(CSchematicView, 0, 150, CSchematicView::FSCommandFlash, VTS_BSTR VTS_BSTR)
END_EVENTSINK_MAP()

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::FSCommandFlash
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		FlashからのFSCommandイベント処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		LPCTSTR		command			[I] コマンド
//		LPCTSTR		args			[I] パラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CSchematicView::FSCommandFlash(LPCTSTR command, LPCTSTR args)
{
	CString sCommand = command;
	CString sArgs = args;

	if (sCommand == "ActionEnd") {
		// アクション終了イベント
		m_pMenuDlg->ActionEnd(atoi(sArgs));
	} else if (sCommand == "Notify") {
		// アクション通知イベント
		if (sArgs == "CloseWindow")
			GetParent()->SendMessage(WM_CLOSE);
		else if (sArgs == "ActionStart")
			m_pMenuDlg->EnableSpeedButton(TRUE);
		else if (sArgs == "ActionEnd")
			m_pMenuDlg->EnableSpeedButton(FALSE);
	} else if (sCommand == "OpenFS") {
		// 機能系統図表示イベント
		CString sSchematicCode;
		sSchematicCode.Format("FS%02d", atoi(sArgs));
		CSchematic::OpenSchematicWindow(sSchematicCode);
	} else if (sCommand == "FS13") {
		// リミットスイッチのサブ画面表示イベント
		CSchematic::OpenSchematicWindow("FS13_2", atoi(sArgs));
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::CallFlashFunction
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		Flash内の関数呼び出し処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		LPCTSTR		pFunc			[I] 関数名
//		LPCTSTR		pParams			[I] パラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CSchematicView::CallFlashFunction(LPCTSTR pFunc, LPCTSTR pParams)
{
	// 関数呼び出しをXML形式で記述する
	CString sCommand;
	sCommand.Format("<invoke name=\"%s\" returntype=\"xml\">%s</invoke>", pFunc, pParams);

	try {
		// XMLの内容に従ってFlash内の関数を呼び出す
		m_cFlash.CallFunction(sCommand);
	} catch (CException *e) {
		// 呼び出しに失敗した場合は例外がスローされるのでそれをキャッチする
		AfxMessageBox("この機能は未実装です。");
		e->Delete();
	}
}

// 以下開発用機能
#ifdef _DEVELOP

#include "MenuFS08.h"

// 内容：符号付きの値を代入する変数には、符号付きの型にキャストしなければなりません
#pragma warning( disable : 4146 )	// リビルド時のWarningを非表示にするため
// 内容：'name' を自動的に除外し、タイプ ライブラリ 'library' をインポートします
#pragma warning( disable : 4192 )	// リビルド時のWarningを非表示にするため
// 内容：コンパイラの制限 : 行番号の出力を中止します。
#pragma warning( disable : 4049 )	// リビルド時のWarningを非表示にするため

// Excel
#import "C:\Program Files\Microsoft Office\Office\Mso9.dll" no_namespace rename("DocumentProperties", "DocumentPropertiesXL")
#import "C:\Program Files\Common Files\Microsoft Shared\VBA\VBA6\Vbe6ext.olb" no_namespace
#import "C:\Program Files\Microsoft Office\Office\excel9.olb" rename("DialogBox", "DialogBoxXL") rename("RGB", "RBGXL") rename("DocumentProperties", "DocumentPropertiesXL") no_dual_interfaces

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::OnRButtonUp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		右マウスボタンアップメッセージ(WM_RBUTTONUP)ハンドラ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		UINT	nFlags			[I] 押されている仮想キー
//		CPoint	point			[I] マウスカーソルの座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CSchematicView::OnRButtonUp(UINT nFlags, CPoint point)
{
	POINT posScreen;
	::GetCursorPos(&posScreen);

	CMenu menu;
	menu.LoadMenu(IDR_MENU_POPUP);
	CMenu* pPopup = menu.GetSubMenu(0);
	pPopup->CheckMenuItem(IDM_QUICK_MODE, m_bQuickMode ? MF_CHECKED : MF_UNCHECKED);
	pPopup->TrackPopupMenu(TPM_LEFTALIGN | TPM_RIGHTBUTTON, posScreen.x, posScreen.y, this);

	CWnd::OnRButtonUp(nFlags, point);
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::OnQuickMode
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		クイック動作モード設定（開発用）
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CSchematicView::OnQuickMode()
{
	m_bQuickMode = !m_bQuickMode;

	CallFlashFunction("setQuickMode", m_bQuickMode ? "<arguments><true/></arguments>" : "<arguments><false/></arguments>");
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::OnScreenCopy
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		画面コピーをエクセルに貼り付ける（開発用）
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CSchematicView::OnScreenCopy()
{
	CWnd *pParent = GetParent();
	CRect rectWindow;
	pParent->GetWindowRect(rectWindow);
	if (rectWindow.Width() != 1024 || rectWindow.Height() != 768) {
		pParent->SetWindowPos(NULL, 0, 0, 1024, 768, SWP_NOMOVE | SWP_NOZORDER);
		OnUserSizeReset(0, 0);
		pParent->Invalidate();
		pParent->UpdateWindow();
	}

	::CoInitialize(NULL);

	try {
		Excel::_ApplicationPtr	pExcel;
		Excel::WorkbooksPtr		pBooks;
		Excel::_WorkbookPtr		pBook;
		Excel::SheetsPtr		pSheets;
		Excel::_WorksheetPtr	pSheet;
		CString sSheetName;
		CString sNewName;
		_variant_t cell;

		pExcel.GetActiveObject("Excel.Application");
		pBooks = pExcel->Workbooks;
		if (pBooks != NULL) {
			pBook = pBooks->Item[1];
			if (pBook != NULL) {
				pSheets = pBook->Worksheets;
				if (pSheets != NULL) {
					pSheet = pSheets->Item[1];
					sSheetName = (LPCTSTR)pSheet->Name;
					if (sSheetName.Right(2) == "%d") {
						sNewName.Format(sSheetName, pSheets->Count);
						Excel::_WorksheetPtr pSheet2 = pSheets->Item[pSheets->Count];
						VARIANT v;
						v.vt = VT_DISPATCH;
						v.pdispVal = pSheet2;
						pSheet->Copy(vtMissing, v);
						pSheet = pSheets->Item[pSheets->Count];
						pSheet->Name = (LPCTSTR)sNewName;
						cell = pSheet->Cells->GetItem((long)3, (long)1);
						pSheet->GetRange(cell, cell)->Activate();
					} else
						pSheet = pBook->ActiveSheet;

					if (pSheet != NULL) {
						ScreenShot(GetParent());
						pSheet->Paste();

						CWnd *pMenuDlg = m_pMenuDlg;
						if (pMenuDlg != NULL) {
							if (pMenuDlg->IsKindOf(RUNTIME_CLASS(CMenuFS08))) {
								CMenuFS08 *pMenuFS08 = (CMenuFS08 *)pMenuDlg;
								if (pMenuFS08->m_pSubMenu != NULL)
									pMenuDlg = pMenuFS08->m_pSubMenu;
							}

							ScreenShot(pMenuDlg);
							cell = pSheet->Cells->GetItem(pExcel->ActiveCell->Row, (long)16);
							pSheet->GetRange(cell, cell)->Activate();
							pSheet->Paste();
						}

						cell = pSheet->Cells->GetItem((long)1, (long)1);
						pSheet->GetRange(cell, cell)->Activate();
					}
				}
			}
		}
	} catch (_com_error &e) {
		AfxMessageBox(e.Description());
	}

	::CoUninitialize();
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::ScreenShot
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		指定されたウィンドウの画面コピーをエクセルに貼り付ける（開発用）
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		CWnd	*pWnd			[I] 画面コピーを取るウィンドウ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CSchematicView::ScreenShot(CWnd *pWnd)
{
	CDC dcMem;
	CBitmap bmpMem;
	CRect rect;

	pWnd->SetActiveWindow();
	CDC *pDC = pWnd->GetWindowDC();
	dcMem.CreateCompatibleDC(pDC);
	pWnd->GetWindowRect(rect);
	bmpMem.CreateCompatibleBitmap(pDC, rect.Width(), rect.Height());
	ReleaseDC(pDC);
	dcMem.SelectObject(&bmpMem);
	pWnd->PrintWindow(&dcMem, 0);

	OpenClipboard();
	EmptyClipboard();
	SetClipboardData(CF_BITMAP, bmpMem.m_hObject);
	CloseClipboard();
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematicView::OnStartAll
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		全部品を動作状態にする（開発用）
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CSchematicView::OnStartAll()
{
	CallFlashFunction("startAll", "");
}

#endif
