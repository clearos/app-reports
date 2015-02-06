
Name: app-reports
Epoch: 1
Version: 2.0.18
Release: 1%{dist}
Summary: Base Reports
License: GPLv3
Group: ClearOS/Apps
Source: %{name}-%{version}.tar.gz
Buildarch: noarch
Requires: %{name}-core = 1:%{version}-%{release}
Requires: app-base

%description
The Base Reports app provides a set of standard report tools for the operating system.

%package core
Summary: Base Reports - Core
License: LGPLv3
Group: ClearOS/Libraries
Requires: app-base-core
Requires: app-base-core >= 1:1.4.4
Requires: clearos-framework >= 6.4.4
Requires: system-report-driver

%description core
The Base Reports app provides a set of standard report tools for the operating system.

This package provides the core API and libraries.

%prep
%setup -q
%build

%install
mkdir -p -m 755 %{buildroot}/usr/clearos/apps/reports
cp -r * %{buildroot}/usr/clearos/apps/reports/


%post
logger -p local6.notice -t installer 'app-reports - installing'

%post core
logger -p local6.notice -t installer 'app-reports-core - installing'

if [ $1 -eq 1 ]; then
    [ -x /usr/clearos/apps/reports/deploy/install ] && /usr/clearos/apps/reports/deploy/install
fi

[ -x /usr/clearos/apps/reports/deploy/upgrade ] && /usr/clearos/apps/reports/deploy/upgrade

exit 0

%preun
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-reports - uninstalling'
fi

%preun core
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-reports-core - uninstalling'
    [ -x /usr/clearos/apps/reports/deploy/uninstall ] && /usr/clearos/apps/reports/deploy/uninstall
fi

exit 0

%files
%defattr(-,root,root)
/usr/clearos/apps/reports/controllers

%files core
%defattr(-,root,root)
%exclude /usr/clearos/apps/reports/packaging
%dir /usr/clearos/apps/reports
/usr/clearos/apps/reports/deploy
/usr/clearos/apps/reports/language
/usr/clearos/apps/reports/libraries
