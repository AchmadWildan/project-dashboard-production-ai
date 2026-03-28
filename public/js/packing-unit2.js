$(document).ready(function () {
    listLoad();
    setInterval(listLoad, 10000);
});
function listLoad() {
    loadData();
    getRecapMachine();
}
let lastStatus = {}; 

function loadData() {
    $.get("packing-unit2/getLiveCounter", function (data) {
        let rows = "";
        data.forEach(function (item, index) {

            let state = item.status_mesin;
            let statusClass, get_state, get_waktu;

            if (state === 'ON') {
                statusClass = "bg-gradient-success";
                get_state = "Production";
                get_waktu = `${item.updated_at} (Last OFF)`;
            } else {
                statusClass = "bg-gradient-danger";
                get_state = "Machine Off";
                state = 'OFF';
                get_waktu = `${item.updated_at} (Last ON)`;
            }

            lastStatus[item.mesin] = state;

            rows += `
                <tr class="text-center">
                    <td>${index + 1}</td>
                    <td><span class="badge badge-sm ${statusClass}">${state}</span></td>
                    <td>${item.mesin}</td>
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
    $.get("packing-unit2/getRecapMachine", function (data) {
        $("#offlineMachine").text(data.mesin_off);
        $("#onlineMachine").text(data.mesin_on);
        $("#retireMachine").text(data.mesin_nonaktif);
        $("#totalMachine").text(data.mesin_aktif);
    });
}
