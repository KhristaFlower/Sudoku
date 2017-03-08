<?php
/**
 * Created by PhpStorm.
 * User: kriptonic
 * Date: 08/03/2017
 * Time: 00:38
 */

namespace Kriptonic\App\Core;

/**
 * Class Response
 *
 * A response abstract class used as the basis for all responses.
 *
 * @package Kriptonic\App\Core
 * @author Christopher Sharman <christopher.p.sharman@gmail.com>
 */
abstract class Response
{
    /**
     * Handle the response.
     */
    abstract function handle();
}
