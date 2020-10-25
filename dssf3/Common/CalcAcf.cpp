// CalcAcf.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "Common.h"
#include "CalcAcf.h"
#include "filter.h"
#include "fft.h"
#include "regress.h"
#include "comres.h"

CRFFT g_oRFFT;

static AllFactorData *CalcAcfFactor1(HWAVEDATA hWaveData, int nWaveData, const AcfCondition *pAcfCondition,
		double fSamplingRate, int *nFactor, AbortCheckFunc AbortCheck, TaunPhinData **pTaunPhinData);
static AllFactorData *CalcAcfFactor2(HWAVEDATA hWaveData, int nWaveData,
		const AcfCondition *pAcfCondition, const IAcfCondition *pIAcfCondition, const NmsMicCal *pNmsMicCal,
		double fSamplingRate, int *nFactor, AbortCheckFunc AbortCheck, TaunPhinData **pTaunPhinData);

NmsFactorData *CalcNmsFactor(HWAVEDATA hWaveData, const NmsConditionData *pNmsConditionData, double fSamplingRate, int *nFactor, AbortCheckFunc AbortCheck)
{
	NmsFactorData *pNmsFactorData;
	TaunPhinData *pTaunPhinData;

	pNmsFactorData = CalcAcfFactor2(hWaveData, pNmsConditionData->nDataNum,
		&pNmsConditionData->AcfCondition, &pNmsConditionData->IAcfCondition, &pNmsConditionData->NmsMicCal,
		fSamplingRate, nFactor, AbortCheck, &pTaunPhinData);

	if (pTaunPhinData != NULL)
		delete [] pTaunPhinData;

	return pNmsFactorData;
}

AcfFactorData *CalcAcfFactor(HWAVEDATA hWaveData, const AcfConditionData *pAcfConditionData, double fSamplingRate, int *nFactor, AbortCheckFunc AbortCheck, TaunPhinData **pTaunPhinData)
{
	PWAVEDATA pWaveData = (PWAVEDATA)::GlobalLock(hWaveData);
	int nChannels = pWaveData->nChannels;
	::GlobalUnlock(hWaveData);

	if (nChannels == 1) {
		return CalcAcfFactor1(hWaveData, pAcfConditionData->nDataNum, &pAcfConditionData->AcfCondition,
				fSamplingRate, nFactor, AbortCheck, pTaunPhinData);
	} else if (nChannels == 2) {
		return CalcAcfFactor2(hWaveData, pAcfConditionData->nDataNum,
			&pAcfConditionData->AcfCondition, &pAcfConditionData->IAcfCondition, NULL,
			fSamplingRate, nFactor, AbortCheck, pTaunPhinData);
	}

	return NULL;
}

