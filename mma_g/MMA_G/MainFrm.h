// MainFrm.h : CMainFrame クラスのインターフェイス
//


#pragma once

#include "MyCtrl.h"
#include "AutoConv.h"

class CMainFrame : public CFrameWnd
{
protected: // シリアル化からのみ作成します。
	CMainFrame();
	DECLARE_DYNCREATE(CMainFrame)

// オーバーライド
public:
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);

// 実装
public:
	virtual ~CMainFrame();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

protected:
	CSplitterWnd m_wndSplitter;
	CStatusBar  m_wndStatusBar;
	CToolBarEx	m_wndToolBar;

	CAutoConv	m_oAutoConv;

// 生成された、メッセージ割り当て関数
protected:
	DECLARE_MESSAGE_MAP()
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	virtual BOOL OnCreateClient(LPCREATESTRUCT lpcs, CCreateContext* pContext);
	afx_msg void OnClose();
	afx_msg void OnUpdateStatusGude(CCmdUI *pCmdUI);
	afx_msg LRESULT OnUserRealtimeGraph(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnUserCloseGraph(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnUserMessageBox(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnUserAutoConv(WPARAM wParam, LPARAM lParam);
	afx_msg void OnDestroy();
	afx_msg void OnTimer(UINT_PTR nIDEvent);
};


