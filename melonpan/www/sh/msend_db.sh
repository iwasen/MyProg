#! /bin/csh
cd $MELONPAN_HOME/haishin
/usr/bin/perl msend_db.cgi -d $MELONPAN_DB -a $MELONPAN_ALLSEND_ADDR