AllFactorData *CalcAcfFactor1(HWAVEDATA hWaveData, int nWaveData, const AcfCondition *pAcfCondition, double fSamplingRate, int *nFactor, AbortCheckFunc AbortCheck, TaunPhinData **pTaunPhinData)
{
	double *pWaveBuf;
	double fStepSize;
	int nMaxDelaySize;
	int nIntegrationSize1;
	int nIntegrationSize2;
	int nFftSize;
	int nStepCount;
	double *pFilterTbl;
	int i;
	AllFactorData *pAllFactorData;
	int nSampleNum;
	double *pFft;

	// Wave �f�[�^�擾
	pWaveBuf = new double[nWaveData];
	nSampleNum = ReadWaveData(hWaveData, pWaveBuf, NULL, nWaveData);

	// �ϕ��f�[�^�T�C�Y�擾
	nMaxDelaySize = (int)(pAcfCondition->fMaxDelayTime * fSamplingRate);
	nIntegrationSize1 = (int)(pAcfCondition->fIntegrationTime * fSamplingRate);
	nIntegrationSize2 = nIntegrationSize1 + nMaxDelaySize;

	// �ϕ����s�񐔎擾
	fStepSize = pAcfCondition->fRunningStep * fSamplingRate;
	nStepCount = (int)((nSampleNum - nIntegrationSize2) / fStepSize);
	if (nStepCount < 0)
		nStepCount = 0;

	// FFT �o�b�t�@�T�C�Y�擾
	for (nFftSize = 1; nFftSize < nIntegrationSize2; nFftSize *= 2)
		;

	// �t�B���^�쐬
	pFilterTbl = new double[nFftSize];
	MakeFilterTbl2(pFilterTbl, nFftSize, fSamplingRate, pAcfCondition->nFreqWeighting, 20);

	// �v�Z���ʁi�S�̃t�@�N�^�j�i�[�G���A�m��
	pAllFactorData = new AllFactorData[nStepCount];
	memset(pAllFactorData, 0, sizeof(AllFactorData) * nStepCount);

	// �v�Z���ʁi��n, ��n�j�i�[�G���A�m��
	*pTaunPhinData = new TaunPhinData[nStepCount];
	memset(*pTaunPhinData, 0, sizeof(TaunPhinData) * nStepCount);

	// �ϕ����s�񐔕��v�Z
	for (i = 0; i < nStepCount; i++) {
		// ���f�`�F�b�N
		if (AbortCheck != NULL) {
			if (AbortCheck(100 * i / nStepCount)) {
				delete [] pAllFactorData;
				pAllFactorData = NULL;

				delete [] *pTaunPhinData;
				*pTaunPhinData = NULL;
				break;
			}
		}

		// ACF�v�Z
		pFft = CalcAcf1(pWaveBuf + (int)(i * fStepSize), nIntegrationSize1, nIntegrationSize2, nFftSize, pFilterTbl);

		// ACF�t�@�N�^�v�Z
		GetAcfFactor(pAcfCondition, &pAllFactorData[i].Acf, &(*pTaunPhinData)[i], fSamplingRate, pFft, nMaxDelaySize);
		delete [] pFft;
	}

	*nFactor = i;

	delete [] pFilterTbl;
	delete [] pWaveBuf;

	return pAllFactorData;
}

