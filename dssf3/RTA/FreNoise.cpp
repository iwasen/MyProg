// FreNoise.cpp : �����t�@�C��
//

#include "stdafx.h"
#include "RTA.h"
#include "FreNoise.h"
#include "FreDlg.h"
#include "Filter.h"
#include "Help\ContextHelp.h"

#define MIN_LEVEL_VAL	-60
#define MAX_LEVEL_VAL	0

#define MIN_LEVEL_POS 0
#define MAX_LEVEL_POS (-(MIN_LEVEL_VAL))

static const int s_tFftSize[] = {32768, 16384, 8192, 4096};
static const int s_tTimeConstant[] = {1, 2, 4, 8, 16};

// CFreNoise �_�C�A���O

CFreNoise::CFreNoise(CWnd* pParent /*=NULL*/)
	: CDialogEx(CFreNoise::IDD, pParent)
{
	m_pWaveLeft = NULL;
	m_pWaveRight = NULL;
	m_pFreq = NULL;
	m_bValidData = FALSE;
	m_bInitialized = FALSE;
	m_pFilterTblL = NULL;
	m_pFilterTblR = NULL;
}

CFreNoise::~CFreNoise()
{
	DelDataHold(FALSE);
	FreeBuffers();
}

void CFreNoise::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_GRAPH, m_cGraph);
	DDX_Control(pDX, IDC_FREQ_START, m_cFreqStart);
	DDX_Control(pDX, IDC_FREQ_END, m_cFreqEnd);
	DDX_Control(pDX, IDC_LEVEL_SLIDER, m_cLevelSlider);
	DDX_Control(pDX, IDC_LEVEL_EDIT, m_cLevelEdit);
	DDX_Control(pDX, IDC_RESOLUTION, m_cResolution);
	DDX_Control(pDX, IDC_AVERAGING, m_cAveraging);
}


BEGIN_MESSAGE_MAP(CFreNoise, CDialogEx)
	ON_EN_CHANGE(IDC_FREQ_START, &CFreNoise::OnEnChangeFreqStart)
	ON_EN_CHANGE(IDC_FREQ_END, &CFreNoise::OnEnChangeFreqEnd)
	ON_EN_CHANGE(IDC_LEVEL_EDIT, &CFreNoise::OnEnChangeLevelEdit)
	ON_WM_HSCROLL()
	ON_CBN_SELCHANGE(IDC_RESOLUTION, &CFreNoise::OnCbnSelchangeResolution)
	ON_CBN_SELCHANGE(IDC_AVERAGING, &CFreNoise::OnCbnSelchangeAveraging)
	ON_WM_HELPINFO()
	ON_MESSAGE(WM_TAB_INIT_DIALOG, OnTabInitDialog)
	ON_WM_SIZE()
END_MESSAGE_MAP()


// CFreNoise ���b�Z�[�W �n���h��

BOOL CFreNoise::OnInitDialog()
{
	CDialogEx::OnInitDialog();

	m_cGraph.Initialize(2, g_oSetData.Fre.nNoiseFreqStart, g_oSetData.Fre.nNoiseFreqEnd);

	InitLevelSlider();

	SetResolutionList();
	SetAveragingList();

	m_cFreqStart = g_oSetData.Fre.nNoiseFreqStart;
	m_cFreqEnd = g_oSetData.Fre.nNoiseFreqEnd;
	m_cLevelEdit = -g_oSetData.Fre.nNoiseLevel;

	m_cFreqEnd.SetValidChar(VC_NUM);
	m_cFreqEnd.SetValidChar(VC_NUM);
	m_cLevelEdit.SetValidChar(VC_NUM);

	UpdateData(FALSE);

	return TRUE;
}

LRESULT CFreNoise::OnTabInitDialog(WPARAM /*wParam*/, LPARAM /*lParam*/)
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

void CFreNoise::OnEnChangeFreqStart()
{
	int nFreqStart = m_cFreqStart;

	if (nFreqStart <= 1)
		nFreqStart = 1;

	if (nFreqStart != g_oSetData.Fre.nNoiseFreqStart) {
		g_oSetData.Fre.nNoiseFreqStart = nFreqStart;
		Redraw();
	}
}

