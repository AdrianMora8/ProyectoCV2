<?php
echo "<pre>";
echo "DIR test: " . dirname('/srv/http/CV/controller/x.php') . "\n";
$dir = '/srv/http/CV/image/productos/';
echo "Dir exists: " . (is_dir($dir) ? 'yes' : 'no') . "\n";
echo "Dir writable: " . (is_writable($dir) ? 'yes' : 'no') . "\n";
echo "Running as: " . get_current_user() . " / posix uid: " . posix_geteuid() . "\n";
echo "FILES: "; print_r($_FILES);
echo "</pre>";
