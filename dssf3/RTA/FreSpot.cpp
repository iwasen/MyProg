// FreSpot.cpp : �����t�@�C��
//

#include "stdafx.h"
#include "RTA.h"
#include "FreSpot.h"
#include "FreDlg.h"
#include "spline.h"
#include "filter.h"
#include "Help\ContextHelp.h"

#define MIN_LEVEL_VAL	-60
#define MAX_LEVEL_VAL	0

#define MIN_LEVEL_POS 0
#define MAX_LEVEL_POS (-(MIN_LEVEL_VAL))

#define GUARD_CNT	2

// CFreSpot �_�C�A���O

CFreSpot::CFreSpot(CWnd* pParent /*=NULL*/)
	: CDialogEx(CFreSpot::IDD, pParent)
{
	m_pWaveLeft = NULL;
	m_pWaveRight = NULL;
	m_pLeftData = NULL;
	m_pRightData = NULL;
	m_pFreq = NULL;
	m_bValidData = FALSE;
	m_bInitialized = FALSE;
}

CFreSpot::~CFreSpot()
{
	DelDataHold(FALSE);
	FreeBuffers();
}

void CFreSpot::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_FREQ_START, m_cFreqStart);
	DDX_Control(pDX, IDC_FREQ_END, m_cFreqEnd);
	DDX_Control(pDX, IDC_FREQ_POINT, m_cFreqPoint);
	DDX_Control(pDX, IDC_FREQ_CURRENT, m_cFreqCurrent);
	DDX_Control(pDX, IDC_LEVEL_SLIDER, m_cLevelSlider);
	DDX_Control(pDX, IDC_LEVEL_EDIT, m_cLevelEdit);
	DDX_Control(pDX, IDC_GRAPH, m_cGraph);
	DDX_Control(pDX, IDC_FREQ_POINT_SPIN, m_cFreqPointSpin);
}


BEGIN_MESSAGE_MAP(CFreSpot, CDialogEx)
	ON_WM_HSCROLL()
	ON_EN_CHANGE(IDC_FREQ_START, &CFreSpot::OnEnChangeFreqStart)
	ON_EN_CHANGE(IDC_FREQ_END, &CFreSpot::OnEnChangeFreqEnd)
	ON_EN_CHANGE(IDC_FREQ_POINT, &CFreSpot::OnEnChangeFreqPoint)
	ON_EN_CHANGE(IDC_LEVEL_EDIT, &CFreSpot::OnEnChangeLevelEdit)
	ON_WM_HELPINFO()
	ON_MESSAGE(WM_TAB_INIT_DIALOG, OnTabInitDialog)
	ON_WM_SIZE()
END_MESSAGE_MAP()


// CFreSpot ���b�Z�[�W �n���h��

BOOL CFreSpot::OnInitDialog()
{
	CDialogEx::OnInitDialog();

	m_cGraph.Initialize(1, g_oSetData.Fre.nSpotFreqStart, g_oSetData.Fre.nSpotFreqEnd);

	InitLevelSlider();

	m_cFreqStart = g_oSetData.Fre.nSpotFreqStart;
	m_cFreqEnd = g_oSetData.Fre.nSpotFreqEnd;
	m_cFreqPoint = g_oSetData.Fre.nSpotPoint;
	m_cLevelEdit = -g_oSetData.Fre.nSpotLevel;

	m_cFreqStart.SetValidChar(VC_NUM);
	m_cFreqEnd.SetValidChar(VC_NUM);
	m_cFreqPoint.SetValidChar(VC_NUM);
	m_cLevelEdit.SetValidChar(VC_NUM);

	m_cFreqPointSpin.SetRange(1, 999);

	return TRUE;
}

LRESULT CFreSpot::OnTabInitDialog(WPARAM /*wParam*/, LPARAM /*lParam*/)
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

void CFreSpot::OnEnChangeFreqStart()
{
	int nFreqStart = m_cFreqStart;

	if (nFreqStart <= 1)
		nFreqStart = 1;

	if (nFreqStart != g_oSetData.Fre.nSpotFreqStart) {
		g_oSetData.Fre.nSpotFreqStart = nFreqStart;
		Redraw();
	}
}

void CFreSpot::OnEnChangeFreqEnd()
{
	int nFreqEnd = m_cFreqEnd;

	if (nFreqEnd <= 1)
		nFreqEnd = 1;

	if (nFreqEnd != g_oSetData.Fre.nSpotFreqEnd) {
		g_oSetData.Fre.nSpotFreqEnd = nFreqEnd;
		Redraw();
	}
}

void CFreSpot::OnEnChangeFreqPoint()
{
	if (m_cFreqPoint.m_hWnd) {
		int nFreqPoint = m_cFreqPoint;

		if (nFreqPoint <= 1)
			nFreqPoint = 1;

		g_oSetData.Fre.nSpotPoint = nFreqPoint;
	}
}

