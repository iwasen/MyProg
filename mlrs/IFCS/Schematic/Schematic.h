#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		Schematic.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		接続系統図／機能系統図／機能説明図表示メインクラス
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#ifndef __AFXWIN_H__
	#error "PCH に対してこのファイルをインクルードする前に 'stdafx.h' をインクルードしてください"
#endif

#include "resource.h"		// メイン シンボル
#include "Define.h"

// CSchematicApp:
// このクラスの実装については、Schematic.cpp を参照してください。
//

class CSchematicApp : public CWinApp
{
public:
	CSchematicApp();

// オーバーライド
	public:
	virtual BOOL InitInstance();

// 実装

	DECLARE_MESSAGE_MAP()
};

extern CSchematicApp theApp;

// 図表ウィンドウデータ
struct SSchematicWindow {
	CString sSchematicCode;			// 図表コード
	CRuntimeClass *pViewClass;		// ビュークラス
	CRuntimeClass *pMenuClass;		// 操作メニュークラス
	CString sFlashFileName;			// Flashファイル名
	CString sWindowTitle;			// ウィンドウタイトル
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