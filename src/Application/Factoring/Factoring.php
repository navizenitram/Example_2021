<?php

namespace App\Application\Factoring;

use App\Application\Factoring\Exceptions\DebtorLimitException;
use App\Application\Factoring\Exceptions\GlobalLendingException;
use App\Application\Factoring\Exceptions\MontlyRevenueException;
use App\Application\Factoring\Exceptions\SubsidiariesCompaniesException;
use App\Entity\Company;
use App\Entity\Contracts\Entity;
use App\Model\AppThreeshold;
use App\Repository\CompanyRepository;

class Factoring
{
    private const NOT_ALLOWED = false;
    private const IS_ALLOWED = true;

    private $companyRepository;

    public function __construct(?CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function execute(FactoringRequest $factoringRequest): FactoringResponse
    {
        $factoringResponse = new FactoringResponse();
        $factoringResponse->setIsAllowed(self::NOT_ALLOWED);
        $factoringResponse->setCurrentGlobalLending($factoringRequest->getCurrentGlobalLending());

        $debtor = $this->companyRepository->get($factoringRequest->getDebtorId());
        $customer = $this->companyRepository->get($factoringRequest->getCustomerId());

        $this->checkLendingLimit($factoringRequest);
        $this->checkDebtorLimit($debtor, $factoringRequest);
        $this->checkifAreSubsidiariesCompanies($debtor, $customer);
        $this->CheckMontlyRevenueThreeshold($customer, $factoringRequest);

        $debtor->addLimit($factoringRequest->getFactoringValue());
        $this->companyRepository->save($debtor);
        $factoringResponse->addCurrentGlobalLending($factoringRequest->getFactoringValue());

        $factoringResponse->setIsAllowed(self::IS_ALLOWED);
        $factoringResponse->setError(false);

        return $factoringResponse;
    }

    private function checkLendingLimit(FactoringRequest $factoringRequest): void
    {
        if ($factoringRequest->getCurrentGlobalLending() + $factoringRequest->getFactoringValue() >
            AppThreeshold::LEAN_LIMIT) {
            throw new GlobalLendingException();
        }
    }

    private function checkDebtorLimit(Entity $debtor, FactoringRequest $factoringRequest): void
    {
        if (($debtor->getLimit() + $factoringRequest->getFactoringValue()) > AppThreeshold::DEBTOR_LIMIT) {
            throw new DebtorLimitException();
        }
    }

    private function checkifAreSubsidiariesCompanies(Entity $debtor, Entity $customer): void
    {
        if ($debtor->getParent() === $customer->getId()) {
            throw new SubsidiariesCompaniesException();
        }
    }

    private function CheckMontlyRevenueThreeshold(Entity $customer, FactoringRequest $factoringRequest): void
    {
        if ($this->MonthlyRevenueThreesHold($customer) < $factoringRequest->getFactoringValue()) {
            throw new MontlyRevenueException();
        }
    }

    private function MonthlyRevenueThreesHold(Company $company): int
    {
        return (int)(($company->getMonthlyRevenue() * AppThreeshold::MAX_PERCENT_MONTHLY_REVENUE) / 100);
    }
}