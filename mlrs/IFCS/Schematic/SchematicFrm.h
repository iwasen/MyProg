#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		SchematicFrm.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�ڑ��n���}�E�@�\�n���}�E�@�\�����}��Flash�\���p�t���[���E�B���h�E����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "SchematicView.h"

class CSchematicFrame : public CFrameWnd
{
	friend CSchematicView;
protected: 
	DECLARE_DYNAMIC(CSchematicFrame)

// ����
public:
	CTypedPtrList <CPtrList, SSchematicWindow *> m_lSchematicWindow;	// �}�\�f�[�^���X�g

protected:
	CString m_sWindowTitle;			// �E�B���h�E�^�C�g��
	CString m_sFlashFileName;		// Flash�t�@�C����
	CRuntimeClass *m_pViewClass;	// �r���[�N���X
	CRuntimeClass *m_pMenuClass;	// ���상�j���[�N���X

// ����
public:
	void CreateFrameWindow(int nActionCode);

protected:
	void GetSchematicInfo();

// �I�[�o�[���C�h
public:
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);

// ����
public:

protected:  // �R���g���[�� �o�[�p�����o
	CStatusBar  m_wndStatusBar;
	CSchematicView    *m_pWndView;

// �������ꂽ�A���b�Z�[�W���蓖�Ċ֐�
protected:
	DECLARE_MESSAGE_MAP()
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnDestroy();
	afx_msg void OnClose();
public:
	afx_msg void OnScreenCopySize();
};


