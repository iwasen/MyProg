// LLog.h : PROJECT_NAME アプリケーションのメイン ヘッダー ファイルです。
//

#pragma once

#ifndef __AFXWIN_H__
	#error "PCH に対してこのファイルをインクルードする前に 'stdafx.h' をインクルードしてください"
#endif

#include "resource.h"		// メイン シンボル


// CLLogApp:
// このクラスの実装については、LLog.cpp を参照してください。
//

class CLLogApp : public CWinApp
{
public:
	CLLogApp();

// オーバーライド
	public:
	virtual BOOL InitInstance();

// 実装

	DECLARE_MESSAGE_MAP()
	virtual BOOL PreTranslateMessage(MSG* pMsg);
};

extern CLLogApp theApp;

#define URL_LLOG	"http://inet.troot.co.jp/llog"
