/**********************************************************
*      System : SSMSD FAT File System                     *
*      Module : Test sub program                          *
**********************************************************/

#ifndef SF_DEBUG_WINDOWS
#include "vxWorks.h"
#include "taskLib.h"
#endif
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include <errno.h>
#include <sys/stat.h>
#include "sf_api.h"

#define	TRUE	1
#define	FALSE	0
#define	MAX_PATH	256

#ifdef SF_DEBUG_WINDOWS
typedef	int BOOL;
#endif

extern char devName[];
extern	void get_number(char *msg, int *n, char *def);
extern void _PrintDebugMessage(int msglevel, const char *pfmt, ...); /* Output Debug Message */

static	int	test1_cnt;
static	int	dir_cnt = 100;
static	int	file_cnt = 10;
static	int	rec_cnt = 100;

static void test1()
{
	int i;
	FILE *fp;
	char fname[256];

	printf("Test1 : Make Too Many Files in Root Directory\n");

	for (i = 1; ; i++) {
		printf("%d\n", i);
		sprintf(fname, "%s/A%d.DAT", devName, i);
		fp = SF_fopen(fname, "w");
		if (fp == NULL)
			break;
		SF_fclose(fp);
	}
	test1_cnt = i;
}

static void test1_del()
{
	int i, n;
	char fname[256];

	printf("Delete Test1 Files\n");

	for (i = 1; i < test1_cnt; i++) {
		printf("%d\n", i);
		sprintf(fname, "%s/A%d.DAT", devName, i);
		n = SF_remove(fname);
		if (n == -1) {
			printf("ERROR %d\n", i);
		}
	}
}


static void test2()
{
	int i;
	FILE *fp;
	char fname[256];

	printf("Test2 : Make Too Many Files in Sub Directory\n");

	sprintf(fname, "%s/DDD", devName);
	if (SF_mkdir(fname) == -1)
		return;

	for (i = 1; i <= 2000; i++) {
		printf("%d\n", i);
		sprintf(fname, "%s/DDD/%daaaaaaaaaaaaaaaaaaaaa.html", devName, i);
		fp = SF_fopen(fname, "w");
		if (fp == NULL)
			break;
		SF_fclose(fp);
	}
}

static void test2_del()
{
	int i, n;
	char fname[256];

	printf("Delete Test2 Files\n");

	for (i = 1; i <= 2000; i++) {
		printf("%d\n", i);
		sprintf(fname, "%s/DDD/%daaaaaaaaaaaaaaaaaaaaa.html", devName, i);
		n = SF_remove(fname);
		if (n == -1) {
			printf("ERROR %d\n", i);
		}
	}
	sprintf(fname, "%s/DDD", devName);
	if (SF_rmdir(fname) == -1)
		printf("ERROR rmdir()\n");
}

static void test3()
{
	int i, j;
	FILE *fp;
	char fname[256];
	static	char buf[5000];

    printf("Test3 : Read And Write a Large File\n");

	sprintf(fname, "%s/test.dat", devName);
	fp = SF_fopen(fname, "w");
	if (fp == NULL)
		return;

	memset(buf, '*', sizeof(buf));

	for (i = 1; i <= 10000; i++) {
		printf("W %d\n", i);

		if (SF_fwrite(buf, 1, sizeof(buf), fp) != sizeof(buf))
			break;
	}
	SF_fclose(fp);

	printf("Read start\n");
	fp = SF_fopen(fname, "r");
	if (fp == NULL)
		return;

	for (i = 1; i <= 10000; i++) {
		printf("R %d\n", i);

		memset(buf, 0, sizeof(buf));
		if (SF_fread(buf, 1, sizeof(buf), fp) != sizeof(buf))
			break;

		for (j = 0; j < sizeof(buf); j++) {
			if (buf[j] != '*')
				break;
		}
	}
	SF_fclose(fp);
}

#define	TEST4_REC_CNT	20000
static void set_cnt()
{
	printf("------NOW----\n");
	printf("dir_cnt %d\n", dir_cnt);
	printf("file_cnt %d\n", file_cnt);
	printf("rec_cnt %d\n", rec_cnt);
	printf("-------------\n");
	get_number("dir cnt(n def=100) ? ", &dir_cnt, "100");
	get_number("file cnt(n def=10) ? ", &file_cnt, "10");
	get_number("rec cnt (n def=100) ? ", &rec_cnt, "100");
}

