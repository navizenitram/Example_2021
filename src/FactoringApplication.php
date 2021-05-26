<?php

namespace App;

use App\Application\Factoring\Exceptions\DebtorLimitException;
use App\Application\Factoring\Exceptions\GlobalLendingException;
use App\Application\Factoring\Exceptions\MontlyRevenueException;
use App\Application\Factoring\Exceptions\SubsidiariesCompaniesException;
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
        $factoringResponse = new FactoringResponse();
        $factoringRequest = new FactoringRequest();
        $factoringRequest->setCurrentGlobalLending($this->currentGlobalLending);
        $factoringRequest->setCustomerId($customer);
        $factoringRequest->setDebtorId($debtor);
        $factoringRequest->setFactoringValue($factoringValue);
        try {
            $factoring = new Factoring($this->companyRepository);
            $factoringResponse = $factoring->execute($factoringRequest);
            $this->currentGlobalLending = $factoringResponse->getCurrentGlobalLending();

        } catch (DebtorLimitException $e) {
            //TODO: Implement logger
        } catch (GlobalLendingException $e) {
            //TODO: Implement logger
        } catch (MontlyRevenueException $e) {
            //TODO: Implement logger
        } catch (SubsidiariesCompaniesException $e) {
            //TODO: Implement logger
        } finally {
            return $factoringResponse;
        }






    }
}