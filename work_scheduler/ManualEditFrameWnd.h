#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		ManualEditFrameWnd.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CManualEditFrameWnd �N���X�̃C���^�[�t�F�C�X
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

// �蓮�Ґ��t���[���E�B���h�E
class CManualEditFrameWnd : public CMDIFrameWnd
{
	DECLARE_DYNAMIC(CManualEditFrameWnd)
public:
	CManualEditFrameWnd();

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CManualEditFrameWnd)
	protected:
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
public:
// Modify ... ( DELETE )
//	int GetSelectedKishu();
// By Y.Itabashi (xxxxx) 2007.02.26
	void DisplayViews(BOOL bForce);
	void StopBlink(BOOL bStopBlink);

protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CManualEditFrameWnd)
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnDestroy();
	afx_msg void OnFileClose();
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg void OnTimer(UINT nIDEvent);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	CStatusBar m_wndStatusBar;		// �X�e�[�^�X�o�[
	CToolBar m_wndToolBar;			// �c�[���o�[
	CDialogBar m_wndDlgBar;			// �_�C�A���O�o�[
	CWnd *m_pMainWnd;				// ���C���E�B���h�E
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
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B
