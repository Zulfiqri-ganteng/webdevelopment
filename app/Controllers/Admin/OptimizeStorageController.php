<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Config\Database;

class OptimizeStorageController extends BaseController
{
    protected $folders = [
        'public/uploads/siswa/',
        'public/uploads/guru/',
        'public/uploads/users/',
        'public/uploads/dokumen/'
    ];

    // File yang tidak boleh dihapus
    protected $protectedFiles = [
        'default.png',
        'default.jpg',
        'default.jpeg',
        'index.html'
    ];

    public function index()
    {
        return view('admin/optimize_storage');
    }

    private function getValidFiles()
    {
        $db = Database::connect();
        $valid = [];

        // Foto users
        $rows = $db->table('users')->select('foto')->get()->getResult();
        foreach ($rows as $r) {
            if (!empty($r->foto)) $valid[] = $r->foto;
        }

        // Foto siswa
        $rows = $db->table('siswa')->select('foto')->get()->getResult();
        foreach ($rows as $r) {
            if (!empty($r->foto)) $valid[] = $r->foto;
        }

        // Foto guru
        $rows = $db->table('guru')->select('foto')->get()->getResult();
        foreach ($rows as $r) {
            if (!empty($r->foto)) $valid[] = $r->foto;
        }

        return $valid;
    }

    public function preview()
    {
        $valid = $this->getValidFiles();
        $orphans = [];

        foreach ($this->folders as $folder) {
            if (!is_dir($folder)) continue;

            foreach (scandir($folder) as $file) {

                if ($file === '.' || $file === '..') continue;

                // Lewati file default / protected
                if (in_array($file, $this->protectedFiles)) continue;

                // Jika tidak ada referensi di DB → orphan
                if (!in_array($file, $valid)) {
                    $path = $folder . $file;

                    $orphans[] = [
                        'file'   => $file,
                        'folder' => $folder,
                        'size'   => filesize($path),
                        'path'   => $path
                    ];
                }
            }
        }

        return view('admin/optimize_preview', ['orphans' => $orphans]);
    }

    public function run()
    {
        $valid = $this->getValidFiles();
        $deletedCount = 0;

        foreach ($this->folders as $folder) {

            if (!is_dir($folder)) continue;

            foreach (scandir($folder) as $file) {

                if ($file === '.' || $file === '..') continue;

                // Skip protected files
                if (in_array($file, $this->protectedFiles)) continue;

                // Hapus jika tidak digunakan
                if (!in_array($file, $valid)) {
                    @unlink($folder . $file);
                    $deletedCount++;
                }
            }
        }

        return redirect()
            ->to(smart_url('admin/optimize-storage'))
            ->with('success', "$deletedCount file orphan berhasil dihapus.");
    }
    public function jsonPreview()
    {
        $valid = $this->getValidFiles();
        $orphans = [];

        foreach ($this->folders as $folder) {
            if (!is_dir($folder)) continue;

            foreach (scandir($folder) as $file) {
                if ($file === '.' || $file === '..') continue;

                // Default tidak boleh dihapus
                if (in_array($file, ['default.png', 'index.html'])) continue;

                if (!in_array($file, $valid)) {
                    $orphans[] = [
                        'name'   => $file,
                        'folder' => $folder,
                        'size'   => filesize($folder . $file),
                        'url'    => base_url(str_replace('writable/', '', $folder) . $file)
                    ];
                }
            }
        }

        return $this->response->setJSON([
            'count'   => count($orphans),
            'orphans' => $orphans
        ]);
    }
}
