<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class DatabaseToolsController extends BaseController
{
    protected $backupPath;
    protected $restoreTemp;

    public function __construct()
    {
        $this->backupPath = WRITEPATH . 'backups/local/';
        $this->restoreTemp = WRITEPATH . 'restore/';

        if (!is_dir($this->backupPath)) mkdir($this->backupPath, 0755, true);
        if (!is_dir($this->restoreTemp)) mkdir($this->restoreTemp, 0755, true);
    }

    public function index()
    {
        helper('filesystem');

        $files = [];
        $items = directory_map($this->backupPath, 1) ?: [];

        foreach ($items as $f) {
            $full = $this->backupPath . $f;
            if (is_file($full)) {
                $files[] = [
                    'name'  => $f,
                    'size'  => number_format(filesize($full) / 1024, 2) . ' KB',
                    'mtime' => date('Y-m-d H:i:s', filemtime($full))
                ];
            }
        }

        return view('admin/database_tools', [
            'files' => $files,
            'mode'  => 'local'
        ]);
    }

    // =======================
    // BACKUP DATABASE (mysqldump)
    // =======================
    public function backup()
    {
        $dbConfig = new \Config\Database();
        $default = $dbConfig->default;

        $host = $default['hostname'];
        $user = $default['username'];
        $pass = $default['password'];
        $name = $default['database'];

        $timestamp = date('Ymd_His');
        $sqlName = "backup_{$timestamp}.sql";
        $zipName = "backup_{$timestamp}.zip";

        $sqlPath = $this->backupPath . $sqlName;
        $zipPath = $this->backupPath . $zipName;

        $mysqldump = "\"C:\\xampp\\mysql\\bin\\mysqldump.exe\"";

        // Jika ada password
        $pwPart = $pass !== "" ? "-p{$pass}" : "";

        $command = "{$mysqldump} -u{$user} {$pwPart} {$name} > \"{$sqlPath}\"";

        exec($command, $out, $res);
        if ($res !== 0) {
            return redirect()->back()->with('error', "Backup gagal: mysqldump gagal dijalankan.");
        }

        // ZIP-kan SQL
        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE)) {
            $zip->addFile($sqlPath, $sqlName);
            $zip->close();
            unlink($sqlPath);
        }
        // ===================================================
        // AUTO DELETE BACKUP LAMA (simpan hanya 10 file terbaru)
        // ===================================================

        $files = glob($this->backupPath . '*.zip'); // ambil semua file backup zip
        usort($files, function ($a, $b) {
            return filemtime($b) - filemtime($a); // urutkan terbaru → terlama
        });

        $maxBackup = 5; // jumlah backup maksimal yang disimpan

        if (count($files) > $maxBackup) {
            // Hapus file sisanya (yang paling lama)
            for ($i = $maxBackup; $i < count($files); $i++) {
                @unlink($files[$i]);
            }
        }

        return redirect()->back()->with('success', "Backup berhasil disimpan: {$zipName}");
    }

    // =======================
    // UPLOAD FILE RESTORE
    // =======================
    public function uploadRestore()
    {
        $file = $this->request->getFile('restore_file');

        if (!$file->isValid())
            return redirect()->back()->with('error', 'File tidak valid.');

        $ext = $file->getClientExtension();
        if (!in_array($ext, ['sql', 'zip']))
            return redirect()->back()->with('error', 'Hanya .sql dan .zip yang didukung.');

        $name = $file->getRandomName();
        $file->move($this->restoreTemp, $name);

        session()->set('restore_file', $name);

        return redirect()->back()->with('success', 'File restore berhasil diupload.');
    }

    // =======================
    // PROSES RESTORE (mysql.exe)
    // =======================
    public function runRestore()
    {
        $restoreFile = session()->get('restore_file');
        if (!$restoreFile)
            return redirect()->back()->with('error', 'Tidak ada file restore.');

        // Validasi password admin
        $password = $this->request->getPost('password');
        $user = db_connect()->table('users')->where('id', session('id'))->get()->getRow();

        if (!$user || !password_verify($password, $user->password)) {
            return redirect()->back()->with('error', 'Password admin salah.');
        }

        // AUTO BACKUP BEFORE RESTORE
        $this->backup();

        $fullPath = $this->restoreTemp . $restoreFile;
        $ext = pathinfo($fullPath, PATHINFO_EXTENSION);

        // Jika ZIP → extract SQL
        if ($ext === 'zip') {
            $zip = new \ZipArchive();
            $zip->open($fullPath);
            $zip->extractTo($this->restoreTemp);
            $zip->close();

            // Cari file SQL hasil extract
            foreach (scandir($this->restoreTemp) as $f) {
                if (pathinfo($f, PATHINFO_EXTENSION) === 'sql') {
                    $fullPath = $this->restoreTemp . $f;
                    break;
                }
            }
        }

        // =============================
        // RESTORE VIA mysql.exe
        // =============================
        $dbConfig = new \Config\Database();
        $default = $dbConfig->default;

        $host = $default['hostname'];
        $user = $default['username'];
        $pass = $default['password'];
        $name = $default['database'];

        $mysql = "\"C:\\xampp\\mysql\\bin\\mysql.exe\"";

        $pwPart = $pass !== "" ? "-p{$pass}" : "";

        $command = "{$mysql} -u{$user} {$pwPart} {$name} < \"{$fullPath}\"";

        exec($command, $out, $res);

        if ($res !== 0) {
            return redirect()->back()->with('error', "Gagal restore: mysql.exe gagal dijalankan.");
        }

        session()->remove('restore_file');
        return redirect()->back()->with('success', 'Database berhasil direstore.');
    }

    // =======================
    // DOWNLOAD FILE
    // =======================
    public function download($file)
    {
        $file = basename($file);
        $path = $this->backupPath . $file;

        if (!is_file($path))
            return redirect()->back()->with('error', 'File tidak ditemukan.');

        return $this->response->download($path, null);
    }

    // =======================
    // DELETE BACKUP FILE
    // =======================
    public function delete()
    {
        $file = basename($this->request->getPost('file'));
        $path = $this->backupPath . $file;

        if (is_file($path)) unlink($path);

        return redirect()->back()->with('success', 'File backup dihapus.');
    }
}
