#if !defined(AFX_SAVEPROJECTDLG_H__EC323F26_9944_4783_B9E0_6ED2F559314E__INCLUDED_)
#define AFX_SAVEPROJECTDLG_H__EC323F26_9944_4783_B9E0_6ED2F559314E__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// SaveProjectDlg.h : �w�b�_�[ �t�@�C��
//

#include "DialogEx.h"

/////////////////////////////////////////////////////////////////////////////
// CSaveProjectDlg �_�C�A���O

class CSaveProjectDlg : public CDialogEx
{
// �R���X�g���N�V����
public:
	CSaveProjectDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CSaveProjectDlg)
	enum { IDD = IDD_SAVE_PROJECT };
	CMyEdit	m_cEditProjectName;
	//}}AFX_DATA


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CSaveProjectDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CSaveProjectDlg)
	virtual void OnOK();
	virtual BOOL OnInitDialog();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	BOOL ProjectNameCheck();
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_SAVEPROJECTDLG_H__EC323F26_9944_4783_B9E0_6ED2F559314E__INCLUDED_)
