// PictFile.h: CPictFile クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#pragma once

class CPictFile  
{
public:
	CPictFile();
	virtual ~CPictFile();

private:
	LPSTR m_pPict;
	HANDLE m_hMem;
	int m_nActual;
	int m_nScale;
	int m_nHLimit;
	int m_nHPixel;
	int m_nVLimit;
	int m_nVPixel;

public:
	void ReadIniFile();
	LPCTSTR ReadPict(CItemData *pItemData, int nSupportFile, long *pSize);

private:
	void FreeMem();
	SHORT ConvertMMType1(LPCTSTR pPict, int nSupportFile, long *pSize);
	SHORT ConvertMMType2(LPCTSTR pPict, int nSupportFile, long *pSize);
};
