<?php

namespace App\Application\Factoring;

class FactoringResponse
{
    private $isAllowed;
    private $currentGlobalLending;
    private $error = true;


    public function isError(): bool
    {
        return $this->error;
    }

    public function setError(bool $error): void
    {
        $this->error = $error;
    }

    public function isAllowed(): bool
    {
        return $this->isAllowed;
    }

    public function setIsAllowed($isAllowed): void
    {
        $this->isAllowed = $isAllowed;
    }

    public function getCurrentGlobalLending(): int
    {
        return $this->currentGlobalLending;
    }

    public function setCurrentGlobalLending($currentGlobalLending): void
    {
        $this->currentGlobalLending = $currentGlobalLending;
    }

    public function addCurrentGlobalLending(int $value): void
    {
        $this->currentGlobalLending += $value;
    }


}