#!/bin/bash
#V.01 updated: add a test to exit if there is no result & purge old archives
#V.02 updated: no more var to define here all is in /var/www/html/cucmlog/cucminc/varinc.php
#V.03 updated: New dir CDRBACKUP to store original raw CDRS from CUCM
################ christian@comdif.com ########################
## RETENTION TIME FOR OLD RAW CDRS FILES (day)
purgetime="+2"

logpath=${PWD}
archivepath="$logpath/ARCHIVE"
backpath="$logpath/CDRBACKUP"
current=$(dirname "$(pwd)")
dbuser=$( cat $current/cucminc/varinc.php|grep dbuser|awk -F "\"" '{print $2}')
dbpass=$( cat $current/cucminc/varinc.php|grep dbpwd|awk -F "\"" '{print $2}')
mydb=$( cat $current/cucminc/varinc.php|grep dbname|awk -F "\"" '{print $2}')
mytbl=$( cat $current/cucminc/varinc.php|grep bashtble|awk -F "\"" '{print $2}')

	### PURGE OLD FILES
echo "Purge old files from $backpath/ older than $purgetime days"
find $backpath/ -type f -ctime $purgetime -delete

	### GO to logpath and check if there is some CDRS received from the CUCM
cd $logpath
files=`ls $logpath|grep cdr`
[[ -z "$files" ]] && { echo "nothing to do" ; exit 1; }

	### OK there are CDRS we start by deleting two uninteresting lines and creating the ARCHIVE/cdr_file file with them
for fse in $files
	do
	cp $logpath/$fse $backpath/
	sed 1,2d "$fse" >> ARCHIVE/cdr_file
	done

	### Format the cdr_files and move it to a new file named in ARCHIVE/ named  cdr_clean the format must be like 'x','y','z' no comma on line start and line end
tr -d '"' < $archivepath/cdr_file >> $archivepath/cdr_clean
sed -i s/","/"','"/g $archivepath/cdr_clean
sed -i s/^/"'"/g $archivepath/cdr_clean
sed -i s/$/"'"/g $archivepath/cdr_clean

	### Now we can delete the previous ARCHIVE/cdr_file
rm -f $archivepath/cdr_file

	## Go to the ARCHIVE path and populate the Database using the cdr_clean file
cd $archivepath

	## build the insert colum value
/usr/bin/mysql -u $dbuser -p$dbpass -e "select COLUMN_NAME from information_schema.columns where table_schema = '$mydb' and table_name = '$mytbl';" >> colum
sed 1d colum >> clean_colum
rm colum
cat clean_colum | tr -s '\n' ','| awk '{ print substr( $0, 1, length($0)-1 ) }' >> colum
rm clean_colum

	## put it in a var
tbcol=`cat colum`

	## Populate the Database
while IFS= read -r line; do
/usr/bin/mysql -u $dbuser -p$dbpass -e "INSERT INTO $mydb.$mytbl ($tbcol) VALUES ($line);"
done < cdr_clean
## Clean all
rm -f cdr_clean
rm -f colum
cd ..
rm -f cdr*
rm -f cmr*
