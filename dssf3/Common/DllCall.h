#pragma once

class CDllCall
{
public:
	CDllCall(LPCTSTR pDllFile);
	virtual ~CDllCall();

	BOOL IsLoaded() { return m_hLibModule != NULL; }

protected:
	FARPROC LoadFunction(LPCTSTR pFuncName);

	HMODULE m_hLibModule;
};
