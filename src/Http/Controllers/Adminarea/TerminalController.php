<?php

declare(strict_types=1);

namespace Cortex\Console\Http\Controllers\Adminarea;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Cortex\Console\Services\Terminal;
use Cortex\Foundation\Http\Controllers\AuthorizedController;

class TerminalController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'terminal';

    /**
     * {@inheritdoc}
     */
    protected $resourceAbilityMap = [
        'index' => 'run',
        'execute' => 'run',
    ];

    /**
     * {@inheritdoc}
     */
    protected $resourceMethodsWithoutModels = [
        'execute',
    ];

    /**
     * {@inheritdoc}
     */
    public function authorizeResource($model, $parameter = null, array $options = [], $request = null): void
    {
        $middleware = [];
        $parameter = $parameter ?: Str::snake(class_basename($model));

        foreach ($this->mapResourceAbilities() as $method => $ability) {
            $modelName = in_array($method, $this->resourceMethodsWithoutModels()) ? $model : $parameter;

            $middleware["can:{$ability}-{$modelName},{$modelName}"][] = $method;
        }

        foreach ($middleware as $middlewareName => $methods) {
            $this->middleware($middlewareName, $options)->only($methods);
        }
    }

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
