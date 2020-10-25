#include <windows.h>

int	CMENTRY	CMOpen(char *hostName, int option, LPINT handle)
{
	int	i;
	PCCB	pCCB;
	int	err;

	for (i = 0, pCCB = &CCB; i < MAX_CCB; i++, pCCB++) {
		if (pCCB->status == STATUS_FREE)
			break;
	}
	if (i == MAX_CCB)
		return(ERROR_CM_TOO_MANY_OPEN);

	if (strncmp(hostName, "COM", 3) == 0 ||
				strncmp(hostName, "com", 3) == 0) {
		if ((err = LocalOpen(pCCB, portNo, option)) == 0) {
			pCCB->status = STATUS_LOCAL;
			*handle = i;
		}
	} else {
		if ((err = NetOpen(pCCB, hostName, option)) == 0) {
			pCCB->status = STATUS_NETWORK;
			*handle = i;
		}
	}

	return(err);
}

int	CMENTRY	CMClose(int handle)
{
	PCCB	pCCB;
	int	err;

	if (handle < 0 || handle >= MAX_CCB)
		return(ERROR_CM_INVALID_HANDLE);

	pCCB = &CCB[handle];
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

	return(err);
}

int	CMENTRY	CMControl(int handle, int command)
{
	PCCB	pCCB;
	int	err;

	if (handle < 0 || handle >= MAX_CCB)
		return(ERROR_CM_INVALID_HANDLE);

	pCCB = &CCB[handle];
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

	return(err);
}

int	CMENTRY	CMGetCameraOpt(handle, PCAMERAOPT cameraOpt)
{
	PCCB	pCCB;
	int	err;

	if (handle < 0 || handle >= MAX_CCB)
		return(ERROR_CM_INVALID_HANDLE);

	pCCB = &CCB[handle];
	switch (pCCB->status) {
	case STATUS_LOCAL:
		err = LocalGetCameraOpt(pCCB);
		break;
	case STATUS_NETWORK:
		err = NetGetCameraOpt(pCCB);
		break;
	case STATUS_FREE:
		err = ERROR_CM_INVALID_HANDLE;
		break;
	}

	return(err);
}

int	CMENTRY	CMSetCameraOpt(handle, PCAMERAOPT opt)
{
	PCCB	pCCB;
	int	err;

	if (handle < 0 || handle >= MAX_CCB)
		return(ERROR_CM_INVALID_HANDLE);

	pCCB = &CCB[handle];
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

	return(err);
}

int	CMENTRY	CMGetPreset(handle, PPRESET preset)
{
	PCCB	pCCB;
	int	err;

	if (handle < 0 || handle >= MAX_CCB)
		return(ERROR_CM_INVALID_HANDLE);

	pCCB = &CCB[handle];
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

	return(err);
}

int	CMENTRY	CMSetPreset(handle, PPRESET preset, int presetNo)
{
	PCCB	pCCB;
	int	err;

	if (handle < 0 || handle >= MAX_CCB)
		return(ERROR_CM_INVALID_HANDLE);

	pCCB = &CCB[handle];
	switch (pCCB->status) {
	case STATUS_LOCAL:
		err = LocalSetPreset(pCCB, preset, presetNo);
		break;
	case STATUS_NETWORK:
		err = NetSetPreset(pCCB, preset, presetNo);
		break;
	case STATUS_FREE:
		err = ERROR_CM_INVALID_HANDLE;
		break;
	}

	return(err);
}

int	CMENTRY	CMGetCameraPos(handle, PCAMERAPOS pos)
{
	PCCB	pCCB;
	int	err;

	if (handle < 0 || handle >= MAX_CCB)
		return(ERROR_CM_INVALID_HANDLE);

	pCCB = &CCB[handle];
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

	return(err);
}

int	CMENTRY	CMSetCameraPos(handle, PCAMERAPOS pos)
{
	PCCB	pCCB;
	int	err;

	if (handle < 0 || handle >= MAX_CCB)
		return(ERROR_CM_INVALID_HANDLE);

	pCCB = &CCB[handle];
	switch (pCCB->status) {
	case STATUS_LOCAL:
		err = LocalSetCameraPos(pCCB, pos);
		break;
	case STATUS_NETWORK:
		err = NetSetCameraPos(pCCB, pos);
		break;
	case STATUS_FREE:
		err = ERROR_CM_INVALID_HANDLE;
		break;
	}

	return(err);
}