void CFreNoise::OnEnChangeFreqEnd()
{
	int nFreqEnd = m_cFreqEnd;

	if (nFreqEnd <= 1)
		nFreqEnd = 1;

	if (nFreqEnd != g_oSetData.Fre.nNoiseFreqEnd) {
		g_oSetData.Fre.nNoiseFreqEnd = nFreqEnd;
		Redraw();
	}
}

void CFreNoise::OnEnChangeLevelEdit()
{
	SetLevel(-(int)m_cLevelEdit);
}

void CFreNoise::OnCbnSelchangeResolution()
{
	g_oSetData.Fre.nNoiseResolution = m_cResolution.GetCurSel();
	CFreDlg *pDlg = (CFreDlg *)GetParent();
	pDlg->Restart();
}

void CFreNoise::OnCbnSelchangeAveraging()
{
	g_oSetData.Fre.nNoiseAveraging = m_cAveraging.GetCurSel();

	if (m_bValidData)
		AllocAverageBuf();
}

void CFreNoise::OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar)
{
	switch (pScrollBar->GetDlgCtrlID()) {
	case IDC_LEVEL_SLIDER:
		nPos = ((CSliderCtrl *)pScrollBar)->GetPos();
		SetLevel((int)nPos - MAX_LEVEL_POS);
		break;
	}

	CDialogEx::OnHScroll(nSBCode, nPos, pScrollBar);
}

void CFreNoise::InitLevelSlider()
{
	int nLevel;

	m_cLevelSlider.SetRange(MIN_LEVEL_POS, MAX_LEVEL_POS);

	for (nLevel = MIN_LEVEL_POS; nLevel <= MAX_LEVEL_POS; nLevel += 10)
		m_cLevelSlider.SetTic(nLevel);
}

void CFreNoise::SetLevel(int nLevel)
{
	if (nLevel < MIN_LEVEL_VAL)
		nLevel = MIN_LEVEL_VAL;
	else if (nLevel > MAX_LEVEL_VAL)
		nLevel = MAX_LEVEL_VAL;

	m_cLevelEdit = -nLevel;
	m_cLevelSlider.SetPos(nLevel + MAX_LEVEL_POS);
	g_oSetData.Fre.nNoiseLevel = nLevel;
}

int CFreNoise::Initialize()
{
	FreeBuffers();

	Redraw();

	m_bValidData = TRUE;
	m_nSamplingRate = g_oSetData.Fre.nSamplingRate;
	m_nChannel = g_oSetData.Fre.nChannel + 1;
	m_nWaveBufSize = s_tFftSize[g_oSetData.Fre.nNoiseResolution];
	m_nFreqPoint = m_nWaveBufSize / 2;
	m_nLevel = g_oSetData.Fre.nNoiseLevel;

	m_pWaveLeft = new double[m_nWaveBufSize];
	if (g_oSetData.Fre.nChannel == 1)
		m_pWaveRight = new double[m_nWaveBufSize];

	m_pFreq = new double[m_nFreqPoint];

	AllocAverageBuf();

	for (int i = 0; i < m_nFreqPoint; i++)
		m_pFreq[i] = (double)m_nSamplingRate / m_nWaveBufSize * i;

	MakeFilterTbl();

	return m_nWaveBufSize;
}

void CFreNoise::AllocAverageBuf()
{
	int nAverageNum = (s_tTimeConstant[g_oSetData.Fre.nNoiseAveraging] * m_nSamplingRate + m_nWaveBufSize / 2) / m_nWaveBufSize;
	m_oLeftData.Alloc(m_nFreqPoint, nAverageNum);
	if (m_nChannel == 2)
		m_oRightData.Alloc(m_nFreqPoint, nAverageNum);
}

void CFreNoise::SetResolutionList()
{
	CString sText;

	m_cResolution.ResetContent();
	for (int i = 0; i < sizeof(s_tFftSize) / sizeof(int); i++) {
		sText.Format("%.1f Hz", (double)g_oSetData.Fre.nSamplingRate / s_tFftSize[i]);
		m_cResolution.AddString(sText);
	}
	m_cResolution.SetCurSel(g_oSetData.Fre.nNoiseResolution);
}

void CFreNoise::SetAveragingList()
{
	CString sText;

	m_cAveraging.ResetContent();
	for (int i = 0; i < sizeof(s_tTimeConstant) / sizeof(int); i++) {
		sText.Format("%d s", s_tTimeConstant[i]);
		m_cAveraging.AddString(sText);
	}
	m_cAveraging.SetCurSel(g_oSetData.Fre.nNoiseAveraging);
}

