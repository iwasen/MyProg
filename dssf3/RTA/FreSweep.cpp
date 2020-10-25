// FreSweep.cpp : �����t�@�C��
//

#include "stdafx.h"
#include "RTA.h"
#include "FreSweep.h"
#include "FreDlg.h"
#include "spline.h"
#include "filter.h"
#include "Help\ContextHelp.h"

#define MIN_LEVEL_VAL	-60
#define MAX_LEVEL_VAL	0

#define MIN_LEVEL_POS 0
#define MAX_LEVEL_POS (-(MIN_LEVEL_VAL))

#define RESOLUTION	10

// CFreSweep �_�C�A���O

CFreSweep::CFreSweep(CWnd* pParent /*=NULL*/)
	: CDialogEx(CFreSweep::IDD, pParent)
{
	m_pWaveLeft = NULL;
	m_pWaveRight = NULL;
	m_pLeftData = NULL;
	m_pRightData = NULL;
	m_pFreq = NULL;
	m_bValidData = FALSE;
	m_bInitialized = FALSE;
}

CFreSweep::~CFreSweep()
{
	DelDataHold(FALSE);
	FreeBuffers();
}

void CFreSweep::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_GRAPH, m_cGraph);
	DDX_Control(pDX, IDC_FREQ_START, m_cFreqStart);
	DDX_Control(pDX, IDC_FREQ_END, m_cFreqEnd);
	DDX_Control(pDX, IDC_SWEEP_TIME, m_cSweepTime);
	DDX_Control(pDX, IDC_FREQ_CURRENT, m_cFreqCurrent);
	DDX_Control(pDX, IDC_LEVEL_SLIDER, m_cLevelSlider);
	DDX_Control(pDX, IDC_LEVEL_EDIT, m_cLevelEdit);
	DDX_Control(pDX, IDC_SWEEP_TIME_SPIN, m_cSweepTimeSpin);
}


BEGIN_MESSAGE_MAP(CFreSweep, CDialogEx)
	ON_EN_CHANGE(IDC_FREQ_START, &CFreSweep::OnEnChangeFreqStart)
	ON_EN_CHANGE(IDC_FREQ_END, &CFreSweep::OnEnChangeFreqEnd)
	ON_EN_CHANGE(IDC_SWEEP_TIME, &CFreSweep::OnEnChangeSweepTime)
	ON_EN_CHANGE(IDC_LEVEL_EDIT, &CFreSweep::OnEnChangeLevelEdit)
	ON_WM_HSCROLL()
	ON_WM_HELPINFO()
	ON_MESSAGE(WM_TAB_INIT_DIALOG, OnTabInitDialog)
	ON_WM_SIZE()
END_MESSAGE_MAP()


// CFreSweep ���b�Z�[�W �n���h��

BOOL CFreSweep::OnInitDialog()
{
	CDialogEx::OnInitDialog();

	m_cGraph.Initialize(0, g_oSetData.Fre.nSweepFreqStart, g_oSetData.Fre.nSweepFreqEnd);

	InitLevelSlider();

	m_cFreqStart = g_oSetData.Fre.nSweepFreqStart;
	m_cFreqEnd = g_oSetData.Fre.nSweepFreqEnd;
	m_cSweepTime = g_oSetData.Fre.nSweepTime;
	m_cLevelEdit = -g_oSetData.Fre.nSweepLevel;

	m_cFreqStart.SetValidChar(VC_NUM);
	m_cFreqEnd.SetValidChar(VC_NUM);
	m_cSweepTime.SetValidChar(VC_NUM);
	m_cLevelEdit.SetValidChar(VC_NUM);

	m_cSweepTimeSpin.SetRange(1, 999);

	return TRUE;
}

LRESULT CFreSweep::OnTabInitDialog(WPARAM /*wParam*/, LPARAM /*lParam*/)
{
	SaveWindowSize();

	CWnd *pWnd = GetTopWindow();
	while (pWnd != NULL) {
		switch (pWnd->GetDlgCtrlID()) {
		case IDC_GRAPH:
			SetCtlPosition(pWnd, 0, 0, 1, 1);
			break;
		default:
			SetCtlPosition(pWnd, 0, 1, 0, 1);
			break;
		}
		pWnd = pWnd->GetNextWindow();
	}

	m_bInitialized = TRUE;

	return 0;
}

