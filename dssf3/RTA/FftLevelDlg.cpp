// FftLevelDlg.cpp : �����t�@�C��
//

#include "stdafx.h"
#include "RTA.h"
#include "FftLevelDlg.h"


// CFftLevelDlg �_�C�A���O

CFftLevelDlg::CFftLevelDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CFftLevelDlg::IDD, pParent)
{

}

CFftLevelDlg::~CFftLevelDlg()
{
}

void CFftLevelDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_FFT_MAX_LEVEL, m_cFftMaxLevel);
	DDX_Control(pDX, IDC_FFT_MIN_LEVEL, m_cFftMinLevel);
}


BEGIN_MESSAGE_MAP(CFftLevelDlg, CDialog)
	ON_WM_DESTROY()
	ON_WM_NCDESTROY()
	ON_WM_HSCROLL()
END_MESSAGE_MAP()


// CFftLevelDlg ���b�Z�[�W �n���h��

BOOL CFftLevelDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	m_cFftMaxLevel.SetRange(m_nMinRange + 1000, m_nMaxRange + 1000);
	m_cFftMaxLevel.SetPos(m_nMaxLevel + 1000);
	m_cFftMaxLevel.SetTicFreq(10);

	m_cFftMinLevel.SetRange(m_nMinRange + 1000, m_nMaxRange + 1000);
	m_cFftMinLevel.SetPos(m_nMinLevel + 1000);
	m_cFftMinLevel.SetTicFreq(10);

	return TRUE;
}

void CFftLevelDlg::OnDestroy()
{
	CDialog::OnDestroy();

	m_pFftDlg->CloseLevelDlg();
}

void CFftLevelDlg::OnNcDestroy()
{
	CDialog::OnNcDestroy();

	delete this;
}

void CFftLevelDlg::OnOK()
{
	DestroyWindow();
}

void CFftLevelDlg::OnCancel()
{
	DestroyWindow();
}

void CFftLevelDlg::OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar)
{
	switch (pScrollBar->GetDlgCtrlID()) {
	case IDC_FFT_MAX_LEVEL:
		if (m_cFftMaxLevel.GetPos() <= m_cFftMinLevel.GetPos())
			m_cFftMaxLevel.SetPos(m_cFftMinLevel.GetPos() + 1);
		m_pFftDlg->SetFftMaxLevel(m_cFftMaxLevel.GetPos() - 1000);
		break;
	case IDC_FFT_MIN_LEVEL:
		if (m_cFftMinLevel.GetPos() >= m_cFftMaxLevel.GetPos())
			m_cFftMinLevel.SetPos(m_cFftMaxLevel.GetPos() - 1);
		m_pFftDlg->SetFftMinLevel(m_cFftMinLevel.GetPos() - 1000);
		break;
	}

	CDialog::OnHScroll(nSBCode, nPos, pScrollBar);
}
