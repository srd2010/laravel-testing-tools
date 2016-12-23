<?php

namespace Illuminated\Testing\Asserts;

use Illuminate\Support\Facades\File;
use Mockery;

trait ArtisanAsserts
{
    protected function willSeeConfirmation($question, $command, array $parameters = [])
    {
        $mock = Mockery::mock("{$command}[confirm]");
        $mock->shouldReceive('confirm')->once()->with($question);

        $this->runArtisan($mock, $parameters);
    }

    protected function willNotSeeConfirmation($question, $command, array $parameters = [])
    {
        $mock = Mockery::mock("{$command}[confirm]");
        $mock->shouldNotReceive('confirm')->with($question);

        $this->runArtisan($mock, $parameters);
    }

    protected function willGiveConfirmation($question, $command, array $parameters = [])
    {
        $mock = Mockery::mock("{$command}[confirm]");
        $mock->shouldReceive('confirm')->once()->with($question)->andReturn(true);

        $this->runArtisan($mock, $parameters);
    }

    protected function willNotGiveConfirmation($question, $command, array $parameters = [])
    {
        $mock = Mockery::mock("{$command}[confirm]");
        $mock->shouldReceive('confirm')->once()->with($question)->andReturn(false);

        $this->runArtisan($mock, $parameters);
    }

    protected function seeArtisanOutput($output)
    {
        if (File::exists($output)) {
            $output = File::get($output);
        }

        $expected = trim($output);
        $actual = trim($this->getArtisanOutput());
        $this->assertEquals($expected, $actual, "Failed asserting that artisan output is `{$expected}`.");
    }

    protected function dontSeeArtisanOutput($output)
    {
        if (File::exists($output)) {
            $output = File::get($output);
        }

        $expected = trim($output);
        $actual = trim($this->getArtisanOutput());
        $this->assertNotEquals($expected, $actual, "Failed asserting that artisan output is not `{$expected}`.");
    }

    protected function seeArtisanTableOutput(array $data)
    {
        $message = 'Failed asserting that artisan table output consists of expected data.';
        $this->assertEquals($data, $this->parseArtisanTableOutput($this->getArtisanOutput()), $message);
    }

    protected function dontSeeArtisanTableOutput(array $data)
    {
        $message = 'Failed asserting that artisan table output not consists of expected data.';
        $this->assertNotEquals($data, $this->parseArtisanTableOutput($this->getArtisanOutput()), $message);
    }

    protected function seeArtisanTableRowsCount($count)
    {
        $message = "Failed asserting that artisan table rows count equals to `{$count}`.";
        $this->assertEquals($count, count($this->parseArtisanTableOutput($this->getArtisanOutput())), $message);
    }

    protected function dontSeeArtisanTableRowsCount($count)
    {
        $message = "Failed asserting that artisan table rows count not equals to `{$count}`.";
        $this->assertNotEquals($count, count($this->parseArtisanTableOutput($this->getArtisanOutput())), $message);
    }

    private function parseArtisanTableOutput($output)
    {
        $parsed = [];

        $headers = [];
        $outputRows = explode("\n", trim($output));
        foreach ($outputRows as $row) {
            if (!str_contains($row, '|')) {
                continue;
            }

            $row = explode('|', $row);
            $row = array_filter($row);
            $row = array_map('trim', $row);

            if (empty($headers)) {
                $headers = $row;
                continue;
            }

            $parsed[] = array_combine($headers, $row);
        }

        return $parsed;
    }
}
