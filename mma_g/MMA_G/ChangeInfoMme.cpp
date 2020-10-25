// ChangeInfoMme.cpp : 実装ファイル
//

#include "stdafx.h"
#include "MMA_G.h"
#include "ChangeInfoMme.h"


// CChangeInfoMme ダイアログ

IMPLEMENT_DYNAMIC(CChangeInfoMme, CDialog)

CChangeInfoMme::CChangeInfoMme(CWnd* pParent /*=NULL*/)
	: CDialog(CChangeInfoMme::IDD, pParent)
{

}

CChangeInfoMme::~CChangeInfoMme()
{
}

void CChangeInfoMme::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
}


BEGIN_MESSAGE_MAP(CChangeInfoMme, CDialog)
END_MESSAGE_MAP()


// CChangeInfoMme メッセージ ハンドラ