void CFreSweep::OnEnChangeFreqStart()
{
	int nFreqStart = m_cFreqStart;

	if (nFreqStart <= 1)
		nFreqStart = 1;

	if (nFreqStart != g_oSetData.Fre.nSweepFreqStart) {
		g_oSetData.Fre.nSweepFreqStart = nFreqStart;
		Redraw();
	}
}

void CFreSweep::OnEnChangeFreqEnd()
{
	int nFreqEnd = m_cFreqEnd;

	if (nFreqEnd <= 1)
		nFreqEnd = 1;

	if (nFreqEnd != g_oSetData.Fre.nSweepFreqEnd) {
		g_oSetData.Fre.nSweepFreqEnd = nFreqEnd;
		Redraw();
	}
}

void CFreSweep::OnEnChangeSweepTime()
{
	if (m_cSweepTime.m_hWnd) {
		int nSweepTime = m_cSweepTime;

		if (nSweepTime <= 1)
			nSweepTime = 1;

		g_oSetData.Fre.nSweepTime = nSweepTime;
	}
}

void CFreSweep::OnEnChangeLevelEdit()
{
	SetLevel(-(int)m_cLevelEdit);
}

void CFreSweep::OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar)
{
	switch (pScrollBar->GetDlgCtrlID()) {
	case IDC_LEVEL_SLIDER:
		nPos = ((CSliderCtrl *)pScrollBar)->GetPos();
		SetLevel((int)nPos - MAX_LEVEL_POS);
		break;
	}

	CDialogEx::OnHScroll(nSBCode, nPos, pScrollBar);
}

void CFreSweep::InitLevelSlider()
{
	int nLevel;

	m_cLevelSlider.SetRange(MIN_LEVEL_POS, MAX_LEVEL_POS);

	for (nLevel = MIN_LEVEL_POS; nLevel <= MAX_LEVEL_POS; nLevel += 10)
		m_cLevelSlider.SetTic(nLevel);
}

void CFreSweep::SetLevel(int nLevel)
{
	if (nLevel < MIN_LEVEL_VAL)
		nLevel = MIN_LEVEL_VAL;
	else if (nLevel > MAX_LEVEL_VAL)
		nLevel = MAX_LEVEL_VAL;

	m_cLevelEdit = -nLevel;
	m_cLevelSlider.SetPos(nLevel + MAX_LEVEL_POS);
	g_oSetData.Fre.nSweepLevel = nLevel;
}

int CFreSweep::Initialize()
{
	FreeBuffers();

	m_cFreqCurrent.Blank();
	Redraw();

	m_bValidData = TRUE;
	m_nSamplingRate = g_oSetData.Fre.nSamplingRate;
	m_nChannel = g_oSetData.Fre.nChannel + 1;
	m_fFreqCurrent = g_oSetData.Fre.nSweepFreqStart;
	m_fFreqStep = pow((double)g_oSetData.Fre.nSweepFreqEnd / g_oSetData.Fre.nSweepFreqStart, 1.0 / (g_oSetData.Fre.nSweepTime * RESOLUTION));
	m_nFreqPoint = g_oSetData.Fre.nSweepTime * RESOLUTION + 1;
	m_nFreqCount = 0;
	m_nWaveBufSize = g_oSetData.Fre.nSamplingRate / RESOLUTION;
	m_fSweepAngle = 0;
	m_fSweepStep = pow((double)g_oSetData.Fre.nSweepFreqEnd / g_oSetData.Fre.nSweepFreqStart, 1.0 / (g_oSetData.Fre.nSamplingRate * g_oSetData.Fre.nSweepTime));
	m_fSweepFreq = g_oSetData.Fre.nSweepFreqStart / sqrt(m_fFreqStep);
	m_fSweepEnd = g_oSetData.Fre.nSweepFreqEnd * sqrt(m_fFreqStep);
	m_fLevel = pow(10.0, g_oSetData.Fre.nSweepLevel / 20.0);

	m_pWaveLeft = new double[m_nWaveBufSize];
	m_pLeftData = new double[m_nFreqPoint];
	if (g_oSetData.Fre.nChannel == 1) {
		m_pWaveRight = new double[m_nWaveBufSize];
		m_pRightData = new double[m_nFreqPoint];
	}

	m_pFreq = new double[m_nFreqPoint];

	return m_nWaveBufSize;
}

