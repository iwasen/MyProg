#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		Schematic.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�ڑ��n���}�^�@�\�n���}�^�@�\�����}�\�����C���N���X
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#ifndef __AFXWIN_H__
	#error "PCH �ɑ΂��Ă��̃t�@�C�����C���N���[�h����O�� 'stdafx.h' ���C���N���[�h���Ă�������"
#endif

#include "resource.h"		// ���C�� �V���{��
#include "Define.h"

// CSchematicApp:
// ���̃N���X�̎����ɂ��ẮASchematic.cpp ���Q�Ƃ��Ă��������B
//

class CSchematicApp : public CWinApp
{
public:
	CSchematicApp();

// �I�[�o�[���C�h
	public:
	virtual BOOL InitInstance();

// ����

	DECLARE_MESSAGE_MAP()
};

extern CSchematicApp theApp;

// �}�\�E�B���h�E�f�[�^
struct SSchematicWindow {
	CString sSchematicCode;			// �}�\�R�[�h
	CRuntimeClass *pViewClass;		// �r���[�N���X
	CRuntimeClass *pMenuClass;		// ���상�j���[�N���X
	CString sFlashFileName;			// Flash�t�@�C����
	CString sWindowTitle;			// �E�B���h�E�^�C�g��
};

#ifdef _AFXEXT
class AFX_EXT_CLASS CSchematic {
#else
class CSchematic {
#endif
public:
	CSchematic::CSchematic();

	static CString m_sFlashDir;
	static SSchematicWindow m_aSchematicWindow[];

	static void OpenSchematicWindow(LPCTSTR pSchematicCode, int nActionCode = 0);
	static SSchematicWindow *GetSchematicWindowData(LPCTSTR pSchematicCode);
	static void RenzokuFE();

protected:
	static void OpenFrameWindow(SSchematicWindow *pSchematicClass, int nActionCode);
};