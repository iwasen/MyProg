#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MmeRecvApp.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		アプリケーションクラスの定義
//----------------------------------------------------------------------------------------------------
//  3. 備考
//		無し
//*****************************************************************************************************

#ifndef __AFXWIN_H__
	#error "PCH に対してこのファイルをインクルードする前に 'stdafx.h' をインクルードしてください"
#endif

#include "resource.h"		// メイン シンボル


// CMmeRecvApp:
// このクラスの実装については、MmeRecv.cpp を参照してください。
//

class CMmeRecvApp : public CWinApp
{
public:
// オーバーライド
	public:
	virtual BOOL InitInstance();

// 実装

	DECLARE_MESSAGE_MAP()
	virtual int ExitInstance();
};

extern CMmeRecvApp theApp;