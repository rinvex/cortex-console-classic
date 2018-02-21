<?php

declare(strict_types=1);

namespace Cortex\Console\Http\Controllers\Adminarea;

use Exception;
use Illuminate\Http\Request;
use Cortex\Console\Services\Terminal;
use Cortex\Foundation\Http\Controllers\AuthorizedController;

class TerminalController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'run-terminal';

    /**
     * Show terminal index.
     *
     * @param \Illuminate\Http\Request          $request
     * @param \Cortex\Console\Services\Terminal $terminal
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request, Terminal $terminal)
    {
        $token = null;

        if ($request->hasSession() === true) {
            $token = $request->session()->token();
        }

        $terminal->call('list --ansi');
        $options = json_encode([
            'username' => 'LARAVEL',
            'hostname' => php_uname('n'),
            'os' => PHP_OS,
            'csrfToken' => $token,
            'helpInfo' => $terminal->output(),
            'basePath' => app()->basePath(),
            'environment' => app()->environment(),
            'version' => app()->version(),
            'endpoint' => route('adminarea.console.terminal.execute'),
            'interpreters' => [
                'mysql' => 'mysql',
                'artisan tinker' => 'tinker',
                'tinker' => 'tinker',
            ],
            'confirmToProceed' => [
                'artisan' => [
                    'migrate',
                    'migrate:install',
                    'migrate:refresh',
                    'migrate:reset',
                    'migrate:rollback',
                    'db:seed',
                ],
            ],
        ]);

        return view('cortex/console::adminarea.pages.terminal', compact('options'));
    }

    /**
     * Process the form for store/update of the given resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function execute(Terminal $terminal, Request $request)
    {
        $error = $terminal->call($request->get('command'));

        return response()->json([
            'jsonrpc' => $request->get('jsonrpc'),
            'id' => $request->get('id'),
            'result' => $terminal->output(),
            'error' => $error,
        ]);
    }

    /**
     * Render exception.
     *
     * @param \Exception $exception
     *
     * @return \Illuminate\View\View
     */
    protected function renderException(Exception $exception)
    {
        return view('cortex/console::adminarea.pages.error', ['message' => $exception->getMessage()]);
    }
}
