let chatHistory = [];

function sendMessage(event) {
    event.preventDefault();
    const input = document.getElementById("message-input");
    const message = input.value.trim();

    if (message) {
        appendUserMessage(message);
        input.value = "";
        input.style.height = "auto";

        showTypingIndicator();

        // Prepare request to Python server
        const payload = {
            message: message,
            history: chatHistory,
            options: {
                chart_mode: "auto",
            },
        };

        fetch("http://127.0.0.1:8010/api/chat", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(payload),
        })
            .then((response) => response.json())
            .then((data) => {
                removeTypingIndicator();
                if (data.ok) {
                    if (data.out_of_scope) {
                        appendAIMessage(
                            data.answer ||
                                "Maaf, pertanyaan Anda di luar konteks database.",
                        );
                    } else {
                        // Handle SQL result
                        let responseHtml = `<p>${data.summary}</p>`;

                        if (data.sql) {
                            responseHtml += `
                            <div class="mt-2">
                                <button class="btn btn-sm btn-outline-info py-1 mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#sql-debug" aria-expanded="false">
                                    <i class="fas fa-code me-1"></i> Lihat SQL Query
                                </button>
                                <div class="collapse" id="sql-debug">
                                    <div class="card card-body bg-dark text-white p-2 mb-0">
                                        <code class="text-xs text-info">${data.sql}</code>
                                    </div>
                                </div>
                            </div>
                        `;
                        }

                        if (data.rows && data.rows.length > 0) {
                            responseHtml += renderTable(
                                data.columns,
                                data.rows,
                            );
                        }

                        appendAIMessage(responseHtml);

                        // Add to local history
                        chatHistory.push({ role: "user", content: message });
                        chatHistory.push({
                            role: "assistant",
                            content: data.summary,
                        });
                    }
                } else {
                    appendAIMessage(
                        "Maaf, terjadi kesalahan: " +
                            (data.error || "Gagal menghubungi server."),
                    );
                }
            })
            .catch((error) => {
                removeTypingIndicator();
                console.error("Error:", error);
                appendAIMessage(
                    "Maaf, tidak dapat terhubung ke server AI. Pastikan server Python (server.py) sudah berjalan di port 8010.",
                );
            });
    }
}

function renderTable(columns, rows) {
    if (!columns || !rows) return "";

    let tableHtml = `
        <div class="table-responsive mt-3 border-radius-lg shadow-sm">
            <table class="table align-items-center mb-0 table-sm bg-white">
                <thead class="bg-gray-100">
                    <tr>
    `;

    columns.forEach((col) => {
        tableHtml += `<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">${col}</th>`;
    });

    tableHtml += `
                    </tr>
                </thead>
                <tbody>
    `;

    // Limit to 5 rows in chat for brevity
    const displayRows = rows.slice(0, 5);
    displayRows.forEach((row) => {
        tableHtml += "<tr>";
        columns.forEach((col) => {
            tableHtml += `<td class="text-xs font-weight-bold ps-2">${row[col] !== null ? row[col] : "-"}</td>`;
        });
        tableHtml += "</tr>";
    });

    tableHtml += `
                </tbody>
            </table>
        </div>
    `;

    if (rows.length > 5) {
        tableHtml += `<p class="text-xxs text-muted mt-1 italic text-center">* Menampilkan 5 dari ${rows.length} baris</p>`;
    }

    return tableHtml;
}

function appendUserMessage(text) {
    const chatContainer = document.getElementById("chat-messages");
    const time = new Date().toLocaleTimeString([], {
        hour: "2-digit",
        minute: "2-digit",
    });

    const messageHtml = `
            <div class="d-flex mb-4 justify-content-end animate__animated animate__fadeInUp">
                <div class="card bg-primary border-0 shadow-sm" style="max-width: 80%; border-top-right-radius: 0;">
                    <div class="card-body p-3">
                        <p class="mb-0 text-sm text-white">${escapeHtml(text)}</p>
                        <div class="text-xs text-white opacity-8 mt-1 text-end">
                            <i class="far fa-clock me-1"></i> ${time}
                        </div>
                    </div>
                </div>
                <div class="avatar avatar-sm bg-white rounded-circle ms-2 d-flex align-items-center justify-content-center shadow-sm">
                    <img src="assets/img/illustrations/rocket-white.png" class="w-100" alt="AI Assistant">
                </div>
            </div>
                `;

    // <i class="fas fa-user text-secondary text-xs"></i>
    chatContainer.insertAdjacentHTML("beforeend", messageHtml);
    scrollToBottom();
}

function clearChat() {
    const chatContainer = document.getElementById("chat-messages");
    // Keep only the welcome message
    const welcomeMessage = chatContainer.firstElementChild.outerHTML;
    chatContainer.innerHTML = welcomeMessage;
    chatHistory = [];
}

function appendAIMessage(htmlContent) {
    const chatContainer = document.getElementById("chat-messages");
    const time = new Date().toLocaleTimeString([], {
        hour: "2-digit",
        minute: "2-digit",
    });

    const messageHtml = `
            <div class="d-flex mb-4 justify-content-start animate__animated animate__fadeInUp">
                <div class="avatar avatar-sm bg-white rounded-circle me-2 d-flex align-items-center justify-content-center shadow-sm">
                    <img src="assets/img/illustrations/rocket-white.png" class="w-100" alt="AI Assistant">
                </div>
                <div class="card border-0 shadow-sm" style="max-width: 90%; border-top-left-radius: 0;">
                    <div class="card-body p-3">
                        <div class="text-sm text-dark">${htmlContent}</div>
                        <div class="text-xs text-muted mt-2 text-end">
                            <i class="far fa-clock me-1"></i> ${time}
                        </div>
                    </div>
                </div>
            </div>
        `;

    chatContainer.insertAdjacentHTML("beforeend", messageHtml);
    scrollToBottom();
}

function showTypingIndicator() {
    const chatContainer = document.getElementById("chat-messages");
    const typingHtml = `
            <div id="typing-indicator" class="d-flex mb-4 justify-content-start animate__animated animate__fadeIn">
                <div class="avatar avatar-sm bg-white rounded-circle me-2 d-flex align-items-center justify-content-center shadow-sm">
                    <img src="assets/img/illustrations/rocket-white.png" class="w-100" alt="AI Assistant">
                </div>
                <div class="card border-0 shadow-sm" style="max-width: 80%; border-top-left-radius: 0;">
                    <div class="card-body p-3">
                        <div class="typing-indicator">
                            <span></span><span></span><span></span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    chatContainer.insertAdjacentHTML("beforeend", typingHtml);
    scrollToBottom();
}

function removeTypingIndicator() {
    const indicator = document.getElementById("typing-indicator");
    if (indicator) {
        indicator.remove();
    }
}

function scrollToBottom() {
    const chatContainer = document.getElementById("chat-messages");
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

function escapeHtml(text) {
    if (typeof text !== "string") return text;
    return text
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// Auto-resize textarea
const messageInput = document.getElementById("message-input");
if (messageInput) {
    messageInput.addEventListener("input", function () {
        this.style.height = "auto";
        this.style.height = this.scrollHeight + "px";
        if (this.value === "") {
            this.style.height = "auto";
        }
    });

    // Handle Enter key to submit (Shift+Enter for new line)
    messageInput.addEventListener("keydown", function (e) {
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault();
            sendMessage(e);
        }
    });
}
