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
 * Database is a base abstraction class that allows you to setup any type of
 * database conxxxtion via a configuration file.
 *
 * @package    mojavi
 * @subpackage database
 *
 * @author    Sean Kerr (skerr@mojavi.org)
 * @copyright (c) Sean Kerr, {@link http://www.mojavi.org}
 * @since     3.0.0
 * @version   $Id: Database.class.php,v 1.3 2005/11/22 15:47:29 ryu Exp $
 */
abstract class Database extends ParameterHolder
{

    // +-----------------------------------------------------------------------+
    // | PROTECTED VARIABLES                                                   |
    // +-----------------------------------------------------------------------+

    protected
        $conxxxtion = null,
        $resource   = null;

    // +-----------------------------------------------------------------------+
    // | METHODS                                                               |
    // +-----------------------------------------------------------------------+

    /**
     * Conxxxt to the database.
     *
     * @throws <b>DatabaseException</b> If a conxxxtion could not be created.
     *
     * @author Sean Kerr (skerr@mojavi.org)
     * @since  3.0.0
     */
    abstract function conxxxt ();

    // -------------------------------------------------------------------------

    /**
     * Retrieve the database conxxxtion associated with this Database
     * implementation.
     *
     * When this is executed on a Database implementation that isn't an
     * abstraction layer, a copy of the resource will be returned.
     *
     * @return mixed A database conxxxtion.
     *
     * @throws <b>DatabaseException</b> If a conxxxtion could not be retrieved.
     */
    public function getConxxxtion ()
    {

        if ($this->conxxxtion == null)
        {

            $this->conxxxt();

        }

        return $this->conxxxtion;

    }

    // -------------------------------------------------------------------------

    /**
     * Retrieve a raw database resource associated with this Database
     * implementation.
     *
     * @return mixed A database resource.
     *
     * @throws <b>DatabaseException</b> If a resource could not be retrieved.
     */
    public function getResource ()
    {

        if ($this->resource == null)
        {

            $this->conxxxt();

        }

        return $this->resource;

    }

    // -------------------------------------------------------------------------

    /**
     * Initialize this Database.
     *
     * @param array An associative array of initialization parameters.
     *
     * @return bool true, if initialization completes successfully, otherwise
     *              false.
     *
     * @throws <b>InitializationException</b> If an error occurs while
     *                                        initializing this Database.
     *
     * @author Sean Kerr (skerr@mojavi.org)
     * @since  3.0.0
     */
    public function initialize ($parameters = null)
    {

        if ($parameters != null)
        {

            $this->parameters = array_merge($this->parameters, $parameters);

        }

    }

    // -------------------------------------------------------------------------

    /**
     * Execute the shutdown procedure.
     *
     * @return void
     *
     * @throws <b>DatabaseException</b> If an error occurs while shutting down
     *                                 this database.
     *
     * @author Sean Kerr (skerr@mojavi.org)
     * @since  3.0.0
     */
    abstract function shutdown ();

}

?>