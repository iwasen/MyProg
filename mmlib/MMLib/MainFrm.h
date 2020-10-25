// MainFrm.h : CMainFrame �N���X�̐錾����уC���^�[�t�F�C�X�̒�`�����܂��B
//
/////////////////////////////////////////////////////////////////////////////

#pragma once

#define IDM_TRANS_APP	40000

struct TransMenu {
	CString fileName;
	int nSupportFile;
};

class CMainFrame : public CFrameWnd
{
protected: // �V���A���C�Y�@�\�݂̂���쐬���܂��B
	CMainFrame();
	DECLARE_DYNCREATE(CMainFrame)

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	public:
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	protected:
	virtual BOOL OnCreateClient(LPCREATESTRUCT lpcs, CCreateContext* pContext);

// �C���v�������e�[�V����
public:
	virtual ~CMainFrame();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

protected:  // �R���g���[�� �o�[�p�����o
	CStatusBar  m_wndStatusBar;
	CMyToolBar    m_wndMainToolBar;
	CMyToolBar    m_wndViewToolBar;
	CMyToolBar    m_wndPictToolBar;
	CComboBox	m_wndComboBox;

// �������ꂽ���b�Z�[�W �}�b�v�֐�
protected:
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnClose();
	afx_msg void OnInitMenu(CMenu* pMenu);
	afx_msg void OnToolbarPict();
	afx_msg void OnUpdateToolbarPict(CCmdUI* pCmdUI);
	afx_msg void OnToolbarStandard();
	afx_msg void OnUpdateToolbarStandard(CCmdUI* pCmdUI);
	afx_msg void OnToolbarString();
	afx_msg void OnUpdateToolbarString(CCmdUI* pCmdUI);
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg void OnToolbarView();
	afx_msg void OnUpdateToolbarView(CCmdUI* pCmdUI);
	afx_msg LRESULT OnEnterSizeMove(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnMyCommand(WPARAM wParam, LPARAM lParam);
	DECLARE_MESSAGE_MAP()
	afx_msg void OnUpdateInfo(CCmdUI *pCmdUI);

public:
	void ShowPictView(BOOL bShowPict);
	void MaxPictView(BOOL bMaxPict);
	BOOL CheckShowPict() {return m_bShowPict;}
	CComboBox &GetComboBox() {return m_wndComboBox;}
	int m_nTransMenu;
	TransMenu *m_pTransMenu;

	void RefreshTransMenu();
	void MaxInfoView(int nMaxHeight);

private:
	CSplitterWnd m_wndSplitter;
	CSplitterWnd m_wndSplitter2;
	CSplitterWnd m_wndSplitter3;
	CView *m_pListView;
	CView *m_pPictView;
	BOOL m_bShowPict;
	BOOL m_bMaxPict;
	BOOL m_bToolbarStandard;
	BOOL m_bToolbarPict;
	BOOL m_bToolbarString;
	int m_nFixedHeight;
	int m_nClientHeight;

	void DockControlBarLeftOf(CToolBar* Bar, CToolBar* LeftOf);
	void SetToolbar();
};
