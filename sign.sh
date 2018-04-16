#!bin/bash
#Author :   Wang
#Useage :   QQ qun sign
#Date   :   2018/04/04

var=`date "+%Y-%m-%d %H:%M:%S"` 
echo -e $var >> ~/shell/date.txt

export DISPLAY=:6666
/usr/bin/python /root/shell/getSkey.py 2> /root/shell/result
