<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Utils;

/**
 * This class is used to provide an example of integrating simple classes as
 * services into a Symfony application.
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class Slugger
{
    public function slugify($string)
    {
        $string = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower();', $string);

        return trim(preg_replace('/[^a-z0-9]+/', '-', strip_tags($string)), '-');
    }
}