static void test4()
{
	int i, j, k;
	FILE *fp;
	static	char dname[256];
	static	char fname[256];
	char *buf;
	
    printf("Test4 : Make Too Many Large Files.\n");
	if ((buf = (char *)malloc(TEST4_REC_CNT)) == NULL) {
		printf("allocate error!");
		return;
	}

	set_cnt();

	memset(buf, '*', TEST4_REC_CNT);

	for (i = 0; i < dir_cnt; i++) {
		sprintf(dname, "%s/subdir%d", devName, i);
		_PrintDebugMessage(6, "***[SF_mkdir]:\n");
		if (SF_mkdir(dname) == -1)
			break;

		for (j = 1; j <= file_cnt; j++) {
			printf("D:%d F:%d\n", i, j);
			sprintf(fname, "%s/test%d.dat", dname, j);
			_PrintDebugMessage(6, "***[SF_fopen]:\n");
			fp = SF_fopen(fname, "w");
			if (fp == NULL)
				break;

			_PrintDebugMessage(6, "***[SF_fwrite]:\n");
			for (k = 1; k <= rec_cnt; k++) {
				if (SF_fwrite(buf, 1, TEST4_REC_CNT, fp) != TEST4_REC_CNT)
					break;
			}
			_PrintDebugMessage(6, "***[SF_fclose]:\n");
			SF_fclose(fp);
			_PrintDebugMessage(6, "***[NEXT]:\n");
		}
	}
	free(buf);
}

static void test5()
{
	int i, j, k, l;
	FILE *fp;
	int loop = 1;
	static	char dname[256];
	static	char fname[256];
	char *buf;

    printf("Test5 : Check Data of the Test4 Files\n");
	if ((buf = (char *)malloc(TEST4_REC_CNT)) == NULL) {
		printf("allocate error!");
		return;
	}

	for (i = 0; i < dir_cnt && loop; i++) {
		sprintf(dname, "%s/subdir%d", devName, i);

		for (j = 1; j <= file_cnt && loop; j++) {
			printf("D:%d F:%d\n", i, j);
			sprintf(fname, "%s/test%d.dat", dname, j);
			fp = SF_fopen(fname, "r");
			if (fp == NULL) {
				loop = 0;
				break;
			}

			for (k = 1; k <= rec_cnt && loop; k++) {
				/* Clear Read Buffer */
				memset(buf, 0, TEST4_REC_CNT);
				if (SF_fread(buf, 1, TEST4_REC_CNT, fp) != TEST4_REC_CNT) {
					loop = 0;
					break;
				}
				for (l = 0; l < TEST4_REC_CNT; l++) {
					if (buf[l] != '*') {
						printf("Data Error\n");
						loop = 0;
						break;
					}
				}
			}

			SF_fclose(fp);
		}
	}
	free(buf);
}

static void test6()
{
	int i, j;
	static	char dname[256];
	static	char fname[256];

    printf("Test6 : Remove Test4 Files All\n");

	for (i = 0; i < dir_cnt; i++) {
		sprintf(dname, "%s/subdir%d", devName, i);
		for (j = 1; j <= file_cnt; j++) {
			printf("D:%d F:%d\n", i, j);
			sprintf(fname, "%s/test%d.dat", dname, j);

			if (SF_remove(fname) == -1)
				break;
		}

		if (SF_rmdir(dname) == -1)
			break;
	}

	SF_sync(devName);
}

