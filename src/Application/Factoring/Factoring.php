<?php

namespace App\Application\Factoring;

use App\Entity\Company;
use App\Entity\Contracts\Entity;
use App\Model\AppThreeshold;
use App\Repository\CompanyRepository;
use Exception;

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

        try {
            $debtor = $this->companyRepository->get($factoringRequest->getDebtorId());
            $customer = $this->companyRepository->get($factoringRequest->getCustomerId());

            $this->checkKoalabooxLendingLimit($factoringRequest);
            $this->checkDebtorLimit($debtor, $factoringRequest);
            $this->checkifAreSubsidiariesCompanies($debtor, $customer);
            $this->CheckMontlyRevenueThreeshold($customer, $factoringRequest);

            $debtor->addLimit($factoringRequest->getFactoringValue());
            $this->companyRepository->save($debtor);
            $factoringResponse->addCurrentGlobalLending($factoringRequest->getFactoringValue());

            $factoringResponse->setIsAllowed(self::IS_ALLOWED);

            return $factoringResponse;
        } catch (Exception $e) {
            $factoringResponse->setErrorMessage($e->getMessage());
            return $factoringResponse;
        }
    }

    private function checkKoalabooxLendingLimit(FactoringRequest $factoringRequest): void
    {
        if ($factoringRequest->getCurrentGlobalLending() + $factoringRequest->getFactoringValue() >
            AppThreeshold::LEAN_LIMIT) {
            throw new Exception(
                'KoalaBoox has a global lending limit of €70,000:' . $factoringRequest->getCurrentGlobalLending()
            );
        }
    }

    private function checkDebtorLimit(Entity $debtor, FactoringRequest $factoringRequest): void
    {
        if (($debtor->getLimit() + $factoringRequest->getFactoringValue()) > AppThreeshold::DEBTOR_LIMIT) {
            throw new Exception(
                'Each debtor has a fixed limit -- €50,000 -- on the total amount they can owe to us at any time:' .
                ($debtor->getLimit() + $factoringRequest->getFactoringValue())
            );
        }
    }

    private function checkifAreSubsidiariesCompanies(Entity $debtor, Entity $customer): void
    {
        if ($debtor->getParent() === $customer->getId()) {
            throw new Exception(
                'No factoring is allowed for debts owed by subsidiaries to their parent companies'
            );
        }
    }

    private function CheckMontlyRevenueThreeshold(Entity $customer, FactoringRequest $factoringRequest): void
    {
        if ($this->MonthlyRevenueThreesHold($customer) < $factoringRequest->getFactoringValue()) {
            throw new Exception(
                'We cant provide factoring for more than 15% of a single customers average monthly revenue.'
            );
        }
    }

    private function MonthlyRevenueThreesHold(Company $company): int
    {
        return (int)(($company->getMonthlyRevenue() * AppThreeshold::MAX_PERCENT_MONTHLY_REVENUE) / 100);
    }
}