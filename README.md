# CUCM-BILLING
Get and process Cisco CUCM cdrs with simple tools ( shell script, php, mysql )

# Requirements

Build on Ubuntu 22.0.4 LTS but you can use any modern LAMP server.

Required packages:

apt install php libapache2-mod-php php-mysql php-soap php-xml

or php7.x-soap or php8.x-soap depending your php version

# Instructions:
install all files in /var/www/html ( or customise if needed ), install the provided cron file in /var/spool/root with content:

- Log into the Cisco Unified CM Administration application Go to Application --> Plugins

Click on the Download link by the Cisco CallManager AXL SQL Toolkit Plugin extract the Zip file and upload

AXLAPI.wsdl, AXLEnums.xsd, AXLSoap.xsd in this directory.

- Login to Cisco Unified Serviceability and configure your Call manager to send CDRS via SFTP to the dir /var/www/html/CDRS.
