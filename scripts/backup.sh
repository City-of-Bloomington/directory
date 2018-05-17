#!/bin/bash
# Creates a tarball containing a full snapshot of the data in the site
#
# @copyright Copyright 2011-2018 City of Bloomington, Indiana
# @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
APPLICATION_NAME="directory"
MYSQL_CREDENTIALS="/etc/cron.daily/backup.d/${APPLICATION_NAME}.cnf"
BACKUP_DIR="/srv/backups/${APPLICATION_NAME}"
APPLICATION_HOME="/srv/sites/${APPLICATION_NAME}"
SITE_HOME="/srv/data/${APPLICATION_NAME}"

MYSQL_DBNAME="${APPLICATION_NAME}"

# Update the timestamp for zabbix monitoring
touch "/var/log/cron/${APPLICATION_NAME}"

#----------------------------------------------------------
# Backup
# Creates a tarball containing a full snapshot of the data in the site
#----------------------------------------------------------
# How many days worth of tarballs to keep around
num_days_to_keep=5

now=`date +%s`
today=`date +%F`

if [ ! -d "${BACKUP_DIR}" ]
	then mkdir "${BACKUP_DIR}"
fi

# Dump the database
cd "${SITE_HOME}"
mysqldump --defaults-extra-file="${MYSQL_CREDENTIALS}" $MYSQL_DBNAME > $MYSQL_DBNAME.sql

# Tarball all the data
cd ..
data=`basename "${SITE_HOME}"`
tar czf $today.tar.gz "${data}"
mv $today.tar.gz "${BACKUP_DIR}"

# Purge any backup tarballs that are too old
cd "${BACKUP_DIR}"
for file in `ls`
do
	atime=`stat -c %Y $file`
	if [ $(( $now - $atime >= $num_days_to_keep*24*60*60 )) = 1 ]
	then
		rm $file
	fi
done
