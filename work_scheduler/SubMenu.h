#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		SubMenu.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CSubMenu �N���X�̃C���^�[�t�F�C�X
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "SubMenuDlg.h"

// �T�u���j���[��ʏ���
class CSubMenu : public CStatic
{
// �R���X�g���N�V����
public:
	CSubMenu();

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CSubMenu)
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
public:
	virtual ~CSubMenu();

	void Add(CSubMenuDlg *pDialog, UINT nTemplateID);
	void Show(int nIndex);
	void DeleteAll();
	void EnableMenu();

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
protected:
	//{{AFX_MSG(CSubMenu)
		// ���� - ClassWizard �͂��̈ʒu�Ƀ����o�֐���ǉ��܂��͍폜���܂��B
	//}}AFX_MSG

	DECLARE_MESSAGE_MAP()

	CObArray m_arrayDialog;		// �T�u���j���[�_�C�A���O�z��
	CSubMenuDlg *m_pDialog;
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B
