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

	// 日時
	CString sDateTime;
	sDateTime.Format("%04d%02d%02d-%02d%02d%02d", oTime.GetYear(), oTime.GetMonth(), oTime.GetDay(), oTime.GetHour(), oTime.GetMinute(), oTime.GetSecond());

	// 元ファイルフォルダ名
	CString sSaveDirName, sSavePathName;
	sSaveDirName = CGeneral::GetOriginalPhotoPath(oTime, sFolderName, NULL);

	// ファイル名重複チェック
	sSaveFileName.Format("%s.jpg", sDateTime);
	int nCount = 1;
	while (true) {
		sSavePathName.Format("%s\\%s", sSaveDirName, sSaveFileName);
		if (!::PathFileExists(sSavePathName))
			break;

		sSaveFileName.Format("%s_%d.jpg", sDateTime, nCount++);
	}

	// 元ファイル保存
	CGeneral::CreateDirectoryAll(sSaveDirName);
	::CopyFile(sSrcPath, sSavePathName, FALSE);

	// 読み込みファイル名
	WCHAR wSrcPath[_MAX_PATH];
	size_t nConvertedChars;
	::mbstowcs_s(&nConvertedChars, wSrcPath, _MAX_PATH, sSrcPath, sSrcPath.GetLength() + 1); 

	// 元ファイルを読み込み
	Bitmap oImageSrc(wSrcPath);

	// 書き込みファイルパス
	CString sDstFolder = CGeneral::GetSmallPhotoPath(oTime, sFolderName, NULL);
	CString sDstPath = CGeneral::GetSmallPhotoPath(oTime, sFolderName, sSaveFileName);

	// 書き込みフォルダ作成
	CGeneral::CreateDirectoryAll(sDstFolder);

	// 縮小画像保存
	CSize sizeJpeg = ResizeJpeg(oImageSrc, sSrcPath, sDstPath, 675, TRUE, oTime);

	return sizeJpeg;
}

CSize ResizeJpeg(Bitmap &oImageSrc, CString &sSrcPath, CString &sDstPath, int nMaxSize, BOOL bExif, CTime &oTime)
{
	// 書き込みパス名
	WCHAR wDstPath[_MAX_PATH];
	size_t nConvertedChars;
	::mbstowcs_s(&nConvertedChars, wDstPath, _MAX_PATH, sDstPath, sDstPath.GetLength() + 1);

	// サイズのチェック
	int nSrcWidth = oImageSrc.GetWidth();
	int nSrcHeight = oImageSrc.GetHeight();
	int nDstWidth, nDstHeight;
	if (nSrcWidth > nMaxSize || nSrcHeight > nMaxSize) {
		// 書き込みサイズを計算
		if (nSrcWidth < nSrcHeight) {
			nDstWidth = nMaxSize;
			nDstHeight = nSrcHeight * nMaxSize / nSrcWidth;
		} else {
			nDstWidth = nSrcWidth * nMaxSize / nSrcHeight;
			nDstHeight = nMaxSize;
		}

		// 書き込みビットマップ
		Bitmap oImageDst(nDstWidth, nDstHeight, PixelFormat24bppRGB);
		Graphics oGraphicDst(&oImageDst);

		// 縮小処理
		oGraphicDst.SetInterpolationMode(InterpolationModeHighQualityBicubic);
		oGraphicDst.DrawImage(&oImageSrc, 0, 0, nDstWidth, nDstHeight);

		// 輪郭強調
		Sharpen oEffect;
		SharpenParams oSharpenParams;
		oSharpenParams.amount = 75;
		oSharpenParams.radius = 1;
		oEffect.SetParameters(&oSharpenParams);
		CRect rect(0, 0, oImageDst.GetWidth(), oImageDst.GetHeight());
		oImageDst.ApplyEffect(&oEffect, &rect);

		// exifデータをコピー
		if (bExif) {
			UINT nCount = oImageSrc.GetPropertyCount();
			if (nCount != 0) {
				PROPID* propIDs = new PROPID[nCount];

				oImageSrc.GetPropertyIdList(nCount, propIDs);

				for(UINT nCnt = 0; nCnt < nCount; ++nCnt){
					if (propIDs[nCnt] != 0x501b && propIDs[nCnt] != 0x927c) {	// サムネイルとメーカーノートを除く
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

		// jpegファイルを書き込み
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
