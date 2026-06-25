<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Website</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="content">
        @yield('content') {{-- Yahan aapke baaki pages ka data aayega --}}
    </div>

    {{-- Navbar ke andar jahan icons hain, wahan ye check lagayein --}}
@auth
  @if(in_array(auth()->user()->role, ['customer', 'tailor']))
    
    <div class="dropdown me-3">
      <a href="#" class="position-relative text-dark" id="notifBell" data-bs-toggle="dropdown">
        <i class="fas fa-bell fs-5"></i>
        <span id="notifBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:10px; display:none;">
          0
        </span>
      </a>

      {{-- Dropdown Menu --}}
      <div class="dropdown-menu dropdown-menu-end shadow" style="width:320px; max-height:400px; overflow-y:auto">
        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
          <span class="fw-bold">Notifications</span>
          <form method="POST" action="{{ route('notifications.readAll') }}">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-link btn-sm p-0 text-muted">
              Mark all read
            </button>
          </form>
        </div>

        @php
          $latestNotifs = auth()->user()->notifications()->latest()->take(5)->get();
        @endphp

        @forelse($latestNotifs as $notif)
        <a href="{{ route('notifications.read', $notif->id) }}" class="dropdown-item py-2 px-3 border-bottom {{ $notif->is_read ? '' : 'bg-light' }}">
          <div class="fw-bold small">{{ $notif->title }}</div>
          <div class="text-muted" style="font-size:12px; white-space:normal">
            {{ Str::limit($notif->message, 60) }}
          </div>
          <div class="text-muted" style="font-size:11px">
            {{ $notif->created_at->diffForHumans() }}
          </div>
        </a>
        @empty
        <div class="text-center text-muted py-3" style="font-size:13px">
          Koi notification nahi
        </div>
        @endforelse

        <div class="text-center py-2">
          <a href="{{ route('notifications.index') }}" class="text-primary small">View All</a>
        </div>
      </div>
    </div>

    {{-- Script for Auto-Update --}}
    <script>
    function updateNotifBadge() {
      fetch('{{ route("notifications.count") }}')
        .then(r => r.json())
        .then(data => {
          const badge = document.getElementById('notifBadge');
          if (badge && data.count > 0) {
            badge.textContent = data.count > 9 ? '9+' : data.count;
            badge.style.display = 'block';
          } else if (badge) {
            badge.style.display = 'none';
          }
        });
    }
    updateNotifBadge();
    setInterval(updateNotifBadge, 30000); // 30 Seconds Auto Refresh
    </script>
    @endif
@endauth

    {{-- ══════════════════════════════════════ --}}
{{-- AI CHATBOT WIDGET                      --}}
{{-- ══════════════════════════════════════ --}}

