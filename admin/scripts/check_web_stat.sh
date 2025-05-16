#!/bin/bash

URL="http://admin.local"
LOG_FILE="$HOME/server_status/server_status.log"
DATE=$(date +'%Y-%m-%d_%H_%M_%S')

HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" "$URL")
if [ "$HTTP_CODE" -eq 200 ]; then
STATUS="ONLINE"
else
STATUS="OFFLINE - HTTP code: $HTTP_CODE"
fi

echo "[$DATE] Status: $STATUS" >> "$LOG_FILE"

