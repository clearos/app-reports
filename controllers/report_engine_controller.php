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
    /**
     * Reports engine core constructor.
     *
     * @param string $report report details
     *
     * @return view
     */

    function __construct($report)
    {
        $this->report_info = $report;
    }

    /**
     * Default controller.
     *
     * @return view
     */

    function _index($type, $driver)
    {
        $options['javascript'] = array(clearos_app_htdocs($driver) . '/reports.js.php');

        // FIXME: review
        if ($type === 'dashboard') {
            $view = 'reports/dashboard_report';
        } else {
            $view = 'reports/full_report';
            $options['type'] = MY_Page::TYPE_REPORT;
        }

        $this->page->view_form($view, $this->report_info, $report['title'], $options);
    }

    function _get_summary_range()
    {
        $this->load->library('reports/Report');

        return $this->report->get_date_range();
    }

    function _get_summary_ranges()
    {
        $this->load->library('reports/Report');

        return $this->report->get_date_ranges();
    }

    /**
     * Date range handler.
     */

    function _handle_range()
    {
        if ($this->input->post('report_range'))
            $this->session->set_userdata('report_sr', $this->input->post('report_range'));

        // FIXME: hard-coded today
/*
        if (!$this->session->userdata('report_sr'))
            $this->session->set_userdata('reports_sr', 'today');
*/
    }

    function get_data()
    {
        clearos_profile(__METHOD__, __LINE__);

        // Load dependencies
        //------------------

        $this->load->library($this->report_info['app'] . '/' . $this->report_info['library']);

        // Load data
        //----------

        try {
            $library = strtolower($this->report_info['library']);
            $method = $this->report_info['method'];

            $data = $this->$library->$method(
                $this->session->userdata('report_sr'),
                10
            );
        } catch (Exception $e) {
            echo json_encode(array('code' => clearos_exception_code($e), 'errmsg' => clearos_exception_message($e)));
        }

        // Show data
        //----------

        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Fri, 01 Jan 2010 05:00:00 GMT');
        header('Content-type: application/json');
        echo json_encode($data);
    }
}
