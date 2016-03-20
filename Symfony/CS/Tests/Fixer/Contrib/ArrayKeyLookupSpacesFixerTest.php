<?php

/*
 * This file is part of the PHP CS utility.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\CS\Tests\Fixer\Contrib;

use Symfony\CS\Tests\Fixer\AbstractFixerTestBase;

/**
 * @author Giel Wijgergangs (gielwijgergangs@gmail.com)
 *
 * @internal
 */
final class ArrayKeyLookupSpacesFixerTest extends AbstractFixerTestBase
{
    /**
     * @dataProvider provideFixCases
     */
    public function testFix($expected, $input = null)
    {
        $this->makeTest($expected, $input);
    }

    public function provideFixCases()
    {
        return array(

            array(
                '<?php
                $lookup = array();
                $test = $lookup[ $var["index1"] ][ $var["index2"] ];',
                '<?php
                $lookup = array();
                $test = $lookup[ $var["index1"]][$var["index2"] ];',
            ),
            array(
                '<?php
                $lookup = array();
                $test = $lookup[ $var ][ $var1 ];',
                '<?php
                $lookup = array();
                $test = $lookup[$var][$var1];',
                ),
            array(
                '<?php
                $lookup = array();
                $test = $lookup[ $var[0] ][ $var[1] ];',
                '<?php
                $lookup = array();
                $test = $lookup[$var[0]][$var[1]];',
            ),
            array(
                '<?php
                $lookup = array();
                $test = $lookup[ $var[ $key["next"] ] ][ $var[ $object->var ] ];',
                '<?php
                $lookup = array();
                $test = $lookup[ $var[$key["next"]] ][ $var[ $object->var] ];',
            ),
        );
    }
}