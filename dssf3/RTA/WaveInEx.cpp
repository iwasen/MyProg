#include "stdafx.h"
#include "RTA.h"
#include "WaveInEx.h"
#include "AsioManager.h"
#include "WasapiManager.h"

CObList CWaveInEx::s_WaveInList;
IWaveNotify *CWaveInEx::s_pMonitorWnd;
IWaveNotify *CWaveInEx::s_pRecWnd;
int CWaveInEx::s_nRecMode;

CWaveInEx::CWaveInEx()
{
	m_pWaveNotify = NULL;
	m_pMonitorWnd = NULL;
	m_pRecWnd = NULL;

	s_WaveInList.AddHead(this);
}

CWaveInEx::~CWaveInEx()
{
	POSITION pos;

	if ((pos = s_WaveInList.Find(this)) != NULL)
		s_WaveInList.RemoveAt(pos);
}

void CWaveInEx::Init()
{
}

void CWaveInEx::Exit()
{
}

CWaveInEx *CWaveInEx::GetInstance()
{
	return new CWaveInEx;
}

void CWaveInEx::ReleaseInstance()
{
	delete this;
}

BOOL CWaveInEx::Open(
				INT_PTR nWaveDevice,
				IWaveNotify *pWaveNotify,
				int nChannels,
				int nSamplesPerSec,
				int nSamplesPerBuffer,
				int nBufferNum,
				BOOL bErrMsg)
{
	if (s_pRecWnd != NULL) {
		if (FindRec() == NULL)
			SetRecMode2(s_pRecWnd, s_nRecMode);
	}

	m_nWaveDevice = nWaveDevice;
	m_pWaveNotify = pWaveNotify;
	m_nChannels = nChannels;
	m_nSamplesPerSec = nSamplesPerSec;
	m_bErrMsg = bErrMsg;

	if (FindMonitor() == NULL)
		m_pMonitorWnd = s_pMonitorWnd;

	if (!OpenDevice(nWaveDevice, pWaveNotify, nChannels, nSamplesPerSec, nSamplesPerBuffer, nBufferNum, bErrMsg))
		return FALSE;

	return TRUE;
}

BOOL CWaveInEx::OpenDevice(
				INT_PTR nWaveDevice,
				IWaveNotify * /*pWaveNotify*/,
				int nChannels,
				int nSamplesPerSec,
				int nSamplesPerBuffer,
				int nBufferNum,
				BOOL bErrMsg)
{
	if (m_pRecWnd != NULL && m_nRecMode == 1) {
		if (nWaveDevice >= 0) {
			// WAVE
			m_cWaveOut.Close();

			if (!m_cWaveOut.Open(WAVE_MAPPER, this, nChannels, nSamplesPerSec, nSamplesPerBuffer, nBufferNum, TRUE, FALSE)) {
				m_cWaveOut.Close();
				return FALSE;
			}
		} else if (nWaveDevice <= -100) {
			// WASAPI
			if (!g_cWasapiManager.OpenOut(-(nWaveDevice + 100), this, nChannels, nSamplesPerSec, nSamplesPerBuffer, bErrMsg)) {
				SetRecMode2(NULL, s_nRecMode);

				return FALSE;
			}
		} else {
			// ASIO
			if (!g_cAsioManager.OpenOut(-(nWaveDevice + 1), this, nChannels, nSamplesPerSec, nSamplesPerBuffer, bErrMsg)) {
				SetRecMode2(NULL, s_nRecMode);

				return FALSE;
			}
		}
	} else {
		if (nWaveDevice >= 0) {
			// WAVE
			if (!m_oWaveIn.Open(nWaveDevice, this, nChannels, nSamplesPerSec, nSamplesPerBuffer, nBufferNum, FALSE)) {
				CloseAll();

				if (!m_oWaveIn.Open(nWaveDevice, this, nChannels, nSamplesPerSec, nSamplesPerBuffer, nBufferNum, bErrMsg)) {
					SetRecMode2(NULL, s_nRecMode);

					return FALSE;
				}
			}
		} else if (nWaveDevice <= -100) {
			// WASAPI
			if (!g_cWasapiManager.OpenIn(-(nWaveDevice + 100), this, nChannels, nSamplesPerSec, nSamplesPerBuffer, bErrMsg)) {
				SetRecMode2(NULL, s_nRecMode);

				return FALSE;
			}
		} else {
			// ASIO
			if (!g_cAsioManager.OpenIn(-(nWaveDevice + 1), this, nChannels, nSamplesPerSec, nSamplesPerBuffer, bErrMsg)) {
				SetRecMode2(NULL, s_nRecMode);

				return FALSE;
			}
		}
	}

	return TRUE;
}