void CFreSweep::Redraw()
{
	if (!m_bValidData) {
		m_nFreqStart = g_oSetData.Fre.nSweepFreqStart;
		m_nFreqEnd = g_oSetData.Fre.nSweepFreqEnd;
		m_nFreqCount = 0;
	}

	m_cGraph.SetBitmap(m_nFreqStart, m_nFreqEnd);

	SDataHold oDataHold;
	POSITION pos;
	for (pos = m_oDataHoldList.GetHeadPosition(); pos != NULL; m_oDataHoldList.GetNext(pos)) {
		oDataHold = m_oDataHoldList.GetAt(pos);
		m_cGraph.DispGraph(oDataHold.pLeftData, oDataHold.pRightData, oDataHold.pFreq, oDataHold.nFreqCount, m_nFreqStart, m_nFreqEnd, 0, FALSE, oDataHold.colorLeft, oDataHold.colorRight);
	}

	m_cGraph.DispGraph(m_pLeftData, m_pRightData, m_pFreq, m_nFreqCount, m_nFreqStart, m_nFreqEnd, 0, FALSE);
}

void CFreSweep::FreeBuffers()
{
	if (m_pWaveLeft != NULL) {
		delete [] m_pWaveLeft;
		m_pWaveLeft = NULL;
	}

	if (m_pWaveRight != NULL) {
		delete [] m_pWaveRight;
		m_pWaveRight = NULL;
	}

	if (m_pLeftData != NULL) {
		delete [] m_pLeftData;
		m_pLeftData = NULL;
	}

	if (m_pRightData != NULL) {
		delete [] m_pRightData;
		m_pRightData = NULL;
	}

	if (m_pFreq != NULL) {
		delete [] m_pFreq;
		m_pFreq = NULL;
	}

	m_bValidData = FALSE;
}

BOOL CFreSweep::WaveOutData(double *pData, int nBitsPerSample)
{
	for (int i = 0; i < m_nWaveBufSize; i++) {
		if (m_fSweepFreq <= m_fSweepEnd) {
			*pData++ = sin(2 * M_PI * m_fSweepAngle) * m_fLevel + GetDither(nBitsPerSample);

			double fAngleStep = m_fSweepFreq / m_nSamplingRate;
			m_fSweepAngle += fAngleStep;
			while (m_fSweepAngle >= 1)
				m_fSweepAngle -= 1;

			m_fSweepFreq *= m_fSweepStep;
		} else
			*pData++ = 0;
	}

	m_cFreqCurrent.Format("%.0f", m_fFreqCurrent);

	return FALSE;
}

BOOL CFreSweep::WaveInData(const double *pData)
{
	CFreDlg *pDlg = (CFreDlg *)GetParent();

	for (int i = 0; i < m_nWaveBufSize; i++) {
		m_pWaveLeft[i] = *pData++;
		if (m_nChannel == 2)
			m_pWaveRight[i] = *pData++;
	}

	if (m_nFreqCount < m_nFreqPoint) {
		m_pLeftData[m_nFreqCount] = CalcFreqResponse(m_pWaveLeft, m_fFreqCurrent, pDlg->m_oMicCalDataL);
		if (m_nChannel == 2)
			m_pRightData[m_nFreqCount] = CalcFreqResponse(m_pWaveRight, m_fFreqCurrent, pDlg->m_oMicCalDataR);

		m_pFreq[m_nFreqCount++] = m_fFreqCurrent;

		Redraw();

		m_fFreqCurrent *= m_fFreqStep;
	}

	return m_nFreqCount < m_nFreqPoint;
}

