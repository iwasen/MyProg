#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		StartMenuDlg.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CStartMenuDlg �N���X�̃C���^�[�t�F�C�X
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "DialogEx.h"
#include "ColorBUtton.h"

// �Ґ��J�n���j���[�{�^���̃T�u���j���[���
class CStartMenuDlg : public CSubMenuDlg
{
// �R���X�g���N�V����
public:
	CStartMenuDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CStartMenuDlg)
	enum { IDD = IDD_START_MENU };
	CColorButton	m_cButtonReadData;
	//}}AFX_DATA


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CStartMenuDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CStartMenuDlg)
	afx_msg void OnButtonReadData();
	virtual BOOL OnInitDialog();
	afx_msg void OnButtonDebug1();
	afx_msg void OnButtonDebug2();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	void ReadData(LPCTSTR pFolderName);
	void OnButtonDebug(int nJokenSetteiSosa);
	void EnableMenuButton();
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B
