<?php

declare(strict_types=1);

namespace Cortex\Console\Console\Commands;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\StringInput;
use Illuminate\Contracts\Console\Kernel as ArtisanContract;

class Artisan extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'artisan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Laravel Artisan';

    /**
     * no support array.
     *
     * @var array
     */
    protected $notSupport = [
        'down' => '',
        'tinker' => '',
    ];

    /**
     * $artisan.
     *
     * @var \Illuminate\Contracts\Console\Kernel
     */
    protected $artisan;

    /**
     * __construct.
     *
     * @param \Illuminate\Contracts\Console\Kernel $artisan
     */
    public function __construct(ArtisanContract $artisan)
    {
        parent::__construct();

        $this->artisan = $artisan;
    }

    /**
     * handle.
     *
     * @throws \InvalidArgumentException
     */
    public function handle()
    {
        $command = $this->forceCommand($this->option('command'));

        $input = new StringInput($command);

        $input->setInteractive(false);

        if (isset($this->notSupport[$input->getFirstArgument()]) === true) {
            throw new InvalidArgumentException('Command "'.$command.'" is not supported');
        }

        $this->artisan->handle($input, $this->getOutput());
    }

    /**
     * need focre option.
     *
     * @param string $command
     *
     * @return string
     */
    protected function forceCommand($command): string
    {
        return (
            (Str::startsWith($command, 'migrate') === true && Str::startsWith($command, 'migrate:status') === false) ||
            Str::startsWith($command, 'db:seed') === true
        ) && mb_strpos('command', '--force') === false ?
            $command .= ' --force' : $command;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['command', null, InputOption::VALUE_OPTIONAL],
        ];
    }
}
