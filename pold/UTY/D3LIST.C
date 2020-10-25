/************************************************************
 *	FILE DESCRIPTION
 *		XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
 *	FILE	:	XXXXXXXX
 *		:	XXXXXXXX
 *	REMARKS	:	1988-04-26	X.XXXXXX
 *
 ************************************************************/
#include <stdio.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <dirent.h>
#include <errno.h>
#include <string.h>
#include <memory.h>
#include "la_ws/include/d3libc.h"
#include "la_ws/include/d2lerr.h"
#include "d3item.h"

extern	DIR	*opendir();
extern	struct	dirent	*readdir();
extern	char	*d3allc(), *strrchr();
extern	char	*regcmp(), *regex();

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	char	dir[128], buf[128], reg_ex[128];
static	struct	stat	stat_buf;
static	char	*name_ex;
static	D_CELL	*t_ptr_0, *t_ptr;
static	int	num;

d3list(path_name, m_no, cell_p, cell_n, dir_name)
int	m_no, *cell_n;
char	*path_name;
D_CELL	**cell_p;
char	*dir_name;
{
	DIR	*fp;
	struct	dirent	*entp;
	int	rtn, buf_len, size, list90();
	char	ws[128];
	
	rtn = 0;
	fp = NULL;
	name_ex = NULL;

	if(strcmp(path_name, "") == 0)
		strcpy(buf, ".");
	else {
		strcpy(buf, path_name);
		buf_len = strlen(buf);
		if (buf_len > 1 && buf[buf_len-1] == '/')
			buf[buf_len-1] = '\0';
	}

	rtn = list10(buf);
	if (rtn != 0)
		return(rtn);

	if ((fp = opendir(dir)) == NULL)
		return(E_POLD_NEXE);

	for (size = 0; readdir(fp) != 0; size++)
		;
	rewinddir(fp);

	num = 0;
	t_ptr_0 = (D_CELL *) d3allc(m_no, sizeof(D_CELL) * size);
	if (t_ptr_0 == NULL) {
		rtn = E_M;
		goto err_ret;
	}
	*cell_p = t_ptr_0; t_ptr = t_ptr_0;

	d3cvre(reg_ex, ws, 0);
	if ((name_ex = regcmp(ws, 0)) == NULL) {
		rtn = E_M;
		goto err_ret;
	}

	while(1) {
		entp = readdir(fp);
		if (entp == NULL) break;

		rtn = list20(entp->d_name, m_no);
		if (rtn != 0)
			goto err_ret;
	}

	*cell_n = num;
	if (num > 0)
		qsort((char *)t_ptr_0, num, sizeof(D_CELL), list90);

	strcpy(dir_name, dir);

err_ret:
	if (fp != NULL)
		closedir(fp);
	if (name_ex != NULL)
		free(name_ex);
	return(rtn);
}

/*******************************************************/
/* 	list10                                         */
/*******************************************************/
static	list10(buf)
char	*buf;
{
	char	*p;
	int	rtn;

	rtn = stat(buf, &stat_buf);

	if (rtn == 0 && (stat_buf.st_mode & S_IFMT) == S_IFDIR) {
		/* all files under the specified directory */
		strcpy(dir, buf);
		strcpy(reg_ex, "*");
	} else if ( (rtn == 0 && ((stat_buf.st_mode & S_IFMT) != S_IFDIR)) ||
			(rtn == -1 && errno == ENOENT) ) {
		/* partial files under the specified directory */
		if ((p = strrchr(buf, '/')) != NULL) {
			if (p == buf)
				strcpy(dir, "/");
			else {
				*p = '\0'; strcpy(dir, buf); *p = '/';
			}
			strcpy(reg_ex, ++p);
		} else {	/* current directory */
			strcpy(dir, ".");
			strcpy(reg_ex, buf);
		}
	} else
		return(E_POLD_NEXE);

	return(0);
}

/*******************************************************/
/* 	list20                                         */
/*******************************************************/
static	list20(name, m_no)
char	*name;
int	m_no;
{
	int	rtn;
	char	*match, *d_ptr;

	if (*name == '.')
		return(0);
/*
	strcpy(buf, dir);
	if (strcmp(dir, "/") != 0) strcat(buf, "/");
	strcat(buf, name);

	if ((rtn = stat(buf, &stat_buf)) != 0)
		return(E_POLD_NEXE);
	if ((stat_buf.st_mode & S_IFMT) != S_IFDIR) {/# file? #/
*/
		match = regex(name_ex, name);
		if (match != 0)  {
			if ((d_ptr = d3allc(m_no, strlen(name) + 1)) == NULL)
				return(E_M);
			strcpy(d_ptr, name);
			t_ptr->ptr = d_ptr;
			t_ptr->result = t_ptr->flag = 0;
			t_ptr++;
			num++;
		}
/*
	}
*/

	return(0);
}

/***********************************************/
/*      Sort compare ( char type )	       */
/***********************************************/
static list90(t_ptr1, t_ptr2) 
D_CELL	*t_ptr1, *t_ptr2;
{
	return(strcmp(t_ptr1->ptr, t_ptr2->ptr));
}
