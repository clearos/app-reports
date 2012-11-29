<?php

/**
 * Report engine class.
 *
 * @category   Apps
 * @package    Reports
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2012 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/reports/
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU Lesser General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Lesser General Public License for more details.
//
// You should have received a copy of the GNU Lesser General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// N A M E S P A C E
///////////////////////////////////////////////////////////////////////////////

namespace clearos\apps\reports;

///////////////////////////////////////////////////////////////////////////////
// B O O T S T R A P
///////////////////////////////////////////////////////////////////////////////

$bootstrap = getenv('CLEAROS_BOOTSTRAP') ? getenv('CLEAROS_BOOTSTRAP') : '/usr/clearos/framework/shared';
require_once $bootstrap . '/bootstrap.php';

///////////////////////////////////////////////////////////////////////////////
// T R A N S L A T I O N S
///////////////////////////////////////////////////////////////////////////////

clearos_load_language('reports');

///////////////////////////////////////////////////////////////////////////////
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

use \clearos\apps\base\Engine as Engine;

clearos_load_library('base/Engine');

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Report engine class.
 *
 * @category   Apps
 * @package    Reports
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2012 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/reports/
 */

class Report_Engine extends Engine
{
    ///////////////////////////////////////////////////////////////////////////////
    // C O N S T A N T S
    ///////////////////////////////////////////////////////////////////////////////

    const RANGE_TODAY = 'today';
    const RANGE_YESTERDAY = 'yesterday';
    const RANGE_LAST_7_DAYS = 'last7';
    const RANGE_LAST_30_DAYS = 'last30';

    const INTERVAL_HOURLY = 'hourly';
    const INTERVAL_DAILY = 'daily';
    const INTERVAL_WEEKLY = 'weekly';
    const INTERVAL_MONTHLY = 'monthly';

    ///////////////////////////////////////////////////////////////////////////////
    // M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Report engine constructor.
     */

    public function __construct()
    {
        clearos_profile(__METHOD__, __LINE__);
    }

    /**
     * Returns report information for a given report.
     *
     * Every report class defines the details of a report via a hash 
     * array.  Details include:
     *
     * - title
     * - headers for the report data
     * - data types for the report data
     * - etc.
     *
     * @param string $report report name
     *
     * @return array report information
     */

    public function get_report_info($report)
    {
        clearos_profile(__METHOD__, __LINE__);

        $info = $this->_initialize_report_info();

        return $info[$report];
    }

    /**
     * Checks to see if report exists.
     *
     * @param string $report report name
     *
     * @return boolean TRUE if report exists
     */

    public function report_exists($report)
    {
        clearos_profile(__METHOD__, __LINE__);

        $info = $this->_initialize_report_info();

        if (empty($info[$report]))
            return FALSE;
        else
            return TRUE;
    }

    /**
     * Returns report data information for a given report.
     *
     * Details include:
     *
     * - header
     * - type (data types)
     * - chart_series
     *
     * @param string $report report name
     *
     * @return array report information
     */

    public function _get_data_info($report)
    {
        clearos_profile(__METHOD__, __LINE__);

        $info = $this->_initialize_report_info();

        $report_data['header'] = $info[$report]['headers'];
        $report_data['type'] = $info[$report]['types'];
        $report_data['detail'] = $info[$report]['detail'];

        if (isset($info[$report]['format']))
            $report_data['format'] = $info[$report]['format'];

        if (isset($info[$report]['chart_series']))
            $report_data['chart_series'] = $info[$report]['chart_series'];

        return $report_data;
    }

    /**
     * Checks to see if report exists.
     *
     * @param string $report report name
     *
    /**
     * Initializes report information.
     *
     * The report definition in an app developer's report is slimmed
     * down to keep it as simple as possible for the developer.
     * We calculate some implied fields and set optional values.
     *
     * @return array report information
     */

    protected function _initialize_report_info()
    {
        clearos_profile(__METHOD__, __LINE__);

        $definitions = $this->_get_definition();

        // Handy: get the class and app name
        //----------------------------------

        $full_class = get_class($this);
        $matches = array();

        preg_match('/clearos\\\apps\\\(.*)\\\(.*)/', $full_class, $matches);

        $app = $matches[1];
        $library = $matches[2];

        // Loop through report definitions
        // Manually the special "overview" report first
        //---------------------------------------------

        $urls = array();
        $dashboards = array();
        $report_info = array();

        $report_info['overview']['title'] = lang('base_overview');
        $report_info['overview']['url'] = $app;
        $urls['/app/' . $report_info['overview']['url']] = lang('base_overview');

        foreach ($definitions as $report => $definition) {

            // Non-intuitive.  There are two types of reports to handle:
            // - A simple report (e.g. system load)
            // - A bunch of reports based on a key value (e.g. eth0, eth1, for the network report)
            //
            // We do some things a bit differently depending on the type

            // Set the report name
            $report_name = (empty($definition['key_value'])) ? $report : $definition['key_value'];

            // Pull in definition
            $info = $definitions[$report_name];

            // Add report name to the array for convenience
            $info['report'] = $report_name;

            // Add library info
            $info['library'] = $library;

            // Set basename
            if (! isset($info['basename']))
                $info['basename'] = $report_name;

            // Set an empty key value if one is not defined
            if (! isset($info['key_value']))
                $info['key_value'] = '';

            // Add URLs
            if (empty($info['key_value']))
                $info['url'] = $info['app'] . '/' . $info['basename'];
            else
                $info['url'] = $info['app'] . '/' . $info['basename'] . '/index/' . $info['key_value'];

            // Add default sort column if one is not specified
            // - timeline charts are sorted by the time (column 0)
            // - normal x/y are sorted by y (column 1)

            if (! isset($info['sort_column']))
                $info['sort_column'] = (preg_match('/timeline/', $info['chart_type'])) ? 0 : 1;

            // Track URLs and Dashboards
            if (! isset($info['is_detail']) || !$info['is_detail']) {
                $urls['/app/' . $info['url']] = $info['title'];

                $dashboards[$info['url']] = array(
                    'controller' =>  $info['app'] . '/' . $info['basename'],
                    'method' => 'dashboard',
                    'params' => $info['key_value']
                );
            }

            $report_info[$report_name] = $info;
        }

        // Each report should also have URLs to the other reports, so
        // Loop through again to add this information.
        //-------------------------------------------------------------

        foreach ($report_info as $report => $info)
            $report_info[$report]['urls'] = $urls;

        // And add dashboard info to special overview report
        $report_info['overview']['dashboards'] = $dashboards;
        $report_info['overview']['report'] = 'overview';

        return $report_info;
    }
}
