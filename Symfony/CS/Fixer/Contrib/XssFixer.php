<?php

/*
 * This file is part of the PHP CS utility.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\CS\Fixer\Contrib;

use Symfony\CS\AbstractFixer;
use Symfony\CS\Tokenizer\Token;
use Symfony\CS\Tokenizer\Tokens;

/**
 * @author Vincent Klaiber <hello@vinkla.com>
 */
class XssFixer extends AbstractFixer
{
    /**
     * {@inheritdoc}
     */
    public function fix(\SplFileInfo $file, $content)
    {
        if ($file->getFilename() !== 'AbstractFixerTestBase.php' && stripos($file->getPathname(), 'views/') === false) {
            return $content;
        }
        $tokens = Tokens::fromCode($content);
        $i = count($tokens);

        for ($i = 0; $i < count($tokens); ++$i) {

            $token = $tokens[$i];
            if (!isset($tokens[$i])) {
                break;
            }
            //echo $token->getName()."-".$tokens->getNextMeaningfulToken($i) ."\n";

            if ($token->isGivenKind(T_INLINE_HTML)) {
                $next = $tokens->getNextMeaningfulToken($i);
                $next_to_echo = $tokens->getNextMeaningfulToken($next);
                if (isset($tokens[$next]) && $tokens[$next]->isGivenKind(T_OPEN_TAG_WITH_ECHO) && isset($tokens[$next_to_echo]) && $tokens[$next_to_echo]->isGivenKind(T_VARIABLE)) {
                    $tokens->insertAt($next_to_echo, new Token('('));
                    $tokens->insertAt($next_to_echo, new Token(array(T_STRING, 'xss')));
                    $close = $tokens->getNextTokenOfKind($next_to_echo, array(';', array(T_CLOSE_TAG)));
                    $tokens->insertAt($close,new Token(')'));
                }
                continue;
            }
            if($token->isGivenKind(T_ECHO)){
                echo "BAM!";
                $i_next = $tokens->getNextMeaningfulToken($i);
                $endTokenIndex = $tokens->getNextTokenOfKind($i_next, array(';', array(T_CLOSE_TAG)));
                $canBeConverted = true;
                for ($n = $i_next; $n < $endTokenIndex; ++$n) {

                    if($tokens[$i]->isGivenKind(T_VARIABLE) || $tokens[$i]->isGivenKind(T_STRING) ){
                        if($tokens[$i]->isGivenKind(T_STRING) && $tokens[$i]->getContent() === 'xss'){
                            continue;
                        }

                    }
                    if ($tokens[$i]->equalsAny(array('(', '['))) {
                        $blockType = $tokens->detectBlockType($tokens[$i]);
                        $i = $tokens->findBlockEnd($blockType['type'], $i);
                    }

                }



                echo $tokens[$i]->getName()."\n\n";
                echo $tokens[$i+1]->getName()."\n\n";
                echo $tokens[$i+2]->getName()."\n\n";
                //if($tokens[$i_next]->isGivenKind())

            }
        }

        return $tokens->generateCode();
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return 'Replace short-echo <?= with long format <?php echo syntax.';
    }
}
