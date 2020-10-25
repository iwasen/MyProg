/* ----- %Z%%M% %I% %E% %U% ----- */
/*--------------------------------------------------------------------------

		FILE DESCRIPTION

			FILE NAME: FUNCTN.H
			ROUTINE	 : HEADER FILE
			REMARKS	 : 		87.10.21	N.MATSUO
			REMARKS	 : 		87.12.3		N.MATSUO

---------------------------------------------------------------------------*/

/*...............................................................
WINDOW		*w1	:	subpad for error messege on pad :
...............................................................*/
	extern		WINDOW	*w1;

/*...............................................................
int		d3mnmk();	menu make routine		:
----------------------------------------------------------------:
int		mmode   : 0 = not input  ,1 = input		:
char		*title	: title	for window			:
char 		*item[]	: item for menu				:
int		itemn	: number of item			:
char		*func[]	: cription of func_key			:
char		*imes	: prompt message			:
char		*pno	: contnt of input			:
chtype		*fno	: puted function number			:
...............................................................*/

	extern		int	d3mnmk();

/*...............................................................
chtype		d3chin();	input	routine			:
-----------------------------------------------------------------
int		x	: pointer for subpad(w3)		:
int		y	: pointer for subpad			:
int		ncols	: 					:
unsigned char	*str	:					:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
output	:	chtype		last	: puted last key	:
...............................................................*/
	extern		chtype	d3chin();

/*...............................................................
int		d3msed()	; error message display		:
----------------------------------------------------------------:
WINDOW		*win	: pointer for pad			:
int 		msno	: message no. (error code)		:
...............................................................*/

	extern		int	d3msed();


/*...............................................................
int		d3pdmk();	pad make routine		:
----------------------------------------------------------------:
no input :
----------------------------------------------------------------:
output :	int	ps	:0 = OK ; 1 = NG		:
................................................................*/
	extern		int	d3pdmk();
	extern		WINDOW	*pad;
	extern		int	l_num;

/*...............................................................
int 		d3fcmk(); function make routine			:
----------------------------------------------------------------:
char		*str[]	: cription of func_key			:
................................................................*/

	extern		int	d3fcmk();

/*...............................................................
int		d3pned();					:
-----------------------------------------------------------------
int		pno	: 					:
................................................................*/

	extern		int	d3pned();


/*...............................................................
int		d3cnmk();	menu make routine		:
----------------------------------------------------------------:
int		mmode   : 0 = not input  ,1 = input		:
char		*title	: title	for window			:
char		*item	: item for menu				:
int		itemn	: number of item			:
char		*func[]	: cription of func_key			:
char		*imes	: prompt message			:
char		*pno	: contnt of input			:
chtype		*fno	: puted function number			:
...............................................................*/
	extern		int	d3cmmk();

/*...............................................................
int		d3keka(); 	keka displsay routine		:
----------------------------------------------------------------:
FILE		*jfile	:					:
int		*ptbl	: table for error message		:
................................................................*/

	extern		int	d3keka();

/*...............................................................
chtype		wledit();	input strings			:
----------------------------------------------------------------:
WINDOW		*win	: pad for input				:
unsigned char	*str	: input strings				:
...............................................................*/

	extern		chtype	wledit();

/*...............................................................
int		d3drrd();	dir name read			:
----------------------------------------------------------------:
char		dirbuf	: dir name baffer			:
................................................................*/

extern		int	d3drrd();

/*...............................................................
int		d3edds(file_name);  edit file display		:
-----------------------------------------------------------------
char		*file_name:	edit file name			:
................................................................*/

extern		int	d3edds();

/*...............................................................
TTY_NAM		;terminale name get				:
...............................................................*/

#define		TTY_NM		(char *)((char *)ttyname(1) + 5)

#define		ON	1
#define		OFF	0
