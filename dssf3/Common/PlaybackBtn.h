#pragma once

#include "pcscl.h"
#include "mmdb.h"
#include "WaveOut.h"

class CPlaybackBtn : public CButton, public IWaveNotify
{
public:
	CPlaybackBtn();

	void Play(HWAVEDATA hWaveData, DWORD nWaveDevice = WAVE_MAPPER, double fOffsetTime = 0);
	void Play(const double *pLeftData, const double *pRightData, int nData, int nRate, DWORD nWaveDevice = WAVE_MAPPER);
	void Stop();

protected:
	int ModeHwave(LPWAVENOTIFY pWaveNotify);
	int ModeBuf(LPWAVENOTIFY pWaveNotify);
	double GetMaxValue();

	CWaveOut m_oWaveOut;
	HWAVEDATA m_hWaveData;
	int m_nOffset;
	int m_nMode;
	const double *m_pLeftData;
	const double *m_pRightData;
	int m_nData;
	double m_fMaxValue;

	DECLARE_MESSAGE_MAP()
	int OnWaveNotify(int nCode, LPWAVENOTIFY pWaveNotify);
	afx_msg void OnDestroy();
};
