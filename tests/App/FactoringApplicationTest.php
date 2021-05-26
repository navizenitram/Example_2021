<?php
namespace App\Tests;

use App\Application\Factoring\Exceptions\GlobalLendingException;
use App\Application\Factoring\Exceptions\MontlyRevenueException;
use App\Application\Factoring\Exceptions\SubsidiariesCompaniesException;
use App\FactoringApplication;
use PHPUnit\Framework\TestCase;

class FactoringApplicationTest extends TestCase
{

    public function test_app()
    {
        // Let's imagine that FactoringApp is the outer most layer of our application, so any object construction
        // should be done inside.
        $factoringApp = new FactoringApplication();
        $response = $factoringApp->requestFactoring('acme', 'skynet', 12000);
        $this->assertTrue($response->isAllowed());

        $response = $factoringApp->requestFactoring('acme', 'skynet', 13000);
        $this->expectException(MontlyRevenueException::class);

        $response = $factoringApp->requestFactoring('wayne', 'wonka', 50000);
        $this->assertTrue($response->isAllowed());

        $response = $factoringApp->requestFactoring('wayne', 'wonka', 50000);
        $this->expectException(GlobalLendingException::class);

        $response= $factoringApp->requestFactoring('acme', 'wayne', 1);
        $this->expectException(SubsidiariesCompaniesException::class);

    }

}