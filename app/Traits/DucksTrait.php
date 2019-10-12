<?php

namespace App\Traits;
use App\Ducks;

/**
 * Trait DucksTrait
 *
 * This trait stores the modifiers needed to handle the ducks stats as well as the min and max values
 */
trait DucksTrait
{
    public function illnessModifiers() {
        return [
            'happyness' => ['add' => 1, 'subtract' => 2],
            'weight' => ['add' => .1, 'subtract' => .2],
            'hunger' => ['add' => 1, 'subtract' => 2],
        ];
    }

    public function healthyModifiers() {
        return [
            'happyness' => ['add' => 2, 'subtract' => 1],
            'weight' => ['add' => .2, 'subtract' => .1],
            'hunger' => ['add' => 2, 'subtract' => 1],
        ];
    }

    public function min() {
        return [
            'health' => 0,
            'hunger' => 0,
            'weight' => ['healthy' => '3.2', 'unhealthy' => 0.0],
            'happyness' => 0
        ];
    }

    public function max() {
        return [
            'health' => 10,
            'hunger' => 10,
            'weight' => 5.5,
            'happyness' => 10
        ];
    }
}

?>