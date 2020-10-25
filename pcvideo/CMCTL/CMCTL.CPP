#include <windows.h>
#include <mmsystem.h>
#include <string.h>
#include "cmctldll.h"

static	CCB	ccb[MAX_CCB];
static	void	ConvertPos1(LPCAMERAPOS);
static	void	ConvertPos2(LPCAMERAPOS);

extern	"C" int	CMENTRY	CMOpen(LPSTR hostName, LPSTR userName, int option,
								LPINT handle)
{
	int	i;
	PCCB	pCCB;
	int	err;

	for (i = 0, pCCB = ccb; i < MAX_CCB; i++, pCCB++) {
		if (pCCB->status == STATUS_FREE)
			break;
	}
	if (i == MAX_CCB)
		return ERROR_CM_TOO_MANY_OPEN;

	if (hostName == NULL || hostName[0] == '\0' ||
				strncmp(hostName, "COM", 3) == 0 ||
				strncmp(hostName, "com", 3) == 0) {
		if ((err = LocalOpen(pCCB, hostName, option)) == 0) {
			pCCB->status = STATUS_LOCAL;
			*handle = i;
		}
	} else {
		if ((err = NetOpen(pCCB, hostName, userName, option)) == 0) {
			pCCB->status = STATUS_NETWORK;
			*handle = i;
		}
	}

	return err;
}

extern	"C" int	CMENTRY	CMClose(int handle)
{
	PCCB	pCCB;
	int	err;

	if (handle < 0 || handle >= MAX_CCB)
		return ERROR_CM_INVALID_HANDLE;

	pCCB = &ccb[handle];
	switch (pCCB->status) {
	case STATUS_LOCAL:
		err = LocalClose(pCCB);
		break;
	case STATUS_NETWORK:
		err = NetClose(pCCB);
		break;
	case STATUS_FREE:
		err = ERROR_CM_INVALID_HANDLE;
		break;
	}

	pCCB->status = STATUS_FREE;

	return err;
}

extern	"C" int	CMENTRY	CMControl(int handle, int command)
{
	PCCB	pCCB;
	int	err;

	if (handle < 0 || handle >= MAX_CCB)
		return ERROR_CM_INVALID_HANDLE;

	pCCB = &ccb[handle];
	switch (pCCB->status) {
	case STATUS_LOCAL:
		err = LocalControl(pCCB, command);
		break;
	case STATUS_NETWORK:
		err = NetControl(pCCB, command);
		break;
	case STATUS_FREE:
		err = ERROR_CM_INVALID_HANDLE;
		break;
	}

	return err;
}

extern	"C" int	CMENTRY	CMGetCameraOpt(int handle, LPCAMERAOPT cameraOpt)
{
	PCCB	pCCB;
	int	err;

	if (handle < 0 || handle >= MAX_CCB)
		return ERROR_CM_INVALID_HANDLE;

	pCCB = &ccb[handle];
	switch (pCCB->status) {
	case STATUS_LOCAL:
		err = LocalGetCameraOpt(pCCB, cameraOpt);
		break;
	case STATUS_NETWORK:
		err = NetGetCameraOpt(pCCB, cameraOpt);
		break;
	case STATUS_FREE:
		err = ERROR_CM_INVALID_HANDLE;
		break;
	}

	return err;
}

extern	"C" int	CMENTRY	CMSetCameraOpt(int handle, LPCAMERAOPT opt)
{
	PCCB	pCCB;
	int	err;

	if (handle < 0 || handle >= MAX_CCB)
		return ERROR_CM_INVALID_HANDLE;

	pCCB = &ccb[handle];
	switch (pCCB->status) {
	case STATUS_LOCAL:
		err = LocalSetCameraOpt(pCCB, opt);
		break;
	case STATUS_NETWORK:
		err = NetSetCameraOpt(pCCB, opt);
		break;
	case STATUS_FREE:
		err = ERROR_CM_INVALID_HANDLE;
		break;
	}

	return err;
}

extern	"C" int	CMENTRY	CMGetPreset(int handle, LPPRESET preset)
{
	PCCB	pCCB;
	int	i;
	int	err;

	if (handle < 0 || handle >= MAX_CCB)
		return ERROR_CM_INVALID_HANDLE;

	pCCB = &ccb[handle];
	switch (pCCB->status) {
	case STATUS_LOCAL:
		err = LocalGetPreset(pCCB, preset);
		break;
	case STATUS_NETWORK:
		err = NetGetPreset(pCCB, preset);
		break;
	case STATUS_FREE:
		err = ERROR_CM_INVALID_HANDLE;
		break;
	}

	for (i = 0; i < N_PRESET; i++)
		ConvertPos2(&preset[i].pos);

	return err;
}

extern	"C" int	CMENTRY	CMSetPreset(int handle, LPPRESET preset, int presetNo)
{
	PCCB	pCCB;
	PRESET	preset2;
	int	err;

	if (handle < 0 || handle >= MAX_CCB)
		return ERROR_CM_INVALID_HANDLE;

	if (presetNo < 1 || presetNo > MAX_PRESET_NO)
		return ERROR_CM_PRESET_NO;

	preset2 = *preset;
	ConvertPos1(&preset2.pos);

	pCCB = &ccb[handle];
	switch (pCCB->status) {
	case STATUS_LOCAL:
		err = LocalSetPreset(pCCB, &preset2, presetNo - 1);
		break;
	case STATUS_NETWORK:
		err = NetSetPreset(pCCB, &preset2, presetNo - 1);
		break;
	case STATUS_FREE:
		err = ERROR_CM_INVALID_HANDLE;
		break;
	}

	return err;
}

