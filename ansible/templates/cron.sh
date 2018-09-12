#!/bin/bash
# Creates a tarball containing a full snapshot of the data in the site
#
# @copyright Copyright 2011-2018 City of Bloomington, Indiana
# @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
APPLICATION_NAME="directory"
APPLICATION_HOME="{{ directory_install_path }}"
BACKUP_DIR="{{ directory_backup_path }}"
SITE_HOME="{{ directory_site_home }}"
CRON_LOG="/var/log/cron/${APPLICATION_NAME}"
MYSQL_CREDENTIALS="/etc/cron.daily/backup.d/${APPLICATION_NAME}.cnf"
MYSQL_DBNAME="{{ directory_db.default.name }}"

#----------------------------------------------------------
# Nightly cron scripts
#----------------------------------------------------------
# Set this is your SITE_HOME is not in APPLICATION_HOME
export SITE_HOME=$SITE_HOME
# Set this if your install lives behind a reverse proxy
#export HTTP_X_FORWARDED_HOST=some.serer.gov
#php $APPLICATION_HOME/scripts/updateCardValues.php &> $CRON_LOG

#----------------------------------------------------------
# Backups
#----------------------------------------------------------
# How many days worth of tarballs to keep around
num_days_to_keep=5

now=`date +%s`
today=`date +%F`

# Dump the database
mysqldump --defaults-extra-file=$MYSQL_CREDENTIALS $MYSQL_DBNAME > $SITE_HOME/$MYSQL_DBNAME.sql
cd $SITE_HOME/..
tar czf $today.tar.gz $(basename $SITE_HOME)
mv $today.tar.gz $BACKUP_DIR

# Purge any backup tarballs that are too old
cd $BACKUP_DIR
for file in `ls`
do
	atime=`stat -c %Y $file`
	if [ $(( $now - $atime >= $num_days_to_keep*24*60*60 )) = 1 ]
	then
		rm $file
	fi
done
