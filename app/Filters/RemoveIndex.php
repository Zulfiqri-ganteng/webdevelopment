<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RemoveIndex implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Jangan lakukan apa-apa untuk permintaan AJAX atau CLI
        if (method_exists($request, 'isAJAX') && $request->isAJAX()) {
            return;
        }

        $uri = $request->getUri();
        $path = ltrim($uri->getPath(), '/'); // tanpa leading slash

        // Jika tidak ada "index.php" di path -> tidak perlu redirect
        if (!str_contains($path, 'index.php')) {
            return;
        }

        // Jika path tepat "index.php" (akses root via index.php),
        // jangan redirect untuk menghindari loop (login/Auth dapat menyebabkan loop).
        if ($path === 'index.php') {
            return;
        }

        // Ubah path bersih (jaga segment yang lain)
        $cleanPath = str_replace('index.php/', '', $path);

        // Bangun ulang URL, sertakan query string kalau ada
        $query = $uri->getQuery();
        $cleanUrl = base_url($cleanPath) . ($query ? '?' . $query : '');

        return redirect()->to($cleanUrl);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak diperlukan aksi after
    }
}
