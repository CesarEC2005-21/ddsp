<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:generate';
    protected $description = 'Generate a database backup';

    public function handle()
    {
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST', '127.0.0.1');
        
        $filename = "backup_" . $database . "_" . date("Y-m-d_H-i-s") . ".sql";
        $path = storage_path('app/backups/' . $filename);

        if (!is_dir(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        $mysqlPath = "d:\\xampp\\mysql\\bin\\mysqldump.exe";
        if (!file_exists($mysqlPath)) {
            $mysqlPath = "c:\\xampp\\mysql\\bin\\mysqldump.exe";
            if (!file_exists($mysqlPath)) {
                $mysqlPath = "mysqldump";
            }
        }

        $passArg = empty($password) ? "" : "--password=\"$password\"";
        $command = "\"$mysqlPath\" --user=\"$username\" $passArg --host=\"$host\" \"$database\" > \"$path\" 2>&1";
        
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            $this->error('Backup generation failed.');
        } else {
            $this->info('Backup generated successfully: ' . $path);
        }
    }
}
