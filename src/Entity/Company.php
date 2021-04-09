<?php

namespace App\Entity;

use App\Entity\Contracts\Entity;

class Company implements Entity
{
    private $name;
    private $id;
    private $pastYearRevenue;
    private $parent;
    private $limit;


    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }


    public function getId(): string
    {
        return $this->id;
    }


    public function setId($id): void
    {
        $this->id = $id;
    }


    public function getPastYearRevenue(): int
    {
        return $this->pastYearRevenue;
    }


    public function setPastYearRevenue($pastYearRevenue): void
    {
        $this->pastYearRevenue = $pastYearRevenue;
    }


    public function getParent(): ?string
    {
        return $this->parent;
    }


    public function setParent($parent): void
    {
        $this->parent = $parent;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit($limit): void
    {
        $this->limit = $limit;
    }

    public function getMonthlyRevenue(): int
    {
        return (int)($this->getPastYearRevenue() / 12);
    }

    public function addLimit($value) {
        $this->limit += $value;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }


}