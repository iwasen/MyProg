#include "StdAfx.h"
#include "GdiPlusUtil.h"
#include "General.h"
#include <locale.h> 
#include <initguid.h>
#include <shlwapi.h>
//#include "..\\gdiplus1_1\\include\\gdiplus1_1.h"
#define GDIPVER 0x0110
#include <gdiplus.h>

using namespace Gdiplus;

static CSize ResizeJpeg(class Bitmap &oImageSrc, CString &sSrcPath, CString &sDstPath, int nMaxSize, BOOL bExif, CTime &oTime);
static int GetEncoderClsid(const WCHAR* format, CLSID* pClsid);

CSize CGdiPlusUtil::SaveJpeg(CString &sSrcPath, CString &sFolderName, CString &sFileName, CTime &oTime, CString &sSaveFileName)
{
	::setlocale(LC_ALL, "");

	// ����
	CString sDateTime;
	sDateTime.Format("%04d%02d%02d-%02d%02d%02d", oTime.GetYear(), oTime.GetMonth(), oTime.GetDay(), oTime.GetHour(), oTime.GetMinute(), oTime.GetSecond());

	// ���t�@�C���t�H���_��
	CString sSaveDirName, sSavePathName;
	sSaveDirName = CGeneral::GetOriginalPhotoPath(oTime, sFolderName, NULL);

	// �t�@�C�����d���`�F�b�N
	sSaveFileName.Format("%s.jpg", sDateTime);
	int nCount = 1;
	while (true) {
		sSavePathName.Format("%s\\%s", sSaveDirName, sSaveFileName);
		if (!::PathFileExists(sSavePathName))
			break;

		sSaveFileName.Format("%s_%d.jpg", sDateTime, nCount++);
	}

	// ���t�@�C���ۑ�
	CGeneral::CreateDirectoryAll(sSaveDirName);
	::CopyFile(sSrcPath, sSavePathName, FALSE);

	// �ǂݍ��݃t�@�C����
	WCHAR wSrcPath[_MAX_PATH];
	size_t nConvertedChars;
	::mbstowcs_s(&nConvertedChars, wSrcPath, _MAX_PATH, sSrcPath, sSrcPath.GetLength() + 1); 

	// ���t�@�C����ǂݍ���
	Bitmap oImageSrc(wSrcPath);

	// �������݃t�@�C���p�X
	CString sDstFolder = CGeneral::GetSmallPhotoPath(oTime, sFolderName, NULL);
	CString sDstPath = CGeneral::GetSmallPhotoPath(oTime, sFolderName, sSaveFileName);

	// �������݃t�H���_�쐬
	CGeneral::CreateDirectoryAll(sDstFolder);

	// �k���摜�ۑ�
	CSize sizeJpeg = ResizeJpeg(oImageSrc, sSrcPath, sDstPath, 675, TRUE, oTime);

	return sizeJpeg;
}

CSize ResizeJpeg(Bitmap &oImageSrc, CString &sSrcPath, CString &sDstPath, int nMaxSize, BOOL bExif, CTime &oTime)
{
	// �������݃p�X��
	WCHAR wDstPath[_MAX_PATH];
	size_t nConvertedChars;
	::mbstowcs_s(&nConvertedChars, wDstPath, _MAX_PATH, sDstPath, sDstPath.GetLength() + 1);

	// �T�C�Y�̃`�F�b�N
	int nSrcWidth = oImageSrc.GetWidth();
	int nSrcHeight = oImageSrc.GetHeight();
	int nDstWidth, nDstHeight;
	if (nSrcWidth > nMaxSize || nSrcHeight > nMaxSize) {
		// �������݃T�C�Y���v�Z
		if (nSrcWidth < nSrcHeight) {
			nDstWidth = nMaxSize;
			nDstHeight = nSrcHeight * nMaxSize / nSrcWidth;
		} else {
			nDstWidth = nSrcWidth * nMaxSize / nSrcHeight;
			nDstHeight = nMaxSize;
		}

		// �������݃r�b�g�}�b�v
		Bitmap oImageDst(nDstWidth, nDstHeight, PixelFormat24bppRGB);
		Graphics oGraphicDst(&oImageDst);

		// �k������
		oGraphicDst.SetInterpolationMode(InterpolationModeHighQualityBicubic);
		oGraphicDst.DrawImage(&oImageSrc, 0, 0, nDstWidth, nDstHeight);

		// �֊s����
		Sharpen oEffect;
		SharpenParams oSharpenParams;
		oSharpenParams.amount = 75;
		oSharpenParams.radius = 1;
		oEffect.SetParameters(&oSharpenParams);
		CRect rect(0, 0, oImageDst.GetWidth(), oImageDst.GetHeight());
		oImageDst.ApplyEffect(&oEffect, &rect);

		// exif�f�[�^���R�s�[
		if (bExif) {
			UINT nCount = oImageSrc.GetPropertyCount();
			if (nCount != 0) {
				PROPID* propIDs = new PROPID[nCount];

				oImageSrc.GetPropertyIdList(nCount, propIDs);

				for(UINT nCnt = 0; nCnt < nCount; ++nCnt){
					if (propIDs[nCnt] != 0x501b && propIDs[nCnt] != 0x927c) {	// �T���l�C���ƃ��[�J�[�m�[�g������
						UINT size = oImageSrc.GetPropertyItemSize(propIDs[nCnt]);

						PropertyItem* pi = new PropertyItem[size];
						oImageSrc.GetPropertyItem(propIDs[nCnt], size, pi);

						oImageDst.SetPropertyItem(pi);

						delete[] pi;
					}
				}

				delete[] propIDs;
			}
		}

		// jpeg�t�@�C������������
		CLSID encoderClsid;
		::GetEncoderClsid(L"image/jpeg", &encoderClsid);
		EncoderParameters encoderParameters;
		encoderParameters.Count = 1;
		encoderParameters.Parameter[0].Guid = EncoderQuality;
		encoderParameters.Parameter[0].Type = EncoderParameterValueTypeLong;
		encoderParameters.Parameter[0].NumberOfValues = 1;
		long quality = 92;
		encoderParameters.Parameter[0].Value = &quality;
		oImageDst.Save(wDstPath, &encoderClsid, &encoderParameters);
	} else {
		::CopyFile(sSrcPath, sDstPath, FALSE);

		nDstWidth = nSrcWidth;
		nDstHeight = nSrcHeight;
	}

	CFileStatus fsDst;
	CFile::GetStatus(sDstPath, fsDst);
	fsDst.m_ctime = oTime;
	fsDst.m_mtime = oTime;
	CFile::SetStatus(sDstPath, fsDst);

	return CSize(nDstWidth, nDstHeight);
}

int GetEncoderClsid(const WCHAR* format, CLSID* pClsid)
{
	 UINT  num = 0;
	 UINT  size = 0;
	 ImageCodecInfo* pImageCodecInfo;

	 ::GetImageEncodersSize(&num, &size);
	 if (size == 0)
		  return -1;

	 pImageCodecInfo = (ImageCodecInfo *)new char[size];
	 if (pImageCodecInfo == NULL)
		  return -1;

	 ::GetImageEncoders(num, size, pImageCodecInfo);
	 for(UINT n=0; n<num; ++n) {
		  if (::wcscmp(pImageCodecInfo[n].MimeType, format) == 0) {
			   *pClsid = pImageCodecInfo[n].Clsid;
			   delete pImageCodecInfo;
			   return n;
		  }
	 }
	 delete pImageCodecInfo;
	 return -1;
}
