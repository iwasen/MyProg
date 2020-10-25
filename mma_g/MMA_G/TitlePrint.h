#pragma once

class CTitlePrint
{
public:
	CTitlePrint();
	~CTitlePrint();
	void StartTitle(CDC& dc, CRect rectView);
	void EndTitle();
	void PrintMid(int nLine, LPCSTR pTitle);
	void PrintLeft(int nLine, LPCSTR pTitle);
	void PrintRight(int nLine, LPCSTR pTitle);
	void PrintDir(int x, int nLine, LPCSTR pTitle);

protected:
	CFont *m_pFontOrg;
	CDC *m_dc;
	CRect m_Rect;
};
