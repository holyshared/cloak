<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace cloak\value;

use cloak\result\LineCountResult;


/***
 * Class Coverage
 * @package cloak\value
 */
class Coverage
{

    /**
     * @var float
     */
    private $value;


    /**
     * @param float $value
     */
    public function __construct($value)
    {
        $this->value = (float) $value;
    }

    /**
     * @param Coverage $value
     * @return bool
     */
    public function equals(Coverage $value)
    {
        return ($this->value() === $value->value());
    }

    /**
     * @param Coverage $value
     * @return bool
     */
    public function lessEquals(Coverage $value)
    {
        return $this->value() <= $value->value();
    }

    /**
     * @param Coverage $value
     * @return bool
     */
    public function lessThan(Coverage $value)
    {
        return $this->value() < $value->value();
    }

    /**
     * @param Coverage $value
     * @return bool
     */
    public function greaterEqual(Coverage $value)
    {
        return $this->value() >= $value->value();
    }

    /**
     * @param Coverage $value
     * @return bool
     */
    public function greaterThan(Coverage $value)
    {
        return $this->value() > $value->value();
    }

    /**
     * @return float
     */
    public function value()
    {
        return (float) $this->value;
    }

    /**
     * @return string
     */
    public function formattedValue()
    {
        return sprintf('%6.2f%%', $this->value());
    }

    /**
     * @param LineCountResult $result
     * @return \cloak\value\Coverage
     */
    public static function fromLineResult(LineCountResult $result)
    {
        $value = 0.0;
        $executedLineCount = $result->getExecutedLineCount();
        $executableLineCount = $result->getExecutableLineCount();

        //PHP Warning:  Division by zero in ....
        if ($executedLineCount <= 0 || $executableLineCount <= 0) {
            return new self($value);
        }

        $realCoverage = ($executedLineCount / $executableLineCount) * 100;
        $coverage = (float) round($realCoverage, 2);

        return new self($coverage);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->value;
    }

}
