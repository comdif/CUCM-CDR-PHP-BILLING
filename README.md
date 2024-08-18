# CUCM-BILLING
Get and process Cisco CUCM cdrs with simple tools ( shell script, php, mysql )
This is a custom interface build for my own need, I have a CUCM and a Freepbx server working together on the same network
Freepbx have already a CDRS interface and I just build a module to get and show also the CUCM CDRS & have a billing system.

# Requirements

Build on Ubuntu 22.0.4 LTS but you can use any modern LAMP server.

Required packages:

apt install php libapache2-mod-php php-mysql php-soap php-xml

or php7.x-soap or php8.x-soap depending your php version

# Instructions:
install all files in /var/www/html ( or customise if needed ), install the provided cron file in /var/spool/root
create the Mysql database.

- Log into the Cisco Unified CM Administration application Go to Application --> Plugins

Click on the Download link by the Cisco CallManager AXL SQL Toolkit Plugin extract the Zip file and upload

AXLAPI.wsdl, AXLEnums.xsd, AXLSoap.xsd in this directory.

- Login to Cisco Unified Serviceability and configure your Call manager to send CDRS via SFTP to the dir /var/www/html/CDRS.

I know my instructions are not very clear, don't hesitate if you need some help !
