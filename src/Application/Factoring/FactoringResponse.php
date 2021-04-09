<?php

namespace App\Application\Factoring;

class FactoringResponse
{
    private $isAllowed;
    private $currentGlobalLending;
    private $errorMessage = '';


    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }


    public function setErrorMessage($errorMessage): void
    {
        $this->errorMessage = $errorMessage;
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