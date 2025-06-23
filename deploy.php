<?php

echo "<pre>";
$output = shell_exec('whoami');
if ($output) {
    echo "✅ shell_exec() is enabled.<br>";
    echo "Current user: $output";
} else {
    echo "❌ shell_exec() is disabled or restricted.";
}
echo "</pre>";
die;
// Git auto-deploy script
$webRoot = '/home/indiagurjargaud/public_html/new-site';
$logFile = $webRoot . '/deploy.log';

$cmd = "cd $webRoot && git pull origin main 2>&1";
$output = shell_exec($cmd);

// Optional: run Laravel commands
// $shellCommands = [
//     "composer install --no-dev --prefer-dist",
//     "php artisan migrate --force",
//     "php artisan config:cache",
//     "php artisan route:cache",
//     "php artisan view:clear"
// ];

// foreach ($shellCommands as $command) {
//     $output .= "\n\n> " . $command . "\n";
//     $output .= shell_exec("cd $webRoot && $command 2>&1");
// }

// Save log
file_put_contents($logFile, date('Y-m-d H:i:s') . "\n" . $output . "\n\n", FILE_APPEND);

echo "<pre>$output</pre>";
?>
