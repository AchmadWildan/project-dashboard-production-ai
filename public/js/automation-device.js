$(document).ready(function () {
    listLoad();
});

function listLoad() {
    Promise.all([
        loadDataMesinP1(),
        loadDataTimbanganP1(),
        loadDataMesinP2(),
        loadDataTimbanganP2(),
        loadDataHormanDCIn(),
        loadDataHormanDCOut(),
        loadDataHormanP1(),
        loadDataHormanP2()
    ])
        .then(() => {
            getRecapDevice();
            // update timestamp
            const now = new Date();
            $('#adLastUpdate').text(
                'Last updated: ' + now.toLocaleDateString('id-ID') + ' ' + now.toLocaleTimeString('id-ID')
            );
        })
        .catch((err) => {
            console.error('Error loading device data:', err);
        })
        .finally(() => {
            setTimeout(listLoad, 10000);
        });
}

/* ─── helpers ─── */
function ipLink(ip) {
    if (!ip || ip === '-' || ip === '') return '<span class="text-muted" style="font-size:.78rem">—</span>';
    return `<a class="ad-ip-link" href="http://${ip}" target="_blank" rel="noopener noreferrer">
                <i class="fa fa-external-link"></i>${ip}
            </a>`;
}

function statusBadge(isOnline) {
    return isOnline
        ? '<span class="ad-badge online"><span class="bd"></span>ONLINE</span>'
        : '<span class="ad-badge offline"><span class="bd"></span>OFFLINE</span>';
}

function emptyRow(cols) {
    return `<tr><td colspan="${cols}" class="text-center py-4 text-muted" style="font-size:.82rem">No data available</td></tr>`;
}

/* ─── Machine P1 ─── */
function loadDataMesinP1() {
    return $.get('automation-device/getDataMesinP1', function (data) {
        let rows = '';
        if (!data.length) {
            rows = emptyRow(4);
        } else {
            $.each(data, function (i, item) {
                rows += `
                    <tr class="text-center">
                        <td>${i + 1}</td>
                        <td>${item.kode_mesin}</td>
                        <td>${ipLink(item.ip)}</td>
                        <td>${statusBadge(item.online)}</td>
                    </tr>`;
            });
        }
        $('#tabel-mesin-p1 tbody').html(rows);
    });
}
function loadDataTimbanganP1() {
    return $.get('automation-device/getDataTimbanganP1', function (data) {
        let rows = '';
        if (!data.length) {
            rows = emptyRow(4);
        } else {
            $.each(data, function (i, item) {
                rows += `
                    <tr class="text-center">
                        <td>${i + 1}</td>
                        <td>${item.device}</td>
                        <td>${ipLink(item.ip)}</td>
                        <td>${statusBadge(item.online)}</td>
                    </tr>`;
            });
        }
        $('#tabel-timbangan-p1 tbody').html(rows);
    });
}
function loadDataTimbanganP2() {
    return $.get('automation-device/getDataTimbanganP2', function (data) {
        let rows = '';
        if (!data.length) {
            rows = emptyRow(4);
        } else {
            $.each(data, function (i, item) {
                rows += `
                    <tr class="text-center">
                        <td>${i + 1}</td>
                        <td>${item.device}</td>
                        <td>${ipLink(item.ip)}</td>
                        <td>${statusBadge(item.online)}</td>
                    </tr>`;
            });
        }
        $('#tabel-timbangan-p2 tbody').html(rows);
    });
}

/* ─── Machine P2 ─── */
function loadDataMesinP2() {
    return $.get('automation-device/getDataMesinP2', function (data) {
        let rows = '';
        if (!data.length) {
            rows = emptyRow(4);
        } else {
            $.each(data, function (i, item) {
                rows += `
                    <tr class="text-center">
                        <td>${i + 1}</td>
                        <td>${item.kode_mesin}</td>
                        <td>${ipLink(item.ip)}</td>
                        <td>${statusBadge(item.online)}</td>
                    </tr>`;
            });
        }
        $('#tabel-mesin-p2 tbody').html(rows);
    });
}

/* ─── Hormann DC In ─── */
function loadDataHormanDCIn() {
    return $.get('automation-device/getDataHormannDCIn', function (data) {
        let rows = '';
        if (!data.length) {
            rows = emptyRow(4);
        } else {
            $.each(data, function (i, item) {
                rows += `
                    <tr class="text-center">
                        <td>${i + 1}</td>
                        <td>${item.area}</td>
                        <td>${ipLink(item.ip)}</td>
                        <td>${statusBadge(item.online)}</td>
                    </tr>`;
            });
        }
        $('#tabel-hormann-dcin tbody').html(rows);
    });
}

/* ─── Hormann DC Out ─── */
function loadDataHormanDCOut() {
    return $.get('automation-device/getDataHormannDCOut', function (data) {
        let rows = '';
        if (!data.length) {
            rows = emptyRow(4);
        } else {
            $.each(data, function (i, item) {
                rows += `
                    <tr class="text-center">
                        <td>${i + 1}</td>
                        <td>${item.area}</td>
                        <td>${ipLink(item.ip)}</td>
                        <td>${statusBadge(item.online)}</td>
                    </tr>`;
            });
        }
        $('#tabel-hormann-dcout tbody').html(rows);
    });
}

/* ─── Hormann WH P1 ─── */
function loadDataHormanP1() {
    return $.get('automation-device/getDataHormannWHP1', function (data) {
        let rows = '';
        if (!data.length) {
            rows = emptyRow(4);
        } else {
            $.each(data, function (i, item) {
                rows += `
                    <tr class="text-center">
                        <td>${i + 1}</td>
                        <td>${item.area}</td>
                        <td>${ipLink(item.ip)}</td>
                        <td>${statusBadge(item.online)}</td>
                    </tr>`;
            });
        }
        $('#tabel-hormann-whp1 tbody').html(rows);
    });
}

/* ─── Hormann WH P2 ─── */
function loadDataHormanP2() {
    return $.get('automation-device/getDataHormannWHP2', function (data) {
        let rows = '';
        if (!data.length) {
            rows = emptyRow(4);
        } else {
            $.each(data, function (i, item) {
                rows += `
                    <tr class="text-center">
                        <td>${i + 1}</td>
                        <td>${item.area}</td>
                        <td>${ipLink(item.ip)}</td>
                        <td>${statusBadge(item.online)}</td>
                    </tr>`;
            });
        }
        $('#tabel-hormann-whp2 tbody').html(rows);
    });
}

/* ─── Recap counters + progress bars ─── */
function getRecapDevice() {
    let onlineCount = 0, offlineCount = 0;

    const tables = [
        '#tabel-mesin-p1', '#tabel-mesin-p2',
        '#tabel-hormann-dcin', '#tabel-hormann-dcout',
        '#tabel-hormann-whp1', '#tabel-hormann-whp2'
    ];

    tables.forEach(id => {
        $(id + ' tbody tr').each(function () {
            const badge = $(this).find('.ad-badge');
            if (badge.hasClass('online')) onlineCount++;
            else if (badge.hasClass('offline')) offlineCount++;
        });
    });

    const total = onlineCount + offlineCount;

    $('#onlineDevice').text(onlineCount);
    $('#offlineDevice').text(offlineCount);
    $('#totalDevice').text(total);

    if (total > 0) {
        $('#onlineBar').css('width', (onlineCount / total * 100) + '%');
        $('#offlineBar').css('width', (offlineCount / total * 100) + '%');
    }
}