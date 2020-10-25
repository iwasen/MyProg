// MainFrm.h : CMainFrame �N���X�̐錾����уC���^�[�t�F�C�X�̒�`�����܂��B
//
/////////////////////////////////////////////////////////////////////////////

#if !defined(AFX_MAINFRM_H__EA0547D9_BB10_11D2_8BC7_00104B939DF5__INCLUDED_)
#define AFX_MAINFRM_H__EA0547D9_BB10_11D2_8BC7_00104B939DF5__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "LBView.h"

class CMainFrame : public CFrameWnd
{
	
public: // �V���A���C�Y�@�\�݂̂���쐬���܂��B
	CMainFrame();
	DECLARE_DYNCREATE(CMainFrame)

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CMainFrame)
	public:
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
public:
	virtual ~CMainFrame();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

protected:  // �R���g���[�� �o�[�p�����o
	CStatusBar  m_wndStatusBar;
	CToolBar    m_wndToolBar;

// �������ꂽ���b�Z�[�W �}�b�v�֐�
protected:
	//{{AFX_MSG(CMainFrame)
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnClose();
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg void OnSetting();
	afx_msg void OnSethost();
	afx_msg void OnVideoSource();
	afx_msg void OnRemoteSetting();
	afx_msg void OnConxxxt();
	afx_msg void OnChat();
	afx_msg void OnVersion();
	afx_msg void OnDisconxxxt();
	afx_msg void OnActivate(UINT nState, CWnd* pWndOther, BOOL bMinimized);
	afx_msg void OnActivateApp(BOOL bActive, HTASK hTask);
	afx_msg void OnTimer(UINT nIDEvent);
	afx_msg void OnTile();
	afx_msg void OnRecvDisp();
	afx_msg void OnRecvStill();
	afx_msg void OnSendDisp();
	afx_msg void OnSendStill();
	afx_msg void OnRecvCamera();
	afx_msg void OnSendCamera();
	afx_msg void OnVoice();
	afx_msg void OnIpaddress();
	afx_msg void OnFront();
	afx_msg void OnConxxxtDirect();
	afx_msg void OnUpdateDisconxxxt(CCmdUI* pCmdUI);
	afx_msg void OnUpdateRecvDisp(CCmdUI* pCmdUI);
	afx_msg void OnUpdateFront(CCmdUI* pCmdUI);
	afx_msg void OnUpdateRemoteSetting(CCmdUI* pCmdUI);
	afx_msg void OnUpdateRecvStill(CCmdUI* pCmdUI);
	afx_msg void OnUpdateRecvCamera(CCmdUI* pCmdUI);
	afx_msg void OnUpdateSendCamera(CCmdUI* pCmdUI);
	afx_msg void OnUpdateVideoSource(CCmdUI* pCmdUI);
	afx_msg void OnUpdateVoice(CCmdUI* pCmdUI);
	afx_msg void OnUpdateSendDisp(CCmdUI* pCmdUI);
	afx_msg void OnUpdateSendStill(CCmdUI* pCmdUI);
	afx_msg void OnUpdateChat(CCmdUI* pCmdUI);
	afx_msg void OnUpdateIpaddress(CCmdUI* pCmdUI);
	afx_msg void OnOrderConxxxt();
	afx_msg void OnOrderIpaddress();
	afx_msg void OnOrderName();
	afx_msg void OnUpdateOrderName(CCmdUI* pCmdUI);
	afx_msg void OnUpdateOrderConxxxt(CCmdUI* pCmdUI);
	afx_msg void OnUpdateOrderIpaddress(CCmdUI* pCmdUI);
	afx_msg void OnVideoFormat();
	afx_msg void OnUpdateVideoFormat(CCmdUI* pCmdUI);
	//}}AFX_MSG
	afx_msg BOOL OnConxxxtHost(UINT);
	afx_msg LONG OnSocketUDP(UINT, LONG);
	afx_msg LONG OnSocketTCP(UINT, LONG);
	afx_msg void OnSelChangeListBox();
	afx_msg void OnDblClkListBox();
	DECLARE_MESSAGE_MAP()

public:
	CLBView	m_wndListBox;
	void	SetConxxxtMenu();

private:
	void	SetListBoxRect();
	void	OpenVideoWindow();
	void	FrontWindow();

private:
	BOOL	m_bActive;
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_MAINFRM_H__EA0547D9_BB10_11D2_8BC7_00104B939DF5__INCLUDED_)
