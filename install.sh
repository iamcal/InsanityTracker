#!/bin/bash

mv /var/www/html/InsanityTracker /var/www/html/insanitytracker.com

ln -s /var/www/html/insanitytracker.com/site.conf /etc/apache2/sites-available/insanitytracker.com.conf
a2ensite insanitytracker.com
service apache2 reload

cd /var/www/html/insanitytracker.com/db
./init_db.sh
