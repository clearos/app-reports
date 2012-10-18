<?php

/**
 * Reports engine core controller.
 *
 * @category   Apps
 * @package    Reports
 * @subpackage Controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2012 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/reports/
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Reports engine controller.
 *
 * @category   Apps
 * @package    Reports
 * @subpackage Controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2012 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/reports/
 */

class Report_Engine_Controller extends ClearOS_Controller
{
    ///////////////////////////////////////////////////////////////////////////////
    // V A R I A B L E S
    ///////////////////////////////////////////////////////////////////////////////

    protected $report_info = array();

    ///////////////////////////////////////////////////////////////////////////////
    // M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Reports engine core constructor.
     *
     * @param string $report report details
     *
     * @return view
     */

    function __construct($app, $library, $report)
    {
        // Load the report class
        //----------------------

        $this->load->library($app . '/' . $library);

        // Load the report data using the standard report methods
        //-------------------------------------------------------

        try {
            $ci_library = strtolower($library);

            $report = $this->$ci_library->get_report_info($report);
            $report['links'] = $this->$ci_library->get_report_urls();
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }

        $this->report_info = $report;
    }
}
