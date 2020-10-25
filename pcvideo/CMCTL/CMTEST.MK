cmtest.exe : cmtest.obj cmctl.lib
	link /NOD /NOE cmtest,cmtest,,libw+llibcew+cmctl+winsock,cmtest

cmtest.obj : cmtest.c
	cl -c -AL -Gsw -J -W3 cmtest.c
