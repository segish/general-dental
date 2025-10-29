<?php

namespace App\Services;

use App\Models\TestAttributeReference;

class ReferenceEvaluatorService
{
    /**
     * Evaluate a test result against all references.
     *
     * @param int $attributeId
     * @param mixed $resultValue (e.g., 98, ">100.0 ng/mL↑", "Yellow", etc.)
     * @param string|null $gender
     * @param int|null $age
     * @param bool $isPregnant
     * @return string|null 'NORMAL', 'HIGH', 'LOW', 'ABNORMAL', or null
     */
    public function evaluate($attributeId, $resultValue, $gender = null, $age = null, $isPregnant = false)
    {
        $references = TestAttributeReference::where('test_attribute_id', $attributeId)->get();

        if ($references->isEmpty()) {
            return null;
        }

        $statusResults = [];

        foreach ($references as $ref) {
            // Skip if reference has gender and it doesn't match
            if ($ref->gender && $ref->gender !== $gender) {
                continue;
            }
            // Skip if pregnancy mismatch
            if (!is_null($ref->is_pregnant) && (bool)$ref->is_pregnant !== (bool)$isPregnant) {
                continue;
            }
            // Skip if age mismatch
            if (!is_null($age)) {
                if (!is_null($ref->min_age) && $age < $ref->min_age) continue;
                if (!is_null($ref->max_age) && $age > $ref->max_age) continue;
            }

            // Case 1: Qualitative reference check
            if ($ref->reference_text !== null) {
                $validValues = array_map('trim', explode(',', strtolower($ref->reference_text)));
                if (in_array(strtolower(trim($resultValue)), $validValues)) {
                    $statusResults[] = 'NORMAL';
                    continue; // no need to check further for this ref
                } else {
                    $statusResults[] = 'ABNORMAL';
                    continue;
                }
            }

            // Case 2: Quantitative check
            $parsed = $this->parseNumericResult($resultValue);
            if (!is_null($parsed)) {
                $value = $parsed['value'];
                $symbol = $parsed['symbol'];
                $min = $ref->lower_limit;
                $max = $ref->upper_limit;

                if (!is_null($min) && ($value < $min || ($symbol === '<' && $value == $min))) {
                    $statusResults[] = 'LOW';
                    continue;
                }
                if (!is_null($max) && ($value > $max || ($symbol === '>' && $value == $max))) {
                    $statusResults[] = 'HIGH';
                    continue;
                }
                $statusResults[] = 'NORMAL';
            }
        }

        if (empty($statusResults)) {
            return null; // no applicable references matched context
        }

        // If ANY reference marks it as NORMAL, we treat as NORMAL
        if (in_array('NORMAL', $statusResults)) {
            return 'NORMAL';
        }

        // Otherwise return the first abnormal/high/low found
        // (you could make this logic more sophisticated if needed)
        return $statusResults[0];
    }

    /**
     * Extract numeric value and symbol from messy result like ">100 ng/mL↑"
     *
     * @param mixed $rawValue
     * @return array|null ['value' => float, 'symbol' => '>' | '<' | null]
     */
    protected function parseNumericResult($rawValue)
    {
        if (!is_string($rawValue)) {
            return is_numeric($rawValue)
                ? ['value' => floatval($rawValue), 'symbol' => null]
                : null;
        }

        $raw = trim($rawValue);
        $symbol = null;

        if (strpos($raw, '>') !== false) {
            $symbol = '>';
        } elseif (strpos($raw, '<') !== false) {
            $symbol = '<';
        }

        // Extract numeric value
        if (preg_match('/([-+]?[0-9]*\.?[0-9]+)/', $raw, $matches)) {
            return [
                'value' => floatval($matches[1]),
                'symbol' => $symbol
            ];
        }

        return null;
    }
}
 
