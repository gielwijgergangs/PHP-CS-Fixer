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
 * @author Vincent Klaiber <hello@vinkla.com>
 */
class XssFixerTest extends AbstractFixerTestBase
{
    /**
     * @dataProvider provideClosingTagExamples
     * @requires PHP 5.4
     */
    public function test($expected, $input = null)
    {
        /*
         * short_echo_tag setting is ignored by HHVM
         * @see https://github.com/facebook/hhvm/issues/4809
         */
        if (!defined('HHVM_VERSION')) {
            $this->makeTest($expected, $input);
        }
    }

    public function provideClosingTagExamples()
    {
        return array(
            array('<input type="<?=xss($test)?>"/>', '<input type="<?=$test?>"/>'),
            array('<input type="<?=xss($test.$text2)?>"/>', '<input type="<?=$test.$text2?>"/>'),
            array('<?php echo \'<tr><td>\' . xss($request[\'artikelnummer\']) . \'</td></tr>\';', '<?php echo \'<tr><td>\' . $request[\'artikelnummer\'] . \'</td></tr>\';'),
        /**
        array('<input type="class-<?=xss($test)?>"/>', '<input type="class-<?=$test?>"/>'),
**/
        );
    }
}
