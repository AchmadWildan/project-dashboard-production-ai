$(document).ready(function () {
    listLoad();
    setInterval(listLoad, 5000);
});
function listLoad() {
    loadWhUnit1();
    loadWhUnit2();
    loadDC();
}
function loadWhUnit1() {
    $.get("live-hormann/getLiveWhUnit1", function (data) {
        $("#tb-wh-unit1 tbody").empty();

        const groups = ["A", "B", "C"];
        let html = "";

        groups.forEach((prefix) => {
            html += `<tr>`;
            for (let i = 1; i <= 14; i++) {
                const pintuName = `${prefix}${i}`;
                const item = data.find((p) => p.Pintu === pintuName);
                const statusClass = item
                    ? item.Status === "OPEN"
                        ? "bg-gradient-success"
                        : "bg-gradient-danger"
                    : "bg-gradient-danger";

                html += `
                <td class="align-middle text-center text-sm">
                    <span class="badge badge-sm ${statusClass}">${pintuName}</span>
                </td>
            `;
            }
            html += `</tr>`;
        });

        $("#tb-wh-unit1 tbody").html(html);
    });
}
function loadWhUnit2() {
    $.get("live-hormann/getLiveWhUnit2", function (data) {
        $("#tb-wh-unit2 tbody").empty();

        const groups = ["A", "B", "C"];
        let html = "";

        groups.forEach((prefix) => {
            html += `<tr>`;
            for (let i = 1; i <= 12; i++) {
                const pintuName = `${prefix}${i}`;
                const item = data.find((p) => p.Pintu === pintuName);
                const statusClass = item
                    ? item.Status === "OPEN"
                        ? "bg-gradient-success"
                        : "bg-gradient-danger"
                    : "bg-gradient-danger";

                html += `
                <td class="align-middle text-center text-sm">
                    <span class="badge badge-sm ${statusClass}">${pintuName}</span>
                </td>
            `;
            }
            html += `</tr>`;
        });

        $("#tb-wh-unit2 tbody").html(html);
    });
}
function loadDC() {
    $.get("live-hormann/getLiveDC", function (data) {
        $("#tb-dc tbody").empty();

        const groups = ["A", "B", "C"];
        let html = "";

        groups.forEach((prefix) => {
            html += `<tr>`;
            for (let i = 13; i <= 21; i++) {
                const pintuName = `${prefix}${i}`;
                const item = data.find((p) => p.Pintu === pintuName);
                const statusClass = item
                    ? item.Status === "OPEN"
                        ? "bg-gradient-success"
                        : "bg-gradient-danger"
                    : "bg-gradient-danger";

                html += `
                <td class="align-middle text-center text-sm">
                    <span class="badge badge-sm ${statusClass}">${pintuName}</span>
                </td>
            `;
            }
            html += `</tr>`;
        });

        $("#tb-dc tbody").html(html);
    });
}