void CFreSpot::OnEnChangeLevelEdit()
{
	SetLevel(-(int)m_cLevelEdit);
}

void CFreSpot::OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar)
{
	switch (pScrollBar->GetDlgCtrlID()) {
	case IDC_LEVEL_SLIDER:
		nPos = ((CSliderCtrl *)pScrollBar)->GetPos();
		SetLevel((int)nPos - MAX_LEVEL_POS);
		break;
	}

	CDialogEx::OnHScroll(nSBCode, nPos, pScrollBar);
}

void CFreSpot::InitLevelSlider()
{
	int nLevel;

	m_cLevelSlider.SetRange(MIN_LEVEL_POS, MAX_LEVEL_POS);

	for (nLevel = MIN_LEVEL_POS; nLevel <= MAX_LEVEL_POS; nLevel += 10)
		m_cLevelSlider.SetTic(nLevel);
}

void CFreSpot::SetLevel(int nLevel)
{
	if (nLevel < MIN_LEVEL_VAL)
		nLevel = MIN_LEVEL_VAL;
	else if (nLevel > MAX_LEVEL_VAL)
		nLevel = MAX_LEVEL_VAL;

	m_cLevelEdit = -nLevel;
	m_cLevelSlider.SetPos(nLevel + MAX_LEVEL_POS);
	g_oSetData.Fre.nSpotLevel = nLevel;
}

int CFreSpot::Initialize()
{
	FreeBuffers();

	m_cFreqCurrent.Blank();
	Redraw();

	m_bValidData = TRUE;
	m_nSamplingRate = g_oSetData.Fre.nSamplingRate;
	m_nChannel = g_oSetData.Fre.nChannel + 1;
	m_fFreqCurrent = g_oSetData.Fre.nSpotFreqStart;
	m_fFreqStep = pow((double)g_oSetData.Fre.nSpotFreqEnd / g_oSetData.Fre.nSpotFreqStart, 1.0 / (g_oSetData.Fre.nSpotPoint - 1));
	m_nFreqPoint = g_oSetData.Fre.nSpotPoint;
	m_nFreqCount = 0;
	m_fLevel = pow(10.0, g_oSetData.Fre.nSpotLevel / 20.0);
	m_nWaveInOffset = 0;
	m_fAngle = 0;
	m_nWaveBufSize = g_oSetData.Fre.nSamplingRate / 5;
	m_bFreqChange = FALSE;
	m_nGuardCnt = GUARD_CNT;

	m_pWaveLeft = new double[m_nSamplingRate];
	m_pLeftData = new double[m_nFreqPoint];
	if (g_oSetData.Fre.nChannel == 1) {
		m_pWaveRight = new double[m_nSamplingRate];
		m_pRightData = new double[m_nFreqPoint];
	}
	m_pFreq = new double[m_nFreqPoint];

	return m_nWaveBufSize;
}

void CFreSpot::Redraw()
{
	if (!m_bValidData) {
		m_nFreqStart = g_oSetData.Fre.nSpotFreqStart;
		m_nFreqEnd = g_oSetData.Fre.nSpotFreqEnd;
		m_nFreqCount = 0;
		m_nFreqPoint = 0;
	}

	m_cGraph.SetBitmap(m_nFreqStart, m_nFreqEnd);

	SDataHold oDataHold;
	POSITION pos;
	for (pos = m_oDataHoldList.GetHeadPosition(); pos != NULL; m_oDataHoldList.GetNext(pos)) {
		oDataHold = m_oDataHoldList.GetAt(pos);
		m_cGraph.DispGraph(oDataHold.pLeftData, oDataHold.pRightData, oDataHold.pFreq, oDataHold.nFreqCount, m_nFreqStart, m_nFreqEnd, oDataHold.nFreqPoint, FALSE, oDataHold.colorLeft, oDataHold.colorRight);
	}

	m_cGraph.DispGraph(m_pLeftData, m_pRightData, m_pFreq, m_nFreqCount, m_nFreqStart, m_nFreqEnd, m_nFreqPoint, FALSE);
}

void CFreSpot::FreeBuffers()
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

BOOL CFreSpot::WaveOutData(double *pData, int nBitsPerSample)
{
	int bReset = FALSE;

	if (m_bFreqChange) {
		m_bFreqChange = FALSE;
		m_nGuardCnt = GUARD_CNT;
		m_nWaveInOffset = 0;
		bReset = TRUE;
	}

	double fAngleStep = m_fFreqCurrent / m_nSamplingRate * 2 * M_PI;
	for (int i = 0; i < m_nWaveBufSize; i++) {
		*pData++ = sin(m_fAngle) * m_fLevel + GetDither(nBitsPerSample);

		m_fAngle += fAngleStep;
		while (m_fAngle >= 2 * M_PI)
			m_fAngle -= 2 * M_PI;
	}

	m_cFreqCurrent.Format("%.0f", m_fFreqCurrent);

	return bReset;
}

