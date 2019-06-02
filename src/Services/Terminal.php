<?php

declare(strict_types=1);

namespace Cortex\Console\Services;

use Illuminate\Http\Request;
use Cortex\Console\Console\Commands\Artisan;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Illuminate\Console\Application as ConsoleApplication;

class Terminal extends ConsoleApplication
{
    /**
     * Resolve an array of commands through the application.
     *
     * @param array|mixed $commands
     *
     * @return static|\Illuminate\Console\Application
     */
    public function resolveCommands($commands)
    {
        return in_array(Artisan::class, $commands) ? parent::resolveCommands($commands) : $this;
    }

    /**
     * Run an Artisan console command by name.
     *
     * @param string                                            $command
     * @param array                                             $parameters
     * @param \Symfony\Component\Console\Output\OutputInterface $outputBuffer
     *
     * @throws \Exception
     *
     * @return int
     */
    public function call($command, array $parameters = [], $outputBuffer = null): int
    {
        $class = $outputBuffer ?: new BufferedOutput();

        if ($this->request()->ajax() === true) {
            $this->lastOutput = new $class(BufferedOutput::VERBOSITY_NORMAL, true, new OutputFormatter(true));
            $this->setCatchExceptions(true);
        } else {
            $this->lastOutput = new $class();
            $this->setCatchExceptions(false);
        }

        $parameters = collect($parameters)->prepend($command);
        $input = new StringInput($parameters->implode(' '));

        $input->setInteractive(false);

        $result = $this->run($input, $this->lastOutput);

        $this->setCatchExceptions(true);

        return $result;
    }

    /**
     * Request.
     *
     * @return \Illuminate\Http\Request
     */
    protected function request()
    {
        return $this->laravel['request'] ?: Request::capture();
    }
}