extern	"C" int	CMENTRY	CMMovePreset(int handle, int presetNo)
{
	PCCB	pCCB;
	int	err;

	if (handle < 0 || handle >= MAX_CCB)
		return ERROR_CM_INVALID_HANDLE;

	if (presetNo < 1 || presetNo > MAX_PRESET_NO)
		return ERROR_CM_PRESET_NO;

	pCCB = &ccb[handle];
	switch (pCCB->status) {
	case STATUS_LOCAL:
		err = LocalMovePreset(pCCB, presetNo - 1);
		break;
	case STATUS_NETWORK:
		err = NetMovePreset(pCCB, presetNo - 1);
		break;
	case STATUS_FREE:
		err = ERROR_CM_INVALID_HANDLE;
		break;
	}

	return err;
}

extern	"C" int	CMENTRY	CMGetCameraPos(int handle, LPCAMERAPOS pos)
{
	PCCB	pCCB;
	int	err;

	if (handle < 0 || handle >= MAX_CCB)
		return ERROR_CM_INVALID_HANDLE;

	pCCB = &ccb[handle];
	switch (pCCB->status) {
	case STATUS_LOCAL:
		err = LocalGetCameraPos(pCCB, pos);
		break;
	case STATUS_NETWORK:
		err = NetGetCameraPos(pCCB, pos);
		break;
	case STATUS_FREE:
		err = ERROR_CM_INVALID_HANDLE;
		break;
	}

	if (err == 0)
		ConvertPos2(pos);

	return err;
}

extern	"C" int	CMENTRY	CMSetCameraPos(int handle, LPCAMERAPOS pos)
{
	PCCB	pCCB;
	CAMERAPOS pos2;
	int	err;

	if (handle < 0 || handle >= MAX_CCB)
		return ERROR_CM_INVALID_HANDLE;

	pos2 = *pos;
	ConvertPos1(&pos2);

	pCCB = &ccb[handle];
	switch (pCCB->status) {
	case STATUS_LOCAL:
		err = LocalSetCameraPos(pCCB, &pos2);
		break;
	case STATUS_NETWORK:
		err = NetSetCameraPos(pCCB, &pos2);
		break;
	case STATUS_FREE:
		err = ERROR_CM_INVALID_HANDLE;
		break;
	}

	return err;
}

extern	"C" int	CMENTRY CMLock(int handle, int lockFlag)
{
	PCCB	pCCB;
	int	err;

	if (handle < 0 || handle >= MAX_CCB)
		return ERROR_CM_INVALID_HANDLE;

	pCCB = &ccb[handle];
	switch (pCCB->status) {
	case STATUS_LOCAL:
		err = LocalLock(pCCB, lockFlag);
		break;
	case STATUS_NETWORK:
		err = NetLock(pCCB, lockFlag);
		break;
	case STATUS_FREE:
		err = ERROR_CM_INVALID_HANDLE;
		break;
	}

	return err;
}

extern	"C" int	CMENTRY CMCaptureVideo(int handle, LPCAPTUREMODE captureMode,
								LPSTR buf)
{
	PCCB	pCCB;
	int	err;

	if (handle < 0 || handle >= MAX_CCB)
		return ERROR_CM_INVALID_HANDLE;

	if (captureMode->size < SIZE_MIN || captureMode->size > SIZE_MAX)
		return ERROR_CM_CAPTURE_MODE;

	if (captureMode->color != COLOR_8 &&
			captureMode->color == COLOR_16 &&
			captureMode->color == COLOR_24)
		return ERROR_CM_CAPTURE_MODE;

	pCCB = &ccb[handle];
	switch (pCCB->status) {
	case STATUS_LOCAL:
		err = LocalCaptureVideo(pCCB, captureMode, buf);
		break;
	case STATUS_NETWORK:
		err = NetCaptureVideo(pCCB, captureMode, buf);
		break;
	case STATUS_FREE:
	
		err = ERROR_CM_INVALID_HANDLE;
		break;
	}

	return err;
}

extern	"C" int	CMENTRY CMDraw(HDC hDC, LPCAPTUREMODE captureMode, LPSTR buf)
{
	BITMAPINFO	*bip;
	static	BOOL	bInitPalette;
	static	char	bitmapInfo[sizeof(BITMAPINFOHEADER) +
						sizeof(RGBQUAD) * 256];

	bip = (BITMAPINFO *)bitmapInfo;

	if (!bInitPalette) {
		int	i;

		bip->bmiHeader.biSize = sizeof(BITMAPINFOHEADER);
		bip->bmiHeader.biPlanes = 1;
		bip->bmiHeader.biCompression = BI_RGB;
		for (i = 0; i < 256; i++) {
			bip->bmiColors[i].rgbRed = i;
			bip->bmiColors[i].rgbGreen = i;
			bip->bmiColors[i].rgbBlue = i;
		}

		bInitPalette = TRUE;
	}

	bip->bmiHeader.biWidth = videoSize[captureMode->size].width;
	bip->bmiHeader.biHeight = videoSize[captureMode->size].height;
	bip->bmiHeader.biBitCount = colorTable[captureMode->color];

	StretchDIBits(hDC, 0, 0, (int)bip->bmiHeader.biWidth,
			(int)bip->bmiHeader.biHeight, 0, 0,
			(int)bip->bmiHeader.biWidth,
			(int)bip->bmiHeader.biHeight,
			buf, bip, DIB_RGB_COLORS, SRCCOPY);

	return 0;
}

static	void	ConvertPos1(LPCAMERAPOS pos)
{
	pos->pan = (int)((pos->pan + 500) * 650L / 500);
	pos->tilt = (int)((pos->tilt + 200) * 289L / 200);
}

static	void	ConvertPos2(LPCAMERAPOS pos)
{
	pos->pan = (int)(pos->pan * 500L / 650) - 500;
	pos->tilt = (int)(pos->tilt * 200L / 289) - 200;
}
