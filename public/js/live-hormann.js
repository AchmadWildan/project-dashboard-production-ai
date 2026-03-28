$(document).ready(function () {
    listLoad();
    setInterval(listLoad, 5000);
});

function listLoad() {
    loadWhUnit1();
    loadWhUnit2();
    loadDC();
    // update timestamp
    const now = new Date();
    $('#lhLastUpdate').text(
        'Last updated: ' + now.toLocaleDateString('id-ID') + ' ' + now.toLocaleTimeString('id-ID')
    );
}

/* ── helper: build one door card ── */
function doorCard(name, status) {
    const isOpen = status === 'OPEN';
    const stateClass = isOpen ? 'door-open' : 'door-closed';
    const icon = isOpen ? '🟢' : '🔴';
    return `
        <div class="lh-door ${stateClass}" title="${name}: ${status}">
            <span class="door-status-dot"></span>
            <span class="door-icon">${icon}</span>
            <span class="door-name">${name}</span>
        </div>`;
}

/* ── WH Unit 1 · Door 1–14 · Rows A, B, C ── */
function loadWhUnit1() {
    $.get('live-hormann/getLiveWhUnit1', function (data) {
        const groups = ['A', 'B', 'C'];
        let html = '';
        let openCount = 0, closedCount = 0;

        groups.forEach(function (prefix) {
            html += `<p class="lh-row-label">Row ${prefix}</p><div class="lh-door-grid">`;
            for (let i = 1; i <= 14; i++) {
                const name = `${prefix}${i}`;
                const item = data.find(p => p.Pintu === name);
                const status = (item && item.Status) ? item.Status : 'CLOSED';
                if (status === 'OPEN') openCount++; else closedCount++;
                html += doorCard(name, status);
            }
            html += `</div>`;
        });

        $('#body-wh-unit1').html(html);
        $('#wh1-open').text(openCount);
        $('#wh1-closed').text(closedCount);
    });
}

/* ── WH Unit 2 · Door 1–12 · Rows A, B, C
       Special: kolom 12 hanya Row A (A12); B12 dan C12 dihilangkan ── */
function loadWhUnit2() {
    $.get('live-hormann/getLiveWhUnit2', function (data) {
        const groups = ['A', 'B', 'C'];
        let html = '';
        let openCount = 0, closedCount = 0;

        groups.forEach(function (prefix) {
            html += `<p class="lh-row-label">Row ${prefix}</p><div class="lh-door-grid">`;

            for (let i = 1; i <= 12; i++) {
                // B12 dan C12 dilewati — hanya A12 yang ditampilkan
                if (i === 12 && prefix !== 'A') continue;

                const name = `${prefix}${i}`;
                const item = data.find(p => p.Pintu === name);
                const status = (item && item.Status) ? item.Status : 'CLOSED';
                if (status === 'OPEN') openCount++; else closedCount++;
                html += doorCard(name, status);
            }
            html += `</div>`;
        });

        $('#body-wh-unit2').html(html);
        $('#wh2-open').text(openCount);
        $('#wh2-closed').text(closedCount);
    });
}

/* ── Distribution Center · Door 13–21 · Rows A, B, C ── */
function loadDC() {
    $.get('live-hormann/getLiveDC', function (data) {
        const groups = ['A', 'B', 'C'];
        let html = '';
        let openCount = 0, closedCount = 0;

        groups.forEach(function (prefix) {
            html += `<p class="lh-row-label">Row ${prefix}</p><div class="lh-door-grid">`;
            for (let i = 13; i <= 21; i++) {
                const name = `${prefix}${i}`;
                const item = data.find(p => p.Pintu === name);
                const status = (item && item.Status) ? item.Status : 'CLOSED';
                if (status === 'OPEN') openCount++; else closedCount++;
                html += doorCard(name, status);
            }
            html += `</div>`;
        });

        $('#body-dc').html(html);
        $('#dc-open').text(openCount);
        $('#dc-closed').text(closedCount);
    });
}
