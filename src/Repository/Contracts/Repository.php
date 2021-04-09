<?php


namespace App\Repository\Contracts;

use App\Entity\Contracts\Entity;

interface Repository
{
    public function get(string $id): ?Entity;
    public function save(Entity $entity): void;
}