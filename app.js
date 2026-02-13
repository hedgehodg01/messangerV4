let currentChatId = null;
let lastCache = "";

document.addEventListener('DOMContentLoaded', () => {
    const msgInput = document.getElementById('msg-input');
    const sendBtn = document.getElementById('send-btn');
    const msgList = document.getElementById('messages-list');

    window.toggleModal = (s) => document.getElementById('profile-modal').style.display = s ? 'flex' : 'none';

    // Функция отправки
    window.sendMsg = async () => {
        const text = msgInput.value.trim();
        if (!text || !currentChatId) return;

        const fd = new FormData();
        fd.append('chat_id', currentChatId);
        fd.append('message', text);

        msgInput.value = ''; // Очищаем поле сразу
        await fetch('api.php?action=send', { method: 'POST', body: fd });
        loadMessages();
    };

    // Загрузка сообщений
    async function loadMessages() {
        if (!currentChatId) return;
        const r = await fetch(`api.php?action=fetch&chat_id=${currentChatId}`);
        const msgs = await r.json();

        if (JSON.stringify(msgs) === lastCache) return;
        lastCache = JSON.stringify(msgs);

        msgList.innerHTML = msgs.map(m => `
            <div class="msg ${m.user_id == MY_ID ? 'my' : ''}">
                <div class="msg-bubble">
                    <small style="opacity:0.5">${m.username}</small><br>${m.message}
                </div>
            </div>
        `).join('');
        msgList.scrollTop = msgList.scrollHeight;
    }

    // Обработчики
    sendBtn.onclick = sendMsg;
    msgInput.onkeydown = (e) => { if(e.key === 'Enter') sendMsg(); };

    // Загрузка чатов
    window.loadChats = async () => {
        const r = await fetch('api.php?action=get_chats');
        const chats = await r.json();
        const list = document.getElementById('chat-list');
        list.innerHTML = chats.map(c => `
            <div class="chat-item ${c.id == currentChatId ? 'active' : ''}" 
                 onclick="selectChat(${c.id})"># ${c.name}</div>
        `).join('');
    };

    window.selectChat = (id) => {
        currentChatId = id;
        lastCache = "";
        loadMessages();
        loadChats();
    };

    window.createChat = async () => {
        const n = prompt("Имя чата:");
        if(!n) return;
        const fd = new FormData(); fd.append('name', n);
        await fetch('api.php?action=create_chat', {method:'POST', body:fd});
        loadChats();
    };

    setInterval(loadMessages, 2000);
    loadChats();
});