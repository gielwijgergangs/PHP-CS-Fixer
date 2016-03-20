<?php

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\CS\Fixer\Contrib;

use Symfony\CS\AbstractFixer;
use Symfony\CS\Tokenizer\Token;
use Symfony\CS\Tokenizer\Tokens;

/**
 * @author Gregor Harlan <gharlan@web.de>
 */
class ArrayKeyLookupSpacesFixer extends AbstractFixer
{
    /**
     * {@inheritdoc}
     */
    public function fix(\SplFileInfo $file, $content)
    {
        $tokens = Tokens::fromCode($content);
        for ($index=0;$index < $tokens->count();$index++) {

            if ($tokens[$index]->getContent() !== '[') {
                continue;
            }
            if ($tokens->isShortArray($index)) {
                continue;
            }
            if ($tokens[$index + 1]->isGivenKind(T_VARIABLE)) {
                $tokens->insertAt($index+1, new Token(array(T_WHITESPACE, " ")));
            }else{
                $next = $tokens->getNextNonWhitespace($index);
                if($tokens[$next]->getId() !== T_VARIABLE){
                    continue;
                }
            }
            $closeIndex = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_SQUARE_BRACE, $index);
            if ($tokens[$closeIndex - 1]->getId() !== T_WHITESPACE) {
                $tokens->insertAt($closeIndex, new Token(array(T_WHITESPACE, " ")));
            }
        }
        return $tokens->generateCode();
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return 'Array dynamic key lookup add spaces';
    }
}
