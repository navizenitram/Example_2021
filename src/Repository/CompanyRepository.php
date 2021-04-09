<?php

namespace App\Repository;

use App\Entity\Company;
use App\Entity\Contracts\Entity;


class CompanyRepository implements Contracts\Repository
{
    private $data =
        [

            'acme'=>[
                'Name' => 'Acme Corp',
                'Id' => 'acme',
                'PastYearRevenue' => 1000000,
                'Parent' => NULL,
                'Limit' => 0,
            ],
            'wonka'=>[
                'Name' => 'Wonka Industries',
                'Id' => 'wonka',
                'PastYearRevenue' => 1500000,
                'Parent' => NULL,
                'Limit' => 0,
            ],
            'skynet'=>[
                'Name' => 'Sky Net',
                'Id' => 'skynet',
                'PastYearRevenue' => 1500000,
                'Parent' => NULL,
                'Limit' => 0,
            ],
            'wayne'=>[
                'Name' => 'Wayne Enterprises',
                'Id' => 'wayne',
                'PastYearRevenue' => 10000000,
                'Parent' => 'acme',
                'Limit' => 0,
            ],

        ];



    public function get(string $id): ?Entity
    {
        if(isset($this->data[$id])) {
            $company = new Company();
            foreach ($this->data[$id] as $key=>$value) {
                $company->{'set'.$key}($value);
            }

            return $company;
        }

        return null;
    }

    public function save(Entity $entity): void
    {
        $this->data[$entity->getId()] = $entity->toArray();
    }
}