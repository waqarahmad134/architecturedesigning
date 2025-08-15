<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use ZipArchive;
use Response;
use Log;
use File;
use App\Models\Setting;
use App\Models\Blog;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index()
    {
        return view('home');
    }

    public function project()
    {
        return view('project');
    }

    
    public function blog_details()
    {
        return view('blog_details');
    }

    public function services()
    {
        return view('services');
    }

    public function about()
    {
        return view('about');
    }


    

    
    
    public function backup()
    {
        $folderToBackup = public_path();
        $zipFileName = 'backup_' . Carbon::now()->format('Y_m_d_H_i_s') . '.zip';
        $zipFilePath = base_path($zipFileName);

        $zip = new ZipArchive();

        if ($zip->open($zipFilePath, ZipArchive::CREATE) !== true) {
            Log::error("Unable to create ZIP file at: " . $zipFilePath);
            return response()->json(['error' => 'Unable to create ZIP file'], 500);
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($folderToBackup),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($folderToBackup) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }

        $dbBackupPath = $this->backupDatabase();
        if ($dbBackupPath) {
            $zip->addFile($dbBackupPath, 'database_backup.sql');
        } else {
            Log::error("Database backup failed.");
        }

        if ($zip->close()) {
            Log::info("ZIP file created successfully: " . $zipFilePath);
        } else {
            Log::error("Failed to close the ZIP file.");
            return response()->json(['error' => 'Unable to finalize ZIP file'], 500);
        }

        if (File::exists($zipFilePath)) {
            Log::info("Sending ZIP file for download.");
            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        }

        Log::error("The ZIP file does not exist after creation.");
        return response()->json(['error' => 'Unable to create backup'], 500);
    }

    public function backupDatabase()
    {
        try {
            $dbHost = env('DB_HOST', '127.0.0.1');
            $dbPort = env('DB_PORT', '3306');
            $dbName = env('DB_DATABASE');
            $dbUser = env('DB_USERNAME');
            $dbPass = env('DB_PASSWORD');

            // Create PDO connection to the database
            $pdo = new \PDO("mysql:host={$dbHost};port={$dbPort};dbname={$dbName}", $dbUser, $dbPass);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // Get all tables in the database
            $tables = $pdo->query('SHOW TABLES')->fetchAll(\PDO::FETCH_COLUMN);

            $backupData = "";
            foreach ($tables as $table) {
                // Add DROP TABLE if it exists
                $backupData .= "DROP TABLE IF EXISTS {$table};\n";

                // Get the CREATE TABLE SQL for each table
                $createTableSQL = $pdo->query("SHOW CREATE TABLE {$table}")->fetch(\PDO::FETCH_ASSOC);
                $backupData .= $createTableSQL['Create Table'] . ";\n\n";

                // Get data from the table
                $rows = $pdo->query("SELECT * FROM {$table}")->fetchAll(\PDO::FETCH_ASSOC);
                foreach ($rows as $row) {
                    $columns = implode(",", array_keys($row));
                    $values = implode(",", array_map(function ($value) {
                        return "'" . addslashes($value) . "'";
                    }, array_values($row)));
                    $backupData .= "INSERT INTO {$table} ({$columns}) VALUES ({$values});\n";
                }
                $backupData .= "\n";
            }

            // Save the backup to a .sql file
            $backupFile = base_path('database_backup_' . Carbon::now()->format('Y_m_d_H_i_s') . '.sql');
            file_put_contents($backupFile, $backupData);

            // Return the path to the backup file if it exists
            if (File::exists($backupFile)) {
                Log::info("Database backup successful: " . $backupFile);
                return $backupFile;
            } else {
                Log::error("Database backup failed.");
                return null;
            }
        } catch (\Exception $e) {
            Log::error("Database backup error: " . $e->getMessage());
            return null;
        }
    }
}
