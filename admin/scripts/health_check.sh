#!/bin/bash

DATE=$(date +'%Y-%m-%d %H:%M:%S')
LOG_FILE="/home/admin/clinic_dev/Clinic-Appointment-System/admin/storage/logs/system_health.log"

MEMORY=$(free -h | grep Mem | awk '{print $3 " used / " $2 " total"}')
DISK=$(df -h / | tail -1 | awk '{print $5 " used"}')

APACHE_STATUS=$(systemctl is-active apache2)

echo "[$DATE] Memory: $MEMORY | Disk: $DISK | Apache: $APACHE_STATUS" >>  $LOG_FILE
