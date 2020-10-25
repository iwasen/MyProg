#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		ManualSchedulingDlg.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CManualSchedulingDlg �N���X�̃C���^�[�t�F�C�X
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "DialogEx.h"
#include "MyCtrl.h"
#include "ColorButton.h"

// �蓮�Ґ����j���[�{�^���̃T�u���j���[���
class CManualSchedulingDlg : public CSubMenuDlg
{
// �R���X�g���N�V����
public:
	CManualSchedulingDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CManualSchedulingDlg)
	enum { IDD = IDD_MANUAL_SCHEDULING };
	CColorButton	m_cButtonManual;
	//}}AFX_DATA
	CMyButton	m_aCheckSubline[MAX_SUBLINE];


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CManualSchedulingDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CManualSchedulingDlg)
	afx_msg void OnButtonManual();
	virtual BOOL OnInitDialog();
	afx_msg void OnButtonZensentaku();
	afx_msg void OnButtonZenkaijo();
	afx_msg void OnShowWindow(BOOL bShow, UINT nStatus);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	void SetAllSublixxxheck(BOOL bCheck);
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B
