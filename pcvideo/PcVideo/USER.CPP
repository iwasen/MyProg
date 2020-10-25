/************************************************************************
 *
 *			‚o‚b|‚u‚h‚c‚d‚n
 *
 *		–¼Ì		: ƒ†[ƒUŠÇ—ˆ—
 *		ƒtƒ@ƒCƒ‹–¼	: user.cpp
 *		ì¬ŽÒ		: s.aizawa
 *
 ************************************************************************/

#include "stdafx.h"
#include "resource.h"
#include "pcvcomm.h"

static int nConxxxtCounter;

static	void	SetListBox();
static void InsertUser(PUSER pUser, PSEND pSend);
static void InsertSend(PSEND pSend, PUSER pUser);
static void ReorderSend(PSEND pSend);
static void ReorderUser(PUSER pUser);

PUSER	AddUser(PSOCK pSockSend, PCONxxxT pConxxxt)
{
	PUSER	pUser;
	PUSER	pUserTmp;
	PSEND	pSend;

	if ((pUser = new USER) == NULL)
		return NULL;

	memset(pUser, 0, sizeof(USER));
	pUser->id = pConxxxt->header.id;
	lstrcpy(pUser->userName, pConxxxt->userName);
	pUser->modeFlag = pConxxxt->modeFlag;
	pUser->channelNo = pConxxxt->channelNo;
	pUser->nConxxxtCounter = nConxxxtCounter++;

	for (pUserTmp = pTopUser; pUserTmp != NULL; pUserTmp = pUserTmp->pChain) {
		if (pUserTmp->id == pUser->id)
			break;
	}
	if (pUserTmp == NULL)
		pUser->bMaster = TRUE;

	if (iniData.settings.bReceiveVideo && (pUser->modeFlag & MODE_SENDVIDEO)) {
		if ((pUser->pVideo = CreateVideo(pUser)) == NULL) {
			delete pUser;
			return NULL;
		}
	}

	for (pSend = pTopSend; pSend != NULL; pSend = pSend->pChain) {
		if (memcmp(&pSend->sock, pSockSend, sizeof(SOCK)) == 0)
			break;
	}

	if (pSend == NULL) {
		if ((pSend = new SEND) == NULL) {
			if (pUser->pVideo != NULL)
				DeleteVideo(pUser->pVideo);
			delete pUser;
			return NULL;
		}
		memset(pSend, 0, sizeof(SEND));
		pSend->sock = *pSockSend;

		InsertSend(pSend, pUser);
	}

	pUser->pSend = pSend;

	InsertUser(pUser, pSend);

	SetListBox();

	return pUser;
}

static	void	SetListBox()
{
	PUSER	pUser, pUserSel, pUserLast;
	PSEND	pSend;
	int	index;

	if (!IsWindow(pMainListWnd->m_hWnd))
		return;

	pMainListWnd->SetRedraw(FALSE);

	if ((index = pMainListWnd->GetCurSel()) != LB_ERR) {
		pUserSel = (PUSER)pMainListWnd->GetItemData(index);
	} else
		pUserSel = NULL;

	pMainListWnd->ResetContent();

	index = 0;
	for (pSend = pTopSend; pSend != NULL; pSend = pSend->pChain) {
		pUserLast = NULL;
		for (pUser = pSend->pUser; pUser != NULL; pUser = pUser->pSendUser) {
			if (strncmp(pUser->userName, "//", 2) == 0)
					continue;

			if (pUser == pSend->pUser)
				pUser->indent = 0;
			else {
				pUser->indent = 1;
				pUserLast = pUser;
			}

			pMainListWnd->InsertString(index, (LPCSTR)pUser);

			if (pUser == pUserSel) {
				pMainListWnd->SetCurSel(index);
			}

			pUser->listIndex = index++;
		}

		if (pUserLast != NULL)
			pUserLast->indent = 2;
	}

	pMainListWnd->SetRedraw(TRUE);
}

PUSER	SearchUser(DWORD id, PSOCK pSockSend)
{
	PUSER	pUser;

	for (pUser = pTopUser; pUser != NULL; pUser = pUser->pChain) {
		if (pUser->id == id && memcmp(&pUser->pSend->sock, pSockSend, sizeof(SOCK)) == 0)
			return pUser;
	}

	return NULL;
}

PUSER	GetUser(int index)
{
	PUSER	pUser;

	for (pUser = pTopUser; pUser != NULL; pUser = pUser->pChain) {
		if (pUser->listIndex == index)
			break;
	}
	return pUser;
}

