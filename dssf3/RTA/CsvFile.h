#pragma once

class CCsvFile : public CStdioFile
{
public:
	CCsvFile();
	void SetData(LPCTSTR pData);
	void SetData(int nData, LPCTSTR pFormat = "%d");
	void SetData(double fData, LPCTSTR pFormat = "%.5g");
	void Output();

	CString m_sSeparator;

protected:
	CStringArray m_aCsvData;
};
