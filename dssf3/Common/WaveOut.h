#pragma once

#include <mmsystem.h>
#pragma warning(disable : 4819)
#include <Mmreg.h>
#include <ks.h>
#include <ksmedia.h>
#include "WaveNotify.h"

class CWaveOut : public CWnd
{
public:
	CWaveOut();
	virtual ~CWaveOut();

	BOOL Open(INT_PTR nWaveDevice, IWaveNotify *pWnd, int nChannels, int nSamplesPerSec, int nSamplesPerBuffer, int nBufferNum, BOOL bErrMsg = TRUE, BOOL bVolumeSet = TRUE);
	void Start();
	void Close();
	void Reset();
	void Pause();
	void Restart();
	void SetVolume(UINT left, UINT right);
	int GetBitsPerSample();

	HWAVEOUT m_hWave;
	IWaveNotify *m_pWnd;
	static BOOL m_b16BitOnly;

protected:
	int NotifyMessage(int nCode, LPWAVEHDR pWaveHdr);
	BOOL AllocWaveBuffer(int nBufferNum, int nBufferSize);
	void FreeWaveBuffer();
	void ConvertDoubleToWave(LPWAVEHDR pWaveHdr, int rc);

	WAVEFORMATEXTENSIBLE m_oWaveFormat;
	LPWAVEHDR m_pWaveHdr;
	int m_nBufCounter;
	DWORD m_dwOrgVolume;
	BOOL m_bCloseWait;
	BOOL m_bVolumeSet;
	double *m_pSamplesBuffer;

	DECLARE_MESSAGE_MAP()
	afx_msg void OnDestroy();
	afx_msg LRESULT OnWaveOutDone(WPARAM wParam, LPARAM lParam);
};
