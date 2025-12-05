<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class ErrorLogController extends BaseController
{
    protected $logPath;

    public function __construct()
    {
        $this->logPath = WRITEPATH . 'logs/';
    }

    public function index()
    {
        return view('admin/error-log/index');
    }

    public function fetch()
    {
        $files = glob($this->logPath . 'log-*.log');

        if (!$files) {
            return $this->response->setJSON([
                'filename' => 'Tidak ada file log',
                'content'  => ''
            ]);
        }

        $latest = end($files);
        $content = @file_get_contents($latest) ?: '';

        // balik agar yang terbaru di atas
        $lines = explode("\n", $content);
        $lines = array_reverse($lines);
        $content = implode("\n", $lines);

        return $this->response->setJSON([
            'filename' => basename($latest),
            'content'  => $content
        ]);
    }


    // ==========================================================
    //  FIX UTAMA — FORCE CLEAR LOG (PASTI KOSONG DI WINDOWS)
    // ==========================================================
    public function clear()
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid request method.'
            ]);
        }

        $autoDays = 10;
        $now = time();
        $deleted = 0;
        $cleaned = 0;

        $files = glob($this->logPath . 'log-*.log');

        if ($files) {
            foreach ($files as $f) {

                // AUTO HAPUS file lama
                $mtime = filemtime($f);
                if ($mtime && ($now - $mtime) > ($autoDays * 86400)) {
                    @unlink($f);
                    $deleted++;
                    continue;
                }

                // ===================================================
                //  FIX PENTING — FORCE CLEAR LOG
                // ===================================================
                // Mode "w" akan membuat file baru meski file lama locked
                $fp = @fopen($f, 'w');
                if ($fp) {
                    fwrite($fp, "");  // kosongkan
                    fclose($fp);
                    $cleaned++;
                }
            }
        }

        return $this->response->setJSON([
            'status' => 'ok',
            'message' => "Log dikosongkan: $cleaned file. File lama dihapus: $deleted file."
        ]);
    }
}
