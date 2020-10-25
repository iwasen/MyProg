// DlgCommon.cpp : �����t�@�C��
//

#include "stdafx.h"
#include "LLog.h"
#include "DlgCommon.h"


// CDlgCommon �_�C�A���O

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

	// �ݒ�����\���̂ɐݒ�
	SDlgCtlPosition cDlgCtlPosition;
	cDlgCtlPosition.hWnd = hWnd;
	cDlgCtlPosition.fLeft = fLeft;
	cDlgCtlPosition.fTop = fTop;
	cDlgCtlPosition.fRight = fRight;
	cDlgCtlPosition.fBottom = fBottom;

	// ���݂̃E�B���h�E�ʒu���擾
	WINDOWPLACEMENT wp;
	pWnd->GetWindowPlacement(&wp);
	cDlgCtlPosition.rectCtl = wp.rcNormalPosition;

	// �z��ɐݒ����ǉ�
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

	// �_�C�A���O�R���g���[����K�؂Ȉʒu�ɍĔz�u
	if (m_sizeDlg.cx && cx != 0) {
		// �T�C�Y�̍���
		int nx = cx - m_sizeDlg.cx;
		int ny = cy - m_sizeDlg.cy;

		// �ύX���ꂽ�T�C�Y���_�C�A���O���̃R���g���[���̑傫���ƈʒu��ύX����
		int nDlgCtlPosition = (int)m_aDlgCtlPosition.GetSize();
		for (int i = 0; i < nDlgCtlPosition; i++) {
			// �q�E�B���h�E���擾
			SDlgCtlPosition &cDlgCtlPosition = m_aDlgCtlPosition[i];
			CWnd *pWnd = FromHandle(m_aDlgCtlPosition[i].hWnd);

			// �q�E�B���h�E�̈ʒu�A�傫�����擾
			CRect rect(cDlgCtlPosition.rectCtl);

			// �ʒu�Ƒ傫���𒲐�
			rect.left += (int)(nx * cDlgCtlPosition.fLeft);
			rect.top += (int)(ny * cDlgCtlPosition.fTop);
			rect.right += (int)(nx * cDlgCtlPosition.fRight);
			rect.bottom += (int)(ny * cDlgCtlPosition.fBottom);

			// �q�E�B���h�E���ړ�
			pWnd->SetWindowPos(NULL, rect.left, rect.top, rect.Width(), rect.Height(), SWP_NOZORDER);
		}

		// �T�C�Y�ύX�t���OON
		m_bSizeChange = TRUE;

		Invalidate(FALSE);
//		UpdateWindow();
	}
}

LRESULT CDlgCommon::OnExitSizeMove(WPARAM wParam, LPARAM lParam)
{
	// �T�C�Y�ύX�t���O��ON�Ȃ�_�C�A���O���ĕ\��
	if (m_bSizeChange) {
		Invalidate();
		m_bSizeChange = FALSE;
	}

	return 0;
}

void CDlgCommon::OnGetMinMaxInfo(MINMAXINFO* lpMMI)
{
	// �E�B���h�E�̍ŏ��T�C�Y��ݒ�
	if (m_sizeDlg.cx != 0) {
		lpMMI->ptMinTrackSize.x = m_sizeDlg.cx;
		lpMMI->ptMinTrackSize.y = m_sizeDlg.cy;
	}

	CDialog::OnGetMinMaxInfo(lpMMI);
}
