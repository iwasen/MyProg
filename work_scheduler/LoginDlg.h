#if !defined(AFX_LOGINDLG_H__C08ED5CC_069D_497C_A3D0_19478621B600__INCLUDED_)
#define AFX_LOGINDLG_H__C08ED5CC_069D_497C_A3D0_19478621B600__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// LoginDlg.h : �w�b�_�[ �t�@�C��
//

#include "DialogEx.h"
#include "MyCtrl.h"

/////////////////////////////////////////////////////////////////////////////
// CLoginDlg �_�C�A���O

class CLoginDlg : public CDialogEx
{
// �R���X�g���N�V����
public:
	CLoginDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CLoginDlg)
	enum { IDD = IDD_LOGIN };
	CMyEdit	m_cEditShokuban;
	//}}AFX_DATA


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CLoginDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CLoginDlg)
	virtual void OnOK();
	virtual BOOL OnInitDialog();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_LOGINDLG_H__C08ED5CC_069D_497C_A3D0_19478621B600__INCLUDED_)
