<?php
require_once __DIR__ . '/env.php';

define('SITE_NAME',   getenv('SITE_NAME')   ?: 'Human Rights & Social Work');
define('SITE_URL',    rtrim(getenv('SITE_URL') ?: 'http://localhost:8080', '/'));
define('ADMIN_EMAIL', getenv('ADMIN_EMAIL') ?: 'admin@humanrights.org');