BOOL CFreSpot::WaveInData(const double *pData)
{
	CFreDlg *pDlg = (CFreDlg *)GetParent();
	int i;

	if (m_nGuardCnt > 0) {
		m_nGuardCnt--;
		return TRUE;
	}

	for (i = 0; i < m_nWaveBufSize; i++) {
		m_pWaveLeft[m_nWaveInOffset] = *pData++;
		if (m_nChannel == 2)
			m_pWaveRight[m_nWaveInOffset] = *pData++;

		m_nWaveInOffset++;
	}

	if (m_nWaveInOffset == m_nSamplingRate) {
		m_nWaveInOffset = 0;

		if (m_nFreqCount < m_nFreqPoint) {
			m_pLeftData[m_nFreqCount] = CalcFreqResponse(m_pWaveLeft, m_fFreqCurrent, pDlg->m_oMicCalDataL);
			if (m_nChannel == 2)
				m_pRightData[m_nFreqCount] = CalcFreqResponse(m_pWaveRight, m_fFreqCurrent, pDlg->m_oMicCalDataL);

			m_pFreq[m_nFreqCount++] = m_fFreqCurrent;

			Redraw();

			m_fFreqCurrent *= m_fFreqStep;
			m_bFreqChange = TRUE;
		}
	}

	return m_nFreqCount < m_nFreqPoint;
}

double CFreSpot::CalcFreqResponse(const double *pData, double fFreq, const DbMicCalRec &oMicCalData)
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

	double fSinSum = 0;
	double fCosSum = 0;
	double fAngleStep = m_fFreqCurrent / m_nSamplingRate * 2 * M_PI;
	for (i = 0; i < m_nSamplingRate; i++) {
		fSinSum += pData[i] * sin(m_fAngle);
		fCosSum += pData[i] * cos(m_fAngle);

		m_fAngle += fAngleStep;
		while (m_fAngle >= 2 * M_PI)
			m_fAngle -= 2 * M_PI;
	}

	fSinSum = fSinSum * fFilter / m_nSamplingRate;
	fCosSum = fCosSum * fFilter / m_nSamplingRate;

	return (fSinSum * fSinSum + fCosSum * fCosSum) * 2;
}

BOOL CFreSpot::CheckDataExist()
{
	return m_bValidData && m_nFreqCount != 0;
}

void CFreSpot::CsvOutput(LPCTSTR pFileName)
{
	CCsvFile cCsvFile;

	if (cCsvFile.Open(pFileName, CFile::modeCreate | CFile::modeWrite | CFile::shareDenyNone))
		m_cGraph.CsvOutput(cCsvFile, m_pLeftData, m_pRightData, m_pFreq, m_nFreqCount, "Spot");
}

HBITMAP CFreSpot::GetBitmap()
{
	return m_cGraph.GetBitmap();
}

BOOL CFreSpot::OnHelpInfo(HELPINFO* pHelpInfo)
{
	static const UINT aIDs[] = {
		IDC_GRAPH, IDH_FRE_SPOT_GRAPH,
		IDC_FREQ_START, IDH_FRE_SPOT_FREQ_START,
		IDC_FREQ_END, IDH_FRE_SPOT_FREQ_END,
		IDC_FREQ_POINT, IDH_FRE_SPOT_FREQ_POINT,
		IDC_FREQ_CURRENT, IDH_FRE_SPOT_FREQ_CURRENT,
		IDC_LEVEL_SLIDER, IDH_FRE_SPOT_LEVEL_SLIDER,
		IDC_LEVEL_EDIT, IDH_FRE_SPOT_LEVEL_EDIT,
		0
	};

	DispContextHelp(pHelpInfo, aIDs);

	return TRUE;
}

void CFreSpot::OnSize(UINT nType, int cx, int cy)
{
	CDialogEx::OnSize(nType, cx, cy);

	if (m_bInitialized) {
		m_cGraph.Resize();
		Redraw();
	}
}

void CFreSpot::AddDataHold(COLORREF colorLeft, COLORREF colorRight)
{
	SDataHold oDataHold;
	memset(&oDataHold, 0, sizeof(oDataHold));

	oDataHold.colorLeft = colorLeft;
	oDataHold.colorRight = colorRight;
	oDataHold.nFreqPoint = m_nFreqPoint;
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

void CFreSpot::DelDataHold(BOOL bRedraw)
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

BOOL CFreSpot::CheckDataHold()
{
	return !m_oDataHoldList.IsEmpty();
}
