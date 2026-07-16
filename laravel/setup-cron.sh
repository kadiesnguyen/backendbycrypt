#!/bin/bash

# Laravel Scheduler Cron Setup Script
# This script adds the Laravel scheduler to crontab

CRON_COMMAND="* * * * * cd /www/wwwroot/api.wnskcex.com && /usr/bin/php artisan schedule:run >> /dev/null 2>&1"

# Check if cron entry already exists
if crontab -l 2>/dev/null | grep -q "schedule:run"; then
    echo "Cron job already exists!"
    crontab -l | grep "schedule:run"
else
    # Add new cron entry
    (crontab -l 2>/dev/null; echo "$CRON_COMMAND") | crontab -
    echo "Cron job added successfully!"
    echo "$CRON_COMMAND"
fi

echo ""
echo "Current crontab:"
crontab -l
