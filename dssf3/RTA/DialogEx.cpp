// CDialogEx.cpp : �����t�@�C��
//

#include "stdafx.h"
#include "Rta.h"
#include "DialogEx.h"

// CDialogEx �_�C�A���O

CDialogEx::CDialogEx(UINT nID, CWnd* pParent)
	: CDialog(nID, pParent)
{
	m_sizeWindow = 0;
	m_sizeClient = 0;
	m_hMaximunCtrl = NULL;
}

BEGIN_MESSAGE_MAP(CDialogEx, CDialog)
	ON_WM_SIZE()
END_MESSAGE_MAP()


// CDialogEx ���b�Z�[�W �n���h��

void CDialogEx::SetCtlPosition(UINT nID, double fLeft, double fTop, double fRight, double fBottom)
{
	SetCtlPosition(GetDlgItem(nID), fLeft, fTop, fRight, fBottom);
}

void CDialogEx::SetCtlPosition(CWnd *pWnd, double fLeft, double fTop, double fRight, double fBottom)
{
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

	int nSize = (int)m_aDlgCtlPosition.GetSize();
	for (int i = 0; i < nSize; i++) {
		if (m_aDlgCtlPosition[i].hWnd == hWnd) {
			// �z��ɐݒ�����㏑��
			m_aDlgCtlPosition[i] = cDlgCtlPosition;
			return;
		}
	}

	// �z��ɐݒ����ǉ�
	m_aDlgCtlPosition.Add(cDlgCtlPosition);
}

void CDialogEx::SaveWindowSize()
{
	CRect rect;
	GetClientRect(rect);
	m_sizeClient = rect.Size();
	GetWindowRect(rect);
	m_sizeWindow = rect.Size();
}

BOOL CDialogEx::OnInitDialog()
{
	CDialog::OnInitDialog();

	SaveWindowSize();

	return TRUE;
}

void CDialogEx::OnSize(UINT nType, int cx, int cy)
{
	CDialog::OnSize(nType, cx, cy);

	RepositionControls(cx, cy);
}

void CDialogEx::RepositionControls(int cx, int cy)
{
	// �X�e�[�^�X�o�[���Ĕz�u
	if (m_wndStatusBar.m_hWnd) {
//		RepositionBars(AFX_IDW_CONTROLBAR_FIRST, AFX_IDW_CONTROLBAR_LAST, 0);
		CRect rectStatusBar;
		m_wndStatusBar.GetWindowRect(rectStatusBar);
		cy -= rectStatusBar.Height();
	}

	// �_�C�A���O�R���g���[����K�؂Ȉʒu�ɍĔz�u
	if (m_sizeClient.cx && cx != 0) {
		// �T�C�Y�̍���
		int nx = cx - m_sizeClient.cx;
		int ny = cy - m_sizeClient.cy;

		// �ύX���ꂽ�T�C�Y���_�C�A���O���̃R���g���[���̑傫���ƈʒu��ύX����
		int nDlgCtlPosition = (int)m_aDlgCtlPosition.GetSize();
		for (int i = 0; i < nDlgCtlPosition; i++) {
			// �q�E�B���h�E���擾
			SDlgCtlPosition &cDlgCtlPosition = m_aDlgCtlPosition[i];
			CWnd *pWnd = FromHandle(m_aDlgCtlPosition[i].hWnd);

			if (m_hMaximunCtrl == pWnd->m_hWnd) {
				// �q�E�B���h�E���ړ�
				pWnd->SetWindowPos(NULL, 0, 0, cx, cy, SWP_NOZORDER);
			} else {
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
			pWnd->UpdateWindow();
		}

		Invalidate();
	}

	if (m_wndStatusBar.m_hWnd)
		RepositionBars(AFX_IDW_CONTROLBAR_FIRST, AFX_IDW_CONTROLBAR_LAST, 0);
}

void CDialogEx::SetMaximunControl(HWND hMaximunCtrl)
{
	m_hMaximunCtrl = hMaximunCtrl;

	CRect rect;
	GetClientRect(rect);
	RepositionControls(rect.right, rect.bottom);
}

void CDialogEx::CreateStatusBar()
{
	if (m_wndStatusBar.Create(this)) {
		const UINT ind[2] = {0};

		m_wndStatusBar.SetIndicators(ind, 1);
		m_wndStatusBar.SetPaneInfo(0, 0, SBPS_STRETCH, 0);

		RepositionBars(AFX_IDW_CONTROLBAR_FIRST, AFX_IDW_CONTROLBAR_LAST, 0);

		CRect rectStatusBar;
		m_wndStatusBar.GetWindowRect(rectStatusBar);

		CRect rectDialog;
		GetWindowRect(rectDialog);
		rectDialog.bottom += rectStatusBar.Height();
		m_sizeWindow = rectDialog.Size();
		MoveWindow(rectDialog, FALSE);
	}
}

void CDialogEx::SetStatusBar(UINT nID)
{
	CString sText;
	sText.LoadString(nID);
	SetStatusBar(sText);
}

void CDialogEx::SetStatusBar(LPCTSTR pText)
{
	m_wndStatusBar.SetPaneText(0, pText);
}
