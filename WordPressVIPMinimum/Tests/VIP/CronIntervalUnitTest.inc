<?php

// phpcs:set WordPressVIPMinimum.VIP.CronInterval min_interval 600
add_filter( 'cron_schedules', 'my_add_weekly'); // Error: 6 min.

// phpcs:set WordPressVIPMinimum.VIP.CronInterval min_interval 900
