// ELARA Parlor & Spa - Floating AI Assistant Client Logic

let isChatOpen = false;
const chatHistory = [];

function toggleElaraChat() {
    const modal = document.getElementById('elaraChatModal');
    if (!modal) return;

    isChatOpen = !isChatOpen;
    if (isChatOpen) {
        modal.classList.remove('translate-x-full');
        document.getElementById('chatInput')?.focus();
    } else {
        modal.classList.add('translate-x-full');
    }
}

function sendQuickPrompt(promptText) {
    const input = document.getElementById('chatInput');
    if (input) {
        input.value = promptText;
        handleChatSubmit(new Event('submit'));
    }
}

async function handleChatSubmit(event) {
    if (event) event.preventDefault();

    const input = document.getElementById('chatInput');
    const messagesContainer = document.getElementById('chatMessages');
    if (!input || !messagesContainer) return;

    const message = input.value.trim();
    if (!message) return;

    // Append user message
    appendMessage('user', message);
    input.value = '';

    // Record in history
    chatHistory.push({ sender: 'user', text: message });

    // Show loading typing indicator
    const typingId = showTypingIndicator();

    try {
        const response = await fetch('/api/elara/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                message: message,
                history: chatHistory.slice(-6)
            })
        });

        const data = await response.json();
        removeTypingIndicator(typingId);

        if (data.success && data.reply) {
            appendMessage('model', data.reply, data.provider);
            chatHistory.push({ sender: 'model', text: data.reply });
        } else {
            appendMessage('model', data.error || 'Sorry, I encountered an issue processing your query.');
        }
    } catch (err) {
        removeTypingIndicator(typingId);
        appendMessage('model', 'Network error contacting ELARA AI server. Please check connection.');
        console.error('ELARA Chat error:', err);
    }
}

function appendMessage(sender, text, provider = null) {
    const container = document.getElementById('chatMessages');
    if (!container) return;

    const wrapper = document.createElement('div');
    wrapper.className = 'flex items-start space-x-2' + (sender === 'user' ? ' justify-end' : '');

    const formattedText = renderMarkdownMini(text);

    if (sender === 'user') {
        wrapper.innerHTML = `
            <div class="bg-rose-600 text-white p-3 rounded-2xl rounded-tr-none text-xs leading-relaxed max-w-[85%] shadow-sm">
                ${escapeHtml(text)}
            </div>
            <div class="w-7 h-7 rounded-full bg-slate-800 text-white flex items-center justify-center text-[10px] shrink-0 mt-0.5 font-bold">You</div>
        `;
    } else {
        const badge = provider ? `<span class="inline-block px-1.5 py-0.5 bg-rose-50 text-rose-700 text-[9px] font-bold rounded mt-1 border border-rose-200">${provider}</span>` : '';
        wrapper.innerHTML = `
            <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-rose-700 to-rose-500 text-white flex items-center justify-center text-xs shrink-0 mt-0.5 font-bold shadow-2xs">E</div>
            <div class="bg-white p-3.5 rounded-2xl rounded-tl-none border border-rose-100 shadow-2xs text-xs text-slate-800 leading-relaxed max-w-[85%]">
                ${formattedText}
                ${badge}
            </div>
        `;
    }

    container.appendChild(wrapper);
    container.scrollTop = container.scrollHeight;
}

function showTypingIndicator() {
    const container = document.getElementById('chatMessages');
    const id = 'typing_' + Date.now();
    const div = document.createElement('div');
    div.id = id;
    div.className = 'flex items-start space-x-2';
    div.innerHTML = `
        <div class="w-7 h-7 rounded-full bg-rose-600 text-white flex items-center justify-center text-xs shrink-0 mt-0.5 font-bold">E</div>
        <div class="bg-white px-3.5 py-2.5 rounded-2xl rounded-tl-none border border-rose-100 shadow-2xs flex items-center space-x-1.5">
            <span class="w-2 h-2 rounded-full bg-rose-400 animate-bounce"></span>
            <span class="w-2 h-2 rounded-full bg-rose-500 animate-bounce" style="animation-delay: 0.15s"></span>
            <span class="w-2 h-2 rounded-full bg-rose-600 animate-bounce" style="animation-delay: 0.3s"></span>
        </div>
    `;
    container.appendChild(div);
    container.scrollTop = container.scrollHeight;
    return id;
}

function removeTypingIndicator(id) {
    const el = document.getElementById(id);
    if (el) el.remove();
}

function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

function renderMarkdownMini(text) {
    let out = text;
    // Code blocks
    out = out.replace(/```([a-z]*)\n([\s\S]*?)```/gi, '<pre class="bg-slate-900 text-slate-100 p-2.5 rounded-lg my-1.5 text-[11px] overflow-x-auto"><code>$2</code></pre>');
    // Inline code
    out = out.replace(/`([^`]+)`/g, '<code class="bg-slate-100 text-rose-700 px-1 py-0.5 rounded text-[11px] font-mono">$1</code>');
    // Bold
    out = out.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
    // Italic
    out = out.replace(/_([^_]+)_/g, '<em>$1</em>');
    // Bullet points
    out = out.replace(/^\s*•\s*(.+)$/gm, '<li class="ml-3 list-disc">$1</li>');
    out = out.replace(/^\s*\*\s*(.+)$/gm, '<li class="ml-3 list-disc">$1</li>');
    // Numbers
    out = out.replace(/^\s*(\d+)\.\s*(.+)$/gm, '<li class="ml-3 list-decimal">$2</li>');
    // Newlines
    out = out.replace(/\n\n/g, '<br><br>');
    out = out.replace(/\n/g, '<br>');

    return out;
}

