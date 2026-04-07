<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>WHOIS | Mira</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&amp;family=Inter:wght@300;400;500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "on-background": "#1a1c1c",
                    "on-primary": "#e2e2e2",
                    "secondary": "#5e5e5e",
                    "primary-fixed": "#5e5e5e",
                    "surface-container": "#eeeeee",
                    "on-secondary-fixed-variant": "#3b3b3c",
                    "on-tertiary-container": "#ffffff",
                    "tertiary-container": "#737575",
                    "surface-bright": "#f9f9f9",
                    "tertiary-fixed-dim": "#454747",
                    "surface-variant": "#e2e2e2",
                    "on-secondary": "#ffffff",
                    "surface-container-high": "#e8e8e8",
                    "surface": "#f9f9f9",
                    "secondary-fixed-dim": "#acabab",
                    "primary-fixed-dim": "#474747",
                    "outline-variant": "#c6c6c6",
                    "error": "#ba1a1a",
                    "surface-container-lowest": "#ffffff",
                    "on-primary-fixed": "#ffffff",
                    "background": "#f9f9f9",
                    "on-primary-fixed-variant": "#e2e2e2",
                    "secondary-container": "#d5d4d4",
                    "surface-dim": "#dadada",
                    "on-tertiary": "#e2e2e2",
                    "inverse-primary": "#c6c6c6",
                    "on-primary-container": "#ffffff",
                    "error-container": "#ffdad6",
                    "on-error-container": "#410002",
                    "on-tertiary-fixed": "#ffffff",
                    "surface-tint": "#5e5e5e",
                    "on-error": "#ffffff",
                    "primary": "#000000",
                    "on-surface-variant": "#474747",
                    "surface-container-highest": "#e2e2e2",
                    "on-secondary-fixed": "#1b1c1c",
                    "surface-container-low": "#f3f3f3",
                    "on-secondary-container": "#1b1c1c",
                    "primary-container": "#3b3b3b",
                    "inverse-surface": "#2f3131",
                    "tertiary-fixed": "#5d5f5f",
                    "inverse-on-surface": "#f1f1f1",
                    "on-surface": "#1a1c1c",
                    "outline": "#777777",
                    "tertiary": "#3a3c3c",
                    "secondary-fixed": "#c7c6c6",
                    "on-tertiary-fixed-variant": "#e2e2e2"
            },
            "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
            },
            "fontFamily": {
                    "headline": ["Manrope"],
                    "body": ["Inter"],
                    "label": ["Inter"]
            }
          },
        },
      }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
            font-size: 1.25rem;
        }
        .tonal-shift-bg {
            background: linear-gradient(to bottom, #f9f9f9, #f3f3f3);
        }
    @keyframes whoisTypingBlink {
      0%, 80%, 100% { opacity: 0.3; transform: translateY(0); }
      40% { opacity: 1; transform: translateY(-1px); }
    }
    .whois-typing-dot {
      animation: whoisTypingBlink 1.1s infinite ease-in-out;
    }
    </style>
</head>
<body class="bg-surface font-body text-on-surface antialiased overflow-hidden">
<!-- Top Navigation Anchor -->
<header class="fixed top-0 w-full z-50 bg-white/80 dark:bg-neutral-900/80 backdrop-blur-xl shadow-sm shadow-black/5">
<?php require __DIR__ . '/_top_nav.php'; ?>
</header>
<main class="flex h-screen pt-[72px] overflow-hidden">
<!-- Left Sidebar: History -->
<aside class="hidden lg:flex flex-col w-64 bg-surface-container-low border-r border-outline-variant/20 p-6">
<div class="flex items-center justify-between mb-8">
<span class="text-xs font-label uppercase tracking-widest text-secondary font-bold">History</span>
<span class="material-symbols-outlined text-secondary cursor-pointer">history</span>
</div>
<div class="space-y-4" data-ai-history-list></div>
<div class="mt-auto pt-6">
<div class="bg-primary-container p-4 rounded-xl text-on-primary-container">
<p class="text-xs font-bold mb-2">PRO ACCESS</p>
<p class="text-[11px] opacity-80 leading-relaxed mb-3">Unlock unlimited STR% analytics and bulk registration tools.</p>
<button class="w-full py-2 bg-white text-black text-xs font-bold rounded-lg hover:bg-neutral-100 transition-colors">Upgrade</button>
</div>
</div>
</aside>
<!-- Center: Chat Interface -->
<section class="flex-1 min-w-0 bg-[radial-gradient(circle_at_top,#ffffff_0%,#f7f7f7_36%,#efefef_100%)] relative overflow-hidden" data-brand-chat>
<div class="flex h-full flex-col px-4 py-4 sm:px-6">
  <div class="mx-auto mb-4 max-w-4xl text-center">
    <h1 class="text-4xl md:text-5xl font-headline font-extrabold tracking-tighter text-primary mb-3">Mira</h1>
    <p class="text-on-surface-variant text-lg font-body max-w-lg mx-auto leading-relaxed">Your expert partner in domain acquisition and brand strategy.</p>
  </div>

  <div class="mx-auto mb-4 w-full max-w-4xl rounded-[1.5rem] border border-outline-variant/20 bg-white/75 p-3 shadow-sm backdrop-blur-xl sm:p-4">
    <div class="flex flex-wrap gap-2">
      <button type="button" data-chat-fill="Find a high-value .com for a tech startup focused on AI-driven design. Return a short list of premium options, acquisition advice, and a quick brandability assessment." class="rounded-full border border-outline-variant/30 bg-white px-4 py-2 text-sm font-medium text-on-surface-variant transition-all hover:border-primary/40 hover:text-primary">High-value .com</button>
      <button type="button" data-chat-fill="Explain my domain's appraisal score and STR% in plain language, then suggest the fastest way to improve the asset's market appeal." class="rounded-full border border-outline-variant/30 bg-white px-4 py-2 text-sm font-medium text-on-surface-variant transition-all hover:border-primary/40 hover:text-primary">Appraisal score</button>
      <button type="button" data-chat-fill="Suggest improvements for my business name 'Trovalabs' and give me stronger domain options with a clear positioning angle." class="rounded-full border border-outline-variant/30 bg-white px-4 py-2 text-sm font-medium text-on-surface-variant transition-all hover:border-primary/40 hover:text-primary">Improve Trovalabs</button>
      <button type="button" data-chat-fill="Check registration status across all major TLDs for a brandable domain and summarize the best acquisition move." class="rounded-full border border-outline-variant/30 bg-white px-4 py-2 text-sm font-medium text-on-surface-variant transition-all hover:border-primary/40 hover:text-primary">Registration check</button>
    </div>
  </div>

  <div class="mx-auto flex min-h-0 w-full max-w-4xl flex-1 flex-col overflow-hidden rounded-[2rem] border border-outline-variant/20 bg-white/85 shadow-[0_20px_60px_rgba(0,0,0,0.06)] backdrop-blur-xl">
    <div class="flex items-center justify-between border-b border-outline-variant/20 px-5 py-4 sm:px-6">
      <div>
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Conversation</p>
        <p class="mt-1 text-sm text-on-surface-variant">Start a thread and keep the chat focused on one question at a time.</p>
      </div>
      <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-500">Mira</span>
    </div>

    <div class="flex-1 min-h-0 overflow-y-auto px-5 py-6 sm:px-6" data-chat-feed>
      <div data-chat-empty class="flex justify-start">
        <div class="max-w-[42rem] rounded-3xl rounded-tl-none border border-dashed border-outline-variant/30 bg-surface-container-lowest px-5 py-4 text-sm leading-7 text-on-surface-variant shadow-sm">
          Tell me what you are building, and I will help with naming, positioning, domain selection, and acquisition strategy.
        </div>
      </div>
    </div>

    <form class="border-t border-outline-variant/20 p-4 sm:p-5" data-chat-form>
      <div class="rounded-full border border-outline-variant/40 bg-surface-container-lowest p-1.5 shadow-sm transition-all focus-within:ring-2 focus-within:ring-black">
        <div class="flex items-center gap-2">
          <button type="button" class="rounded-full p-2.5 transition-colors hover:bg-surface-container-high">
            <span class="material-symbols-outlined text-secondary">attach_file</span>
          </button>
          <input data-chat-input class="flex-1 border-none bg-transparent px-2 py-2 text-sm text-primary focus:ring-0 font-body placeholder-neutral-400" placeholder="Ask anything about domains or brand strategy..." type="text"/>
          <button type="button" class="rounded-full p-2.5 transition-colors hover:bg-surface-container-high">
            <span class="material-symbols-outlined text-secondary">analytics</span>
          </button>
          <button data-chat-send type="submit" class="flex items-center justify-center rounded-full bg-primary p-2.5 text-white transition-all active:scale-90 hover:bg-primary-container">
            <span class="material-symbols-outlined text-white" style="font-variation-settings: 'FILL' 1;">send</span>
          </button>
        </div>
      </div>
      <p class="mt-3 text-center text-[10px] font-medium uppercase tracking-widest text-neutral-400">Press Enter or Send to add your message to the thread.</p>
    </form>
  </div>
</div>
</section>
<!-- Right Sidebar: Metrics -->
<aside class="hidden xl:flex flex-col w-80 bg-surface-container-low border-l border-outline-variant/20 p-8 overflow-y-auto">
<h2 class="text-xs font-label uppercase tracking-widest text-secondary font-bold mb-8">AI Metrics Guide</h2>
<div class="space-y-8">
<!-- Appraisal Card -->
<div class="bg-surface-container-lowest p-5 rounded-2xl shadow-sm border border-outline-variant/20">
<div class="flex items-center gap-3 mb-3">
<div class="w-8 h-8 rounded-lg bg-neutral-100 flex items-center justify-center">
<span class="material-symbols-outlined text-primary">monitoring</span>
</div>
<h3 class="font-headline font-bold text-sm">Appraisal</h3>
</div>
<p class="text-xs text-on-surface-variant leading-relaxed">Market valuation based on historical comparable sales, length, and keyword search volume.</p>
<button type="button" data-chat-fill="Appraise this domain in plain text. Include an estimated value range, the main pricing drivers, and one acquisition recommendation." class="mt-4 inline-flex rounded-full border border-outline-variant/30 bg-white px-4 py-2 text-[11px] font-bold uppercase tracking-[0.16em] text-primary transition-colors hover:border-primary/40 hover:bg-surface-container-high">Ask appraisal</button>
</div>
<!-- STR Card -->
<div class="bg-surface-container-lowest p-5 rounded-2xl shadow-sm border border-outline-variant/20">
<div class="flex items-center gap-3 mb-3">
<div class="w-8 h-8 rounded-lg bg-neutral-100 flex items-center justify-center">
<span class="material-symbols-outlined text-primary">percent</span>
</div>
<h3 class="font-headline font-bold text-sm">STR%</h3>
</div>
<p class="text-xs text-on-surface-variant leading-relaxed">Sell-Through Rate. The probability of this domain being sold within a 12-month period in the open market.</p>
<button type="button" data-chat-fill="Estimate the STR% for this domain and explain the key reasons in plain language with a short action plan." class="mt-4 inline-flex rounded-full border border-outline-variant/30 bg-white px-4 py-2 text-[11px] font-bold uppercase tracking-[0.16em] text-primary transition-colors hover:border-primary/40 hover:bg-surface-container-high">Estimate STR%</button>
</div>
<!-- Domain Score -->
<div class="bg-surface-container-lowest p-5 rounded-2xl shadow-sm border border-outline-variant/20">
<div class="flex items-center gap-3 mb-3">
<div class="w-8 h-8 rounded-lg bg-neutral-100 flex items-center justify-center">
<span class="material-symbols-outlined text-primary">verified</span>
</div>
<h3 class="font-headline font-bold text-sm">Domain Score</h3>
</div>
<p class="text-xs text-on-surface-variant leading-relaxed">A holistic 1-100 rating combining readability, SEO potential, and branding flexibility.</p>
<button type="button" data-chat-fill="Score this domain from 1 to 100 for readability, SEO potential, and branding flexibility. Return the score and one reason for each factor." class="mt-4 inline-flex rounded-full border border-outline-variant/30 bg-white px-4 py-2 text-[11px] font-bold uppercase tracking-[0.16em] text-primary transition-colors hover:border-primary/40 hover:bg-surface-container-high">Score domain</button>
</div>
</div>
<!-- Secondary List: Recent Analysis -->
<div class="mt-12">
<h2 class="text-xs font-label uppercase tracking-widest text-secondary font-bold mb-6">Recent Insights</h2>
<div class="space-y-4" data-ai-recent-list></div>
</div>
</aside>
</main>
<script src="../assets/js/ai-workflows.js"></script>
<script>
(function () {
  function escapeHtml(value) {
    return String(value)
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#39;');
  }

  function textToHtml(value) {
    return escapeHtml(String(value || '')).replace(/\n/g, '<br>');
  }

  function normalizeAssistantText(value) {
    return String(value || '')
      .replace(/\r\n/g, '\n')
      .replace(/```[\s\S]*?```/g, function (block) {
        return block.replace(/```/g, '');
      })
      .replace(/\[(.*?)\]\((.*?)\)/g, '$1')
      .split('\n')
      .map(function (line) {
        return line
          .replace(/^#{1,6}\s+/, '')
          .replace(/^\s*[-*+]\s+/, '')
          .replace(/^\s*\d+\.\s+/, '')
          .replace(/\*\*(.*?)\*\*/g, '$1')
          .replace(/__(.*?)__/g, '$1')
          .replace(/`([^`]+)`/g, '$1')
          .replace(/~~(.*?)~~/g, '$1')
          .trimStart();
      })
      .join('\n')
      .trim();
  }

  function normalizeAssistantText(value) {
    return String(value || '')
      .replace(/\r\n/g, '\n')
      .replace(/```[\s\S]*?```/g, function (block) {
        return block.replace(/```/g, '');
      })
      .replace(/\[(.*?)\]\((.*?)\)/g, '$1')
      .split('\n')
      .map(function (line) {
        return line
          .replace(/^#{1,6}\s+/, '')
          .replace(/^\s*[-*+]\s+/, '')
          .replace(/^\s*\d+\.\s+/, '')
          .replace(/\*\*(.*?)\*\*/g, '$1')
          .replace(/__(.*?)__/g, '$1')
          .replace(/`([^`]+)`/g, '$1')
          .replace(/~~(.*?)~~/g, '$1')
          .trimStart();
      })
      .join('\n')
      .trim();
  }

  function sleep(milliseconds) {
    return new Promise(function (resolve) {
      window.setTimeout(resolve, milliseconds);
    });
  }

  const chatRoot = document.querySelector('[data-brand-chat]');

  if (!chatRoot) {
    return;
  }

  const feed = chatRoot.querySelector('[data-chat-feed]');
  const emptyState = chatRoot.querySelector('[data-chat-empty]');
  const form = chatRoot.querySelector('[data-chat-form]');
  const input = chatRoot.querySelector('[data-chat-input]');
  const send = chatRoot.querySelector('[data-chat-send]');
  const quickButtons = chatRoot.querySelectorAll('[data-chat-fill]');
  const historyRoot = chatRoot.querySelector('[data-ai-history-list]');
  const recentRoot = chatRoot.querySelector('[data-ai-recent-list]');
  const endpoint = '/api/ai.php';
  const workflow = 'brand_assistant';
  let conversationMessages = [];

  function refreshHistoryViews() {
    if (window.WhoisAIHistory && typeof window.WhoisAIHistory.renderAll === 'function') {
      window.WhoisAIHistory.renderAll();
    }
  }

  function clearFeed() {
    if (feed) {
      feed.innerHTML = '';
    }
  }

  function setEmptyState() {
    if (!feed || feed.querySelector('[data-chat-empty]')) {
      return;
    }

    const empty = document.createElement('div');
    empty.setAttribute('data-chat-empty', 'true');
    empty.className = 'flex justify-start';
    empty.innerHTML = '<div class="max-w-[42rem] rounded-3xl rounded-tl-none border border-dashed border-outline-variant/30 bg-surface-container-lowest px-5 py-4 text-sm leading-7 text-on-surface-variant shadow-sm">Tell me what you are building, and I will help with naming, positioning, domain selection, and acquisition strategy.</div>';
    feed.appendChild(empty);
  }

  function renderThreadEntry(role, title, message) {
    return appendMessage(role, title, message, false);
  }

  function hydrateConversation(entry) {
    const messages = Array.isArray(entry && entry.messages) ? entry.messages : [];

    conversationMessages = messages.map(function (message) {
      return {
        role: message.role,
        text: message.text || '',
      };
    });

    clearFeed();

    if (!messages.length) {
      if (entry && entry.prompt) {
        renderThreadEntry('user', 'You', entry.prompt);
      }

      if (entry && entry.message) {
        renderThreadEntry('assistant', entry.title || 'Mira', entry.message);
      }

      if (!entry || (!entry.prompt && !entry.message)) {
        setEmptyState();
      }

      scrollToBottom();
      return;
    }

    messages.forEach(function (message) {
      if (!message || !message.role) {
        return;
      }

      renderThreadEntry(message.role, message.role === 'assistant' ? (entry.title || 'Mira') : 'You', message.text || '');
    });

    scrollToBottom();
  }

  function scrollToBottom() {
    if (feed) {
      feed.scrollTop = feed.scrollHeight;
    }
  }

  function removeEmptyState() {
    if (!feed) {
      return;
    }

    const currentEmptyState = feed.querySelector('[data-chat-empty]');

    if (currentEmptyState) {
      currentEmptyState.remove();
    }
  }

  function createMessage(role, title, message, loading) {
    const wrapper = document.createElement('div');

    wrapper.className = role === 'user' ? 'flex justify-end' : 'flex justify-start';

    if (role === 'user') {
      wrapper.innerHTML =
        '<div class="max-w-[42rem] rounded-3xl rounded-tr-none bg-primary px-5 py-4 text-on-primary shadow-sm">' +
        '<p class="mb-2 text-[10px] font-bold uppercase tracking-[0.24em] text-white/70">You</p>' +
        '<p class="whitespace-pre-wrap text-sm leading-7" data-chat-message></p>' +
        '</div>';
      wrapper.querySelector('[data-chat-message]').innerHTML = textToHtml(message);
      return wrapper;
    }

    wrapper.innerHTML =
      '<div class="max-w-[42rem] rounded-3xl rounded-tl-none border border-outline-variant/20 bg-surface-container-lowest px-5 py-4 shadow-sm">' +
      '<div class="mb-3 flex items-center gap-2">' +
          '<span class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Mira</span>' +
      '<span class="text-[10px] font-bold uppercase tracking-[0.18em] text-neutral-500" data-chat-title></span>' +
      '</div>' +
      '<p class="whitespace-pre-wrap text-sm leading-7 text-on-surface-variant" data-chat-message></p>' +
      '</div>';

    wrapper.querySelector('[data-chat-title]').textContent = title;
    wrapper.querySelector('[data-chat-message]').innerHTML = loading ? '<span class="inline-flex items-center gap-1"><span class="whois-typing-dot">•</span><span class="whois-typing-dot" style="animation-delay: 0.15s">•</span><span class="whois-typing-dot" style="animation-delay: 0.3s">•</span><span class="ml-2">Thinking with Grok...</span></span>' : textToHtml(message);

    return wrapper;
  }

  function appendMessage(role, title, message, loading) {
    const messageNode = createMessage(role, title, message, loading);
    feed.appendChild(messageNode);
    scrollToBottom();
    return messageNode;
  }

  function pushConversationMessage(role, text) {
    conversationMessages.push({
      role: role,
      text: String(text || ''),
    });
  }

  function buildRequestContext() {
    return conversationMessages.slice(-10).map(function (message) {
      return {
        role: message.role,
        content: message.text,
      };
    });
  }

  async function typeIntoMessage(node, text) {
    const target = node.querySelector('[data-chat-message]');
    const content = String(text || '');

    if (!target) {
      return;
    }

    target.textContent = '';

    const step = Math.max(1, Math.ceil(content.length / 120));

    for (let index = 0; index < content.length; index += step) {
      const chunk = content.slice(index, index + step);
      target.textContent += chunk;
      scrollToBottom();
      await sleep(10 + Math.min(28, Math.floor(content.length / 40)));
    }
  }

  async function submitPrompt(rawPrompt) {
    const prompt = String(rawPrompt || '').trim();

    if (prompt === '') {
      input.focus();
      return;
    }

    removeEmptyState();
    const requestContext = buildRequestContext();
    appendMessage('user', 'You', prompt, false);
    pushConversationMessage('user', prompt);

    const pending = appendMessage('assistant', 'Mira', '', true);
    const historyEntry = window.WhoisAIHistory ? window.WhoisAIHistory.record({
      workflow: workflow,
      title: 'Mira',
      prompt: prompt,
      message: '',
      messages: [
        { role: 'user', text: prompt },
      ],
      status: 'pending',
    }) : null;

    input.value = '';
    send.disabled = true;
    send.setAttribute('aria-busy', 'true');

    try {
      const response = await fetch(endpoint, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          workflow,
          input: prompt,
          context: requestContext,
        }),
      });

      const data = await response.json();

      if (!response.ok || !data.ok) {
        throw new Error(data.error || 'The AI request failed.');
      }

      pending.querySelector('[data-chat-title]').textContent = data.label || 'Mira';

      if (historyEntry && window.WhoisAIHistory) {
        window.WhoisAIHistory.update(historyEntry.id, {
          title: data.label || 'Mira',
          message: data.output || 'No response returned.',
          messages: [
            { role: 'user', text: prompt },
            { role: 'assistant', text: data.output || 'No response returned.' },
          ],
          status: 'done',
        });
      }

      pushConversationMessage('assistant', data.output || 'No response returned.');

      await typeIntoMessage(pending, normalizeAssistantText(data.output || 'No response returned.'));
    } catch (error) {
      pending.querySelector('[data-chat-title]').textContent = 'Grok is unavailable';

      if (historyEntry && window.WhoisAIHistory) {
        window.WhoisAIHistory.update(historyEntry.id, {
          title: 'Grok is unavailable',
          message: error instanceof Error ? error.message : 'The AI request failed.',
          messages: [
            { role: 'user', text: prompt },
            { role: 'assistant', text: error instanceof Error ? error.message : 'The AI request failed.' },
          ],
          status: 'error',
        });
      }

      pushConversationMessage('assistant', error instanceof Error ? error.message : 'The AI request failed.');

      await typeIntoMessage(pending, normalizeAssistantText(error instanceof Error ? error.message : 'The AI request failed.'));
    } finally {
      send.disabled = false;
      send.removeAttribute('aria-busy');
      scrollToBottom();
      refreshHistoryViews();
    }
  }

  form.addEventListener('submit', function (event) {
    event.preventDefault();
    submitPrompt(input.value);
  });

  quickButtons.forEach(function (button) {
    button.addEventListener('click', function () {
      input.value = button.getAttribute('data-chat-fill') || '';
      submitPrompt(input.value);
    });
  });

  window.addEventListener('whois-ai-history-selected', function (event) {
    const entry = event.detail || {};

    if (!entry.prompt) {
      return;
    }

    input.value = entry.prompt;
    hydrateConversation(entry);
    input.focus();
  });

  input.addEventListener('keydown', function (event) {
    if (event.key === 'Enter' && !event.shiftKey) {
      event.preventDefault();
      submitPrompt(input.value);
    }
  });

  input.focus();
  refreshHistoryViews();
}());
</script>
<script src="../assets/js/nav-state.js"></script>
</body></html>
