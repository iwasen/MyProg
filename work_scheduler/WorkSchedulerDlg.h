#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		WorkSchedulerDlg.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CWorkSchedulerDlg �N���X�̃C���^�[�t�F�C�X
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "DialogEx.h"
#include "SubMenu.h"
#include "ColorButton.h"

// ���C���_�C�A���O
class CWorkSchedulerDlg : public CDialogEx
{
// �\�z
public:
	CWorkSchedulerDlg(CWnd* pParent = NULL);	// �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CWorkSchedulerDlg)
	enum { IDD = IDD_WORKSCHEDULER_DIALOG };
	CProgressCtrl	m_cProgressBar;
	CStatic	m_cStaticStatus;
	CColorButton	m_buttonExit;
	CColorButton	m_buttonAutoScheduling;
	CColorButton	m_buttonManualScheduling;
	CColorButton	m_buttonSetConditions;
	CColorButton	m_buttonStart;
	CSubMenu	m_cSubMenu;
	//}}AFX_DATA

	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CWorkSchedulerDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV �̃T�|�[�g
	virtual void PostNcDestroy();
	//}}AFX_VIRTUAL

public:
	void EnableMenu();
	void SetSubMenuStatus(LPCTSTR pText);
	void SetSubMenuStatus(CWnd &wndButton);
	void ClearSubMenuStatus();

// �C���v�������e�[�V����
protected:
	HICON m_hIcon;		// �A�C�R��

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CWorkSchedulerDlg)
	virtual BOOL OnInitDialog();
	afx_msg void OnSysCommand(UINT nID, LPARAM lParam);
	afx_msg void OnPaint();
	afx_msg HCURSOR OnQueryDragIcon();
	afx_msg void OnButtonStart();
	afx_msg void OnButtonSetConditions();
	afx_msg void OnButtonAutoScheduling();
	afx_msg void OnButtonManualScheduling();
	afx_msg void OnButtonExit();
	virtual void OnCancel();
	afx_msg void OnFileSave();
	afx_msg void OnFileSaveAs();
	afx_msg void OnUpdateFileSave(CCmdUI* pCmdUI);
	afx_msg void OnUpdateFileSaveAs(CCmdUI* pCmdUI);
	afx_msg void OnInitMenuPopup(CMenu* pPopupMenu, UINT nIndex, BOOL bSysMenu);
	afx_msg void OnHelpVersion();
	//}}AFX_MSG
	virtual void OnOK();
	afx_msg LRESULT OnOpenExcel(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnCloseExcel(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnErrorExcel(WPARAM wParam, LPARAM lParam);
	DECLARE_MESSAGE_MAP()

	CTypedPtrArray< CObArray, CColorButton* > m_arrayMenuButton;	// ���j���[�{�^���z��
	CString m_sMainStatus;		// ���C�����j���[�X�e�[�^�X

	void InitMenuButton();
	void InitSubMenu();
	void SelectMenu(int nIndex);
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B
