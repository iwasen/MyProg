// LLog.h : PROJECT_NAME �A�v���P�[�V�����̃��C�� �w�b�_�[ �t�@�C���ł��B
//

#pragma once

#ifndef __AFXWIN_H__
	#error "PCH �ɑ΂��Ă��̃t�@�C�����C���N���[�h����O�� 'stdafx.h' ���C���N���[�h���Ă�������"
#endif

#include "resource.h"		// ���C�� �V���{��


// CLLogApp:
// ���̃N���X�̎����ɂ��ẮALLog.cpp ���Q�Ƃ��Ă��������B
//

class CLLogApp : public CWinApp
{
public:
	CLLogApp();

// �I�[�o�[���C�h
	public:
	virtual BOOL InitInstance();

// ����

	DECLARE_MESSAGE_MAP()
	virtual BOOL PreTranslateMessage(MSG* pMsg);
};

extern CLLogApp theApp;

#define URL_LLOG	"http://inet.troot.co.jp/llog"
