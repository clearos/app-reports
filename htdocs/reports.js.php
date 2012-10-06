<?php

/**
 * Report ajax helpers.
 *
 * All reports generate IDs to allow this javascript to take action.  Here's
 * an example of the Domains report in the Proxy Report app:
 *
 * <input type='hidden' id='clearos_report_proxy_report_domains_basename' value='proxy_report_domains'>
 * <input type='hidden' id='proxy_report_domains_app_name' value='proxy_report'>
 * <input type='hidden' id='proxy_report_domains_report_name' value='domains'>
 *
 * @category   ClearOS
 * @package    Reports
 * @subpackage Javascript
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2012 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/reports/
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
// B O O T S T R A P
///////////////////////////////////////////////////////////////////////////////

$bootstrap = getenv('CLEAROS_BOOTSTRAP') ? getenv('CLEAROS_BOOTSTRAP') : '/usr/clearos/framework/shared';
require_once $bootstrap . '/bootstrap.php';

///////////////////////////////////////////////////////////////////////////////
// T R A N S L A T I O N S
///////////////////////////////////////////////////////////////////////////////

clearos_load_language('reports');

///////////////////////////////////////////////////////////////////////////////
// J A V A S C R I P T
///////////////////////////////////////////////////////////////////////////////

header('Content-Type:application/x-javascript');
?>

///////////////////////////////////////////////////////////////////////////
// M A I N
///////////////////////////////////////////////////////////////////////////

$(document).ready(function() {

    // Translations
    //-------------

    lang_received = '<?php echo lang("proxy_report_received"); ?>';

    // Main
    //-----
/*
clearos_report_domains = 1234
clearos_report_domains_app_name = 'proxy_report'
clearos_report_domains_report = 'ips'

        <input type='hidden' id='report_chart_id' value='proxy_report_domains'>
        <input type='hidden' id='report_app_name' value='proxy_report'>
        <input type='hidden' id='report_name' value='ips'>
    
        <input type='hidden' id='report_chart_id' value='proxy_report_ips'>
        <input type='hidden' id='report_app_name' value='proxy_report'>
        <input type='hidden' id='report_name' value='ips'>

// search for all ids starting with clearos_reports
// basename = preg.... 
// 

*/

    // Date range form action
    //-----------------------

    $("#report_range").click(function(){
        $('form#report_form').submit();
    });

    // Scan for reports on the page
    //-----------------------------

    var report_list = $("input[id^='clearos_report']");

    $.each(report_list, function(index, value) {
        var id_prefix = $(value).val();

        var app = $("#" + id_prefix + "_app_name").val();
        var report = $("#" + id_prefix + "_report_name").val();
        var chart_id = id_prefix + "_chart";

        generate_report(app, report, chart_id);
    });
});

/**
 * Ajax call for standard report.
 */

function generate_report(app, report, chart_id) {

    $.ajax({
        url: '/app/' + app + '/' + report + '/get_data',
        method: 'GET',
        dataType: 'json',
        success : function(payload) {
            create_pie_chart(payload, chart_id);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            window.setTimeout(generate_report, 3000);
        }
    });
}

/**
 * Generates dashboard report.
 */

function create_pie_chart(payload, chart_id) {

    hits = Array();

    for (var details in payload) {
        if (payload.hasOwnProperty(details)) {
            hits.push([payload[details].hits, details]);
        }
    }

    var chart = jQuery.jqplot (chart_id, [hits],
    {
        animate: !$.jqplot.use_excanvas,
        seriesDefaults: {
            renderer: jQuery.jqplot.BarRenderer,
            rendererOptions: {
                barDirection: 'horizontal'
            },
            pointLabels: { show: true, location: 'e', edgeTolerance: -15 },
        },
        axes: {
            yaxis: {
                renderer: $.jqplot.CategoryAxisRenderer,
            }
        }
    });

    chart.redraw();
}

// vim: ts=4 syntax=javascript
