<?php

/////////////////////////////////////////////////////////////////////////////
// General information
/////////////////////////////////////////////////////////////////////////////

$app['basename'] = 'reports';
$app['version'] = '1.5.0';
$app['release'] = '1';
$app['vendor'] = 'ClearFoundation';
$app['packager'] = 'ClearFoundation';
$app['license'] = 'GPLv3';
$app['license_core'] = 'LGPLv3';
$app['description'] = lang('reports_app_description');

/////////////////////////////////////////////////////////////////////////////
// App name and categories
/////////////////////////////////////////////////////////////////////////////

$app['name'] = lang('reports_app_name');
$app['category'] = lang('base_category_reports');
$app['subcategory'] = lang('base_subcategory_settings');
$app['menu_enabled'] = FALSE;

/////////////////////////////////////////////////////////////////////////////
// Packaging
/////////////////////////////////////////////////////////////////////////////

// FIXME: remove app-dashboard, app-log-viewer, app-process-viewer, app-network-visualiser requirements for or after 6.4 release
$app['core_requires'] = array(
    'app-dashboard >= 1:1.4.5',
    'app-process-viewer >= 1:1.4.5',
    'app-log-viewer >= 1:1.4.5',
    'app-network-visualiser >= 1:1.4.5',
    'app-base-core >= 1:1.4.4',
    'clearos-framework >= 6.4.4',
    'system-report-driver',
    'theme-default >= 6.4.4',
);
