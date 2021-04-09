<?php

namespace App;

use App\Application\Factoring\FactoringRequest;
use App\Application\Factoring\Factoring;
use App\Application\Factoring\FactoringResponse;
use App\Repository\CompanyRepository;

class FactoringApplication
{
    private $currentGlobalLending = 0;
    private $companyRepository;

    public function __construct()
    {
        $this->companyRepository = new CompanyRepository();
    }

    public function requestFactoring(string $customer, string $debtor, int $factoringValue): FactoringResponse
    {
        $factoringRequest = new FactoringRequest();
        $factoringRequest->setCurrentGlobalLending($this->currentGlobalLending);
        $factoringRequest->setCustomerId($customer);
        $factoringRequest->setDebtorId($debtor);
        $factoringRequest->setFactoringValue($factoringValue);

        $factoring = new Factoring($this->companyRepository);
        $factoringResponse = $factoring->execute($factoringRequest);

        $this->currentGlobalLending = $factoringResponse->getCurrentGlobalLending();

        return $factoringResponse;

    }
}