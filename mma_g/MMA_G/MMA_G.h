// MMA_G.h : MMA_G アプリケーションのメイン ヘッダー ファイル
//
#pragma once

#ifndef __AFXWIN_H__
	#error "PCH に対してこのファイルをインクルードする前に 'stdafx.h' をインクルードしてください"
#endif

#include "resource.h"       // メイン シンボル

#define ID_VALUE		0x00ffffff
#define ID_KIND			0xf0000000
#define ID_DATA_TYPE	0x0f000000
#define ID_SYSTEM		0x10000000
#define ID_FOLDER		0x20000000
#define ID_DATA			0x30000000
#define ID_OLD_DATA		0x40000000

#define	DATA_TYPE_MMA	0x00000000
#define	DATA_TYPE_MME	0x01000000
#define	DATA_TYPE_ASU	0x02000000

#define IMAGE_SYSTEM	3

#define	WM_KEY_RET_KEY	(WM_USER + 100)

// CMMA_GApp:
// このクラスの実装については、MMA_G.cpp を参照してください。
//

class CMMA_GApp : public CWinApp
{
public:
	CMMA_GApp();

	// ツリービューとリストビューのイメージリスト
	CImageList m_imageList;

// オーバーライド
protected:
	virtual BOOL InitInstance();

// 実装
protected:
	afx_msg void OnAppAbout();
	DECLARE_MESSAGE_MAP()
};

extern CMMA_GApp theApp;