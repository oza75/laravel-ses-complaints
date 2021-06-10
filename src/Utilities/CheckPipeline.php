<?php


namespace Oza75\LaravelSesComplaints\Utilities;


use Illuminate\Pipeline\Pipeline;
use Throwable;

class CheckPipeline extends Pipeline
{
    protected function carry()
    {
        return function ($stack, $pipe) {
            return function ($passable) use ($stack, $pipe) {
                try {
                    $name = $pipe['middleware'];
                    $options = $pipe['options'] ?? [];

                    $pipe = $this->getContainer()->make($name);

                    $carry = method_exists($pipe, $this->method)
                        ? $pipe->{$this->method}($passable, $stack, $options)
                        : $pipe($passable, $stack, $options);

                    return $this->handleCarry($carry);
                } catch (Throwable $e) {
                    return $this->handleException($passable, $e);
                }
            };
        };
    }
}
