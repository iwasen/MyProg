#!/bin/sh
php -q job.php
php -q mail_recv.php
php -q mail_send.php
php -q mail_err.php
