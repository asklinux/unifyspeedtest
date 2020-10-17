#TM unifi speedtest script

first install speedtest-cli

install python

wget -O speedtest-cli https://raw.githubusercontent.com/sivel/speedtest-cli/master/speedtest.py

cp speedtest-cli /bin/speedtest
chmod +x /bin/speedtest

copy bandwidth.sh and bandwidth.db file to 

/usr/local/etc/


run crontab -e

add :-

1 * * * * /usr/local/etc/bandwidth.sh 2>&1

copy index.php to web folder
