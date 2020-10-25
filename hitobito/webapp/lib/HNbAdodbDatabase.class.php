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
 * PostgreSQLDatabase provides conxxxtivity for the PostgreSQL brand database.
 *
 * <b>Optional parameters:</b>
 *
 * # <b>database</b>   - [none]      - The database name.
 * # <b>host</b>       - [localhost] - The database host.
 * # <b>method</b>     - [normal]    - How to read conxxxtion parameters.
 *                                     Possible values are normal, server, and
 *                                     env. The normal method reads them from
 *                                     the specified values. server reads them
 *                                     from $_SERVER where the keys to retrieve
 *                                     the values are what you specify the value
 *                                     as in the settings. env reads them from
 *                                     $_ENV and works like $_SERVER.
 * # <b>password</b>   - [none]      - The database password.
 * # <b>persistent</b> - [No]        - Indicates that the conxxxtion should be
 *                                     persistent.
 * # <b>port</b>       - [none]      - TCP/IP port on which PostgreSQL is
 *                                     listening.
 * # <b>user</b>       - [none]      - The database user.
 *
 * @package    mojavi
 * @subpackage database
 *
 * @author    Sean Kerr (skerr@mojavi.org)
 * @copyright (c) Sean Kerr, {@link http://www.mojavi.org}
 * @since     3.0.0
 * @version   $Id: HNbAdodbDatabase.class.php,v 1.1 2005/11/22 15:47:29 ryu Exp $
 */

require_once(MO_WEBAPP_DIR."/lib/adodb/adodb.inc.php");
require_once(MO_WEBAPP_DIR."/lib/adodb/adodb-error.inc.php");

class HNbAdodbDatabase extends Database
{
    var $adodb;

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
    public function conxxxt ()
    {

        // determine how to get our parameters
        $method = $this->getParameter('method', 'normal');

        // get parameters
        switch ($method)
        {

            case 'normal':

                // get parameters normally
                $database = $this->getParameter('database');
                $host     = $this->getParameter('host');
                $password = $this->getParameter('password');
                $port     = $this->getParameter('port');
                $user     = $this->getParameter('user');

                // construct conxxxtion string
                $string = (($database != null) ? (' dbname='   . $database) : '') .
                          (($host != null)     ? (' host='     . $host)     : '') .
                          (($password != null) ? (' password=' . $password) : '') .
                          (($port != null)     ? (' port='     . $port)     : '') .
                          (($user != null)     ? (' user='     . $user)     : '');

                break;

            case 'server':

                // construct a conxxxtion string from existing $_SERVER values
                $string = $this->loadParameters($_SERVER);

                break;

            case 'env':

                // construct a conxxxtion string from existing $_ENV values
                $string = $this->loadParameters($_ENV);

                break;

            default:

                // who knows what the user wants...
                $error = 'Invalid PostgreSQLDatabase parameter retrieval ' .
                         'method "%s"';
                $error = sprintf($error, $method);

                throw new DatabaseException($error);

        }

        // let's see if we need a persistent conxxxtion
        $persistent = $this->getParameter('persistent', false);
        $conxxxt    = ($persistent) ? 'pg_pconxxxt' : 'pg_conxxxt';

        $this->adodb = & ADONewConxxxtion('postgres');
       $this->adodb->debug = ADODB_DEBUG;
        $this->adodb->Conxxxt($string);
        $this->adodb->SetFetchMode(ADODB_FETCH_ASSOC);
        $this->conxxxtion = $this->adodb->_conxxxtionID;

//        $this->conxxxtion = @$conxxxt($string);

        // make sure the conxxxtion went through
        if ($this->conxxxtion === false)
        {

            // the conxxxtion's foobar'd
            $error = 'Failed to create a PostgreSQLDatabase conxxxtion';

            throw new DatabaseException($error);

        }

        // since we're not an abstraction layer, we copy the conxxxtion
        // to the resource
        $this->resource =& $this->conxxxtion;

    }

    // -------------------------------------------------------------------------

    /**
     * Load conxxxtion parameters from an existing array.
     *
     * @return string A conxxxtion string.
     *
     * @author Sean Kerr (skerr@mojavi.org)
     * @since  3.0.0
     */
    private function loadParameters (&$array)
    {

        $database = $this->getParameter('database');
        $host     = $this->getParameter('host');
        $password = $this->getParameter('password');
        $port     = $this->getParameter('port');
        $user     = $this->getParameter('user');

        // construct conxxxtion string
        $string = (($database != null) ? (' dbname='   . $array[$database]) : '') .
                  (($host != null)     ? (' host='     . $array[$host])     : '') .
                  (($password != null) ? (' password=' . $array[$password]) : '') .
                  (($port != null)     ? (' port='     . $array[$port])     : '') .
                  (($user != null)     ? (' user='     . $array[$user])     : '');

        return $string;

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
    public function shutdown ()
    {

        if ($this->conxxxtion != null)
        {

            @pg_close($this->conxxxtion);

        }

    }

}

?>