// PictFile.cpp: CPictFile �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "mmlib.h"
#include "PictFile.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//////////////////////////////////////////////////////////////////////
// �\�z/����
//////////////////////////////////////////////////////////////////////

CPictFile::CPictFile()
{
	m_pPict = NULL;
	m_hMem = NULL;
	m_nActual = 0;
	m_nScale = 0;
	m_nHLimit = 0;
	m_nHPixel = 0;
	m_nVLimit = 0;
	m_nVPixel = 0;
}

CPictFile::~CPictFile()
{
	FreeMem();
}

void CPictFile::ReadIniFile()
{
	CWinApp *pWinApp = AfxGetApp();

	m_nActual = pWinApp->GetProfileInt(g_SectionMailAtt, "Actual", 0);
	m_nScale = (int)pWinApp->GetProfileInt(g_SectionMailAtt, "Scale", 50);
	m_nHLimit = pWinApp->GetProfileInt(g_SectionMailAtt, "HLimit", 0);
	m_nHPixel = (int)pWinApp->GetProfileInt(g_SectionMailAtt, "HPixel", 800);
	m_nVLimit = pWinApp->GetProfileInt(g_SectionMailAtt, "VLimit", 0);
	m_nVPixel = (int)pWinApp->GetProfileInt(g_SectionMailAtt, "VPixel", 800);
}

void CPictFile::FreeMem()
{
	if (m_pPict != NULL) {
		delete [] m_pPict;
		m_pPict = NULL;
	}

	if (m_hMem != NULL) {
		GlobalUnlock(m_hMem);
		GlobalFree(m_hMem);
		m_hMem = NULL;
	}
}

LPCTSTR CPictFile::ReadPict(CItemData *pItemData, int nSupportFile, long *pSize)
{
	SHORT nMmType;

	FreeMem();

	if (!pItemData->m_pPcsDB->ReadBinaryDataEx(pItemData->m_DataRec.dataID, PICT1_FIELD, &m_pPict, pSize))
		return NULL;

	if (*pSize == 0)
		return NULL;

	if (m_nActual == 0)
		nMmType = ConvertMMType1(m_pPict, nSupportFile, pSize);
	else
		nMmType = ConvertMMType2(m_pPict, nSupportFile, pSize);

	if (nMmType != 0) {
		if (m_hMem != NULL)
			return (LPCTSTR)GlobalLock(m_hMem);
		else
			return m_pPict;
	} else
		return NULL;
}

SHORT CPictFile::ConvertMMType1(LPCTSTR pPict, int nSupportFile, long *pSize)
{
	SHORT nMmType;
	HDIB hDIB;

	switch (*(SHORT *)pPict) {
	case MMTYPE_FAX:
		if (nSupportFile & PICTFILE_FAX)
			nMmType = MMTYPE_FAX;
		else if (nSupportFile & PICTFILE_PNG)
			nMmType = MMTYPE_PNG;
		else if (nSupportFile & PICTFILE_GIF)
			nMmType = MMTYPE_GIF;
		else if (nSupportFile & PICTFILE_BMP)
			nMmType = MMTYPE_BMP;
		else if (nSupportFile & PICTFILE_JPG)
			nMmType = MMTYPE_JPG;
		else
			nMmType = 0;
		break;
	case MMTYPE_GIF:
		if (nSupportFile & PICTFILE_GIF)
			nMmType = MMTYPE_GIF;
		else if (nSupportFile & PICTFILE_PNG)
			nMmType = MMTYPE_PNG;
		else if (nSupportFile & PICTFILE_BMP)
			nMmType = MMTYPE_BMP;
		else if (nSupportFile & PICTFILE_JPG)
			nMmType = MMTYPE_JPG;
		else
			nMmType = 0;
		break;
	case MMTYPE_JPG:
		if (nSupportFile & PICTFILE_JPG)
			nMmType = MMTYPE_JPG;
		else if (nSupportFile & PICTFILE_BMP)
			nMmType = MMTYPE_BMP;
		else
			nMmType = 0;
		break;
	case MMTYPE_BMP:
		if (nSupportFile & PICTFILE_BMP)
			nMmType = MMTYPE_BMP;
		else if (nSupportFile & PICTFILE_JPG)
			nMmType = MMTYPE_JPG;
		else
			nMmType = 0;
		break;
	case MMTYPE_PNG:
		if (nSupportFile & PICTFILE_PNG)
			nMmType = MMTYPE_PNG;
		else if (nSupportFile & PICTFILE_GIF)
			nMmType = MMTYPE_GIF;
		else if (nSupportFile & PICTFILE_BMP)
			nMmType = MMTYPE_BMP;
		else if (nSupportFile & PICTFILE_JPG)
			nMmType = MMTYPE_JPG;
		else
			nMmType = 0;
		break;
	}

	if (nMmType != 0) {
		if (*(SHORT *)pPict != nMmType) {
			MMPictureReadMem(&hDIB, pPict, *pSize);
			MMPictureWriteMem(hDIB, &m_hMem, nMmType, pSize);
			MMPictureFree(hDIB);
		}
	}

	return nMmType;
}

