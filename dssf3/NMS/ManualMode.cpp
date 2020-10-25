// ManualMode.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "Nms.h"
#include "NmsDoc.h"
#include "ManualMode.h"
#include "SetData.h"
#include "Help\ContextHelp.h"

/////////////////////////////////////////////////////////////////////////////
// CManualMode �_�C�A���O


CManualMode::CManualMode(CWnd* pParent /*=NULL*/)
	: CDialog(CManualMode::IDD, pParent)
{

	Create(IDD, (CWnd *)&wndTop);
}


void CManualMode::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_SAMPLING_TIME, m_cSamplingTime);
	DDX_Control(pDX, IDC_START_STOP, m_cStartStop);
	DDX_Control(pDX, IDC_ELAPSE_TIME, m_cElpseTime);
}


BEGIN_MESSAGE_MAP(CManualMode, CDialog)
	ON_WM_DESTROY()
	ON_EN_CHANGE(IDC_SAMPLING_TIME, OnChangeSamplingTime)
	ON_BN_CLICKED(IDC_START_STOP, OnStartStop)
	ON_WM_HELPINFO()
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CManualMode ���b�Z�[�W �n���h��

BOOL CManualMode::OnInitDialog()
{
	CDialog::OnInitDialog();

	m_cSamplingTime = g_oSetData.ManualWindow.fSamplingTime;

	SetWindowPos(&wndTopMost, g_oSetData.ManualWindow.left, g_oSetData.ManualWindow.top, 0, 0, SWP_NOSIZE);
	ShowWindow(SW_SHOW);

	return TRUE;
}

void CManualMode::OnOK()
{
}

void CManualMode::OnCancel()
{
	DestroyWindow();
}

void CManualMode::OnDestroy()
{
	WINDOWPLACEMENT winpl;

	m_pDoc->StopManualMode();

	winpl.length = sizeof(WINDOWPLACEMENT);
	GetWindowPlacement(&winpl);			// �_�C�A���O�\���ʒu�擾
	g_oSetData.ManualWindow.left = winpl.rcNormalPosition.left;
	g_oSetData.ManualWindow.top = winpl.rcNormalPosition.top;

	CDialog::OnDestroy();
}

void CManualMode::PostNcDestroy()
{
	delete this;
	g_pManualWnd  = NULL;
}

void CManualMode::OnChangeSamplingTime()
{
	if (g_oSetData.ManualWindow.fSamplingTime != (double)m_cSamplingTime) {
		g_oSetData.ManualWindow.fSamplingTime = m_cSamplingTime;
		m_pDoc->m_oMeasurement.Stop();
		m_pDoc->m_oMeasurement.Start(MEASUREMENT_MANUAL);
	}
}

void CManualMode::OnStartStop()
{
	if ((BOOL)m_cStartStop == FALSE)
		m_pDoc->m_oMeasurement.StartManualSampling();
	else
		m_pDoc->m_oMeasurement.StopManualSampling();
}

void CManualMode::NotifyMeasuring(BOOL bStart)
{
	CString str;

	m_cStartStop = bStart;
	str.LoadString(bStart ? IDS_STOP : IDS_START);
	m_cStartStop.SetWindowText(str);
}

void CManualMode::NotifyElpseTime(double fTime)
{
	m_cElpseTime.Format("%.1f", fTime);
}

BOOL CManualMode::OnHelpInfo(HELPINFO* pHelpInfo)
{
	static const UINT aIDs[] = {
		IDC_START_STOP, IDH_MANUAL_MODE_START_STOP,
		IDCANCEL, IDH_MANUAL_MODE_CANCEL,
		IDC_SAMPLING_TIME, IDH_MANUAL_MODE_SAMPLING_TIME,
		IDC_ELAPSE_TIME, IDH_MANUAL_MODE_ELAPSE_TIME,
		0
	};

	DispContextHelp(pHelpInfo, aIDs);

	return TRUE;
}
