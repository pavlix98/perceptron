<?php

namespace Pavlix\Perceptron;

use InvalidArgumentException;

class Perceptron
{
    /**
     * Vector length
     *
     * @var int
     */
    private $length;

    /**
     * Perceptron weights
     * array of float
     *
     * @var array
     */
    private $weights;

    /**
     * @var float
     */
    private $learningCoeficient = 0.1;

    public function __construct(int $length)
    {
        $this->length = $length;

        for ($i = 0; $i < $length; $i++) {
            //set random value in interval <-1, 1> for every weight
            $this->weights[] = mt_rand(-mt_getrandmax(), mt_getrandmax()) / mt_getrandmax();
        }
    }

	/**
	 * @param array $xVector
	 * @return int
	 */
    public function guess(array $xVector): int
    {
        //normalize array keys
        $xVector = array_values($xVector);

        //check, if input vector is ok
        $this->validateVector($xVector);

        $sum = 0;
        foreach ($xVector as $key => $value) {
            $sum += $value * $this->weights[$key];
        }

        return $this->sign($sum);
    }


	/**
	 * @param array $xVector
	 * @param $result
	 */
    public function train(array $xVector, $result): void
    {
        //count guess of perceptron
        $guess = $this->guess($xVector);

        $error = $result - $guess;

        //weights modification
        foreach ($this->weights as $key => &$weight){
            $weight += $xVector[$key] * $error * $this->learningCoeficient;
        }
        unset($weight);
    }


    /**
     * Returns 1 if float is bigger than zero, -1 otherwise
     *
     * @param float $value
     * @return int
     */
    private function sign(float $value): int
    {
        if($value > 0) {
            return 1;
        }

        return -1;
    }


	/**
	 * Check, if vector has valid length and if items of vector has valid data types
	 *
	 * @param array $xVector
	 */
    private function validateVector(array $xVector): void
    {
        // check, if vector has right length
        if(!count($xVector) === $this->length) {
            throw new InvalidArgumentException('Expected length of vector is ' . $this->length);
        }

        // check, if type of values in vector is correct
        foreach ($xVector as $value) {
            if(!in_array(gettype($value), ['integer', 'double'])) {
                throw new InvalidArgumentException('All values in vector has to bee numerical');
            }
        }
    }

}