SHORT CPictFile::ConvertMMType2(LPCTSTR pPict, int nSupportFile, long *pSize)
{
	HDIB hDIB, hDIB2;
	SHORT nWidth, nHeight, nColor;
	SHORT nWidth2, nHeight2;
	SHORT nMmType;
	long nSize1, nSize2;

	MMPictureReadMem(&hDIB, pPict, *pSize);

	MMPictureGetInfo(hDIB, &nWidth, &nHeight, &nColor);
	nWidth2 = (SHORT)(nWidth * m_nScale / 100);
	nHeight2 = (SHORT)(nHeight * m_nScale / 100);

	if (m_nHLimit && nWidth2 > m_nHPixel) {
		nHeight2 = (SHORT)(nHeight2 * m_nHPixel / nWidth2);
		nWidth2 = (SHORT)m_nHPixel;
	}
	if (m_nVLimit && nHeight2 > m_nVPixel) {
		nWidth2 = (SHORT)(nWidth2 * m_nVPixel / nHeight2);
		nHeight2 = (SHORT)m_nVPixel;
	}

	if (nWidth != nWidth2 || nHeight != nHeight2) {
		MMPictureCopy(hDIB, &hDIB2, nWidth2, nHeight2);
		MMPictureFree(hDIB);
		hDIB = hDIB2;
	}

	switch (nColor) {
	case 1:
		if ((nSupportFile & PICTFILE_FAX) && (nSupportFile & PICTFILE_PNG)) {
			MMPictureWriteNull(hDIB, MMTYPE_FAX, &nSize1);
			MMPictureWriteNull(hDIB, MMTYPE_PNG, &nSize2);
			if (nSize1 < nSize2)
				nMmType = MMTYPE_FAX;
			else
				nMmType = MMTYPE_PNG;
		} else if (nSupportFile & PICTFILE_FAX)
			nMmType = MMTYPE_FAX;
		else if (nSupportFile & PICTFILE_PNG)
			nMmType = MMTYPE_PNG;
		else if (nSupportFile & PICTFILE_GIF)
			nMmType = MMTYPE_GIF;
		else if (nSupportFile & PICTFILE_BMP)
			nMmType = MMTYPE_BMP;
		else if (nSupportFile & PICTFILE_JPG)
			nMmType = MMTYPE_JPG;
		else
			nMmType = 0;
		break;
	case 8:
		if ((nSupportFile & PICTFILE_PNG) && (nSupportFile & PICTFILE_JPG)) {
			MMPictureWriteNull(hDIB, MMTYPE_PNG, &nSize1);
			MMPictureWriteNull(hDIB, MMTYPE_JPG, &nSize2);
			if (nSize1 < nSize2)
				nMmType = MMTYPE_PNG;
			else
				nMmType = MMTYPE_JPG;
		} else if (nSupportFile & PICTFILE_PNG)
			nMmType = MMTYPE_PNG;
		else if (nSupportFile & PICTFILE_GIF)
			nMmType = MMTYPE_GIF;
		else if (nSupportFile & PICTFILE_BMP)
			nMmType = MMTYPE_BMP;
		else if (nSupportFile & PICTFILE_JPG)
			nMmType = MMTYPE_JPG;
		else
			nMmType = 0;
		break;
	case 24:
		if (nSupportFile & PICTFILE_JPG)
			nMmType = MMTYPE_JPG;
		else if (nSupportFile & PICTFILE_BMP)
			nMmType = MMTYPE_BMP;
		else
			nMmType = 0;
		break;
	}

	if (nMmType != 0)
		MMPictureWriteMem(hDIB, &m_hMem, nMmType, pSize);

	MMPictureFree(hDIB);

	return nMmType;
}

