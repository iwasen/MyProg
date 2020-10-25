#pragma once

#include "GpsData.h"
#include "atlimage.h"

// CGoogleMap

class CGoogleMap : public CStatic
{
	DECLARE_DYNAMIC(CGoogleMap)

public:
	void DispMap(double fLat, double fLon, CArrayGpsData *pArrayGpsData);
	void Clear();

protected:
	CImage m_oImage;

	DECLARE_MESSAGE_MAP()
	virtual void DrawItem(LPDRAWITEMSTRUCT lpDrawItemStruct);
};