AllFactorData *CalcAcfFactor2(HWAVEDATA hWaveData, int nWaveData,
		const AcfCondition *pAcfCondition, const IAcfCondition *pIAcfCondition, const NmsMicCal *pNmsMicCal,
		double fSamplingRate, int *nFactor, AbortCheckFunc AbortCheck, TaunPhinData **pTaunPhinData)
{
	double *pWaveBuf1, *pWaveBuf2;
	double fStepSize;
	int nMaxDelaySize;
	int nIntegrationSize1;
	int nIntegrationSize2;
	int nFftSize;
	double *pIAcfData;
	int nStepCount;
	double *pFilterTbl;
	int i;
	AllFactorData *pAllFactorData;
	int nSampleNum;
	double *pFft;
	int nIAcfData;
	double fSPL;
	int nOffset;

	// Wave �f�[�^�擾
	pWaveBuf1 = new double[nWaveData];
	pWaveBuf2 = new double[nWaveData];
	nSampleNum = ReadWaveData(hWaveData, pWaveBuf1, pWaveBuf2, nWaveData);

	// �ϕ��f�[�^�T�C�Y�擾
	nMaxDelaySize = (int)(pAcfCondition->fMaxDelayTime * fSamplingRate);
	nIntegrationSize1 = (int)(pAcfCondition->fIntegrationTime * fSamplingRate);
	nIntegrationSize2 = nIntegrationSize1 + nMaxDelaySize;

	// �ϕ����s�񐔎擾
	fStepSize = pAcfCondition->fRunningStep * fSamplingRate;
	nStepCount = (int)((nSampleNum - nIntegrationSize2) / fStepSize);
	if (nStepCount < 0)
		nStepCount = 0;

	// FFT �o�b�t�@�T�C�Y�擾
	for (nFftSize = 1; nFftSize < nIntegrationSize2; nFftSize *= 2)
		;

	// �t�B���^�쐬
	pFilterTbl = new double[nFftSize];
	if (pNmsMicCal != NULL) {
		double *pCalibration = new double[nFftSize];
		MakeFilterTbl3(pCalibration, nFftSize, fSamplingRate, (FilterData *)pNmsMicCal->freq, pNmsMicCal->nFreqData, 20);
		pCalibration[0] = 1;
		double sens = pow(10.0, -pNmsMicCal->fInputSens / 20);
		MakeFilterTbl2(pFilterTbl, nFftSize, fSamplingRate, pAcfCondition->nFreqWeighting, 20);
		for (i = 0; i < nFftSize; i++)
			pFilterTbl[i] *= sens / pCalibration[i];
		delete [] pCalibration;
	} else
		MakeFilterTbl2(pFilterTbl, nFftSize, fSamplingRate, pAcfCondition->nFreqWeighting, 20);

	// IACF �e���|�����o�b�t�@�m��
	nIAcfData = (int)(fSamplingRate * 0.002);		// �}1ms
	pIAcfData = new double[nIAcfData];

	// �v�Z���ʊi�[�G���A�m��
	pAllFactorData = new AllFactorData[nStepCount];
	memset(pAllFactorData, 0, sizeof(AllFactorData) * nStepCount);

	// �v�Z���ʁi��n, ��n�j�i�[�G���A�m��
	*pTaunPhinData = new TaunPhinData[nStepCount];
	memset(*pTaunPhinData, 0, sizeof(TaunPhinData) * nStepCount);

	// �ϕ����s�񐔕��v�Z
	for (i = 0; i < nStepCount; i++) {
		// ���f�`�F�b�N
		if (AbortCheck != NULL) {
			if (AbortCheck(100 * i / nStepCount)) {
				delete [] pAllFactorData;
				pAllFactorData = NULL;

				delete [] *pTaunPhinData;
				*pTaunPhinData = NULL;
				break;
			}
		}

		nOffset = (int)(i * fStepSize);

		// ACF�v�Z
		pFft = CalcAcf1(pWaveBuf1 + nOffset, nIntegrationSize1, nIntegrationSize2, nFftSize, pFilterTbl);

		// SPL�v�Z
		fSPL = CalcSPL(pWaveBuf1 + nOffset, pWaveBuf2 + nOffset, nIntegrationSize1, nFftSize, pFilterTbl);

		// ACF�t�@�N�^�v�Z
		GetAcfFactor(pAcfCondition, &pAllFactorData[i].Acf, &(*pTaunPhinData)[i], fSamplingRate, pFft, nMaxDelaySize);
		delete [] pFft;

		// IACF�v�Z
		pFft = CalcIAcf(pWaveBuf1 + nOffset, pWaveBuf2 + nOffset, nIntegrationSize1, nFftSize, pFilterTbl);
		memcpy(pIAcfData, pFft + (nFftSize - nIAcfData / 2), (nIAcfData / 2) * sizeof(double));
		memcpy(pIAcfData + nIAcfData / 2, pFft, (nIAcfData / 2) * sizeof(double));
		delete [] pFft;

		// IACF�t�@�N�^�v�Z
		GetIAcfFactor(pIAcfCondition, &pAllFactorData[i].IAcf, fSamplingRate, pIAcfData, nIAcfData, fSPL);
	}

	*nFactor = i;

	delete [] pFilterTbl;
	delete [] pIAcfData;
	delete [] pWaveBuf1;
	delete [] pWaveBuf2;

	return pAllFactorData;
}

