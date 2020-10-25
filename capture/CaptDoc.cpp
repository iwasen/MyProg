// CaptDoc.cpp : CCaptureDoc クラスの動作の定義を行います。
//

#include "stdafx.h"
#include "Capture.h"

#include "CaptDoc.h"
#include "IniFile.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CCaptureDoc

IMPLEMENT_DYNCREATE(CCaptureDoc, CDocument)

BEGIN_MESSAGE_MAP(CCaptureDoc, CDocument)
	//{{AFX_MSG_MAP(CCaptureDoc)
		// メモ - ClassWizard はこの位置にマッピング用のマクロを追加または削除します。
		//        この位置に生成されるコードを編集しないでください。
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CCaptureDoc クラスの構築/消滅

CCaptureDoc::CCaptureDoc()
{
	// TODO: この位置に１回だけ構築用のコードを追加してください。

}

CCaptureDoc::~CCaptureDoc()
{
}

BOOL CCaptureDoc::OnNewDocument()
{
	if (!CDocument::OnNewDocument())
		return FALSE;

	SetWindowTitle(iniData.m_StartupMode);	// タイトル表示

	return TRUE;
}

/////////////////////////////////////////////////////////////////////////////
// CCaptureDoc シリアライゼーション

void CCaptureDoc::Serialize(CArchive& ar)
{
	if (ar.IsStoring())
	{
		// TODO: この位置に保存用のコードを追加してください。
	}
	else
	{
		// TODO: この位置に読み込み用のコードを追加してください。
	}
}

/////////////////////////////////////////////////////////////////////////////
// CCaptureDoc クラスの診断

#ifdef _DEBUG
void CCaptureDoc::AssertValid() const
{
	CDocument::AssertValid();
}

void CCaptureDoc::Dump(CDumpContext& dc) const
{
	CDocument::Dump(dc);
}
#endif //_DEBUG

/////////////////////////////////////////////////////////////////////////////
// CCaptureDoc コマンド

// ウィンドウに現在のモードを表示
void CCaptureDoc::SetWindowTitle(int mode)
{
	switch (mode) {
	case MODE_AUTO:
		SetTitle("自動");
		break;
	case MODE_PREVIEW:
		SetTitle("ﾌﾟﾚﾋﾞｭｰ");
		break;
	case MODE_STOP:
		SetTitle("停止");
		break;
	}
}
