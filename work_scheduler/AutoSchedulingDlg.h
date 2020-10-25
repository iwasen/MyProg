#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		AutoSchedulingDlg.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CAutoSchedulingDlg �N���X�̃C���^�[�t�F�C�X
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "DialogEx.h"

// �Ґ����s���j���[�{�^���̃T�u���j���[���
class CAutoSchedulingDlg : public CSubMenuDlg
{
// �R���X�g���N�V����
public:
	CAutoSchedulingDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CAutoSchedulingDlg)
	enum { IDD = IDD_AUTO_SCHEDULING };
	CComboBox	m_cComboKijunKishu;
	CColorButton	m_cButtonExec;
	int		m_nRadioKojun;
	int		m_nRadioTactKeisan;
	//}}AFX_DATA
	CMyButton	m_aCheckSubline[MAX_SUBLINE];


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CAutoSchedulingDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CAutoSchedulingDlg)
	virtual void OnOK();
	virtual BOOL OnInitDialog();
	afx_msg void OnShowWindow(BOOL bShow, UINT nStatus);
	afx_msg void OnButtonZensentaku();
	afx_msg void OnButtonZenkaijo();
	afx_msg void OnCheckSubline1();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	void SetAllSublixxxheck(BOOL bCheck);
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B
