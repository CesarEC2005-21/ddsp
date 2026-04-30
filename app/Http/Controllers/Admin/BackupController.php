<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class BackupController extends Controller
{
    public function index()
    {
        return view('admin.backups.index');
    }

    public function generate()
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

        // En XAMPP Windows, mysqldump suele estar en esta ruta
        $mysqlPath = "C:\\xampp\\mysql\\bin\\mysqldump.exe";
        
        // Si no está ahí, intentamos solo 'mysqldump' asumiendo que está en el PATH
        if (!file_exists($mysqlPath)) {
            $mysqlPath = "mysqldump";
        }

        $command = "\"$mysqlPath\" --user=$username --password=$password --host=$host $database > \"$path\"";
        
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            return redirect()->back()->with('error', 'Error al generar el backup. Verifique la configuración de MySQL.');
        }

        return Response::download($path)->deleteFileAfterSend(true);
    }
}
