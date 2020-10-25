// MainFrm.h : CMainFrame �N���X�̃C���^�[�t�F�C�X
//


#pragma once

#include "MyCtrl.h"
#include "AutoConv.h"

class CMainFrame : public CFrameWnd
{
protected: // �V���A��������̂ݍ쐬���܂��B
	CMainFrame();
	DECLARE_DYNCREATE(CMainFrame)

// �I�[�o�[���C�h
public:
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);

// ����
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

// �������ꂽ�A���b�Z�[�W���蓖�Ċ֐�
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


