<?php

use CodeIgniter\Boot;
use Config\Paths;

// Minimal PHP Version
$minPhpVersion = '8.1';
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    echo "PHP version must be {$minPhpVersion} or higher.";
    exit(1);
}

// Front Controller path
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// App path di hosting (karena app berada di public_html/app)
require FCPATH . 'app/Config/Paths.php';

$paths = new Paths();

// System path untuk booting CI4
require FCPATH . 'vendor/codeigniter4/framework/system/Boot.php';

exit(Boot::bootWeb($paths));
