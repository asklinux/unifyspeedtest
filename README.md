#TM unifi speedtest script

copy bandwidth.sh and bandwidth.db file to 

/usr/local/etc/


run crontab -e

add :-
1 * * * * /usr/local/etc/bandwidth.sh 2>&1

copy index.php to web folder
