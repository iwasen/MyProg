#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		GraphData.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バイナリデータグラフ用のデータクラスの定義
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "DataFile.h"

struct SDispRange {
	double fMin;
	double fMax;
	double fSpan;
	double fBegin;
	double fDisp;
	double fZoom;

	SDispRange() {
		fMin = 0;
		fMax = 0;
		fSpan = 0;
		fBegin = 0;
		fDisp = 0;
		fZoom = 0;
	}
};		

class CGraphData : public CDataFile
{
public:
	CGraphData();
	~CGraphData();

	BOOL ReadDataFile(int nBeginTime, int nEndTime, int nGraphKind, BOOL bProgressBar);
	void AllocFilterBuf(int nGraphKind);
	void CalcZeroPoint();
	void ZeroOffsetlevelRange();
	CString GetDataTypeText();

	SDispRange m_stTimeRange;
	SDispRange m_stLevelRangeX;
	SDispRange m_stLevelRangeY;
	SDispRange m_stLevelRangeZ;
	double m_fTimeCursor;
	double m_fSelectBegin;
	double m_fSelectEnd;
	double m_fMinData;
	double m_fMaxData;
	double m_fTimeDispEnd;
	double *m_pFilterDataX;
	double *m_pFilterDataY;
	double *m_pFilterDataZ;
	double *m_pFilterDataD;
	double *m_pCurrentDataX;
	double *m_pCurrentDataY;
	double *m_pCurrentDataZ;
	double *m_pCurrentDataD;
	BOOL m_bDispX;
	BOOL m_bDispY;
	BOOL m_bDispZ;
	BOOL m_bDispD;
	double m_fAverageX;
	double m_fAverageY;
	double m_fAverageZ;
	double *m_pAverageX;
	double *m_pAverageY;
	double *m_pAverageZ;

private:
	void FreeFilterBuf();
};