{{-- Chatbot button — bottom right corner --}}
<button id="chatToggle"
        style="position:fixed; bottom:24px; right:24px; z-index:9999;
               width:56px; height:56px; border-radius:50%; border:none;
               background:linear-gradient(135deg,#1B2A4A,#212529);
               color:white; font-size:22px; cursor:pointer;
               box-shadow: 0 4px 15px rgba(0,0,0,0.3);
               transition: transform 0.2s ease;"
        onmouseover="this.style.transform='scale(1.1)'"
        onmouseout="this.style.transform='scale(1)'"
        title="Chat with Stitch AI">
  <i class="fas fa-robot" id="chatIcon"></i>
</button>

{{-- Chatbot Window --}}
<div id="chatWindow"
     style="display:none; position:fixed; bottom:90px; right:24px;
            z-index:9998; width:340px; height:480px;
            background:white; border-radius:16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            flex-direction:column; overflow:hidden;">

  {{-- Header --}}
  <div style="background:linear-gradient(135deg,#1B2A4A,#212529);
              padding:16px; color:white; display:flex;
              align-items:center; justify-content:space-between;">
    <div style="display:flex; align-items:center; gap:10px">
      <div style="width:36px;height:36px;border-radius:50%;
                  background:rgba(255,255,255,0.2);
                  display:flex;align-items:center;justify-content:center">
        <i class="fas fa-robot"></i>
      </div>
      <div>
        <div style="font-weight:600; font-size:14px">Stitch AI</div>
        <div style="font-size:11px; opacity:0.8">Always here to help</div>
      </div>
    </div>
    <button onclick="toggleChat()"
            style="background:none;border:none;color:white;
                   font-size:18px;cursor:pointer;opacity:0.8">
      <i class="fas fa-times"></i>
    </button>
  </div>

  {{-- Messages area --}}
  <div id="chatMessages"
       style="flex:1; overflow-y:auto; padding:16px;
              display:flex; flex-direction:column; gap:10px;
              background:#f8f9fa;">

    {{-- Welcome message --}}
    <div style="display:flex; gap:8px; align-items:flex-start">
      <div style="width:28px;height:28px;border-radius:50%;
                  background:#1B2A4A;display:flex;
                  align-items:center;justify-content:center;
                  flex-shrink:0">
        <i class="fas fa-robot text-white" style="font-size:12px"></i>
      </div>
      <div style="background:white; padding:10px 14px;
                  border-radius:0 12px 12px 12px;
                  font-size:13px; max-width:80%;
                  box-shadow:0 1px 3px rgba(0,0,0,0.1)">
        Hi! I'm Stitch, your Stitchify assistant 👋
        How can I help you today?
      </div>
    </div>
  </div>

  {{-- Input area --}}
  <div style="padding:12px; border-top:1px solid #e0e0e0;
              background:white; display:flex; gap:8px">
    <input type="text" id="chatInput"
           placeholder="Type your message..."
           style="flex:1; border:2px solid #e0e0e0; border-radius:20px;
                  padding:8px 14px; font-size:13px; outline:none;
                  transition: border-color 0.2s"
           onfocus="this.style.borderColor='#1B2A4A'"
           onblur="this.style.borderColor='#e0e0e0'"
           onkeypress="if(event.key==='Enter') sendMessage()">
    <button onclick="sendMessage()"
            style="width:38px;height:38px;border-radius:50%;
                   background:linear-gradient(135deg,#1B2A4A,#212529);
                   border:none;color:white;cursor:pointer;
                   display:flex;align-items:center;justify-content:center"
            id="sendBtn">
      <i class="fas fa-paper-plane" style="font-size:14px"></i>
    </button>
  </div>
</div>

<script>
let chatOpen = false;

function toggleChat() {
  chatOpen = !chatOpen;
  const win  = document.getElementById('chatWindow');
  const icon = document.getElementById('chatIcon');
  win.style.display  = chatOpen ? 'flex' : 'none';
  icon.className     = chatOpen ? 'fas fa-times' : 'fas fa-robot';
}

document.getElementById('chatToggle')
        .addEventListener('click', toggleChat);

function addMessage(text, isUser) {
  const msgs = document.getElementById('chatMessages');

  const wrapper = document.createElement('div');
  wrapper.style.cssText = `
    display:flex;
    gap:8px;
    align-items:flex-start;
    ${isUser ? 'flex-direction:row-reverse' : ''}
  `;

  // Avatar
  const avatar = document.createElement('div');
  avatar.style.cssText = `
    width:28px;height:28px;border-radius:50%;
    background:${isUser ? '#e0e0e0' : '#1B2A4A'};
    display:flex;align-items:center;
    justify-content:center;flex-shrink:0
  `;
  avatar.innerHTML = isUser
    ? '<i class="fas fa-user" style="font-size:12px;color:#555"></i>'
    : '<i class="fas fa-robot text-white" style="font-size:12px"></i>';

  // Bubble
  const bubble = document.createElement('div');
  bubble.style.cssText = `
    background:${isUser
      ? 'linear-gradient(135deg,#1B2A4A,#212529)'
      : 'white'};
    color:${isUser ? 'white' : '#212529'};
    padding:10px 14px;
    border-radius:${isUser ? '12px 0 12px 12px' : '0 12px 12px 12px'};
    font-size:13px;max-width:80%;
    box-shadow:0 1px 3px rgba(0,0,0,0.1);
    line-height:1.5;white-space:pre-wrap
  `;
  bubble.textContent = text;

  wrapper.appendChild(avatar);
  wrapper.appendChild(bubble);
  msgs.appendChild(wrapper);

  // Scroll to bottom
  msgs.scrollTop = msgs.scrollHeight;
}

function showTyping() {
  const msgs = document.getElementById('chatMessages');
  const div  = document.createElement('div');
  div.id     = 'typingIndicator';
  div.style.cssText = 'display:flex;gap:8px;align-items:center';
  div.innerHTML = `
    <div style="width:28px;height:28px;border-radius:50%;
                background:#1B2A4A;display:flex;
                align-items:center;justify-content:center">
      <i class="fas fa-robot text-white" style="font-size:12px"></i>
    </div>
    <div style="background:white;padding:10px 14px;
                border-radius:0 12px 12px 12px;
                box-shadow:0 1px 3px rgba(0,0,0,0.1)">
      <span style="display:flex;gap:4px;align-items:center">
        <span style="width:6px;height:6px;border-radius:50%;
                     background:#999;animation:bounce 1s infinite"></span>
        <span style="width:6px;height:6px;border-radius:50%;
                     background:#999;animation:bounce 1s infinite 0.2s"></span>
        <span style="width:6px;height:6px;border-radius:50%;
                     background:#999;animation:bounce 1s infinite 0.4s"></span>
      </span>
    </div>
  `;
  msgs.appendChild(div);
  msgs.scrollTop = msgs.scrollHeight;
}

function hideTyping() {
  const el = document.getElementById('typingIndicator');
  if (el) el.remove();
}

async function sendMessage() {
  const input   = document.getElementById('chatInput');
  const sendBtn = document.getElementById('sendBtn');
  const message = input.value.trim();

  if (!message) return;

  // User message show karo
  addMessage(message, true);
  input.value    = '';
  sendBtn.disabled = true;

  // Typing indicator
  showTyping();

  try {
    const response = await fetch('{{ route("chatbot.reply") }}', {
      method:  'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
      },
      body: JSON.stringify({ message }),
    });

    const data = await response.json();
    hideTyping();
    addMessage(data.reply, false);

  } catch (err) {
    hideTyping();
    addMessage('Sorry, something went wrong. Please try again.', false);
  } finally {
    sendBtn.disabled = false;
    input.focus();
  }
}

// Bounce animation for typing dots
const style = document.createElement('style');
style.textContent = `
  @keyframes bounce {
    0%, 60%, 100% { transform: translateY(0) }
    30%            { transform: translateY(-6px) }
  }
`;
document.head.appendChild(style);
</script>
    <button id="chatToggle" ...> ... </button>
    <div id="chatWindow" ...> ... </div>
    <script> ... </script>

</body>
</html>