static	void	t_test(int dir_cnt, int file_cnt, int rec_cnt, char chr)
{
	int i, j, k;
	FILE *fp;
	char dname[64];
	char fname[64];
	char buf[1024];
	
    printf("Test%c : START\n", chr);

	memset(buf, chr, sizeof(buf));

	for (i = 0; i < dir_cnt; i++) {
		sprintf(dname, "%s/subdir%c%d", devName, chr, i);
		_PrintDebugMessage(4, "***[SF_mkdir Test%c]:\n", chr);
		if (SF_mkdir(dname) == -1)
			break;

		for (j = 1; j <= file_cnt; j++) {
			printf(" Test%c D:%d F:%d\n", chr, i, j);
			sprintf(fname, "%s/test%c%d.dat", dname, chr, j);
			_PrintDebugMessage(4, "***[SF_fopen Test%c]:\n", chr);
			fp = SF_fopen(fname, "w");
			if (fp == NULL)
				break;

			_PrintDebugMessage(4, "***[SF_fwrite Test%c]:\n", chr);
			for (k = 1; k <= rec_cnt; k++) {
				if (SF_fwrite(buf, 1, sizeof(buf), fp) != sizeof(buf))
					break;
			}
			_PrintDebugMessage(4, "***[SF_fclose Test%c]:\n", chr);
			SF_fclose(fp);
			_PrintDebugMessage(4, "***[NEXT Test%c]:\n", chr);
		}
	}
}

/*static	void	t_test_b(int dir_cnt, int file_cnt, int rec_cnt)
{
	int i, j, k;
	FILE *fp;
	char dname[64];
	char fname[64];
	char buf[1024];
	
    printf("TestB : START\n");

	memset(buf, 'B', sizeof(buf));

	for (i = 0; i < dir_cnt; i++) {
		sprintf(dname, "%s/subdirB%d", devName, i);
		_PrintDebugMessage(4, "***[SF_mkdir TestB]:\n");
		if (SF_mkdir(dname) == -1)
			break;

		for (j = 1; j <= file_cnt; j++) {
			printf(" Test B D:%d F:%d\n", i, j);
			sprintf(fname, "%s/testB%d.dat", dname, j);
			_PrintDebugMessage(4, "***[SF_fopen TestB]:\n");
			fp = SF_fopen(fname, "w");
			if (fp == NULL)
				break;

			_PrintDebugMessage(4, "***[SF_fwrite TestB]:\n");
			for (k = 1; k <= rec_cnt; k++) {
				if (SF_fwrite(buf, 1, sizeof(buf), fp) != sizeof(buf))
					break;
			}
			_PrintDebugMessage(4, "***[SF_fclose TestB]:\n");
			SF_fclose(fp);
			_PrintDebugMessage(4, "***[NEXT TestB]:\n");
		}
	}
}*/

static	void	test30()
{
#ifndef SF_DEBUG_WINDOWS
	int tasknum, i;
	char    tname[21];

	set_cnt();
	get_number("number of task(n def=2) ? ", &tasknum, "2");

	for (i = 0; i < tasknum; i++) {
		sprintf(tname, "tTest%c", 'A' + i);
		taskSpawn(tname, 150,  VX_FP_TASK, 200000, (FUNCPTR)t_test,
			dir_cnt,file_cnt,rec_cnt,'A' + i,0,0,0,0,0,0);
	}
#endif
}



void main3()
{
	BOOL loop_flag = TRUE;
	BOOL disp_menu = TRUE;
	int menu_no;

	while (loop_flag) {
		if (disp_menu) {
			printf(" 1.RoorDir      2.Subdir\n");
			printf(" 3.Write/Read   4.AllWrite\n");
			printf(" 5.AllRead      6.AllRemove\n");
			printf(" 7.test1_del    8.test2_del\n");
			printf(" 9.set count\n");
			printf("30.make file pararell\n");
			printf("99.exit\n");

			disp_menu = FALSE;
		}

		menu_no = 0;
		get_number("Test3> ", &menu_no, NULL);
		errno = 0;

		switch (menu_no) {
		case 1:
			test1();
			break;
		case 2:
			test2();
			break;
		case 3:
			test3();
			break;
		case 4:
			test4();
			break;
		case 5:
			test5();
			break;
		case 6:
			test6();
			break;
		case 7:
			test1_del();
			break;
		case 8:
			test2_del();
			break;
		case 9:
			set_cnt();
			break;
		case 30:
			test30();
			break;
		case 99:
			loop_flag = FALSE;
			break;
		default:
			disp_menu = TRUE;
			break;
		}
	}
}
