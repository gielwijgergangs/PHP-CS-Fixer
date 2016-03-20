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
 * @author Giel Wijgergangs (gielwijgergangs@gmail.com)
 */
class NewlineKeywordsFixer extends AbstractFixer
{
    /**
     * {@inheritdoc}
     */
    public function fix(\SplFileInfo $file, $content)
    {
        $tokens = Tokens::fromCode($content);
        $haystack = [T_ELSEIF, T_ELSE, T_FINALLY, T_CATCH];
        for ($index = 0; $index < $tokens->count(); $index++) {
            $token = $tokens[$index];
            if (in_array($token->getId(), $haystack)) {

                if ($tokens[$index - 1]->getId() === T_WHITESPACE) {
                    $idx_close = $index - 2;
                } else {
                    $idx_close = $index - 1;
                }
                if ($tokens[$idx_close]->getContent() === '}' && $tokens[$idx_close - 1]->getId() === T_WHITESPACE) {
                    $content_indention = $tokens[$idx_close - 1]->getContent();
                    $strlpos = strrpos(str_replace(["\r\n", "\r"], "\n", $content_indention), "\n");
                    if ($strlpos !== false) {
                        if ($tokens[$index - 1]->getId() === T_WHITESPACE) {
                            $tokens[$index - 1]->setContent("\n" . substr($content_indention, $strlpos + 1));
                        } else {
                            $tokens->insertAt($index, new Token(array(T_WHITESPACE, "\n" . substr($content_indention, $strlpos + 1))));
                            $index++;
                        }
                    }
                }
            }
        }
        return $tokens->generateCode();
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return 'There should be a newline before finally, catch, else and else if';
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        // should ran last
        return -100;
    }
}
