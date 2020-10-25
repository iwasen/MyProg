//*****************************************************************************************************
//  1. ファイル名
//		GraphFrm.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフのフレームウィンドウクラスの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//		無し
//*****************************************************************************************************

#include "stdafx.h"
#include "MmeRecv.h"
#include "GraphFrm.h"
#include <math.h>

#define WM_READY_GRAPH	(WM_APP + 0)

#define SCROLL_MAX	2000

// CGraphFrm

IMPLEMENT_DYNCREATE(CGraphFrm, CFrameWnd)

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrm::CGraphFrm
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
CGraphFrm::CGraphFrm()
{
	m_hThread = NULL;
	m_bExit = FALSE;
	m_fTimeZoom = 1;
	m_fLevelZoom = 1;
	m_fTimeOffset = 0;
	m_fLevelOffset = 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrm::~CGraphFrm
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		デストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
CGraphFrm::~CGraphFrm()
{
	// ワーカスレッドスレッドの終了を待つ
	if (m_hThread != NULL) {
		m_bExit = TRUE;
		m_oEvent.SetEvent();
		::WaitForSingleObject(m_hThread, 1000);
	}
}


BEGIN_MESSAGE_MAP(CGraphFrm, CFrameWnd)
	ON_WM_CREATE()
	ON_COMMAND(IDM_CLOSE, &CGraphFrm::OnClose)
	ON_MESSAGE(WM_READY_GRAPH, &CGraphFrm::OnReadyGraph)
END_MESSAGE_MAP()


// CGraphFrm メッセージ ハンドラ

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrm::PreCreateWindow
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
BOOL CGraphFrm::PreCreateWindow(CREATESTRUCT& cs)
{
	if( !CFrameWnd::PreCreateWindow(cs) )
		return FALSE;

	cs.dwExStyle &= ~WS_EX_CLIENTEDGE;
	cs.lpszClass = AfxRegisterWndClass(0);

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrm::OnCreate
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
int CGraphFrm::OnCreate(LPCREATESTRUCT lpCreateStruct)
{
	static const UINT indicators[] = {
		IDS_STATUS_BAR,
	};

	if (CFrameWnd::OnCreate(lpCreateStruct) == -1)
		return -1;

	// ビューウィンドウを作成
	if (!m_wndGraphView.CreateEx(WS_EX_CLIENTEDGE, NULL, NULL, WS_CHILD | WS_VISIBLE | WS_HSCROLL | WS_VSCROLL, CRect(0, 0, 0, 0), this, AFX_IDW_PANE_FIRST, NULL)) {
		TRACE0("ビュー ウィンドウを作成できませんでした。\n");
		return -1;
	}

	// スクロールバー設定
	SCROLLINFO si;
	memset(&si, 0, sizeof(si));
	si.cbSize = sizeof(si);
	si.fMask = SIF_PAGE | SIF_RANGE;
	si.nPage = SCROLL_MAX;
	si.nMax = SCROLL_MAX;
	m_wndGraphView.SetScrollInfo(SB_HORZ, &si, FALSE);
	m_wndGraphView.SetScrollInfo(SB_VERT, &si, FALSE);

	// ステータスバーを作成
	if (!m_wndStatusBar.Create(this) || !m_wndStatusBar.SetIndicators(indicators, sizeof(indicators) / sizeof(UINT))) {
		TRACE0("ステータス バーの作成に失敗しました。\n");
		return -1;
	}
	m_wndStatusBar.SetPaneStyle(0, SBPS_NOBORDERS | SBPS_STRETCH);

	// ダイアログバーを作成
	if (!m_wndGraphBar.Create(this, IDD_GRAPH_BAR, CBRS_BOTTOM, (UINT)-1)) {
		TRACE0("ダイアログ バーの作成に失敗しました。\n");
		return -1;
	}

	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrm::OnClose
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		Closeボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrm::OnClose()
{
	DestroyWindow();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrm::DispGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		受信ファイルのグラフ表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCTSTR		pReceiveFilePath	[I] 受信ファイル(.s)ファイルパス
//		SGraphLine	&oGraphLine			[I] グラフデータライン表示情報
//		LPCTSTR		pTitle				[I] ウィンドウタイトル
//		CWnd		*pParentWnd			[I] 親ウィンドウポインタ
//		double		fTempRange1			[I] 温度範囲下限
//		double		fTempRange2			[I] 温度範囲上限
//		double		fHeaterRange1		[I] ヒータ電圧下限
//		double		fHeaterRange2		[I] ヒータ電圧上限
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrm::DispGraph(LPCTSTR pReceiveFilePath, SGraphLine &oGraphLine, LPCTSTR pTitle, CWnd *pParentWnd, double fTempRange1, double fTempRange2, double fHeaterRange1, double fHeaterRange2)
{
	m_sReceiveFilePath = pReceiveFilePath;
	m_oGraphLine = oGraphLine;
	m_fTempRange1 = fTempRange1;
	m_fTempRange2 = fTempRange2;
	m_fHeaterRange1 = fHeaterRange1;
	m_fHeaterRange2 = fHeaterRange2;

	// フレームをリソースからロードして作成
	LoadFrame(IDR_GRAPHFRAME, WS_OVERLAPPEDWINDOW, pParentWnd, NULL);
	SetIcon(AfxGetApp()->LoadIcon(IDR_GRAPHFRAME), FALSE);

	// ウィンドウタイトル設定
	SetWindowText(pTitle);

	// ステータスバーにファイル読み込み中をセット
	m_wndStatusBar.SetPaneText(0, "Reading File");

	// 表示と更新
	ShowWindow(SW_SHOW);

	// ファイル読み込みスレッド開始（受信処理に影響を及ぼさないように別スレッドで行う）
	m_hThread = AfxBeginThread(ReadFileThreadEntry, this, THREAD_PRIORITY_LOWEST)->m_hThread;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrm::ReadFileThreadEntry
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		受信(.s)ファイル読み込みスレッドエントリ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPVOID		pParam		[I] スレッドパラメータ(thisポインタ）
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		UINT		0
//*****************************************************************************************************
UINT CGraphFrm::ReadFileThreadEntry(LPVOID pParam)
{
	((CGraphFrm *)pParam)->ReadFileThread();
	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrm::ReadFileThread
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		受信(.s)ファイル読み込みスレッド処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrm::ReadFileThread()
{
	// ファイル読み込み処理
	ReadFile(m_sReceiveFilePath);

	// メインスレッドに読み込み完了を通知
	if (!m_bExit)
		PostMessage(WM_READY_GRAPH);

	m_hThread = NULL;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrm::ReadFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		受信(.s)ファイル読み込み処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCTSTR		pReceiveNamePath		[I] 受信ファイルパス
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrm::ReadFile(LPCTSTR pReceiveNamePath)
{
	CFile oFile;
	struct {
		char aTime[8];
		BYTE aUserHeader[USER_HEADER_SIZE];
	} oHeaderBuf;
	BYTE aAnalogDataBuf[ANALOG_DATA_SIZE];
	SUserHeader oUserHeader;
	SAnalogData oAnalogData;
	SMmeStatusData oMmeStatusData;
	int nHour, nMinute, nSecond;

	// 読み込みファイルをオープン
	if (!oFile.Open(pReceiveNamePath, CFile::modeRead | CFile::shareDenyWrite))
		return;

	// ファイルからデータを読み出す
	while (!m_bExit) {
		// ヘッダ部分を読み込む
		if (oFile.Read(&oHeaderBuf, sizeof(oHeaderBuf)) != sizeof(oHeaderBuf))
			break;

		// データ受信時刻フォーマットチェック
		if (sscanf_s(oHeaderBuf.aTime, "%2d:%2d:%2d", &nHour, &nMinute, &nSecond) != 3)
			return;

		// ユーザヘッダ情報を取り出す
		g_oMmeData.GetUserHeader(oHeaderBuf.aUserHeader, oUserHeader);
		switch (oUserHeader.nPacketType) {
		case PACKET_TELEMETRY:
			// セグメントカウントが0ならばアナログデータを読み込む
			if (oUserHeader.nSegmentCount == 0) {
				if (oFile.Read(aAnalogDataBuf, sizeof(aAnalogDataBuf)) != sizeof(aAnalogDataBuf))
					return;

				g_oMmeData.GetAnalogData(aAnalogDataBuf, oUserHeader.oTelemetry.oStatusWord, oAnalogData);
				m_oGraphData.AddGraphData(oUserHeader.oTimestamp, oUserHeader.oTelemetry.oStatusWord, oAnalogData);
			}

			// デジタルデータ部分は読み飛ばす
			oFile.Seek(oUserHeader.oTelemetry.nDataNum * DIGITAL_DATA_SIZE, CFile::current);
			break;
		case PACKET_MME_STATUS:
			// MMEステータスデータのアナログデータまでのデータを読み飛ばす
			oFile.Seek(40, CFile::current);

			// アナログデータを読み込む
			if (oFile.Read(aAnalogDataBuf, sizeof(aAnalogDataBuf)) != sizeof(aAnalogDataBuf))
				return;

			// グラフに追加
			g_oMmeData.GetAnalogData(aAnalogDataBuf, oUserHeader.oMmeStatus.oStatusWord, oAnalogData);
			m_oGraphData.AddGraphData(oUserHeader.oTimestamp, oUserHeader.oMmeStatus.oStatusWord, oAnalogData);

			// 前回HALT時エラー情報を読み飛ばす
			oFile.Seek(24, CFile::current);
			break;
		case PACKET_ERROR_STATUS:
			// エラーステータスのデータ部分を読み飛ばす
			oFile.Seek(oUserHeader.oErrorStatus.nDataNum, CFile::current);
			break;
		case PACKET_MESSAGE:
			// メッセージのデータ部分を読み飛ばす
			oFile.Seek(oUserHeader.oMessage.nDataNum, CFile::current);
			break;
		}
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrm::DispGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		受信中のグラフ表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CGraphData	&oGraphData			[I] グラフデータ
//		SGraphLine	&oGraphLine			[I] グラフデータライン表示情報
//		LPCTSTR		pTitle				[I] ウィンドウタイトル
//		CWnd		*pParentWnd			[I] 親ウィンドウポインタ
//		double		fTempRange1			[I] 温度範囲下限
//		double		fTempRange2			[I] 温度範囲上限
//		double		fHeaterRange1		[I] ヒータ電圧下限
//		double		fHeaterRange2		[I] ヒータ電圧上限
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrm::DispGraph(CGraphData &oGraphData, SGraphLine &oGraphLine, LPCTSTR pTitle, CWnd *pParentWnd, double fTempRange1, double fTempRange2, double fHeaterRange1, double fHeaterRange2)
{
	m_pGraphData = &oGraphData;
	m_oGraphLine = oGraphLine;
	m_fTempRange1 = fTempRange1;
	m_fTempRange2 = fTempRange2;
	m_fHeaterRange1 = fHeaterRange1;
	m_fHeaterRange2 = fHeaterRange2;

	// フレームをリソースからロードして作成
	LoadFrame(IDR_GRAPHFRAME, WS_OVERLAPPEDWINDOW, pParentWnd, NULL);

	// ウィンドウタイトルを設定
	SetWindowText(pTitle);

	// 表示と更新
	ShowWindow(SW_SHOW);

	// グラフデータコピースレッドを開始（受信処理に影響を及ぼさないように別スレッドで行う）
	m_hThread = AfxBeginThread(CopyGraphDataThreadEntry, this, THREAD_PRIORITY_LOWEST)->m_hThread;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrm::CopyGraphDataThreadEntry
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフデータコピースレッドエントリ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPVOID		pParam		[I] スレッドパラメータ(thisポインタ）
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		UINT		0
//*****************************************************************************************************
UINT CGraphFrm::CopyGraphDataThreadEntry(LPVOID pParam)
{
	((CGraphFrm *)pParam)->CopyGraphDataThread();
	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrm::CopyGraphDataThread
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフデータコピースレッド処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrm::CopyGraphDataThread()
{
	// グラフデータをコピー
	m_oGraphData = *m_pGraphData;

	// コピー終了をメインスレッドに通知
	if (!m_bExit)
		PostMessage(WM_READY_GRAPH);

	m_hThread = NULL;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrm::OnReadyGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフ準備完了処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		WPARAM		wParam			[I] メッセージパラメータ
//		LPARAM		lParam			[I] メッセージパラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		LRESULT		0
//*****************************************************************************************************
LRESULT CGraphFrm::OnReadyGraph(WPARAM wParam, LPARAM lParam)
{
	// Time Rangeを取得（最終Timestampより30日以上前のデータは無視する）
	int nDataNum = m_oGraphData.GetDataNum();
	if (nDataNum != 0) {
		m_fTimeRange2 = (double)m_oGraphData.GetGraphData(nDataNum - 1).oTimestamp.GetTime();

		for (int i = 0; i < nDataNum; i++) {
			m_fTimeRange1 = (double)m_oGraphData.GetGraphData(i).oTimestamp.GetTime();
			if (m_fTimeRange2 - m_fTimeRange1 < 30 * 24 * 60 * 60)
				break;
		}
	}

	// Data Lineを表示
	m_wndGraphBar.DispDataLine(m_oGraphLine);

	// グラフを更新
	UpdateGraph();

	// ステータスバーに準備完了を表示
	m_wndStatusBar.SetPaneText(0, "Ready");

	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrm::UpdateGraph
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
void CGraphFrm::UpdateGraph()
{
	double fTimeSpan = m_fTimeRange2 - m_fTimeRange1;

	// 横軸範囲の補正
	if (m_fTimeOffset < 0)
		m_fTimeOffset = 0;
	else if (m_fTimeRange1 + fTimeSpan * m_fTimeOffset + 1 / m_fTimeZoom * fTimeSpan > m_fTimeRange2)
		m_fTimeOffset = 1 - 1 / m_fTimeZoom;

	double fTimeRange1 = m_fTimeRange1 + fTimeSpan * m_fTimeOffset;
	double fTimeRange2 = fTimeRange1 + fTimeSpan / m_fTimeZoom;

	double fTempSpan = m_fTempRange2 - m_fTempRange1;

	// 縦軸範囲の補正
	if (m_fLevelOffset < 0)
		m_fLevelOffset = 0;
	else if (m_fTempRange1 + fTempSpan * m_fLevelOffset + 1 / m_fLevelZoom * fTempSpan > m_fTempRange2)
		m_fLevelOffset = 1 - 1 / m_fLevelZoom;

	double fTempRange1 = m_fTempRange1 + fTempSpan * m_fLevelOffset;
	double fTempRange2 = fTempRange1 + fTempSpan / m_fLevelZoom;

	double fHeaterSpan = m_fHeaterRange2 - m_fHeaterRange1;
	double fHeaterRange1 = m_fHeaterRange1 + fHeaterSpan * m_fLevelOffset;
	double fHeaterRange2 = fHeaterRange1 + fHeaterSpan / m_fLevelZoom;

	// 横スクロールバーの設定
	SCROLLINFO si;
	memset(&si, 0, sizeof(si));
	si.cbSize = sizeof(si);
	si.fMask = SIF_PAGE | SIF_POS;
	si.nPage = (int)(SCROLL_MAX / m_fTimeZoom);
	si.nPos = (int)(m_fTimeOffset * SCROLL_MAX);
	m_wndGraphView.SetScrollInfo(SB_HORZ, &si);

	// 縦スクロールバーの設定
	si.nPage = (int)(SCROLL_MAX / m_fLevelZoom);
	si.nPos = SCROLL_MAX - si.nPage - (int)(m_fLevelOffset * SCROLL_MAX);
	m_wndGraphView.SetScrollInfo(SB_VERT, &si);

	// 横軸範囲の表示
	m_wndGraphBar.DispHorizontalAxis(fTimeRange1, fTimeRange2);

	// 縦軸範囲の表示
	m_wndGraphBar.DispVerticalAxis(fTempRange1, fTempRange2, fHeaterRange1, fHeaterRange2);

	// グラフ表示
	m_wndGraphView.DispGraph(
			m_oGraphData,
			m_oGraphLine,
			fTimeRange1, fTimeRange2,
			fTempRange1, fTempRange2,
			fHeaterRange1, fHeaterRange2);

	m_wndGraphView.UpdateWindow();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrm::ChangeGraphLine
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフデータライン変更処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SGraphLine		&oGraphLine			[I] グラフデータライン情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrm::ChangeGraphLine(const SGraphLine &oGraphLine)
{
	m_oGraphLine = oGraphLine;

	UpdateGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrm::ScrollTime
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		横軸スクロール処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		fScrollPos			[I] スクロール位置
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrm::ScrollTime(double fScrollPos)
{
	m_fTimeOffset = fScrollPos;

	UpdateGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrm::ZoomTime
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		横軸ズーム処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		fZoomPos			[I] ズーム位置
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrm::ZoomTime(double fZoomPos)
{
	m_fTimeZoom = exp(fZoomPos * 5);

	UpdateGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrm::ScrollLevel
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		縦軸スクロール処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		fScrollPos			[I] スクロール位置
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrm::ScrollLevel(double fScrollPos)
{
	m_fLevelOffset = fScrollPos;

	UpdateGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrm::ZoomLevel
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		縦軸ズーム処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		fZoomPos			[I] ズーム位置
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrm::ZoomLevel(double fZoomPos)
{
	double fCenter = m_fLevelOffset + 0.5 / m_fLevelZoom;
	m_fLevelZoom = exp(fZoomPos * 5);
	m_fLevelOffset = fCenter - 0.5 / m_fLevelZoom;

	UpdateGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrm::MouseScroll
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		マウスによるスクロール処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		fTimeScroll			[I] 横軸スクロール位置
//		double		fLevelScroll		[I] 縦軸スクロール位置
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrm::MouseScroll(double fTimeScroll, double fLevelScroll)
{
	m_fTimeOffset += fTimeScroll / m_fTimeZoom;
	m_fLevelOffset += fLevelScroll / m_fLevelZoom;

	UpdateGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrm::MouseZoom
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		マウスによるズーム処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		fTimePos1		[I] 横軸ズーム開始位置
//		double		fTimePos2		[I] 横軸ズーム終了位置
//		double		fLevelPos1		[I] 縦軸ズーム開始位置
//		double		fLevelPos2		[I] 縦軸ズーム終了位置
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrm::MouseZoom(double fTimePos1, double fTimePos2, double fLevelPos1, double fLevelPos2)
{
	if (fTimePos1 > fTimePos2) {
		double fTimePos = fTimePos1;
		fTimePos1 = fTimePos2;
		fTimePos2 = fTimePos;
	}

	if (fLevelPos1 > fLevelPos2) {
		double fLevelPos = fLevelPos1;
		fLevelPos1 = fLevelPos2;
		fLevelPos2 = fLevelPos;
	}

	m_fTimeOffset += fTimePos1 / m_fTimeZoom;
	m_fTimeZoom /= fTimePos2 - fTimePos1;

	m_fLevelOffset += fLevelPos1 / m_fLevelZoom;
	m_fLevelZoom /= fLevelPos2 - fLevelPos1;

	m_wndGraphBar.SetTimeZoom(log(m_fTimeZoom) / 5);
	m_wndGraphBar.SetLevelZoom(log(m_fLevelZoom) / 5);

	UpdateGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrm::ZoomReset
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ズーム解除処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrm::ZoomReset()
{
	m_fTimeZoom = 1;
	m_fLevelZoom = 1;

	m_wndGraphBar.SetTimeZoom(0);
	m_wndGraphBar.SetLevelZoom(0);

	UpdateGraph();
}
