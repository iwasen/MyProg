// vjb30030.h : VJB30030 アプリケーションのメイン ヘッダー ファイルです。
//

#if !defined(AFX_VJB30030_H__D9F2D691_7B09_4B71_AF0E_F17A8E77917B__INCLUDED_)
#define AFX_VJB30030_H__D9F2D691_7B09_4B71_AF0E_F17A8E77917B__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#ifndef __AFXWIN_H__
	#error include 'stdafx.h' before including this file for PCH
#endif

#include "resource.h"		// メイン シンボル
#include "global.h"

// リターンコード
#define RC_NORMAL		0				// 正常終了
#define RC_PARAMETER	1				// パラメータが不正
#define RC_STATUS		2				// キューファイルの状態コードが不正
#define RC_VOLUMENAME	3				// ボリュームラベルが違う
#define RC_IOERROR		4				// I/O エラー
#define RC_CANCEL		5				// 処理がキャンセルされた
#define RC_MULTIFILEVOL	6				// ラベル無しのマルチファイル／マルチボリューム
#define RC_NODIRECTORY	7				// コピー元ディレクトリが無い
#define RC_NOFILE		8				// コピー元ファイルが無い
#define RC_ILLEGALFILENAME	9			// ファイル名が不正

#define MAX_VOLUME_LABEL	7			// 最大ボリュームラベル数

// キューファイル情報
struct Queue {
	CString sFileName;					// ファイル名
	CString sShubetsuCode;				// 媒体種別コード
	CString sShikibetsuCode;			// 媒体識別コード
	CString sVolLabel;					// ボリュームラベル
	CString sCreateDate;				// 作成日付
	char cStatus;						// 状態コード
};

extern CString g_sPathFPD;
extern CString g_sPathMOD;
extern CString g_sOutputDir;
extern CString g_sLogDir;

/////////////////////////////////////////////////////////////////////////////
// CVjb30030App:
// このクラスの動作の定義に関しては vjb30030.cpp ファイルを参照してください。
//

class CVjb30030App : public CWinApp
{
public:
	CVjb30030App();

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CVjb30030App)
	public:
	virtual BOOL InitInstance();
	virtual int ExitInstance();
	//}}AFX_VIRTUAL

// インプリメンテーション

	//{{AFX_MSG(CVjb30030App)
		// メモ - ClassWizard はこの位置にメンバ関数を追加または削除します。
		//        この位置に生成されるコードを編集しないでください。
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

private:
	int m_nRetCode;
	CString m_sQueueDir;

	int Main(int argc, char *argv[]);
	void ReadIniFile();
	Queue *GetQueueData(CString sFileName);
	void ChangeStatus(Queue *pQueue, char status);
	void DeleteSrcFile(Queue *pQueue);
	void NotifyChangeStatus();
	void WriteLogFile(Queue *pQueue, LPCTSTR pLogFile);
};


/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_VJB30030_H__D9F2D691_7B09_4B71_AF0E_F17A8E77917B__INCLUDED_)