void CWaveInEx::Close()
{
	if (m_nWaveDevice >= 0) {
		m_oWaveIn.Close();

		if (m_cWaveOut.m_hWave != NULL)
			m_cWaveOut.Close();
	} else if (m_nWaveDevice <= -100) {
		g_cWasapiManager.CloseIn();

		if (m_pRecWnd != NULL && m_nRecMode == 1)
			g_cWasapiManager.CloseOut();
	} else {
		g_cAsioManager.CloseIn();

		if (m_pRecWnd != NULL && m_nRecMode == 1)
			g_cAsioManager.CloseOut();
	}

	m_oAutoRec.StopRecord();
}

void CWaveInEx::CloseRecMonitor()
{
	SetRecMode2(NULL, s_nRecMode);

	m_pMonitorWnd = NULL;

	CWaveInEx *pWaveIn = FindOpen();
	if (pWaveIn != NULL)
		pWaveIn->m_pMonitorWnd = s_pMonitorWnd;
}

void CWaveInEx::SetMonitor(IWaveNotify *pMonitorWnd)
{
	s_pMonitorWnd = pMonitorWnd;

	if (pMonitorWnd == NULL) {
		CWaveInEx *pWaveIn = FindMonitor();
		if (pWaveIn != NULL)
			pWaveIn->m_pMonitorWnd = NULL;
	}
}

BOOL CWaveInEx::SetRecMode(IWaveNotify *pRecWnd, int nRecMode)
{
	CWaveInEx *pWaveIn;

	s_pRecWnd = pRecWnd;
	s_nRecMode = nRecMode;

	if ((pWaveIn = FindRec()) != NULL)
		return pWaveIn->SetRecMode2(pRecWnd, nRecMode);
	else {
		if ((pWaveIn = FindOpen()) != NULL)
			return pWaveIn->SetRecMode2(pRecWnd, nRecMode);
	}

	return FALSE;
}

BOOL CWaveInEx::SetRecMode2(IWaveNotify *pRecWnd, int nRecMode)
{
	m_pRecWnd = pRecWnd;
	m_nRecMode = nRecMode;

	if (m_nWaveDevice >= 0) {
		if (m_oWaveIn.m_pWnd != NULL && pRecWnd != NULL && nRecMode == 1)
			Close();

		return m_oWaveIn.m_hWave != NULL;
	} else if (m_nWaveDevice <= -100) {
		if (g_cWasapiManager.IsOpenIn() && pRecWnd != NULL && nRecMode == 1)
			Close();

		return g_cWasapiManager.IsOpenIn();
	} else {
		if (g_cAsioManager.m_bInOpen && pRecWnd != NULL && nRecMode == 1)
			Close();

		return g_cAsioManager.m_bInOpen;
	}
}

CWaveInEx *CWaveInEx::GetRecWave()
{
	return FindRec();
}

void CWaveInEx::CloseRecWave()
{
	CWaveInEx *pWaveIn;

	if ((pWaveIn = FindRec()) != NULL)
		pWaveIn->Close();
}

int CWaveInEx::GetFixedSamplingRate()
{
	if (s_pRecWnd != NULL && s_nRecMode == 1)
		return s_pRecWnd->OnWaveNotify(WAVEIN_QUERY_RATE, 0);
	else
		return 0;
}

CWaveInEx *CWaveInEx::FindOpen()
{
	POSITION pos = s_WaveInList.GetHeadPosition();
	while (pos != NULL) {
		CWaveInEx *pWaveIn = (CWaveInEx *)s_WaveInList.GetNext(pos);

		if (pWaveIn->m_nWaveDevice >= 0) {
			if (pWaveIn->m_oWaveIn.m_hWave != NULL)
				return pWaveIn;
		} else if (pWaveIn->m_nWaveDevice <= -100) {
			if (g_cWasapiManager.IsOpenIn())
				return pWaveIn;
		} else {
			if (g_cAsioManager.m_bInOpen)
				return pWaveIn;
		}
	}

	return NULL;
}

CWaveInEx *CWaveInEx::FindMonitor()
{
	POSITION pos = s_WaveInList.GetHeadPosition();
	while (pos != NULL) {
		CWaveInEx *pWaveIn = (CWaveInEx *)s_WaveInList.GetNext(pos);
		if (pWaveIn->m_pMonitorWnd != NULL)
			return pWaveIn;
	}

	return NULL;
}

CWaveInEx *CWaveInEx::FindRec()
{
	POSITION pos = s_WaveInList.GetHeadPosition();
	while (pos != NULL) {
		CWaveInEx *pWaveIn = (CWaveInEx *)s_WaveInList.GetNext(pos);
		if (pWaveIn->m_pRecWnd != NULL)
			return pWaveIn;
	}

	return NULL;
}

void CWaveInEx::CloseAll()
{
	POSITION pos = s_WaveInList.GetHeadPosition();
	while (pos != NULL)
		((CWaveIn *)s_WaveInList.GetNext(pos))->Close();
}

