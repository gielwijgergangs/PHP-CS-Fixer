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
final class NewlineKeywordsFixerTest extends AbstractFixerTestBase
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
                '<?php if($test){

            }
            else {

            }',
            '<?php if($test){

            }else {

            }'),
            array(
                '<?php if($test){
                $foo = true;
            }
            elseif {

            }',
                '<?php if($test){
                $foo = true;
            } elseif {

            }'),
            array(
                '<?php try {
                $foo = true;
            }
            catch(Exception $e) {

            }',
                '<?php try {
                $foo = true;
            } catch(Exception $e) {

            }',),
            array(
                '<?php try {
                $foo = true;
            }
            catch(Exception $e) {

            }
            finally{

            }',
                '<?php try {
                $foo = true;
            } catch(Exception $e) {

            }finally{

            }',)
        );
    }
}