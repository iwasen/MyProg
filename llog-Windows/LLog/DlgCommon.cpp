// DlgCommon.cpp : 実装ファイル
//

#include "stdafx.h"
#include "LLog.h"
#include "DlgCommon.h"


// CDlgCommon ダイアログ

IMPLEMENT_DYNAMIC(CDlgCommon, CDialog)

CDlgCommon::CDlgCommon(UINT nID, CWnd* pParent)
	: CDialog(nID, pParent)
{
	m_sizeDlg = 0;
	m_bSizeChange = FALSE;
}

void CDlgCommon::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
}


BEGIN_MESSAGE_MAP(CDlgCommon, CDialog)
	ON_WM_SIZE()
//	ON_MESSAGE(WM_EXITSIZEMOVE, OnExitSizeMove)
	ON_WM_GETMINMAXINFO()
END_MESSAGE_MAP()


UINT CDlgCommon::DispDialog(CDataBase &oDataBase, CTime oDate)
{
	m_pDataBase = &oDataBase;
	m_oDate = oDate;

	return DoModal();
}

void CDlgCommon::SetCtlPosition(UINT nID, double fLeft, double fTop, double fRight, double fBottom)
{
	CWnd *pWnd = GetDlgItem(nID);

	HWND hWnd = pWnd->GetSafeHwnd();
	if (hWnd == NULL)
		return;

	// 設定情報を構造体に設定
	SDlgCtlPosition cDlgCtlPosition;
	cDlgCtlPosition.hWnd = hWnd;
	cDlgCtlPosition.fLeft = fLeft;
	cDlgCtlPosition.fTop = fTop;
	cDlgCtlPosition.fRight = fRight;
	cDlgCtlPosition.fBottom = fBottom;

	// 現在のウィンドウ位置を取得
	WINDOWPLACEMENT wp;
	pWnd->GetWindowPlacement(&wp);
	cDlgCtlPosition.rectCtl = wp.rcNormalPosition;

	// 配列に設定情報を追加
	m_aDlgCtlPosition.Add(cDlgCtlPosition);
}

BOOL CDlgCommon::OnInitDialog()
{
	CDialog::OnInitDialog();

	CRect rect;
	GetClientRect(rect);
	m_sizeDlg = rect.Size();

	return TRUE;
}

void CDlgCommon::OnSize(UINT nType, int cx, int cy)
{
	CDialog::OnSize(nType, cx, cy);

	// ダイアログコントロールを適切な位置に再配置
	if (m_sizeDlg.cx && cx != 0) {
		// サイズの差分
		int nx = cx - m_sizeDlg.cx;
		int ny = cy - m_sizeDlg.cy;

		// 変更されたサイズ分ダイアログ内のコントロールの大きさと位置を変更する
		int nDlgCtlPosition = (int)m_aDlgCtlPosition.GetSize();
		for (int i = 0; i < nDlgCtlPosition; i++) {
			// 子ウィンドウを取得
			SDlgCtlPosition &cDlgCtlPosition = m_aDlgCtlPosition[i];
			CWnd *pWnd = FromHandle(m_aDlgCtlPosition[i].hWnd);

			// 子ウィンドウの位置、大きさを取得
			CRect rect(cDlgCtlPosition.rectCtl);

			// 位置と大きさを調整
			rect.left += (int)(nx * cDlgCtlPosition.fLeft);
			rect.top += (int)(ny * cDlgCtlPosition.fTop);
			rect.right += (int)(nx * cDlgCtlPosition.fRight);
			rect.bottom += (int)(ny * cDlgCtlPosition.fBottom);

			// 子ウィンドウを移動
			pWnd->SetWindowPos(NULL, rect.left, rect.top, rect.Width(), rect.Height(), SWP_NOZORDER);
		}

		// サイズ変更フラグON
		m_bSizeChange = TRUE;

		Invalidate(FALSE);
//		UpdateWindow();
	}
}

LRESULT CDlgCommon::OnExitSizeMove(WPARAM wParam, LPARAM lParam)
{
	// サイズ変更フラグがONならダイアログを再表示
	if (m_bSizeChange) {
		Invalidate();
		m_bSizeChange = FALSE;
	}

	return 0;
}

void CDlgCommon::OnGetMinMaxInfo(MINMAXINFO* lpMMI)
{
	// ウィンドウの最小サイズを設定
	if (m_sizeDlg.cx != 0) {
		lpMMI->ptMinTrackSize.x = m_sizeDlg.cx;
		lpMMI->ptMinTrackSize.y = m_sizeDlg.cy;
	}

	CDialog::OnGetMinMaxInfo(lpMMI);
}
