<?php
$chatbotUser = isset($_SESSION['user']) && $_SESSION['user'] !== '' ? $_SESSION['user'] : 'guest';
?>
<style>
.rm-chatbot-root {
    position: fixed;
    right: 104px;
    bottom: 24px;
    z-index: 1100;
    font-family: "Nunito", sans-serif;
}

.rm-chatbot-toggle {
    width: 62px;
    height: 62px;
    border: 0;
    border-radius: 50%;
    background: #e69500;
    color: #fff;
    font-size: 1.35rem;
    box-shadow: 0 14px 32px rgba(0, 0, 0, 0.24);
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.rm-chatbot-panel {
    position: absolute;
    right: 0;
    bottom: 76px;
    width: min(380px, calc(100vw - 30px));
    max-height: min(620px, calc(100vh - 120px));
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #ebedf3;
    box-shadow: 0 20px 44px rgba(17, 24, 39, 0.2);
    display: none;
    overflow: hidden;
}

.rm-chatbot-panel.is-open {
    display: flex;
    flex-direction: column;
}

.rm-chatbot-head {
    background: linear-gradient(135deg, #0f224a, #1d3e7a);
    color: #fff;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.rm-chatbot-title {
    margin: 0;
    font-size: 1rem;
    font-weight: 800;
    letter-spacing: .2px;
}

.rm-chatbot-sub {
    margin: 2px 0 0;
    font-size: .76rem;
    color: rgba(255, 255, 255, 0.85);
}

.rm-chatbot-head-actions {
    display: flex;
    gap: 8px;
}

.rm-chatbot-head-btn {
    border: 1px solid rgba(255, 255, 255, 0.35);
    background: rgba(255, 255, 255, 0.12);
    color: #fff;
    border-radius: 10px;
    width: 30px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.rm-chatbot-body {
    padding: 14px;
    background: linear-gradient(180deg, #f8fafc 0%, #f2f5fb 100%);
    overflow-y: auto;
    min-height: 260px;
    max-height: 360px;
}

.rm-chatbot-msg {
    max-width: 88%;
    padding: 10px 12px;
    border-radius: 14px;
    margin-bottom: 10px;
    line-height: 1.45;
    font-size: .92rem;
    word-wrap: break-word;
}

.rm-chatbot-msg.user {
    margin-left: auto;
    background: #10274f;
    color: #fff;
    border-bottom-right-radius: 6px;
}

.rm-chatbot-msg.bot {
    margin-right: auto;
    background: #fff;
    color: #24324a;
    border: 1px solid #e4e8f0;
    border-bottom-left-radius: 6px;
}

.rm-chatbot-typing {
    display: inline-flex;
    gap: 5px;
    align-items: center;
}

.rm-chatbot-typing span {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #90a1bc;
    animation: rm-chatbot-dot 1.1s infinite;
}

.rm-chatbot-typing span:nth-child(2) { animation-delay: 0.14s; }
.rm-chatbot-typing span:nth-child(3) { animation-delay: 0.28s; }

@keyframes rm-chatbot-dot {
    0%, 80%, 100% { transform: translateY(0); opacity: .5; }
    40% { transform: translateY(-3px); opacity: 1; }
}

.rm-chatbot-quick {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding: 10px 14px 0;
}

.rm-chatbot-chip {
    border: 1px solid #d8e1ee;
    background: #fff;
    color: #1b315d;
    border-radius: 999px;
    font-size: .75rem;
    font-weight: 700;
    padding: 6px 10px;
}

.rm-chatbot-foot {
    border-top: 1px solid #e8edf5;
    background: #fff;
    padding: 12px;
}

.rm-chatbot-input-row {
    display: flex;
    gap: 8px;
}

.rm-chatbot-input {
    width: 100%;
    border: 1px solid #d4dbe8;
    border-radius: 10px;
    padding: 10px 12px;
    font-size: .92rem;
    outline: none;
}

.rm-chatbot-input:focus {
    border-color: #8ca6d2;
    box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.12);
}

.rm-chatbot-send {
    min-width: 46px;
    border: 0;
    border-radius: 10px;
    background: linear-gradient(135deg, #ffb327, #e69500);
    color: #fff;
    font-size: 1rem;
}

.rm-chatbot-help {
    margin-top: 8px;
    color: #62728d;
    font-size: .74rem;
}

.back-to-top {
    width: 62px !important;
    height: 62px !important;
    border-radius: 50% !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    right: 24px !important;
    bottom: 24px !important;
    background: #e69500;
    border: 0 !important;
}

.back-to-top i {
    font-size: 1.2rem !important;
    line-height: 1 !important;
    margin: 0 !important;
}

@media (max-width: 576px) {
    .rm-chatbot-root {
        right: 86px;
        bottom: 12px;
    }
    .back-to-top {
        right: 12px !important;
        bottom: 12px !important;
    }
    .rm-chatbot-panel {
        position: fixed;
        left: 10px;
        right: 10px;
        width: auto;
        max-width: none;
        bottom: 84px;
        max-height: calc(100vh - 104px);
        border-radius: 16px;
    }
    .rm-chatbot-body {
        max-height: calc(100vh - 310px);
    }
}
</style>

<div id="rmChatbotRoot" class="rm-chatbot-root">
    <button id="rmChatToggle" class="rm-chatbot-toggle" type="button" aria-label="Open support chat">
        <i class="fa fa-comments"></i>
    </button>
    <div id="rmChatPanel" class="rm-chatbot-panel" role="dialog" aria-modal="false" aria-label="Support chatbot">
        <div class="rm-chatbot-head">
            <div>
                <p class="rm-chatbot-title">Pasar Kita </p>
                <p class="rm-chatbot-sub">Order help, menu info, and quick answers</p>
            </div>
            <div class="rm-chatbot-head-actions">
                <button id="rmChatClear" class="rm-chatbot-head-btn" type="button" title="Clear chat">
                    <i class="fa fa-redo"></i>
                </button>
                <button id="rmChatClose" class="rm-chatbot-head-btn" type="button" title="Close">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
        <div id="rmChatBody" class="rm-chatbot-body"></div>
        <div id="rmChatQuick" class="rm-chatbot-quick"></div>
        <div class="rm-chatbot-foot">
            <div class="rm-chatbot-input-row">
                <input id="rmChatInput" class="rm-chatbot-input" type="text" placeholder="Type message or order ID..." maxlength="240">
                <button id="rmChatSend" class="rm-chatbot-send" type="button" aria-label="Send">
                    <i class="fa fa-paper-plane"></i>
                </button>
            </div>
            <div class="rm-chatbot-help">Tips: type an order ID, "menu", "offers", or "delivery time".</div>
        </div>
    </div>
</div>

<script>
(function () {
    if (window.__rmChatbotBooted) return;
    window.__rmChatbotBooted = true;

    const userKey = <?php echo json_encode($chatbotUser, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
    const storageKey = `rmChatbotHistory_${userKey}`;
    const panel = document.getElementById("rmChatPanel");
    const body = document.getElementById("rmChatBody");
    const toggle = document.getElementById("rmChatToggle");
    const closeBtn = document.getElementById("rmChatClose");
    const clearBtn = document.getElementById("rmChatClear");
    const sendBtn = document.getElementById("rmChatSend");
    const input = document.getElementById("rmChatInput");
    const quickBox = document.getElementById("rmChatQuick");
    const root = document.getElementById("rmChatbotRoot");

    if (!panel || !body || !toggle || !closeBtn || !clearBtn || !sendBtn || !input || !quickBox || !root) {
        return;
    }

    const quickActions = [
        "Menu",
        "Order status",
        "Delivery time",
        "Offers",
        "Payment options"
    ];

    const replies = {
        hello: "Hi, how can I help you today?",
        hi: "Hello, how can I assist you?",
        menu: "Our menu includes pizza, burgers, pasta, biryani, and healthy bowls. Open the Menu page for full details.",
        contact: "You can reach support from the Contact page or call the number listed in site footer.",
        bye: "Thanks for chatting with us. Have a good day.",
        offers: "We run rotating deals every week. Check the homepage banners and festival pages for current discounts.",
        hours: "We are available daily from 9:00 AM to 11:00 PM.",
        location: "We currently serve multiple areas from partner restaurants. Enter your location at checkout to verify coverage.",
        specials: "Today specials vary by restaurant. Open categories and filter by top-rated.",
        recommend: "Popular picks: chef special pizza, alfredo pasta, and grilled platters.",
        "thank you": "You're welcome. I can also help with order tracking.",
        "payment options": "We support cash on delivery, cards, and online payment gateways.",
        "delivery time": "Typical delivery time is 30 to 45 minutes depending on your area and traffic.",
        "cancellation policy": "Orders can be cancelled shortly after placing. Contact support quickly for best chance.",
        "refund policy": "Refunds are processed to your original payment method, usually in 3 to 5 business days.",
        "vegetarian options": "Yes, we have dedicated vegetarian items across pizza, pasta, wraps, and thali categories.",
        // "non-veg options": "Yes, we offer chicken, mutton, seafood, and mixed grills from partner kitchens.",
        drinks: "Cold drinks, juices, shakes, and mocktails are available in the drinks section.",
        "kids menu": "Kids options include mini burgers, cheesy pasta, and fresh juices.",
        "allergy info": "Please mention allergies in checkout notes and verify ingredients with the restaurant before ordering.",
        "order status": "Please type your numeric order ID and I will fetch the latest status.",
        help: "I can answer menu, offers, payment, delivery, and order status questions."
    };

    function createQuickActions() {
        quickActions.forEach((label) => {
            const btn = document.createElement("button");
            btn.className = "rm-chatbot-chip";
            btn.type = "button";
            btn.textContent = label;
            btn.addEventListener("click", () => {
                input.value = label.toLowerCase();
                sendMessage();
            });
            quickBox.appendChild(btn);
        });
    }

    function scrollToBottom() {
        body.scrollTop = body.scrollHeight;
    }

    function appendMessage(type, text, save) {
        const node = document.createElement("div");
        node.className = `rm-chatbot-msg ${type}`;
        node.innerHTML = text;
        body.appendChild(node);
        scrollToBottom();

        if (save) {
            const history = JSON.parse(sessionStorage.getItem(storageKey) || "[]");
            history.push({ type, text });
            sessionStorage.setItem(storageKey, JSON.stringify(history));
        }
    }

    function renderHistory() {
        const history = JSON.parse(sessionStorage.getItem(storageKey) || "[]");
        if (history.length === 0) {
            appendMessage("bot", "Hi, I am your support assistant. Ask about menu, delivery, payment, or share your order ID.", false);
            return;
        }

        history.forEach((item) => appendMessage(item.type, item.text, false));
    }

    function typingBubble() {
        const bubble = document.createElement("div");
        bubble.className = "rm-chatbot-msg bot";
        bubble.innerHTML = '<span class="rm-chatbot-typing"><span></span><span></span><span></span></span>';
        body.appendChild(bubble);
        scrollToBottom();
        return bubble;
    }

    function fetchOrderStatus(orderId) {
        const loader = typingBubble();
        fetch("get_order_status.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `order_id=${encodeURIComponent(orderId)}`
        })
        .then((response) => response.json())
        .then((data) => {
            loader.remove();
            if (data && data.success) {
                const text = [
                    `<strong>Order ID:</strong> ${data.order_id}`,
                    `<strong>Customer:</strong> ${data.cus_name}`,
                    `<strong>Total:</strong> INR ${data.total_price}`,
                    `<strong>Status:</strong> ${data.order_status}`
                ].join("<br>");
                appendMessage("bot", text, true);
                return;
            }
            appendMessage("bot", "I could not find that order ID. Please verify and try again.", true);
        })
        .catch(() => {
            loader.remove();
            appendMessage("bot", "Order lookup is temporarily unavailable. Please try again shortly.", true);
        });
    }

    function buildReply(message) {
        const normalized = message.toLowerCase();
        if (/^\d+$/.test(normalized)) {
            fetchOrderStatus(normalized);
            return null;
        }

        for (const key in replies) {
            if (normalized.includes(key)) {
                return replies[key];
            }
        }
        return "I can help with menu, offers, delivery, payment, and order tracking. Try typing 'help'.";
    }

    function sendMessage() {
        const message = input.value.trim();
        if (!message) return;

        appendMessage("user", message, true);
        input.value = "";

        const reply = buildReply(message);
        if (reply === null) return;

        const loader = typingBubble();
        setTimeout(() => {
            loader.remove();
            appendMessage("bot", reply, true);
        }, 450);
    }

    function openPanel() {
        panel.classList.add("is-open");
        toggle.style.display = "none";
        setTimeout(() => input.focus(), 50);
    }

    function closePanel() {
        panel.classList.remove("is-open");
        toggle.style.display = "inline-flex";
    }

    toggle.addEventListener("click", openPanel);
    closeBtn.addEventListener("click", closePanel);
    sendBtn.addEventListener("click", sendMessage);
    input.addEventListener("keydown", (event) => {
        if (event.key === "Enter") {
            event.preventDefault();
            sendMessage();
        }
    });
    clearBtn.addEventListener("click", () => {
        sessionStorage.removeItem(storageKey);
        body.innerHTML = "";
        appendMessage("bot", "Chat cleared. How can I help you now?", false);
    });

    createQuickActions();
    renderHistory();
})();
</script>