double *CalcAcf1(const double *pWaveBuf, int nDataNum1, int nDataNum2, int nFftSize, const double *pFilterTbl)
{
	double *pAcfBuf1, *pAcfBuf2;
	double xt1, yt1, xt2, yt2;
	double m;
	int i, j;

	// FFT ���[�N�o�b�t�@�m��
	pAcfBuf1 = new double[nFftSize];
	pAcfBuf2 = new double[nFftSize];

	// FFT ���[�N�o�b�t�@�ɔg�`�f�[�^���R�s�[
	memcpy(pAcfBuf2, pWaveBuf, nDataNum1 * sizeof(double));
	memset(pAcfBuf2 + nDataNum1, 0, (nFftSize - nDataNum1) * sizeof(double));
	memcpy(pAcfBuf1, pWaveBuf, nDataNum2 * sizeof(double));
	memset(pAcfBuf1 + nDataNum2, 0, (nFftSize - nDataNum2) * sizeof(double));

	// FFT ���s
	g_oRFFT.fft(nFftSize, pAcfBuf1);
	g_oRFFT.fft(nFftSize, pAcfBuf2);

	// ACF �v�Z
	pAcfBuf1[0] = 0;
	pAcfBuf1[1] = 0;
	for (i = 1; i < nFftSize / 2; i++) {
		j = i * 2;

		m = pFilterTbl[i] / nDataNum1;

		xt1 = pAcfBuf1[j] * m;
		yt1 = pAcfBuf1[j + 1] * m;

		xt2 = pAcfBuf2[j] * m;
		yt2 = pAcfBuf2[j + 1] * m;

		pAcfBuf1[j] = xt1 * xt2 + yt1 * yt2;
		pAcfBuf1[j + 1] = xt2 * yt1 - xt1 * yt2;
	}

	// �tFFT ���s
	g_oRFFT.ifft(nFftSize, pAcfBuf1);

	for (i = 0; i < nFftSize; i++)
		pAcfBuf1[i] *= 4.0 * nDataNum1 / nFftSize;

	delete [] pAcfBuf2;

	return pAcfBuf1;
}

double CalcSPL(const double *pWaveBuf1, const double *pWaveBuf2, int nDataNum, int nFftSize, const double *pFilterTbl)
{
	double *pAcfBuf1, *pAcfBuf2;
	double xt1, yt1, xt2, yt2;
	double m;
	double fSPL;
	int i, j;

	// FFT ���[�N�o�b�t�@�m��
	pAcfBuf1 = new double[nFftSize];
	pAcfBuf2 = new double[nFftSize];

	// FFT ���[�N�o�b�t�@�ɔg�`�f�[�^���R�s�[
	memcpy(pAcfBuf1, pWaveBuf1, nDataNum * sizeof(double));
	memset(pAcfBuf1 + nDataNum, 0, (nFftSize - nDataNum) * sizeof(double));
	memcpy(pAcfBuf2, pWaveBuf2, nDataNum * sizeof(double));
	memset(pAcfBuf2 + nDataNum, 0, (nFftSize - nDataNum) * sizeof(double));

	// FFT ���s
	g_oRFFT.fft(nFftSize, pAcfBuf1);
	g_oRFFT.fft(nFftSize, pAcfBuf2);

	// ACF �v�Z
//	pAcfBuf1[0] = (pAcfBuf1[0] * pAcfBuf1[0] + pAcfBuf2[0] * pAcfBuf2[0]) / 2;
	pAcfBuf1[0] = 0;
	pAcfBuf1[1] = 0;
	fSPL = 0;
	for (i = 1; i < nFftSize / 2; i++) {
		j = i * 2;

		m = pFilterTbl[i] / nDataNum;

		xt1 = pAcfBuf1[j] * m;
		yt1 = pAcfBuf1[j + 1] * m;

		xt2 = pAcfBuf2[j] * m;
		yt2 = pAcfBuf2[j + 1] * m;

		fSPL += xt1 * xt1 + yt1 * yt1;
		fSPL += xt2 * xt2 + yt2 * yt2;
	}

	fSPL = fSPL * nDataNum / nFftSize * 2;

	delete [] pAcfBuf1;
	delete [] pAcfBuf2;

	return fSPL;
}

#define MAX_PEAK_DATA	256

