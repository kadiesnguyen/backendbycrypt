<?php

return [
    'superadmin_ids' => array_map('intval', explode(',', env('ADMIN_SUPERADMIN_IDS', '1'))),
    'develop_mode' => (bool) env('ADMIN_DEVELOP_MODE', false),
];