void CWaveInEx::Start()
{
	if (m_nWaveDevice >= 0) {
		if (m_oWaveIn.m_hWave != NULL)
			m_oWaveIn.Start();
		else
			m_cWaveOut.Start();
	} else if (m_nWaveDevice <= -100) {
		if (g_cWasapiManager.IsOpenIn())
			g_cWasapiManager.StartIn();
		else
			g_cWasapiManager.StartOut();
	} else {
		if (g_cAsioManager.m_bInOpen)
			g_cAsioManager.StartIn();
		else
			g_cAsioManager.StartOut();
	}
}

void CWaveInEx::Stop()
{
	if (m_nWaveDevice >= 0)
		return m_oWaveIn.Stop();
	else if (m_nWaveDevice <= -100)
		return g_cWasapiManager.StopIn();
	else
		return g_cAsioManager.StopIn();
}

void CWaveInEx::Reset()
{
	if (m_nWaveDevice >= 0)
		return m_oWaveIn.Reset();
	else if (m_nWaveDevice <= -100)
		return g_cWasapiManager.ResetIn();
	else
		return g_cAsioManager.ResetIn();
}

int CWaveInEx::GetSamplesPerSec()
{
	return m_nSamplesPerSec;
}

int CWaveInEx::GetChannels()
{
	return m_nChannels;
}

int CWaveInEx::GetBitsPerSample()
{
	if (m_pRecWnd != NULL && m_nRecMode == 1)
		return m_pRecWnd->OnWaveNotify(WAVEIN_QUERY_BITS, NULL);
	else {
		if (m_nWaveDevice >= 0)
			return m_oWaveIn.GetBitsPerSample();
		else if (m_nWaveDevice <= -100)
			return g_cWasapiManager.GetBitsPerSampleIn();
		else
			return g_cAsioManager.GetBitsPerSampleIn();
	}
}

int CWaveInEx::OnWaveNotify(int nCode, LPWAVENOTIFY pWaveNotify)
{
	int nRet = 0;

	switch (nCode) {
	case WAVEOUT_OPEN:
		WaveNotify(m_pWaveNotify, WAVEIN_OPEN, pWaveNotify);
		WaveNotify(m_pRecWnd, nCode, pWaveNotify);
		break;
	case WAVEOUT_CLOSE:
		WaveNotify(m_pWaveNotify, WAVEIN_CLOSE, pWaveNotify);
		WaveNotify(m_pRecWnd, nCode, pWaveNotify);
		break;
	case WAVEOUT_DATA:
		if (pWaveNotify->nFlags & WHDR_DONE) {
			nRet = WaveNotify(m_pWaveNotify, WAVEIN_DATA, pWaveNotify);
			if (nRet != 0)
				nRet = WaveNotify(m_pRecWnd, nCode, pWaveNotify);
		} else
			nRet = WaveNotify(m_pRecWnd, nCode, pWaveNotify);
		if (g_oSetData.Flt.nInputOutput == 0)
			FilteringWave(pWaveNotify);
		break;
	case WAVEOUT_CLOSE_WAIT:
		WaveNotify(m_pWaveNotify, WAVEIN_DATA, pWaveNotify);
		WaveNotify(m_pRecWnd, nCode, pWaveNotify);
		break;
	case WAVEIN_OPEN:
		WaveNotify(m_pRecWnd, nCode, pWaveNotify);
		WaveNotify(m_pWaveNotify, nCode, pWaveNotify);
		if (pWaveNotify != NULL && g_oSetData2.bAutoRecInput)
			m_oAutoRec.StartRecord(pWaveNotify->nSamplesPerSec, pWaveNotify->nChannels, pWaveNotify->nWindow, FALSE);
		break;
	case WAVEIN_CLOSE:
		WaveNotify(m_pRecWnd, nCode, pWaveNotify);
		WaveNotify(m_pWaveNotify, nCode, pWaveNotify);
		m_oAutoRec.StopRecord();
		break;
	case WAVEIN_DATA:
		if (g_oSetData.Flt.nInputOutput == 0)
			FilteringWave(pWaveNotify);
		WaveNotify(m_pRecWnd, nCode, pWaveNotify);
		nRet = WaveNotify(m_pWaveNotify, nCode, pWaveNotify);
		m_oAutoRec.WriteData(pWaveNotify->pSamplesData, pWaveNotify->nSamplesRecorded);
		break;
	}

	WaveNotify(m_pMonitorWnd, nCode, pWaveNotify);

	if (nCode == WAVEIN_CLOSE || nCode == WAVEOUT_CLOSE)
		CloseRecMonitor();

	return nRet;
}

int CWaveInEx::WaveNotify(IWaveNotify *pWaveNotifyWnd, int nCode, LPWAVENOTIFY pWaveNotify)
{
	int nRet = 0;

	if (pWaveNotifyWnd != NULL)
		nRet = pWaveNotifyWnd->OnWaveNotify(nCode, pWaveNotify);

	return nRet;
}
