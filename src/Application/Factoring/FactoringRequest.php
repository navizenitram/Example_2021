<?php

namespace App\Application\Factoring;

class FactoringRequest
{
    private $customerId;
    private $debtorId;
    private $factoringValue;
    private $currentGlobalLending;

    public function getCurrentGlobalLending(): int
    {
        return $this->currentGlobalLending;
    }


    public function setCurrentGlobalLending($currentGlobalLending): void
    {
        $this->currentGlobalLending = $currentGlobalLending;
    }


    public function getCustomerId(): string
    {
        return $this->customerId;
    }


    public function setCustomerId($customerId): void
    {
        $this->customerId = $customerId;
    }


    public function getDebtorId(): string
    {
        return $this->debtorId;
    }


    public function setDebtorId($debtorId): void
    {
        $this->debtorId = $debtorId;
    }


    public function getFactoringValue():int
    {
        return $this->factoringValue;
    }


    public function setFactoringValue($factoringValue): void
    {
        $this->factoringValue = $factoringValue;
    }


}