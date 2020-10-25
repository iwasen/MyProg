#pragma once

#include "DigitalFilter.h"

// CWaveEx �R�}���h �^�[�Q�b�g

class CWaveEx : public CObject
{
public:
	CWaveEx();
	virtual ~CWaveEx();

protected:
	int m_nFilterUpdateCounter;
	CDigitalFilter m_cFilterLeft;
	CDigitalFilter m_cFilterRight;

	void FilteringWave(LPWAVENOTIFY pWaveNotify);
};


