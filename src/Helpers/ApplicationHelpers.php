<?php

namespace Illuminated\Testing\Helpers;

trait ApplicationHelpers
{
    protected function isTravis()
    {
        return (bool) getenv('TRAVIS');
    }

    protected function emulateLocal()
    {
        $this->emulateEnvironment('local');
    }

    protected function emulateProduction()
    {
        $this->emulateEnvironment('production');
    }

    protected function emulateEnvironment($environment)
    {
        $this->app->detectEnvironment(function () use ($environment) {
            return $environment;
        });
    }
}
