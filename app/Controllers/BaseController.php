<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * Safe declared properties (WAJIB untuk PHP 8.2+)
     */
    protected string $baseUrl = '';

    /**
     * Helpers auto-load
     */
    protected $helpers = ['log', 'activity'];

    /**
     * Init controller logic
     */
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        // --- SOLUSI BASE URL AMAN TANPA ERROR ---
        $this->baseUrl = base_url();

        // Helpers tambahan
        helper(['url', 'form', 'smart']);
    }
}
