#!/bin/bash
basedir="/usr/local/games/hlstatsx"
mode="install"

function yesno() {
	question=$1
	while [ 1 ]; do
		echo -n "${question}"
		read response

		case $response in
			y|Y)
			return 1
			;;
			n|N)
			return 0
			;;
		esac
	done
}

# Check for current configuration, see if we can figure out the db, user and password.
if [ -f /etc/hlstatsx/hlstats.conf ]; then
	DBHost=`sed -n -e '/DBHost "/p' /etc/hlstatsx/hlstats.conf`
	DBUsername=$(sed -n -e '/DBUsername "/p' /etc/hlstatsx/hlstats.conf)
	DBPassword=$(sed -n -e '/DBPassword "/p' /etc/hlstatsx/hlstats.conf)
	DBName=$(sed -n -e '/DBName "/p' /etc/hlstatsx/hlstats.conf)

	DBHost=${DBHost/DBHost \"/}
	DBHost=${DBHost%\"}
	DBUsername=${DBUsername/DBUsername \"/}
	DBUsername=${DBUsername%\"}
	DBPassword=${DBPassword/DBPassword \"/}
	DBPassword=${DBPassword%\"}
	DBName=${DBName/DBName \"/}
	DBName=${DBName%\"}

	if [ "${DBHost}" == "" ]; then
		mode="install"
	else
		mode="upgrade"
	fi
fi

if [ "${mode}" == "install" ]; then
	echo "No configuration found, expecting new installation"
	echo ""
	echo -n "Host (localhost: enter): "
	read DBHost
	if [ "${DBHost}" == "" ]; then
		DBHost="localhost"
	fi

        echo -n "Database: "
        read DBName

        echo -n "Username: "
        read DBUsername

        echo -n "Password: "
        read DBPassword

	echo ""
	echo "Summary:"
	echo "================================"
        echo "Host:     ${DBHost}"
        echo "Database: ${DBName}"
        echo "Username: ${DBUsername}"
        echo "Password: ${DBPassword}"
	echo "================================"

	yesno "Is this correct? [y/n]: "
	if [ $? -eq 0 ]; then
		echo "Install aborted"
		exit 0
	fi

	# Test connection
	$(mysql --host="${DBHost}" --user="${DBUsername}" --password="${DBPassword}" "${DBName}" -e '' >/dev/null 2>&1)
	if [ $? -eq 0 ]; then
		# Setting variables to config files

		# Perl scripts
		sed -e "s/DBHost \"\"/DBHost \"${DBHost}\"/g" ${basedir}/perl/hlstats.conf > ${basedir}/perl/hlstats.conf.1
		sed -e "s/DBName \"\"/DBName \"${DBName}\"/g" ${basedir}/perl/hlstats.conf.1 > ${basedir}/perl/hlstats.conf
		sed -e "s/DBUsername \"\"/DBUsername \"${DBUsername}\"/g" ${basedir}/perl/hlstats.conf > ${basedir}/perl/hlstats.conf.1
		sed -e "s/DBPassword \"\"/DBPassword \"${DBPassword}\"/g" ${basedir}/perl/hlstats.conf.1 > ${basedir}/perl/hlstats.conf
		rm -fr ${basedir}/perl/hlstats.conf.1

		# Web scripts
		sed -e "s/define(\"DB_ADDR\", \"localhost\");/define(\"DB_ADDR\", \"${DBHost}\");/g" ${basedir}/web/config.php > ${basedir}/web/config.php.1
		sed -e "s/define(\"DB_NAME\", \"\");/define(\"DB_NAME\", \"${DBName}\");/g" ${basedir}/web/config.php.1 > ${basedir}/web/config.php
		sed -e "s/define(\"DB_USER\", \"\");/define(\"DB_USER\", \"${DBUsername}\");/g" ${basedir}/web/config.php > ${basedir}/web/config.php.1
		sed -e "s/define(\"DB_PASS\", \"\");/define(\"DB_PASS\", \"${DBPassword}\");/g" ${basedir}/web/config.php.1 > ${basedir}/web/config.php
		rm -fr ${basedir}/web/config.php.1

		mysql --host="${DBHost}" --user="${DBUsername}" --password="${DBPassword}" "${DBName}" < ${basedir}/sql/install.sql

		echo "Configuration completed!"
		exit 0
	else
		echo "There seems to be some problem with your database and/or credentials"
		echo "Please check your settings and run setup.sh again"
		exit 1
	fi
fi

if [ "${mode}" == "upgrade" ]; then
#	echo "Configuration found, upgrading"
	echo "Configuration found, but upgrading automaticly will be supported in the next release"
	echo "Just showing the info i found"
	echo ""
	echo "Using following settings:"
	echo "Host:     ${DBHost}"
	echo "Database: ${DBName}"
	echo "Username: ${DBUsername}"
	echo "Password: ${DBPassword}"

#        yesno "Continue? [y/n]: "
#        if [ $? -eq 0 ]; then
#                echo "Upgrade aborted"
#                exit 0
#        fi

	# Run the upgradescript
#	mysql --host="${DBHost}" --user="${DBUsername}" --password="${DBPassword}" "${DBName}" < ${basedir}/sql/upgrade_hlxcomm_153_154.sql
#	echo "Upgrade completed!"
	exit 0

fi
