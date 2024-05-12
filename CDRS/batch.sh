# Variables to change
dbuser="yourdbusername"
dbpass="yourdbpassword"
mydb="calldata"
mytbl="calldetails"
# change for your path
logpath="/var/www/html/CDRS"
archivepath="/var/www/html/CDRS/ARCHIVE"
########################################
cd $logpath
files=`ls $logpath|grep cdr`
for fse in $files
do
sed 1,2d "$fse" >> ARCHIVE/cdr_file
done
tr -d '"' < $archivepath/cdr_file >> $archivepath/cdr_clean
sed -i s/","/"','"/g $archivepath/cdr_clean
#### Add quote at the start & end of lines
sed -i s/^/"'"/g $archivepath/cdr_clean
sed -i s/$/"'"/g $archivepath/cdr_clean
#### Clean the dir
rm -f $archivepath/cdr_file
########################################
cd $archivepath
#### build the insert colum value
/usr/bin/mysql -u $dbuser -p$dbpass -e "select COLUMN_NAME from information_schema.columns where table_schema = '$mydb' and table_name = '$mytbl';" >> colum
sed 1d colum >> clean_colum
rm colum
cat clean_colum | tr -s '\n' ','| awk '{ print substr( $0, 1, length($0)-1 ) }' >> colum
rm clean_colum
#### put it in a var
tbcol=`cat colum`
#### do the job
while IFS= read -r line; do
/usr/bin/mysql -u $dbuser -p$dbpass -e "INSERT INTO $mydb.$mytbl ($tbcol) VALUES ($line);"
#echo "$line"
done < cdr_clean

rm -f $logpath/cdr*
tar -czf cdr_clean-$(date +%F).tgz cdr_clean
rm -f cdr_clean
rm -f colum
