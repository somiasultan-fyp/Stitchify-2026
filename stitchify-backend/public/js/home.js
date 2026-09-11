let chatOpen = false;

function toggleChat() {
    chatOpen = !chatOpen;
    const win = document.getElementById('chatWindow');
    const icon = document.getElementById('chatIcon');
    win.style.display = chatOpen ? 'flex' : 'none';
    icon.className = chatOpen ? 'fas fa-times' : 'fas fa-robot';
}

document.getElementById('chatToggle').addEventListener('click', toggleChat);

function addMessage(text, isUser) {
    const msgs = document.getElementById('chatMessages');
    const wrapper = document.createElement('div');
    wrapper.style.cssText = `display:flex;gap:8px;align-items:flex-start;${isUser ? 'flex-direction:row-reverse' : ''}`;

    const avatar = document.createElement('div');
    avatar.style.cssText = `width:28px;height:28px;border-radius:50%;background:${isUser ? '#e0e0e0' : '#1B2A4A'};display:flex;align-items:center;justify-content:center;flex-shrink:0`;
    avatar.innerHTML = isUser
        ? '<i class="fas fa-user" style="font-size:12px;color:#555"></i>'
        : '<i class="fas fa-robot text-white" style="font-size:12px"></i>';

    const bubble = document.createElement('div');
    bubble.style.cssText = `background:${isUser ? 'linear-gradient(135deg,#1B2A4A,#212529)' : 'white'};color:${isUser ? 'white' : '#212529'};padding:10px 14px;border-radius:${isUser ? '12px 0 12px 12px' : '0 12px 12px 12px'};font-size:13px;max-width:80%;box-shadow:0 1px 3px rgba(0,0,0,0.1);line-height:1.5;`;
    bubble.textContent = text;

    wrapper.appendChild(avatar);
    wrapper.appendChild(bubble);
    msgs.appendChild(wrapper);
    msgs.scrollTop = msgs.scrollHeight;
}

async function sendMessage() {
    const input = document.getElementById('chatInput');
    const sendBtn = document.getElementById('sendBtn');
    const message = input.value.trim();
    if (!message) return;

    addMessage(message, true);
    input.value = '';
    sendBtn.disabled = true;

    try {
        const response = await fetch('/chatbot', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({ message }),
        });
        const data = await response.json();
        addMessage(data.reply || 'Sorry, try again!', false);
    } catch (err) {
        addMessage('Sorry, something went wrong. Please try again.', false);
    } finally {
        sendBtn.disabled = false;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const videoModal = document.getElementById('videoModal');
    if (videoModal) {
        const measurementVideo = videoModal.querySelector('video');
        videoModal.addEventListener('hidden.bs.modal', function () {
            if (measurementVideo) {
                measurementVideo.pause();
                measurementVideo.currentTime = 0;
            }
        });
    }
});