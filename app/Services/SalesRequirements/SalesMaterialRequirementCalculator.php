<?php

namespace App\Services\SalesRequirements;

class SalesMaterialRequirementCalculator
{
    /** @var MaterialRequirementRule[] */
    private array $rules;

    public function __construct()
    {
        // Instance rule dipakai polimorfik via method apply() yang sama.
        $this->rules = [
            new SusuRequirementRule(),
            new GulaRequirementRule(),
            new PowderRequirementRule(),
        ];
    }

    public function calculate(iterable $salesRows): array
    {
        $needs = [];

        foreach ($salesRows as $row) {
            foreach ($this->rules as $rule) {
                $rule->apply($row, $needs);
            }
        }

        foreach ($needs as $id => $qty) {
            $needs[$id] = round((float) $qty, 2);
        }

        return $needs;
    }
}