double CFreSweep::CalcFreqResponse(const double *pData, double fFreq, const DbMicCalRec &oMicCalData)
{
	int i;

	double fFilter;
	int nFilterData = oMicCalData.nFreqData;
	if (nFilterData < 3)
		fFilter = 1;
	else {
		CSpline spline;
		FilterData *pFilterData = (FilterData *)oMicCalData.aFreq;
		double *pFreq = new double[nFilterData];
		double *pLevel = new double[nFilterData];

		for (i = 0; i < nFilterData; i++) {
			pFreq[i] = log(pFilterData->fFreq);
			pLevel[i] = pFilterData->fLevel;
			pFilterData++;
		}

		spline.MakeTable(pFreq, pLevel, nFilterData);

		fFilter = pow(10.0, spline.Spline(log(fFreq)) / 20);

		delete [] pFreq;
		delete [] pLevel;
	}

	fFilter = pow(10.0, -oMicCalData.fInputSens / 20) / fFilter;

	double fOffset = 0;
	for (i = 0; i < m_nWaveBufSize; i++)
		fOffset += pData[i];
	fOffset /= m_nWaveBufSize;

	double fSum = 0;
	for (i = 0; i < m_nWaveBufSize; i++) {
		double fData = (*pData++ - fOffset) * fFilter;
		fSum += fData * fData;
	}

	return fSum / m_nWaveBufSize;
}

BOOL CFreSweep::CheckDataExist()
{
	return m_bValidData && m_nFreqCount != 0;
}

void CFreSweep::CsvOutput(LPCTSTR pFileName)
{
	CCsvFile cCsvFile;

	if (cCsvFile.Open(pFileName, CFile::modeCreate | CFile::modeWrite | CFile::shareDenyNone))
		m_cGraph.CsvOutput(cCsvFile, m_pLeftData, m_pRightData, m_pFreq, m_nFreqCount, "Sweep");
}

HBITMAP CFreSweep::GetBitmap()
{
	return m_cGraph.GetBitmap();
}

BOOL CFreSweep::OnHelpInfo(HELPINFO* pHelpInfo)
{
	static const UINT aIDs[] = {
		IDC_GRAPH, IDH_FRE_SWEEP_GRAPH,
		IDC_FREQ_START, IDH_FRE_SWEEP_FREQ_START,
		IDC_FREQ_END, IDH_FRE_SWEEP_FREQ_END,
		IDC_SWEEP_TIME, IDH_FRE_SWEEP_TIME,
		IDC_FREQ_CURRENT, IDH_FRE_SWEEP_FREQ_CURRENT,
		IDC_LEVEL_SLIDER, IDH_FRE_SWEEP_LEVEL_SLIDER,
		IDC_LEVEL_EDIT, IDH_FRE_SWEEP_LEVEL_EDIT,
		0
	};

	DispContextHelp(pHelpInfo, aIDs);

	return TRUE;
}

void CFreSweep::OnSize(UINT nType, int cx, int cy)
{
	CDialogEx::OnSize(nType, cx, cy);

	if (m_bInitialized) {
		m_cGraph.Resize();
		Redraw();
	}
}

void CFreSweep::AddDataHold(COLORREF colorLeft, COLORREF colorRight)
{
	SDataHold oDataHold;
	memset(&oDataHold, 0, sizeof(oDataHold));

	oDataHold.colorLeft = colorLeft;
	oDataHold.colorRight = colorRight;
	oDataHold.nFreqCount = m_nFreqCount;

	if (m_pLeftData != NULL) {
		oDataHold.pLeftData = new double[m_nFreqPoint];
		memcpy(oDataHold.pLeftData, m_pLeftData, m_nFreqPoint * sizeof(double));
	}

	if (m_pRightData != NULL) {
		oDataHold.pRightData = new double[m_nFreqPoint];
		memcpy(oDataHold.pRightData, m_pRightData, m_nFreqPoint * sizeof(double));
	}

	if (m_pFreq != NULL) {
		oDataHold.pFreq = new double[m_nFreqPoint];
		memcpy(oDataHold.pFreq, m_pFreq, m_nFreqPoint * sizeof(double));
	}

	m_oDataHoldList.AddTail(oDataHold);

	m_bValidData = FALSE;
	Redraw();
}

void CFreSweep::DelDataHold(BOOL bRedraw)
{
	SDataHold oDataHold;

	while (!m_oDataHoldList.IsEmpty()) {
		oDataHold = m_oDataHoldList.RemoveTail();

		if (oDataHold.pLeftData != NULL)
			delete [] oDataHold.pLeftData;

		if (oDataHold.pRightData != NULL)
			delete [] oDataHold.pRightData;

		if (oDataHold.pFreq != NULL)
			delete [] oDataHold.pFreq;
	}

	if (bRedraw)
		Redraw();
}

BOOL CFreSweep::CheckDataHold()
{
	return !m_oDataHoldList.IsEmpty();
}
