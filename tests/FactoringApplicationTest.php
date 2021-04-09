<?php

namespace Test;


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
        $this->assertTrue($response->isAllowed(), $response->getErrorMessage());

        $response = $factoringApp->requestFactoring('acme', 'skynet', 13000);
        $this->assertFalse($response->isAllowed(), $response->getErrorMessage());

        $response = $factoringApp->requestFactoring('wayne', 'wonka', 50000);
        $this->assertTrue($response->isAllowed(), $response->getErrorMessage());

        $response = $factoringApp->requestFactoring('wayne', 'wonka', 50000);
        $this->assertFalse($response->isAllowed(), $response->getErrorMessage());

        $response= $factoringApp->requestFactoring('acme', 'wayne', 1);
        $this->assertFalse($response->isAllowed(), $response->getErrorMessage());

    }

}