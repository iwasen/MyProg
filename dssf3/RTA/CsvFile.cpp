#include "stdafx.h"
#include "csvfile.h"

CCsvFile::CCsvFile()
{
	m_sSeparator = ",";
}

void CCsvFile::SetData(LPCTSTR pData)
{
	m_aCsvData.Add(pData);
}

void CCsvFile::SetData(double fData, LPCTSTR pFormat)
{
	CString sData;
	sData.Format(pFormat, fData);
	m_aCsvData.Add(sData);
}

void CCsvFile::SetData(int nData, LPCTSTR pFormat)
{
	CString sData;
	sData.Format(pFormat, nData);
	m_aCsvData.Add(sData);
}

void CCsvFile::Output()
{
	int nSize = (int)m_aCsvData.GetSize();
	for (int i = 0; i < nSize; i++) {
		if (i != 0)
			WriteString(m_sSeparator);
		WriteString(m_aCsvData[i]);
	}
	WriteString("\n");

	m_aCsvData.RemoveAll();
}
