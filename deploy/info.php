<?php

/////////////////////////////////////////////////////////////////////////////
// General information
/////////////////////////////////////////////////////////////////////////////

$app['basename'] = 'reports';
$app['version'] = '2.5.0';
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

$app['core_requires'] = array(
    'app-base-core >= 1:1.4.4',
    'clearos-framework >= 6.4.4',
);

$app['provides'] = array(
    'system-report-driver',
);

$app['core_obsoletes'] = array(
    'app-home-reports-core',
    'app-professional-reports-core',
);

$app['obsoletes'] = array(
    'app-home-reports',
    'app-professional-reports',
);
