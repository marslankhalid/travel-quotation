<?php

namespace App\Services;

use Carbon\Carbon;
use Exception;

class QuotationService
{
    /**
     * @throws Exception
     */
    public function calculate(array $data): array
    {
        $ages = explode(',', $data['age']);
        $start = Carbon::parse($data['start_date']);
        $end = Carbon::parse($data['end_date']);
        $days = $start->diffInDays($end) + 1;

        $total = 0;
        foreach ($ages as $age) {
            $age = (int) trim($age);
            $load = $this->getAgeLoad($age);
            $total += 3 * $load * $days;
        }

        return [
            'quotation_id' => rand(1000, 9999),
            'currency_id' => $data['currency_id'],
            'total' => round($total, 2),
        ];
    }

    /**
     * @throws Exception
     */
    private function getAgeLoad(int $age): float
    {
        return match (true) {
            $age >= 18 && $age <= 30 => 0.6,
            $age >= 31 && $age <= 40 => 0.7,
            $age >= 41 && $age <= 50 => 0.8,
            $age >= 51 && $age <= 60 => 0.9,
            $age >= 61 && $age <= 70 => 1.0,
            default => throw new Exception("Age $age is out of supported range (18–70)"),
        };
    }
}
