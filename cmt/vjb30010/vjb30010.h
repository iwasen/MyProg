// vjb30010.h : VJB30010 アプリケーションのメイン ヘッダー ファイルです。
//

#if !defined(AFX_VJB30010_H__71FB461D_A04F_49FD_9322_657F9DA46131__INCLUDED_)
#define AFX_VJB30010_H__71FB461D_A04F_49FD_9322_657F9DA46131__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#ifndef __AFXWIN_H__
	#error include 'stdafx.h' before including this file for PCH
#endif

#include "resource.h"		// メイン シンボル

// パラメータ長
#define LEN_SHUBETSU_CODE	3	// 媒体種別コード長
#define LEN_SHIKIBETSU_CODE	6	// 媒体識別コード長
#define LEN_VOLUME_LABEL			6	// ボリュームラベル長
#define LEN_TOTAL	(LEN_SHUBETSU_CODE + LEN_SHIKIBETSU_CODE + LEN_VOLUME_LABEL)

// パラメータ位置
#define POS_SHUBETSU_CODE	0	// 媒体種別コード長
#define POS_SHIKIBETSU_CODE	3	// 媒体識別コード長
#define POS_VOLUME_LABEL			9	// ボリュームラベル長

#define MAX_VOLUME_LABEL			7	// 最大ボリュームラベル数
#define INI_FILE	".\\baitai.ini"		// .iniファイル名

// リターンコード
#define RC_NORMAL		0				// 正常終了
#define RC_PARAMNUM		1				// パラメータ数が不正
#define RC_PARAMVAL		2				// パラメータの内容が不正
#define RC_PARAMLEN		3				// パラメータの長さが不正
#define RC_VOLNUM		4				// ボリュームラベルの数が多すぎる
#define RC_VOLLEN		5				// ボリュームラベルの長さが不正
#define RC_QUEUEFILE	6				// キューファイルが作成できない

// パラメータ情報
struct Param {
	char cShubetsuCode[LEN_SHUBETSU_CODE + 1];					// 媒体種別コード
	char cShikibetsuCode[LEN_SHIKIBETSU_CODE + 1];				// 媒体識別コード
	char cVolLabel[MAX_VOLUME_LABEL][LEN_VOLUME_LABEL + 1];		// ボリュームラベル
	int nVolLabel;												// ボリュームラベルの数
};	

/////////////////////////////////////////////////////////////////////////////
// CVjb30010App:
// このクラスの動作の定義に関しては vjb30010.cpp ファイルを参照してください。
//

class CVjb30010App : public CWinApp
{
public:
	CVjb30010App();

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CVjb30010App)
	public:
	virtual BOOL InitInstance();
	virtual int ExitInstance();
	//}}AFX_VIRTUAL

// インプリメンテーション

	//{{AFX_MSG(CVjb30010App)
		// メモ - ClassWizard はこの位置にメンバ関数を追加または削除します。
		//        この位置に生成されるコードを編集しないでください。
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

private:
	int m_nRetCode;

	int GetParam(int argc, char* argv[], Param *pParam);
	int CreateQueueFile(Param *pParam);
	void DispMessage(LPCTSTR msg);
};


/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_VJB30010_H__71FB461D_A04F_49FD_9322_657F9DA46131__INCLUDED_)
