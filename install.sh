#!/bin/bash

# Install script for PHP IP Validator 

# Get Working directory
dir=$(pwd)

# Get current user
user="$USER"
echo "Current user is: " $user

# Get first letter of current user
userFirst=${user::1}
echo "First letter of current user is: " $userFirst

# Get webpath
linkPath=/home/$userFirst/$user/html/attendance
echo "Path to users html link: " $linkPath

# Get Webroot path
webroot=$dir/webroot
echo "Path to Attendance Webroot: " $webroot

# Chmod all files
find $dir -type d -exec chmod 755 {} +
find $dir -type f -exec chmod 644 {} +

# Chmod install file back to +x
chmod u+x $dir/install.sh

# Chmod log files for writing
chmod 606 $dir/log/full.log
chmod 606 $dir/log/attendance.log

# Link webpath to user
if [ ! -L ${linkPath} ]; then
echo "Link doesn't exist, creating"
	$(ln -s $webroot $linkPath)
else
	echo "Link already exists, skipping"
fi

read -p "Do you want to enter a Course? [Yes/No]: " courseBool
while [ "$courseBool" == "Yes" ]
do
	read -p "Enter CourseID in format IA1001: " courseId
	read -p "Enter Course Name: " courseName
	echo courses['"'$courseId'"'] = '"'$courseName'"' >> $dir/config/cidr.ini
	echo ""
	echo "Link to course is: https://cgi.arcada.fi/~"$user"/attendance/index.php?course="$courseId
	echo ""
	read -p "Do you want to enter another? [Yes/No]: " courseBool
done
