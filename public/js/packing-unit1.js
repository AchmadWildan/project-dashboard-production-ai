$(document).ready(function () {
    listLoad();
    setInterval(listLoad, 10000);
});
function listLoad() {
    loadData();
    getRecapMachine();
}
function loadData() {
    $.get("packing-unit1/getLiveScada", function (data) {
        let rows = "";
        // console.log(data);

        data.forEach(function (item, index) {
            if (item.status_mesin == 1) {
                get_waktu = `${item.waktu} (Last Off)`;
                statusClass = "bg-gradient-success"
                state = "ON"
                get_state = `Production`;
            } else {
                state = "OFF"
                get_waktu = `${item.waktu} (Last On)`;
                statusClass = "bg-gradient-danger"
                get_state = `Machine Off`;
            }

            rows += `
            <tr class="text-center">
                <td>${index + 1}</td>
                <td class="align-middle text-center text-sm"><span class="badge badge-sm ${statusClass}">${state}</span></td>
                <td>${item.mesin_angka}</td>
                <td>${get_waktu}</td>
                <td>-</td>
                <td>${get_state}</td>
            </tr>
        `;
        });

        $("#tabel-mesin tbody").html(rows);
    });
}

function getRecapMachine() {
    $.get("packing-unit1/getRecapMachine", function (data) {
        $("#offlineMachine").text(data.mesin_offline);
        $("#onlineMachine").text(data.mesin_online);
        $("#retireMachine").text(data.mesin_nonaktif);
        $("#totalMachine").text(data.total_mesin);
    });
}