void GetAcfFactor(const AcfCondition *pAcfCondition, AcfFactor *pAcfFactor, TaunPhinData *pTaunPhinData, double fSamplingRate, double *pData, int nData)
{
	int nInterval;
	int nEndTime;
	double fPeakData;
	double *pAcf;
	double peakTime[MAX_PEAK_DATA];
	double peakData[MAX_PEAK_DATA];
	double reg0, reg1;
	double fPhi0;
	double fAcf;
	int i, n;
	int nPeakPos;
	int nIntervalPos;

	// ��(0) �ۑ�
	fPhi0 = pData[0];

	// ���K��
	for (i = 0; i < nData; i++)
		pData[i] /= fPhi0;

	// ��(0)�擾
	pAcfFactor->fPhi0 = /*@*/(float)dB10(fPhi0);

	// ��n ��n �擾
	memset(pTaunPhinData, 0, sizeof(TaunPhinData));
	n = 0;
	for (i = 2; i < nData - 2 && n < 10; i++) {
		nPeakPos = 0;
		if (pAcfCondition->nTau1Peak == 0) {
			if (pData[i] > 0 && pData[i - 2] < pData[i - 1] && pData[i - 1] < pData[i] && pData[i] > pData[i + 1] && pData[i + 1] > pData[i + 2])
				nPeakPos = i;
		} else {
			if (pData[i] > 0 && pData[i - 1] < pData[i] && pData[i] > pData[i + 1])
				nPeakPos = i;
		}
		if (nPeakPos != 0) {
			pTaunPhinData->fTaun[n] = /*@*/(float)(nPeakPos / fSamplingRate);
			pTaunPhinData->fPhin[n] = /*@*/(float)pData[nPeakPos];
			n++;
		}
	}

	// ��1 ��1 �擾
	pAcfFactor->fTau1 = pTaunPhinData->fTaun[0];
	pAcfFactor->fPhi1 = pTaunPhinData->fPhin[0];

	// ��e�擾
	nInterval = (int)(pAcfCondition->fTaueInterval / 1000 * fSamplingRate);
	nEndTime = (int)(min(pAcfCondition->fTaueEndTime / 1000, pAcfCondition->fIntegrationTime) * fSamplingRate);
	if (pAcfCondition->bTaueOrigin)
		nIntervalPos = 0;
	else {
		for (nIntervalPos = 0; nIntervalPos < nData; nIntervalPos++) {
			if (pData[nIntervalPos] < 0)
				break;
		}
	}

	n = 0;
	for ( ; nIntervalPos < nEndTime; nIntervalPos += nInterval) {
		pAcf = pData + nIntervalPos;
		fPeakData = dB10(fabs(pAcf[0]));
		nPeakPos = nIntervalPos;
		for (i = 1; i < nInterval; i++) {
			fAcf = dB10(fabs(pAcf[i]));
			if (fAcf > fPeakData) {
				fPeakData = fAcf;
				nPeakPos = nIntervalPos + i;
			}
		}

		if (n > 1 && fPeakData < pAcfCondition->fTaueEndLevel)
			break;

		if (n >= MAX_PEAK_DATA)
			break;

		if (n == 0 || fPeakData < peakData[n - 1]) {
			peakTime[n] = nPeakPos / fSamplingRate;
			peakData[n] = fPeakData;
			n++;
		}
	}
	Regress2x(peakTime, peakData, n, &reg0, &reg1);
	pAcfFactor->fTauE = /*@*/(float)((-10 - reg0) / reg1);
	pAcfFactor->fTauE0 = /*@*/(float)reg0;
}

double *CalcIAcf(const double *pWaveBuf1, const double *pWaveBuf2, int nDataNum, int nFftSize, const double *pFilterTbl)
{
	double *pAcfBuf1, *pAcfBuf2;
	double xt1, yt1, xt2, yt2;
	double m;
	int i, j;

	// FFT ���[�N�o�b�t�@�m��
	pAcfBuf1 = new double[nFftSize];
	pAcfBuf2 = new double[nFftSize];

	// FFT ���[�N�o�b�t�@�ɔg�`�f�[�^���R�s�[
	memcpy(pAcfBuf1, pWaveBuf1, nDataNum * sizeof(double));
	memset(pAcfBuf1 + nDataNum, 0, (nFftSize - nDataNum) * sizeof(double));
	memcpy(pAcfBuf2, pWaveBuf2, nDataNum * sizeof(double));
	memset(pAcfBuf2 + nDataNum, 0, (nFftSize - nDataNum) * sizeof(double));

	// FFT ���s
	g_oRFFT.fft(nFftSize, pAcfBuf1);
	g_oRFFT.fft(nFftSize, pAcfBuf2);

	// IACF �v�Z
//	pAcfBuf1[0] = (pAcfBuf1[0] * pAcfBuf1[0] + pAcfBuf2[0] * pAcfBuf2[0]) / 2;
	pAcfBuf1[0] = 0;
	pAcfBuf1[1] = 0;
	for (i = 1; i < nFftSize / 2; i++) {
		j = i * 2;

		m = pFilterTbl[i] / nDataNum;

		xt1 = pAcfBuf1[j] * m;
		yt1 = pAcfBuf1[j + 1] * m;

		xt2 = pAcfBuf2[j] * m;
		yt2 = pAcfBuf2[j + 1] * m;

		pAcfBuf1[j] = xt1 * xt2 + yt1 * yt2;
		pAcfBuf1[j + 1] = xt2 * yt1 - xt1 * yt2;
	}

	// �t FFT ���s
	g_oRFFT.ifft(nFftSize, pAcfBuf1);

	for (i = 0; i < nFftSize; i++)
		pAcfBuf1[i] *= 4.0 * nDataNum / nFftSize;

	delete [] pAcfBuf2;

	return pAcfBuf1;
}

