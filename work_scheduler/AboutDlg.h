#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		AbountDlg.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CAbountDlg �N���X�̃C���^�[�t�F�C�X
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "DialogEx.h"

// �o�[�W�������_�C�A���O��\������
class CAboutDlg : public CDialogEx
{
public:
	CAboutDlg();

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CAboutDlg)
	enum { IDD = IDD_ABOUTBOX };
	CStatic	m_cStaticVersion;
	//}}AFX_DATA

	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂�
	//{{AFX_VIRTUAL(CAboutDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �̃T�|�[�g
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:
	//{{AFX_MSG(CAboutDlg)
	virtual void OnOK();
	virtual BOOL OnInitDialog();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B