static void InsertUser(PUSER pUser, PSEND pSend)
{
	PUSER	*ppUser;

	if (pUser->modeFlag & MODE_REFLECT) {
		pUser->pSendUser = pSend->pUser;
		pSend->pUser = pUser;
	} else {
		for (ppUser = &pSend->pUser; *ppUser != NULL; ppUser = &(*ppUser)->pSendUser) {
			if ((*ppUser)->modeFlag & MODE_REFLECT)
				continue;

			switch (iniData.mainWindow.nListOrder) {
			case LIST_ORDER_CONxxxT:
				if (pUser->nConxxxtCounter < (*ppUser)->nConxxxtCounter)
					goto big_break1;
				break;
			case LIST_ORDER_NAME:
				if (stricmp(pUser->userName, (*ppUser)->userName) < 0)
					goto big_break1;
				break;
			case LIST_ORDER_IPADDRESS:
				if (ntohl(pUser->id) < ntohl((*ppUser)->id))
					goto big_break1;
				break;
			}
		}
big_break1:

		pUser->pSendUser = *ppUser;
		*ppUser = pUser;
	}

	for (ppUser = &pTopUser; *ppUser != NULL; ppUser = &(*ppUser)->pChain) {
		switch (iniData.mainWindow.nListOrder) {
		case LIST_ORDER_CONxxxT:
			if (pUser->nConxxxtCounter < (*ppUser)->nConxxxtCounter)
				goto big_break2;
			break;
		case LIST_ORDER_NAME:
			if (stricmp(pUser->userName, (*ppUser)->userName) < 0)
				goto big_break2;
			break;
		case LIST_ORDER_IPADDRESS:
			if (ntohl(pUser->id) < ntohl((*ppUser)->id))
				goto big_break2;
			break;
		}
	}
big_break2:

	pUser->pChain = *ppUser;
	*ppUser = pUser;
}

void	DeleteUser(PUSER pUser)
{
	PVIDEO	pVideo;
	PUSER	*ppUser;
	PUSER	pUserTmp;
	PSEND	pSend, *ppSend;
	PCAPTUREBUF pCaptureBuf;
	int	n;

	if ((pVideo = pUser->pVideo) != NULL)
		DeleteVideo(pVideo);

	if ((pSend = pUser->pSend) != NULL) {
		for (ppUser = &pSend->pUser; *ppUser != NULL; ppUser = &(*ppUser)->pSendUser) {
			if ((*ppUser) == pUser) {
				*ppUser = (*ppUser)->pSendUser;
				break;
			}
		}

		if (pSend->pUser == NULL) {
			for (ppSend = &pTopSend; *ppSend != NULL; ppSend = &(*ppSend)->pChain) {
				if ((*ppSend) == pSend) {
					*ppSend = (*ppSend)->pChain;
					break;
				}
			}

			 delete pSend;
		}
	}

	if ((pCaptureBuf = pUser->pCaptureBuf) != NULL) {
		if (pCaptureBuf->hData != NULL) {
			GlobalUnlock(pCaptureBuf->hData);
			GlobalFree(pCaptureBuf->hData);
		}

		if (pCaptureBuf->hVideoHdr != NULL) {
			GlobalUnlock(pCaptureBuf->hVideoHdr);
			GlobalFree(pCaptureBuf->hVideoHdr);
		}

		delete pCaptureBuf;
	}

//	if (pUser->sock.bTCP)
//		closesocket(pUser->sock.sock);

	for (ppUser = &pTopUser, n = 0; *ppUser != NULL; ppUser = &(*ppUser)->pChain, n++) {
		if ((*ppUser) == pUser) {
			*ppUser = (*ppUser)->pChain;
			break;
		}
	}

	if (pUser->cameraControl != 0)
		CommandCloseCamera(pUser);

	SetListBox();

	if (pUser->bMaster) {
		for (pUserTmp = pTopUser; pUserTmp != NULL; pUserTmp = pUserTmp->pChain) {
			if (pUserTmp->id == pUser->id) {
				pUserTmp->bMaster = TRUE;
				break;
			}
		}
	}

	delete pUser;
}

static void InsertSend(PSEND pSend, PUSER pUser)
{
	PSEND *ppSend;

	for (ppSend = &pTopSend; *ppSend != NULL; ppSend = &(*ppSend)->pChain) {
		switch (iniData.mainWindow.nListOrder) {
		case LIST_ORDER_CONxxxT:
			if (pUser->nConxxxtCounter < (*ppSend)->pUser->nConxxxtCounter)
				goto big_break;
			break;
		case LIST_ORDER_NAME:
			if (stricmp(pUser->userName, (*ppSend)->pUser->userName) < 0)
				goto big_break;
			break;
		case LIST_ORDER_IPADDRESS:
			if (ntohl(pSend->sock.IPAddress) < ntohl((*ppSend)->sock.IPAddress))
				goto big_break;
			break;
		}
	}
big_break:

	pSend->pChain = *ppSend;
	*ppSend = pSend;
}

void	DeleteSend(PSEND pSend)
{
	PUSER	pUser, pUser2;

	pUser = pSend->pUser;
	while (pUser != NULL) {
		pUser2 = pUser->pSendUser;
		DeleteUser(pUser);
		pUser = pUser2;
	}
}

void ReorderList()
{
	PSEND pSend;
	PUSER pUser;

	pSend = pTopSend;
	pTopSend = NULL;
	ReorderSend(pSend);

	pTopUser = NULL;
	for (pSend = pTopSend; pSend != NULL; pSend = pSend->pChain) {
		pUser = pSend->pUser;
		pSend->pUser = NULL;
		ReorderUser(pUser);
	}

	SetListBox();
}

static void ReorderSend(PSEND pSend)
{
	if (pSend != NULL) {
		ReorderSend(pSend->pChain);
		InsertSend(pSend, pSend->pUser);
	}
}

static void ReorderUser(PUSER pUser)
{
	if (pUser != NULL) {
		ReorderUser(pUser->pSendUser);
		InsertUser(pUser, pUser->pSend);
	}
}