void GetIAcfFactor(const IAcfCondition *pIAcfCondition, IAcfFactor *pIAcfFactor, double fSamplingRate, const double *pData, int nData, double fSPL)
{
	double fIACC;
	int i, nIACCPos = 0;
	int wPos1, wPos2;
	double wt1, wt2;
	double wLevel;

	fIACC = -FLT_MAX;
	for (i = 0; i < nData; i++) {
		if (pData[i] > fIACC) {
			fIACC = pData[i];
			nIACCPos = i;
		}
	}

	pIAcfFactor->fSPL = /*@*/(float)dB10(fSPL);

	pIAcfFactor->fIACC = /*@*/(float)(fIACC / fSPL);
	if (pIAcfFactor->fIACC > 1.0)
		pIAcfFactor->fIACC = 1.0;

	pIAcfFactor->fTauIACC = /*@*/(float)((nIACCPos - nData / 2) / fSamplingRate);

	wLevel = (1 - pIAcfCondition->fIACCWLevel) * fIACC;
	wPos1 = 0;
	for (i = nIACCPos; i >= 0; i--) {
		if (pData[i] < wLevel) {
			wPos1 = i;
			break;
		}
	}
	wt1 = (nIACCPos - wPos1 - (wLevel - pData[wPos1]) / (pData[wPos1 + 1] - pData[wPos1])) / fSamplingRate;

	wPos2 = nData;
	for (i = nIACCPos; i < nData; i++) {
		if (pData[i] < wLevel) {
			wPos2 = i;
			break;
		}
	}
	wt2 = (wPos2 - nIACCPos - (wLevel - pData[wPos2]) / (pData[wPos2 - 1] - pData[wPos2])) / fSamplingRate;

	pIAcfFactor->fWIACC = /*@*/(float)(wt1 + wt2);
	pIAcfFactor->fWIACC1 = /*@*/(float)wt1;
}

double GetAdjustPhi0(long folderID, long nPhi0RefData, double fPhi0RefLevel)
{
	if (nPhi0RefData == SPL_ABSOLUTE)
		return 0;

	AcfFactorData *pAcfFactorData;
	CDbAcfFactor dbAcfFactor;
	DbAcfFactorRec dbAcfFactorRec;
	double fMaxPhi0 = 0;
	int i, j;

	if (nPhi0RefData == SPL_MAXREF) {
		CDbAcf dbAcf;
		DbAcfRec dbAcfRec;
		CString str;
		long nRec;

		if (!dbAcf.Open())
			return 0;

		if (!dbAcfFactor.Open())
			return 0;

		str.Format("#%d=%ld", dbAcf.m_nIdxFolderID, folderID);
		dbAcf.DBSelect(str, &nRec);
		for (i = 0; ; i++) {
			if (!dbAcf.ReadRecNext(&dbAcfRec))
				break;

			if (dbAcfFactor.ReadRecID(dbAcfRec.nAcfID, &dbAcfFactorRec, NULL, &pAcfFactorData)) {
				for (j = 0; j < dbAcfFactorRec.nAcfFactor; j++) {
					if (pAcfFactorData[j].Acf.fPhi0 > fMaxPhi0)
						fMaxPhi0 = pAcfFactorData[j].Acf.fPhi0;
				}
				delete [] pAcfFactorData;
			}
		}
		return fPhi0RefLevel - fMaxPhi0;
	} else {
		if (!dbAcfFactor.Open())
			return 0;

		if (!dbAcfFactor.ReadRecID(nPhi0RefData, &dbAcfFactorRec, NULL, &pAcfFactorData))
			return 0;

		for (j = 0; j < dbAcfFactorRec.nAcfFactor; j++) {
			if (pAcfFactorData[j].Acf.fPhi0 > fMaxPhi0)
				fMaxPhi0 = pAcfFactorData[j].Acf.fPhi0;
		}
		delete [] pAcfFactorData;

		return fPhi0RefLevel - fMaxPhi0;
	}
}

