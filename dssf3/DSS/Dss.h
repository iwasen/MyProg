// Dss.h : PROJECT_NAME �A�v���P�[�V�����̃��C�� �w�b�_�[ �t�@�C���ł��B
//

#pragma once

#ifndef __AFXWIN_H__
	#error include 'stdafx.h' before including this file for PCH
#endif

#include "resource.h"		// ���C�� �V���{��
#include "comres.h"
#include "DssData.h"


// CDssApp:
// ���̃N���X�̎����ɂ��ẮADss.cpp ���Q�Ƃ��Ă��������B
//

class CDssApp : public CWinApp
{
public:
	CDssApp();

	virtual BOOL InitInstance();

	void OnOnlineUpdate();

	DECLARE_MESSAGE_MAP()
};

extern CDssApp theApp;
