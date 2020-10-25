<?php

// +---------------------------------------------------------------------------+
// | This file is part of the Mojavi package.                                  |
// | Copyright (c) 2003, 2004 Sean Kerr.                                       |
// |                                                                           |
// | For the full copyright and license information, please view the LICENSE   |
// | file that was distributed with this source code. You can also view the    |
// | LICENSE file online at http://www.mojavi.org.                             |
// +---------------------------------------------------------------------------+

/**
 * DateValidator verifies a parameter is of a date format.
 *
 * @package    mojavi
 * @subpackage validator
 *
 * @author    Sean Kerr (skerr@mojavi.org)
 * @copyright (c) Sean Kerr, {@link http://www.mojavi.org}
 * @since     3.0.0
 * @version   $Id: DateValidator.class.php,v 1.2 2005/11/22 15:47:29 ryu Exp $
 */
class DateValidator extends Validator
{

    // +-----------------------------------------------------------------------+
    // | METHODS                                                               |
    // +-----------------------------------------------------------------------+

    /**
     * Execute this validator.
     *
     * @param mixed A file or parameter value/array.
     * @param error An error message reference.
     *
     * @return bool true, if this validator executes successfully, otherwise
     *              false.
     *
     * @author Sean Kerr (skerr@mojavi.org)
     * @since  3.0.0
     */
    public function execute (&$value, &$error)
    {

    }

}

?>