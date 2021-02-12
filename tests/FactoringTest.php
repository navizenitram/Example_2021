<?php

namespace Test;


use App\FactoringApplication;
use PHPUnit\Framework\TestCase;

class FactoringTest extends TestCase
{

    public function test_app()
    {
        // Let's imagine that FactoringApp is the outer most layer of our application, so any object construction
        // should be done inside.
        $factoringApp = new FactoringApplication();
        $factoringApp->requestFactoring('acme', 'skynet', 12000);//OK
        $factoringApp->requestFactoring('acme', 'skynet', 13000);//Should fail, greater than 15% of acme's monthly revenue
        $factoringApp->requestFactoring('wayne', 'wonka', 50000);//OK
        $factoringApp->requestFactoring('wayne', 'wonka', 50000);//Should fail, wonka has now surpassed the 50,000 euro debtor limit
        $factoringApp->requestFactoring('acme', 'wayne', 1);//Should fail, wayne is a subsidiary of acme
    }
}