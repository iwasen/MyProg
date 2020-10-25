#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		FftOctaveDraw.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		1/3オクターブ解析グラフの描画クラスの定義
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "BaseDraw.h"

class CFftOctaveDraw : public CBaseDraw
{
public:
	CFftOctaveDraw(CDC *pDC = NULL, int nPenWidth = 1);

	void DrawGraph(CDC &dc, CRect rectView, const double *pDataX, const double *pDataY, const double *pDataZ, const double *pDataG, const double *pLimit1, const double *pLimit2, int nData, const double *pOctaveBandFreq, double fFreqMin, double fFreqMax, BOOL bFreqLog, double fLevelMin, double fLevelMax, BOOL bLevelLog);

public:
	CPen m_penGraphDataX;
	CPen m_penGraphDataY;
	CPen m_penGraphDataZ;
	CPen m_penGraphDataG;

protected:
	void SetScaleRect(CDC &dc);
	void DrawScale(CDC &dc, double fFreqMin, double fFreqMax, BOOL bFreqLog, double fLevelMin, double fLevelMax, BOOL bLevelLog);
	void DrawData(CDC &dc, const double *pData, int nData, const double *pOctaveBandFreq, double fFreqMin, double fFreqMax, BOOL bFreqLog, double fLevelMin, double fLevelMax, BOOL bLevelLog, CPen &penGraphLine, BOOL bStepLine);

protected:
	CPen m_penScale;
	CPen m_penGray;
	CPen m_penLightGray;
	CPen m_penGraphLimit;
	CFont m_fontGraph, m_fontGraph2, m_fontGraph3;
};
