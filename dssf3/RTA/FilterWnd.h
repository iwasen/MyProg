#pragma once

#include "FreqWnd.h"
#include "DigitalFilter.h"

// CFilterWnd

class CFilterWnd : public CFreqWnd
{
public:
	CFilterWnd();
	virtual ~CFilterWnd();

	void DispGraph(const CDigitalFilter &cDigitalFilter, int minFreq, int maxFreq, int minLevel, int maxLevel);

protected:
	DECLARE_MESSAGE_MAP()
};