void CFreNoise::Redraw()
{
	if (!m_bValidData) {
		m_nFreqStart = g_oSetData.Fre.nNoiseFreqStart;
		m_nFreqEnd = g_oSetData.Fre.nNoiseFreqEnd;
		m_nFreqPoint = 0;
	}

	m_cGraph.SetBitmap(m_nFreqStart, m_nFreqEnd);

	SDataHold oDataHold;
	POSITION pos;
	for (pos = m_oDataHoldList.GetHeadPosition(); pos != NULL; m_oDataHoldList.GetNext(pos)) {
		oDataHold = m_oDataHoldList.GetAt(pos);
		m_cGraph.DispGraph(oDataHold.pLeftData + 1, oDataHold.pRightData == NULL ? NULL : oDataHold.pRightData + 1, oDataHold.pFreq + 1, oDataHold.nFreqCount - 1, m_nFreqStart, m_nFreqEnd, 0, FALSE, oDataHold.colorLeft, oDataHold.colorRight);
	}

	m_cGraph.DispGraph(m_oLeftData.GetBuf() + 1, m_nChannel == 1 ? NULL : m_oRightData.GetBuf() + 1, m_pFreq + 1, m_nFreqPoint == 0 ? 0 : m_nFreqPoint - 1, m_nFreqStart, m_nFreqEnd, 0, FALSE);
}

void CFreNoise::FreeBuffers()
{
	if (m_pWaveLeft != NULL) {
		delete [] m_pWaveLeft;
		m_pWaveLeft = NULL;
	}

	if (m_pWaveRight != NULL) {
		delete [] m_pWaveRight;
		m_pWaveRight = NULL;
	}

	if (m_pFreq != NULL) {
		delete [] m_pFreq;
		m_pFreq = NULL;
	}

	if (m_pFilterTblL != NULL) {
		delete [] m_pFilterTblL;
		m_pFilterTblL = NULL;
	}

	if (m_pFilterTblR != NULL) {
		delete [] m_pFilterTblR;
		m_pFilterTblR = NULL;
	}

	m_bValidData = FALSE;
}

BOOL CFreNoise::WaveOutData(double *pData)
{
	BOOL bReset = FALSE;

	if (m_nLevel != g_oSetData.Fre.nNoiseLevel) {
		bReset = TRUE;
		m_nLevel = g_oSetData.Fre.nNoiseLevel;
	}

	double fLevel = pow(10.0, m_nLevel / 20.0);
	m_oNoise.GeneratePinkNoise(pData, 1, m_nWaveBufSize, fLevel);

	return bReset;
}

BOOL CFreNoise::WaveInData(const double *pData)
{
	for (int i = 0; i < m_nWaveBufSize; i++) {
		m_pWaveLeft[i] = *pData++;
		if (m_nChannel == 2)
			m_pWaveRight[i] = *pData++;
	}

	CalcFreqResponse(m_pWaveLeft, m_oLeftData.GetBuf(), m_pFilterTblL);
	m_oLeftData.Averaging();
	if (m_nChannel == 2) {
		CalcFreqResponse(m_pWaveRight, m_oRightData.GetBuf(), m_pFilterTblR);
		m_oRightData.Averaging();
	}

	Redraw();

	return TRUE;
}

void CFreNoise::CalcFreqResponse(double *pWave, double *pData, const double *m_pFilterTbl)
{
	int i, j;
	double xt, yt;

	m_oFFT.fft(m_nWaveBufSize, pWave);

	for (i = 1; i < m_nFreqPoint; i++) {
		j = i * 2;
		xt = pWave[j] * m_pFilterTbl[i];
		yt = pWave[j + 1] * m_pFilterTbl[i];

		*pData++ = (xt * xt + yt * yt) * i;
	}
}

BOOL CFreNoise::CheckDataExist()
{
	return m_bValidData != 0;
}

void CFreNoise::CsvOutput(LPCTSTR pFileName)
{
	CCsvFile cCsvFile;

	if (cCsvFile.Open(pFileName, CFile::modeCreate | CFile::modeWrite | CFile::shareDenyNone))
		m_cGraph.CsvOutput(cCsvFile, m_oLeftData.GetBuf() + 1, m_nChannel == 1 ? NULL : m_oRightData.GetBuf() + 1, m_pFreq + 1, m_nFreqPoint - 1, "Pink Noise");
}

