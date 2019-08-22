<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\ServeCommand;
use Symfony\Component\Console\Input\InputOption;


class myServerCmd extends ServeCommand
{
    protected function getOptions()
    {
        return [
            ['host', null, InputOption::VALUE_OPTIONAL, 'The host address to serve the application on.', '0.0.0.0'],
            ['port', null, InputOption::VALUE_OPTIONAL, 'The port to serve the application on.', 2000],
        ];
    }   
}