long CalcNoiseSource(const NmsFactorData *pNmsFactorData, int nNmsFactorData, NoiseSrcData *pNoiseSrcData, const IdentCondition *pIdentCondition)
{
	CDbNsTmp dbNsTmp;
	DbNsTmpRec dbNsTmpRec;
	NsTmpData nsTmpData;
	AcfFactor acfFactorData;
	IAcfFactor iacfFactorData;
	double fdPhi0;
	double fdTaue;
	double fdTau1;
	double fdPhi1;
	double fdIACC;
	double fdTIACC;
	double fdWIACC;
	double fdTotal;
	double fdMin;
	CString str;
	NsWeightData nsWeightData;
	long nNoiseTmpID = -1;
	int nIdentPos;

	if (!dbNsTmp.Open())
		return nNoiseTmpID;

	if (nNmsFactorData == 0)
		return nNoiseTmpID;

	ReadNsWeightData(&nsWeightData);

	nIdentPos = GetIdentPos(pNmsFactorData, nNmsFactorData, pIdentCondition);
	acfFactorData = pNmsFactorData[nIdentPos].Acf;
	iacfFactorData = pNmsFactorData[nIdentPos].IAcf;

	str.LoadString(IDS_UNKNOWN);
	strcpy_s(pNoiseSrcData->aName, str);

	fdMin = 1000;

	for (;;) {
		if (!dbNsTmp.ReadRecNext(&dbNsTmpRec, &nsTmpData))
			break;

		if ((!nsTmpData.bPhi0LowerCheck || acfFactorData.fPhi0 >= nsTmpData.fPhi0LowerLimit)
				&& (!nsTmpData.bPhi0UpperCheck || acfFactorData.fPhi0 <= nsTmpData.fPhi0UpperLimit)
				&& (!nsTmpData.bTaueLowerCheck || acfFactorData.fTauE >= nsTmpData.fTaueLowerLimit)
				&& (!nsTmpData.bTaueUpperCheck || acfFactorData.fTauE <= nsTmpData.fTaueUpperLimit)
				&& (!nsTmpData.bTau1LowerCheck || acfFactorData.fTau1 >= nsTmpData.fTau1LowerLimit)
				&& (!nsTmpData.bTau1UpperCheck || acfFactorData.fTau1 <= nsTmpData.fTau1UpperLimit)
				&& (!nsTmpData.bPhi1LowerCheck || acfFactorData.fPhi1 >= nsTmpData.fPhi1LowerLimit)
				&& (!nsTmpData.bPhi1UpperCheck || acfFactorData.fPhi1 <= nsTmpData.fPhi1UpperLimit)
				&& (!nsTmpData.bIACCLowerCheck || iacfFactorData.fIACC >= nsTmpData.fIACCLowerLimit)
				&& (!nsTmpData.bIACCUpperCheck || iacfFactorData.fIACC <= nsTmpData.fIACCUpperLimit)
				&& (!nsTmpData.bTIACCLowerCheck || iacfFactorData.fTauIACC >= nsTmpData.fTIACCLowerLimit)
				&& (!nsTmpData.bTIACCUpperCheck || iacfFactorData.fTauIACC <= nsTmpData.fTIACCUpperLimit)
				&& (!nsTmpData.bWIACCLowerCheck || iacfFactorData.fWIACC >= nsTmpData.fWIACCLowerLimit)
				&& (!nsTmpData.bWIACCUpperCheck || iacfFactorData.fWIACC <= nsTmpData.fWIACCUpperLimit)) {
			fdPhi0 = fabs(acfFactorData.fPhi0 - nsTmpData.fPhi0Standard) / 10 * nsWeightData.fPhi0;
			fdTaue = GetIdentFactor(acfFactorData.fTauE, nsTmpData.fTaueStandard, nsWeightData.fTaue);
			fdTau1 = GetIdentFactor(acfFactorData.fTau1, nsTmpData.fTau1Standard, nsWeightData.fTau1);
			fdPhi1 = GetIdentFactor(acfFactorData.fPhi1, nsTmpData.fPhi1Standard, nsWeightData.fPhi1);
			fdIACC = GetIdentFactor(iacfFactorData.fIACC, nsTmpData.fIACCStandard, nsWeightData.fIACC);
			fdTIACC = fabs(iacfFactorData.fTauIACC - nsTmpData.fTIACCStandard) * 1000 * nsWeightData.fTIACC;
			fdWIACC = GetIdentFactor(iacfFactorData.fWIACC, nsTmpData.fWIACCStandard, nsWeightData.fWIACC);
			fdTotal = fdPhi0 + fdTaue + fdTau1 + fdPhi1 + fdIACC + fdTIACC + fdWIACC;
			if (fdTotal < fdMin) {
				fdMin = fdTotal;
				pNoiseSrcData->fdPhi0 = /*@*/(float)fdPhi0;
				pNoiseSrcData->fdTauE = /*@*/(float)fdTaue;
				pNoiseSrcData->fdTau1 = /*@*/(float)fdTau1;
				pNoiseSrcData->fdPhi1 = /*@*/(float)fdPhi1;
				pNoiseSrcData->fdIACC = /*@*/(float)fdIACC;
				pNoiseSrcData->fdTIACC = /*@*/(float)fdTIACC;
				pNoiseSrcData->fdWIACC = /*@*/(float)fdWIACC;
				strcpy_s(pNoiseSrcData->aName, dbNsTmpRec.sName);
				nNoiseTmpID = dbNsTmpRec.nNsTmpID;
			}
		}
	}

	return nNoiseTmpID;
}

