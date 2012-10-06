<?php

/**
 * Report base class.
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

// Classes
//--------

use \clearos\apps\base\Configuration_File as Configuration_File;
use \clearos\apps\base\Engine as Engine;

clearos_load_library('base/Configuration_File');
clearos_load_library('base/Engine');

// Exceptions
//-----------

use \clearos\apps\base\File_Not_Found_Exception as File_Not_Found_Exception;

clearos_load_library('base/File_Not_Found_Exception');

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Report base class.
 *
 * @category   Apps
 * @package    Reports
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2012 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/reports/
 */

class Report extends Engine
{
    ///////////////////////////////////////////////////////////////////////////////
    // C O N S T A N T S
    ///////////////////////////////////////////////////////////////////////////////

    const SUMMARY_RANGE_TODAY = 'today';
    const SUMMARY_RANGE_YESTERDAY = 'yesterday';
    const SUMMARY_RANGE_LAST_7_DAYS = 'last7';
    const SUMMARY_RANGE_LAST_30_DAYS = 'last30';

    const DETAIL_RANGE_DAILY = 'daily';
    const DETAIL_RANGE_WEEKLY = 'weekly';
    const DETAIL_RANGE_MONTHLY = 'monthly';

    const DEFAULT_SUMMARY_RANGE = 'today';
    const DEFAULT_DETAIL_RANGE = 'daily';

    const FILE_CONFIG = '/etc/clearos/reports.conf';

    ///////////////////////////////////////////////////////////////////////////////
    // V A R I A B L E S
    ///////////////////////////////////////////////////////////////////////////////

    protected $config = NULL;
    protected $summary_ranges = array();

    ///////////////////////////////////////////////////////////////////////////////
    // M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Report constructor.
     */

    public function __construct()
    {
        clearos_profile(__METHOD__, __LINE__);

        $this->summary_ranges = array(
            self::SUMMARY_RANGE_TODAY => lang('reports_today'),
            self::SUMMARY_RANGE_YESTERDAY => lang('reports_yesterday'),
            self::SUMMARY_RANGE_LAST_7_DAYS => lang('reports_last_7_days'),
            self::SUMMARY_RANGE_LAST_30_DAYS => lang('reports_last_30_days')
        );
    }

    /**
     * Returns summary range.
     *
     * @return string summary range
     */

    public function get_summary_range()
    {
        clearos_profile(__METHOD__, __LINE__);

        $this->_load_config();

        return $this->config['summary_range'];
    }

    /**
     * Returns summary ranges.
     *
     * @return array summary ranges
     */

    public function get_summary_ranges()
    {
        clearos_profile(__METHOD__, __LINE__);

        return $this->summary_ranges;
    }

    ///////////////////////////////////////////////////////////////////////////////
    // V A L I D A T I O N   R O U T I N E S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Validation routine for summary ranges.
     *
     * @param string $range summary range
     *
     * @return string error message if summary range is invalid
     */

    public function validate_summary_range($range)
    {
        clearos_profile(__METHOD__, __LINE__);

        // return lang('reports_range_invalid');
    }

    ///////////////////////////////////////////////////////////////////////////////
    // P R I V A T E   M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Loads configuration.
     *
     * @return void.
     */

    protected function _load_config()
    {
        clearos_profile(__METHOD__, __LINE__);

        if (!is_null($this->config))
            return;

        try {
            $file = new Configuration_File(self::FILE_CONFIG, 'explode', '=', 2);
            $this->config = $file->load();
        } catch (File_Not_Found_Exception $e) {
            // Not fatal
        }

        if (empty($this->config['summary_range']))
            $this->config['summary_range'] = self::DEFAULT_SUMMARY_RANGE;
    }
}
