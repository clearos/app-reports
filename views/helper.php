<?php

/**
 * Report helper view.
 *
 * @category   apps
 * @package    reports
 * @subpackage views
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
// Load dependencies
///////////////////////////////////////////////////////////////////////////////

$this->lang->load('reports');

///////////////////////////////////////////////////////////////////////////////
// Form handler
///////////////////////////////////////////////////////////////////////////////

$unique_key = $report['app'] . '_' . $report['report'];
$urls = '';

foreach ($report['urls'] as $url => $title)
    $urls .= field_banner("- <a href='$url'>$title</a>");

///////////////////////////////////////////////////////////////////////////////
// Form
///////////////////////////////////////////////////////////////////////////////

echo form_open($report['form'], array('id' => 'report_form'));
echo form_header(lang('reports_reports')); 

echo fieldset_header(lang('base_filter'));
echo field_dropdown('report_range', $ranges, $range, lang('reports_date_range'));
echo fieldset_footer();

if (! empty($urls)) {
    echo fieldset_header(lang('reports_reports'));
    echo $urls;
    echo fieldset_footer();
}

echo form_footer();
echo form_close();