double GetIdentFactor(double fData, double fStandard, double fWeight)
{
	if (fData > 0 && fStandard > 0)
		return fabs(log(fData / fStandard)) * fWeight;
	else if (fData == fStandard)
		return 0;
	else
		return 1;
}

int GetIdentPos(const NmsFactorData *pNmsFactorData, int nNmsFactorData, const IdentCondition *pIdentCondition)
{
	double fMaxPhi0;
	double fMinTaue;
	int nDataPos;
	int nMaxSpl;
	int i, j;

	fMaxPhi0 = pNmsFactorData[0].Acf.fPhi0;
	nDataPos = 0;
	for (i = 1; i < nNmsFactorData; i++) {
		if (pNmsFactorData[i].Acf.fPhi0 > fMaxPhi0) {
			fMaxPhi0 = pNmsFactorData[i].Acf.fPhi0;
			nDataPos = i;
		}
	}

	if (pIdentCondition->nIdentMode == IDENT_MODE_MINTAUE) {
		nMaxSpl = nDataPos;
		fMinTaue = pNmsFactorData[nMaxSpl].Acf.fTauE;
		for (i = 1; i <= pIdentCondition->nBeforeStep; i++) {
			j = nMaxSpl - i;
			if (j < 0)
				break;
			if (pNmsFactorData[j].Acf.fTauE < fMinTaue) {
				fMinTaue = pNmsFactorData[j].Acf.fTauE;
				nDataPos = j;
			}
		}
		for (i = 1; i <= pIdentCondition->nAfterStep; i++) {
			j = nMaxSpl + i;
			if (j >= nNmsFactorData)
				break;
			if (pNmsFactorData[j].Acf.fTauE < fMinTaue) {
				fMinTaue = pNmsFactorData[j].Acf.fTauE;
				nDataPos = j;
			}
		}
	}

	return nDataPos;
}
