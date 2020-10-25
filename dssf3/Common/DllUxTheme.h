#pragma once

#include "dllcall.h"
#include <uxtheme.h>

class CDllUxTheme : public CDllCall
{
public:
	CDllUxTheme();

	HRESULT CloseThemeData(HTHEME hTheme);
	HRESULT DrawThemeBackground(HTHEME hTheme, HDC hdc, int iPartId, int iStateId, const RECT *pRect, const RECT *pClipRect);
	HRESULT EnableThemeDialogTexture(HWND hWnd, DWORD dwFlags);
	BOOL IsThemeActive();
	HTHEME OpenThemeData(HWND hWnd, LPCWSTR pszClassList);
};
