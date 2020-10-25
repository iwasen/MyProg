#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		ManualEditFrameWnd.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CManualEditFrameWnd クラスのインターフェイス
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

// 手動編成フレームウィンドウ
class CManualEditFrameWnd : public CMDIFrameWnd
{
	DECLARE_DYNAMIC(CManualEditFrameWnd)
public:
	CManualEditFrameWnd();

// アトリビュート
public:

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CManualEditFrameWnd)
	protected:
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	//}}AFX_VIRTUAL

// インプリメンテーション
public:
// Modify ... ( DELETE )
//	int GetSelectedKishu();
// By Y.Itabashi (xxxxx) 2007.02.26
	void DisplayViews(BOOL bForce);
	void StopBlink(BOOL bStopBlink);

protected:

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CManualEditFrameWnd)
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnDestroy();
	afx_msg void OnFileClose();
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg void OnTimer(UINT nIDEvent);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	CStatusBar m_wndStatusBar;		// ステータスバー
	CToolBar m_wndToolBar;			// ツールバー
	CDialogBar m_wndDlgBar;			// ダイアログバー
	CWnd *m_pMainWnd;				// メインウィンドウ
	CDocument *m_pDocument;
	CMDIChildWnd *m_pChildWnd1;
	CMDIChildWnd *m_pChildWnd2;
	CMDIChildWnd *m_pChildWnd3;
	int m_nChildWndType1;
	int m_nChildWndType2;
	int m_nChildWndType3;
	BOOL m_bBlink;
	BOOL m_bStopBlink;

	void CreateChildWnd(int nWindowNo, int DisplayType, CMDIChildWnd *&pChildWnd, int &nChildWndType, BOOL bForce);
	void SetChildWndPos(CMDIChildWnd *pWnd, int nWindowNo, BOOL bForce);
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。
