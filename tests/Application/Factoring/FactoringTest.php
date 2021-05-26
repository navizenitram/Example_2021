<?php

namespace App\Tests\Application\Factoring;

use App\Application\Factoring\Exceptions\GlobalLendingException;
use App\Application\Factoring\Factoring;
use App\Application\Factoring\FactoringRequest;
use App\Application\Factoring\FactoringResponse;
use App\Entity\Company;
use App\Repository\CompanyRepository;
use PHPUnit\Framework\TestCase;

class FactoringTest extends TestCase
{
    private $currentGlobalLending = 0;
    private $companyRepository;



    public function testLimitGlobalLending()
    {

        $customer = 'stark';
        $debtor   = 'oceanic';
        $factoringValue = 35000;

        $response = $this->factoring($customer, $debtor, $factoringValue);
        $this->assertTrue($response->isAllowed());

        $customer = 'trip';
        $debtor   = 'home';
        $factoringValue = 40000;

        $response = $this->factoring($customer, $debtor, $factoringValue);
        $this->expectException(GlobalLendingException::class);
    }

    private function factoring(string $customer, string $debtor, int $factoringValue): FactoringResponse
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

    protected function setUp():void {

        parent::setUp();

        $this->currentGlobalLending = 0;
        $this->companyRepository = new CompanyRepository();

        $customer = new Company();
        $customer->setId('stark');
        $customer->setName('Stark Industries');
        $customer->setPastYearRevenue(90000000000);
        $customer->setParent(null);
        $customer->setLimit(0);

        $debtor = new Company();
        $debtor->setId('oceanic');
        $debtor->setName('Oceanic Airlines');
        $debtor->setPastYearRevenue(90000000000);
        $debtor->setParent(null);
        $debtor->setLimit(0);


        $this->companyRepository->save($customer);
        $this->companyRepository->save($debtor);

        $customer = new Company();
        $customer->setId('trip');
        $customer->setName('Trip');
        $customer->setPastYearRevenue(90000000000);
        $customer->setParent(null);
        $customer->setLimit(0);

        $debtor = new Company();
        $debtor->setId('home');
        $debtor->setName('Home Away');
        $debtor->setPastYearRevenue(90000000000);
        $debtor->setParent(null);
        $debtor->setLimit(0);


        $this->companyRepository->save($customer);
        $this->companyRepository->save($debtor);


    }



}