HBITMAP CFreNoise::GetBitmap()
{
	return m_cGraph.GetBitmap();
}

BOOL CFreNoise::OnHelpInfo(HELPINFO* pHelpInfo)
{
	static const UINT aIDs[] = {
		IDC_GRAPH, IDH_FRE_NOISE_GRAPH,
		IDC_FREQ_START, IDH_FRE_NOISE_FREQ_START,
		IDC_FREQ_END, IDH_FRE_NOISE_FREQ_END,
		IDC_RESOLUTION, IDH_FRE_NOISE_RESOLUTION,
		IDC_AVERAGING, IDH_FRE_NOISE_AVERAGING,
		IDC_LEVEL_SLIDER, IDH_FRE_NOISE_LEVEL_SLIDER,
		IDC_LEVEL_EDIT, IDH_FRE_NOISE_LEVEL_EDIT,
		0
	};

	DispContextHelp(pHelpInfo, aIDs);

	return TRUE;
}

void CFreNoise::OnSize(UINT nType, int cx, int cy)
{
	CDialogEx::OnSize(nType, cx, cy);

	if (m_bInitialized) {
		m_cGraph.Resize();
		Redraw();
	}
}

void CFreNoise::MakeFilterTbl()
{
	CFreDlg *pDlg = (CFreDlg *)GetParent();

	m_pFilterTblL = new double[m_nWaveBufSize];
	m_pFilterTblR = new double[m_nWaveBufSize];

	MakeFilterTbl3(m_pFilterTblL, m_nWaveBufSize, m_nSamplingRate, (FilterData *)pDlg->m_oMicCalDataL.aFreq, pDlg->m_oMicCalDataL.nFreqData, 20);
	MakeFilterTbl3(m_pFilterTblR, m_nWaveBufSize, m_nSamplingRate, (FilterData *)pDlg->m_oMicCalDataR.aFreq, pDlg->m_oMicCalDataR.nFreqData, 20);
	double sensL = pow(10.0, -pDlg->m_oMicCalDataL.fInputSens / 20);
	double sensR = pow(10.0, -pDlg->m_oMicCalDataR.fInputSens / 20);
	m_pFilterTblL[0] = 0;
	m_pFilterTblR[0] = 0;
	double t = (g_oSetData.Fre.nMicCalID == -1) ? m_nWaveBufSize * 0.0525 : m_nWaveBufSize * 0.052;
	for (int i = 1; i < m_nWaveBufSize; i++) {
		m_pFilterTblL[i] = sensL / (m_pFilterTblL[i] * t);
		m_pFilterTblR[i] = sensR / (m_pFilterTblR[i] * t);
	}
}

void CFreNoise::AddDataHold(COLORREF colorLeft, COLORREF colorRight)
{
	SDataHold oDataHold;
	memset(&oDataHold, 0, sizeof(oDataHold));

	oDataHold.colorLeft = colorLeft;
	oDataHold.colorRight = colorRight;
	oDataHold.nFreqCount = m_nFreqPoint;

	if (m_oLeftData.GetBufSize() != 0) {
		oDataHold.pLeftData = new double[m_oLeftData.GetBufSize()];
		memcpy(oDataHold.pLeftData, m_oLeftData.GetBuf(), m_oLeftData.GetBufSize() * sizeof(double));
	}

	if (m_oRightData.GetBufSize() != 0) {
		oDataHold.pRightData = new double[m_oRightData.GetBufSize()];
		memcpy(oDataHold.pRightData, m_oRightData.GetBuf(), m_oRightData.GetBufSize() * sizeof(double));
	}

	if (m_pFreq != NULL) {
		oDataHold.pFreq = new double[m_nFreqPoint];
		memcpy(oDataHold.pFreq, m_pFreq, m_nFreqPoint * sizeof(double));
	}

	m_oDataHoldList.AddTail(oDataHold);

	m_bValidData = FALSE;
	Redraw();
}

void CFreNoise::DelDataHold(BOOL bRedraw)
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

BOOL CFreNoise::CheckDataHold()
{
	return !m_oDataHoldList.IsEmpty();
}
