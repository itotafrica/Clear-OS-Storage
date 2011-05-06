
Name: app-storage
Group: ClearOS/Apps
Version: 5.9.9.0
Release: 1%{dist}
Summary: Storage summary
License: GPLv3
Packager: ClearFoundation
Vendor: ClearFoundation
Source: %{name}-%{version}.tar.gz
Buildarch: noarch
Requires: %{name}-core = %{version}-%{release}
Requires: app-base

%description
Storage long description

%package core
Summary: Storage summary - APIs and install
Group: ClearOS/Libraries
License: LGPLv3
Requires: app-base-core

%description core
Storage long description

This package provides the core API and libraries.

%prep
%setup -q
%build

%install
mkdir -p -m 755 %{buildroot}/usr/clearos/apps/storage
cp -r * %{buildroot}/usr/clearos/apps/storage/

install -d -m 0755 %{buildroot}/etc/clearos/storage
install -D -m 0644 packaging/homes.php %{buildroot}/etc/clearos/storage/homes.php

%post
logger -p local6.notice -t installer 'app-storage - installing'

%post core
logger -p local6.notice -t installer 'app-storage-core - installing'

if [ $1 -eq 1 ]; then
    [ -x /usr/clearos/apps/storage/deploy/install ] && /usr/clearos/apps/storage/deploy/install
fi

[ -x /usr/clearos/apps/storage/deploy/upgrade ] && /usr/clearos/apps/storage/deploy/upgrade

exit 0

%preun
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-storage - uninstalling'
fi

%preun core
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-storage-core - uninstalling'
    [ -x /usr/clearos/apps/storage/deploy/uninstall ] && /usr/clearos/apps/storage/deploy/uninstall
fi

exit 0

%files
%defattr(-,root,root)
/usr/clearos/apps/storage/controllers
/usr/clearos/apps/storage/htdocs
/usr/clearos/apps/storage/views

%files core
%defattr(-,root,root)
%exclude /usr/clearos/apps/storage/packaging
%exclude /usr/clearos/apps/storage/tests
%dir /usr/clearos/apps/storage
%dir /etc/clearos/storage
/usr/clearos/apps/storage/deploy
/usr/clearos/apps/storage/language
/usr/clearos/apps/storage/libraries
/etc/clearos/storage/homes.php
