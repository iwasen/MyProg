// Param1.cpp : �����t�@�C��
//

#include "stdafx.h"
#include "Sas.h"
#include "Param1.h"


// CParam1 �_�C�A���O

CParam1::CParam1(CWnd* pParent /*=NULL*/)
	: CDialog(CParam1::IDD, pParent)
{
	m_iFreqBand = -1;
	m_bTsubAuto = FALSE;
	m_bAFilter = FALSE;
	m_sStartFreq = _T("");
	m_sEndFreq = _T("");
}

CParam1::~CParam1()
{
}

void CParam1::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);

	DDX_Control(pDX, IDC_PREF_SPL, m_cPrefSPL);
	DDX_Control(pDX, IDC_PREF_TAUE, m_cPrefTauE);
	DDX_Control(pDX, IDC_IACC_WLEVEL, m_cIACCWLevel);
	DDX_Control(pDX, IDC_DT1_MINTIME, m_cDT1MinTime);
	DDX_Control(pDX, IDC_TSUB_NOISE, m_cTsubNoise);
	DDX_Control(pDX, IDC_TSUB_END, m_cTsubEnd);
	DDX_Control(pDX, IDC_SPL_REFLEVEL, m_cSplRefLevel);
	DDX_Control(pDX, IDC_SPL_REFDATA, m_cSplRefData);
	DDX_Radio(pDX, IDC_1OCT, m_iFreqBand);
	DDX_Check(pDX, IDC_TSUB_AUTO, m_bTsubAuto);
	DDX_Check(pDX, IDC_AFILTER, m_bAFilter);
	DDX_Text(pDX, IDC_START_FREQ, m_sStartFreq);
	DDX_Text(pDX, IDC_END_FREQ, m_sEndFreq);
}


BEGIN_MESSAGE_MAP(CParam1, CDialog)
END_MESSAGE_MAP()
