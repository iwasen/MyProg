#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		FftSpectrogramDraw.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�X�y�N�g���O������̓O���t�̕`��N���X��`
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2007.08.09 S.Aizawa �V�K�쐬
//*****************************************************************************************************

#include "BaseDraw.h"

class CFftSpectrogramDraw : public CBaseDraw
{
public:
	CFftSpectrogramDraw(CDC *pDC = NULL, int nPenWidth = 1);

	void DrawGraph(CDC &dc, CRect rectView, const double *pDataG, int nFreqData, int nTimeData, double fSampleRate, double fTimeMin, double fTimeMax, double fTimeOffset, double fTimeZoom, double fFreqMin, double fFreqMax, BOOL bFreqLog, double fLevelMin, double fLevelMax, BOOL bLevelLog);

protected:
	void SetScaleRect(CDC &dc);
	void DrawScale(CDC &dc, double fTimeMin, double fTimeMax, double fFreqMin, double fFreqMax, BOOL bFreqLog, double fLevelMin, double fLevelMax, BOOL bLevelLog);
	COLORREF GetLevelColor(double fLevel, double fLevelMin, double fLevelMax);
	void DrawData(CDC &dc, const double *pData, int nFreqData, int nTimeData, double fTimeOffset, double fTimeZoom, double fSampleRate, double fFreqMin, double fFreqMax, BOOL bFreqLog, double fLevelMin, double fLevelMax, BOOL bLevelLog);

protected:
	CPen m_penScale;
	CPen m_penGray;
	CPen m_penLightGray;
	CPen m_penMidGray;
	CFont m_fontGraph, m_fontGraph2;
};
