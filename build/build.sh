#!/bin/bash
# Script to generate and build various packages for linux using 'epm'
# Author: Mattias Ryrlén <mattias@ryrlen.org>
#
if [ ! -f /usr/bin/epm ]; then
	echo "Epm not found, install it, http://www.epmhome.org"
	exit 1
fi

package=$1
arch="noarch"
outputdir="./build/tmp"
cmd=""

if [ "${package}" == "" ]; then
	package="rpm"
fi

if [ "${package}" == "deb" ]; then
	arch="all"
	cmd="sudo "
fi

# Creating spec.list again :)
/usr/bin/mkepmlist -u root -g root --prefix /usr/local/games/hlstatsx . > filelist.list

# Remove all .svn files since we don't care about it.
sed -e '/.svn/d' filelist.list > filelist.list.1
sed -e '/build/d' filelist.list.1 > filelist.list
sed -e '/heatmaps\/src/d' filelist.list > filelist.list.1
# Resetting to filelist.list again if we aren't there
cat filelist.list.1 > filelist.list

# Other files that needs to be excluded
sed -e '/filelist.list/d' filelist.list > filelist.list.1
#sed -e '/spec.settings/d' filelist.list.1 > filelist.list
#sed -e '/spec.list/d' filelist.list > filelist.list.1
#sed -e '/hlstatsx.cron/d' filelist.list.1 > filelist.list
#sed -e '/build.sh/d' filelist.list > filelist.list.1
#sed -e '/hlstatsx.1/d' filelist.list.1 > filelist.list
# Resetting to filelist.list again if we aren't there
cat filelist.list.1 > filelist.list

# Replace /usr/local/games/hlstatsx with $prefix instead
sed -e 's/\/usr\/local\/games\/hlstatsx/\$prefix/g' filelist.list > filelist.list.1
# Resetting to filelist.list again if we aren't there
cat filelist.list.1 > filelist.list

# Change type and attributes on special files, ie .pl and config files

# ./perl folder
sed -e 's/f 644 root root \$prefix\/perl\/hlstats.conf/c 644 root root \$prefix\/perl\/hlstats.conf/g' filelist.list.1 > filelist.list
sed -e 's/f 644 root root \$prefix\/perl\/hlstats-awards.pl/f 755 root root \$prefix\/perl\/hlstats-awards.pl/g' filelist.list > filelist.list.1
sed -e 's/f 644 root root \$prefix\/perl\/hlstats.pl/f 755 root root \$prefix\/perl\/hlstats.pl/g' filelist.list.1 > filelist.list
sed -e 's/f 644 root root \$prefix\/perl\/hlstats-resolve.pl/f 755 root root \$prefix\/perl\/hlstats-resolve.pl/g' filelist.list > filelist.list.1
sed -e 's/f 644 root root \$prefix\/perl\/run_hlstats/f 755 root root \$prefix\/perl\/run_hlstats/g' filelist.list.1 > filelist.list
sed -e 's/f 644 root root \$prefix\/perl\/run_autorestart/f 755 root root \$prefix\/perl\/run_autorestart/g' filelist.list > filelist.list.1
sed -e 's/f 644 root root \$prefix\/perl\/run_multi/f 755 root root \$prefix\/perl\/run_multi/g' filelist.list.1 > filelist.list
# Resetting to filelist.list again if we aren't there
#cat filelist.list.1 > filelist.list

# ./web folder
sed -e 's/f 644 root root \$prefix\/web\/config.php/c 644 root root \$prefix\/web\/config.php/g' filelist.list > filelist.list.1
# Resetting to filelist.list again if we aren't there
cat filelist.list.1 > filelist.list

cat ./build/spec.settings > ./build/spec.list
cat filelist.list >> ./build/spec.list
rm -fr filelist.*

# fixing special settings for the spec.list
echo '' >> ./build/spec.list
echo '# moving setup.sh for package' >> ./build/spec.list
echo 'f 0755 root root $prefix/setup.sh ./build/setup.sh' >> ./build/spec.list

echo '' >> ./build/spec.list
echo '# Linking the web frontend' >> ./build/spec.list
echo 'l 0755 root root /var/www/html/hlstatsx $prefix/web' >> ./build/spec.list
echo 'l 0755 root root /var/www/hlstatsx $prefix/web' >> ./build/spec.list

echo '' >> ./build/spec.list
echo '# make a symlink in /etc/hlstatsx/hlstats.conf to your conf file.' >> ./build/spec.list
echo 'd 755 root root /etc/hlstatsx -' >> ./build/spec.list
echo 'l 0644 root root /etc/hlstatsx/hlstats.conf $prefix/perl/hlstats.conf' >> ./build/spec.list
echo '' >> ./build/spec.list

echo '# Setting up crontab' >> ./build/spec.list
echo 'c 644 root root /etc/cron.d/hlstatsx.cron ./build/hlstatsx.cron' >> ./build/spec.list

gzip ./build/hlstatsx.1
echo '# Setting up man-pages' >> ./build/spec.list
echo 'f 644 root root /usr/share/man/man1/hlstatsx.1.gz ./build/hlstatsx.1.gz' >> ./build/spec.list

echo '# Creating logdir' >> ./build/spec.list
echo 'd 777 root root $prefix/perl/logs' >> ./build/spec.list

# Cleanup before making build
${cmd}epm -f ${package} -nm hlstatsx ./build/spec.list -a ${arch} --output-dir ${outputdir} -v

# Removing files we don't need anymore
gunzip ./build/hlstatsx.1.gz
