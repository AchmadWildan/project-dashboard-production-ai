@extends('layouts.user_type.auth')

@section('content')

<style>
  /* =============================================
     CHATBOT — Premium AI Chat Interface
     ============================================= */

  /* ─── Shell ─── */
  .chat-shell {
    display: flex;
    flex-direction: column;
    height: calc(100vh - 130px);
    min-height: 520px;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,.13);
    background: #fff;
  }

  /* ─── Header ─── */
  .chat-header {
    background: linear-gradient(135deg, #06b6d4 0%, #a855f7 100%);
    padding: 1.1rem 1.5rem;
    position: relative;
    overflow: hidden;
    flex-shrink: 0;
  }
  .chat-header::before {
    content: '';
    position: absolute;
    top: -30px; right: -30px;
    width: 160px; height: 160px;
    border-radius: 50%;
    background: rgba(255,255,255,.08);
  }
  .chat-header::after {
    content: '';
    position: absolute;
    bottom: -50px; left: 30%;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,.05);
  }

  .chat-avatar {
    width: 44px; height: 44px;
    border-radius: 50%;
    background: rgba(255,255,255,.2);
    border: 2px solid rgba(255,255,255,.4);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 1.1rem;
  }
  .chat-ai-name {
    font-size: 1rem;
    font-weight: 700;
    color: #fff;
    margin: 0;
    line-height: 1.2;
  }
  .chat-ai-sub {
    font-size: .72rem;
    color: rgba(255,255,255,.75);
    margin: 0;
  }
  .chat-online-dot {
    display: inline-block;
    width: 8px; height: 8px;
    border-radius: 50%;
    background: #4ade80;
    margin-right: 5px;
    animation: chat-pulse 1.8s infinite;
  }
  @keyframes chat-pulse {
    0%   { box-shadow: 0 0 0 0 rgba(74,222,128,.7); }
    70%  { box-shadow: 0 0 0 7px rgba(74,222,128,0); }
    100% { box-shadow: 0 0 0 0 rgba(74,222,128,0); }
  }

  .chat-clear-btn {
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.3);
    color: #fff;
    border-radius: 10px;
    padding: .4rem .85rem;
    font-size: .75rem;
    font-weight: 600;
    cursor: pointer;
    transition: all .2s ease;
    display: flex;
    align-items: center;
    gap: 5px;
  }
  .chat-clear-btn:hover {
    background: rgba(255,255,255,.28);
    border-color: rgba(255,255,255,.5);
    color: #fff;
  }

  /* ─── Suggestion chips ─── */
  .chat-chips-bar {
    background: #f8fafc;
    border-bottom: 1px solid #f1f5f9;
    padding: .6rem 1.2rem;
    display: flex;
    gap: .5rem;
    flex-wrap: nowrap;
    overflow-x: auto;
    flex-shrink: 0;
  }
  .chat-chips-bar::-webkit-scrollbar { height: 0; }
  .chat-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 50px;
    padding: .3rem .85rem;
    font-size: .72rem;
    font-weight: 500;
    color: #475569;
    cursor: pointer;
    white-space: nowrap;
    transition: all .2s ease;
    flex-shrink: 0;
  }
  .chat-chip:hover {
    background: linear-gradient(135deg,#06b6d4,#a855f7);
    color: #fff;
    border-color: transparent;
  }
  .chat-chip i { font-size: .65rem; }

  /* ─── Messages area ─── */
  #chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 1.5rem 1.5rem 1rem;
    background: #f8fafc;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    scroll-behavior: smooth;
  }
  #chat-messages::-webkit-scrollbar { width: 5px; }
  #chat-messages::-webkit-scrollbar-track { background: transparent; }
  #chat-messages::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
  #chat-messages::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

  /* date separator */
  .chat-date-sep {
    display: flex;
    align-items: center;
    gap: .75rem;
    color: #94a3b8;
    font-size: .68rem;
    font-weight: 600;
    letter-spacing: .5px;
    text-transform: uppercase;
  }
  .chat-date-sep::before,
  .chat-date-sep::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }

  /* ─── Individual messages ─── */
  .chat-msg { display: flex; gap: .75rem; align-items: flex-end; max-width: 82%; }
  .chat-msg.ai  { align-self: flex-start; }
  .chat-msg.usr { align-self: flex-end;   flex-direction: row-reverse; }

  .msg-avatar {
    width: 34px; height: 34px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .85rem;
    flex-shrink: 0;
  }
  .msg-avatar.ai  { background: linear-gradient(135deg,#06b6d4,#a855f7); color: #fff; }
  .msg-avatar.usr { background: linear-gradient(135deg,#6366f1,#818cf8); color: #fff; }

  .msg-bubble {
    padding: .8rem 1.1rem;
    border-radius: 18px;
    font-size: .85rem;
    line-height: 1.6;
    color: #1e293b;
    position: relative;
    max-width: 100%;
    word-break: break-word;
  }
  .chat-msg.ai  .msg-bubble {
    background: #fff;
    border-bottom-left-radius: 6px;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
  }
  .chat-msg.usr .msg-bubble {
    background: linear-gradient(135deg, #6366f1, #a855f7);
    color: #fff;
    border-bottom-right-radius: 6px;
    box-shadow: 0 4px 16px rgba(99,102,241,.35);
  }
  .msg-time {
    font-size: .63rem;
    color: #94a3b8;
    margin-top: .35rem;
    padding: 0 .2rem;
  }
  .chat-msg.usr .msg-time { text-align: right; }

  /* code blocks inside ai bubble */
  .msg-bubble code,
  .msg-bubble pre {
    font-family: 'SFMono-Regular', 'Consolas', monospace;
    font-size: .8rem;
  }
  .msg-bubble pre {
    background: #f1f5f9;
    border-radius: 10px;
    padding: .75rem 1rem;
    margin: .5rem 0 0;
    overflow-x: auto;
    border-left: 3px solid #a855f7;
  }

  /* ─── Typing indicator ─── */
  .typing-indicator { display: flex; gap: 4px; align-items: center; padding: .3rem 0; }
  .typing-indicator span {
    display: inline-block;
    width: 7px; height: 7px;
    background: #a855f7;
    border-radius: 50%;
    animation: typing 1.4s infinite ease-in-out both;
  }
  .typing-indicator span:nth-child(1) { animation-delay: -0.32s; }
  .typing-indicator span:nth-child(2) { animation-delay: -0.16s; }
  @keyframes typing {
    0%, 80%, 100% { transform: scale(0); opacity: .3; }
    40% { transform: scale(1); opacity: 1; }
  }

  /* ─── Input footer ─── */
  .chat-footer {
    background: #fff;
    border-top: 1px solid #f1f5f9;
    padding: 1rem 1.4rem;
    flex-shrink: 0;
  }
  .chat-input-wrap {
    display: flex;
    align-items: flex-end;
    gap: .75rem;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 18px;
    padding: .6rem .6rem .6rem 1rem;
    transition: border-color .2s ease, box-shadow .2s ease;
  }
  .chat-input-wrap:focus-within {
    border-color: #a855f7;
    box-shadow: 0 0 0 3px rgba(168,85,247,.12);
  }
  #message-input {
    flex: 1;
    border: none;
    background: transparent;
    resize: none;
    outline: none;
    font-size: .88rem;
    color: #1e293b;
    min-height: 38px;
    max-height: 120px;
    line-height: 1.5;
    padding: .2rem 0;
  }
  #message-input::placeholder { color: #94a3b8; }

  .chat-send-btn {
    width: 40px; height: 40px;
    border-radius: 12px;
    border: none;
    background: linear-gradient(135deg, #06b6d4, #a855f7);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    transition: transform .15s ease, box-shadow .15s ease;
    box-shadow: 0 4px 14px rgba(168,85,247,.4);
  }
  .chat-send-btn:hover { transform: scale(1.06); box-shadow: 0 6px 18px rgba(168,85,247,.5); }
  .chat-send-btn:active { transform: scale(.96); }

  .chat-hint {
    font-size: .67rem;
    color: #94a3b8;
    text-align: center;
    margin-top: .55rem;
  }
</style>

<div class="row justify-content-center">
  <div class="col-12 col-xl-10">

    <div class="chat-shell">

      {{-- ── Header ── --}}
      <div class="chat-header d-flex align-items-center justify-content-between position-relative" style="z-index:1">
        <div class="d-flex align-items-center gap-3">
          <div class="chat-avatar">🤖</div>
          <div>
            <p class="chat-ai-name">SQL AI Assistant</p>
            <p class="chat-ai-sub">
              <span class="chat-online-dot"></span>Online · Analitik Data Produksi
            </p>
          </div>
        </div>
        <div class="d-flex align-items-center gap-2">
          <span class="badge" style="background:rgba(255,255,255,.15);color:#fff;font-size:.68rem;padding:.3rem .7rem;border-radius:50px;border:1px solid rgba(255,255,255,.25)">Mode Analitik</span>
          <button class="chat-clear-btn" onclick="clearChat()">
            <i class="fas fa-trash-alt"></i> Clear
          </button>
        </div>
      </div>

      {{-- ── Suggestion chips ── --}}
      <div class="chat-chips-bar">
        <button class="chat-chip" onclick="fillPrompt('Berapa total output produksi hari ini?')">
          <i class="fas fa-chart-line"></i> Output hari ini
        </button>
        <button class="chat-chip" onclick="fillPrompt('Mesin mana yang offline saat ini?')">
          <i class="fas fa-exclamation-triangle"></i> Mesin offline
        </button>
        <button class="chat-chip" onclick="fillPrompt('Tampilkan OEE per mesin Plant 1 minggu ini')">
          <i class="fas fa-tachometer-alt"></i> OEE Plant 1
        </button>
        <button class="chat-chip" onclick="fillPrompt('Bandingkan produktivitas Plant 1 dan Plant 2 bulan ini')">
          <i class="fas fa-balance-scale"></i> Perbandingan plant
        </button>
        <button class="chat-chip" onclick="fillPrompt('Kirimkan ringkasan status pintu gudang WH Unit 1')">
          <i class="fas fa-door-open"></i> Status pintu WH
        </button>
      </div>

      {{-- ── Messages ── --}}
      <div id="chat-messages">

        {{-- Date separator --}}
        <div class="chat-date-sep">{{ now()->isoFormat('dddd, D MMMM YYYY') }}</div>

        {{-- Welcome message --}}
        <div class="chat-msg ai">
          <div class="msg-avatar ai">🤖</div>
          <div>
            <div class="msg-bubble">
              Halo! Saya <strong>SQL AI Assistant</strong> — siap membantu analisis data produksi. 👋<br><br>
              Anda bisa bertanya seperti:<br>
              • <em>"Berapa output Line A hari ini?"</em><br>
              • <em>"Mesin mana yang OEE-nya di bawah 80%?"</em><br>
              • <em>"Tampilkan tren produksi minggu ini"</em>
            </div>
            <div class="msg-time"><i class="far fa-clock me-1"></i>{{ now()->format('H:i') }}</div>
          </div>
        </div>

      </div>

      {{-- ── Input ── --}}
      <div class="chat-footer">
        <form id="chat-form" onsubmit="sendMessage(event)">
          <div class="chat-input-wrap">
            <textarea id="message-input"
              placeholder="Ketik pertanyaan tentang data produksi…"
              rows="1"
              onkeydown="handleKeydown(event)"
              oninput="autoResize(this)"></textarea>
            <button type="submit" class="chat-send-btn">
              <i class="fas fa-paper-plane" style="font-size:.85rem"></i>
            </button>
          </div>
          <p class="chat-hint">⚠️ AI dapat membuat kesalahan. Mohon verifikasi hasil query sebelum digunakan.</p>
        </form>
      </div>

    </div>{{-- /chat-shell --}}

  </div>
</div>

<script>
  /* auto-resize textarea */
  function autoResize(el) {
    el.style.height = 'auto';
    el.style.height = Math.min(el.scrollHeight, 120) + 'px';
  }
  /* submit on Enter (Shift+Enter = newline) */
  function handleKeydown(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      document.getElementById('chat-form').dispatchEvent(new Event('submit'));
    }
  }
  /* fill input from chip */
  function fillPrompt(text) {
    const inp = document.getElementById('message-input');
    inp.value = text;
    inp.focus();
    autoResize(inp);
  }
</script>

@endsection

@push('dashboard')
<script src="{{ asset('js/chat-to-ai.js') }}"></script>
@endpush
