(function () {
  const historyStorageKey = 'whois-ai-history-v1';
  const sessionMapStorageKey = 'whois-ai-history-session-map-v1';
  const historyStorage = window.sessionStorage;

  function escapeHtml(value) {
    return String(value)
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#39;');
  }

  function safeReadHistory() {
    try {
      const raw = historyStorage.getItem(historyStorageKey);

      if (!raw) {
        return [];
      }

      const parsed = JSON.parse(raw);

      if (!Array.isArray(parsed)) {
        return [];
      }

      return parsed.filter(function (entry) {
        return entry && typeof entry === 'object';
      });
    } catch (error) {
      return [];
    }
  }

  function safeWriteHistory(entries) {
    try {
      historyStorage.setItem(historyStorageKey, JSON.stringify(entries.slice(0, 30)));
    } catch (error) {
      return false;
    }

    return true;
  }

  function safeReadSessionMap() {
    try {
      const raw = historyStorage.getItem(sessionMapStorageKey);

      if (!raw) {
        return {};
      }

      const parsed = JSON.parse(raw);

      if (!parsed || typeof parsed !== 'object' || Array.isArray(parsed)) {
        return {};
      }

      return parsed;
    } catch (error) {
      return {};
    }
  }

  function safeWriteSessionMap(map) {
    try {
      historyStorage.setItem(sessionMapStorageKey, JSON.stringify(map));
    } catch (error) {
      return false;
    }

    return true;
  }

  function getSessionId(workflow) {
    const key = String(workflow || 'brand_assistant');
    const map = safeReadSessionMap();

    if (typeof map[key] === 'string' && map[key] !== '') {
      return map[key];
    }

    const sessionId = key + '-' + Date.now() + '-' + Math.random().toString(36).slice(2, 10);
    map[key] = sessionId;
    safeWriteSessionMap(map);

    return sessionId;
  }

  function normalizeText(value) {
    return String(value || '').replace(/\s+/g, ' ').trim();
  }

  function summarizeText(value, maxLength) {
    const text = normalizeText(value);

    if (text.length <= maxLength) {
      return text;
    }

    return text.slice(0, Math.max(0, maxLength - 1)).trimEnd() + '...';
  }

  function extractDomain(value) {
    const match = String(value || '').match(/\b[a-z0-9-]+(?:\.[a-z0-9-]+)+\b/i);

    if (!match) {
      return '';
    }

    return match[0].replace(/[.,;:!?]+$/, '');
  }

  function extractScore(value) {
    const text = String(value || '');
    const ratioMatch = text.match(/(\d{1,3}(?:\.\d+)?)\s*\/\s*100/);

    if (ratioMatch) {
      return ratioMatch[1] + '/100';
    }

    const percentMatch = text.match(/(\d{1,3}(?:\.\d+)?)\s*%/);

    if (percentMatch) {
      return percentMatch[1] + '%';
    }

    return '';
  }

  function timeAgo(timestamp) {
    const elapsed = Date.now() - Number(timestamp || 0);

    if (!Number.isFinite(elapsed) || elapsed < 0) {
      return 'Just now';
    }

    const minute = 60 * 1000;
    const hour = 60 * minute;
    const day = 24 * hour;

    if (elapsed < minute) {
      return 'Just now';
    }

    if (elapsed < hour) {
      return Math.max(1, Math.round(elapsed / minute)) + 'm ago';
    }

    if (elapsed < day) {
      return Math.max(1, Math.round(elapsed / hour)) + 'h ago';
    }

    if (elapsed < day * 2) {
      return 'Yesterday';
    }

    return Math.max(1, Math.round(elapsed / day)) + 'd ago';
  }

  function workflowHistoryLabel(workflow) {
    return workflowLabel(workflow);
  }

  function deriveSessionTitle(prompt, workflow) {
    const text = String(prompt || '').toLowerCase();

    if (/clothing|fashion|apparel|streetwear|garment/.test(text)) {
      return 'Clothing Brand Ideas';
    }

    if (/domain|whois|tld|extension|registr/i.test(text)) {
      return 'Domain Strategy Session';
    }

    if (/business|startup|venture|idea/.test(text)) {
      return 'Business Idea Session';
    }

    if (/brand|naming|logo|identity/.test(text)) {
      return 'Brand Strategy Session';
    }

    if (workflow === 'brand_assistant') {
      return 'Brand Strategy Session';
    }

    return workflowHistoryLabel(workflow);
  }

  function isGenericTitle(title, workflow) {
    const text = String(title || '').trim();

    if (text === '') {
      return true;
    }

    if (text === 'Mira') {
      return true;
    }

    return text === workflowHistoryLabel(workflow);
  }

  function getHistoryEntryTitle(entry) {
    const domain = extractDomain(entry.prompt) || extractDomain(entry.message) || extractDomain(entry.title);

    if (domain) {
      return domain;
    }

    if (entry.title) {
      return entry.title;
    }

    return workflowHistoryLabel(entry.workflow);
  }

  function getHistoryEntryPreview(entry, variant) {
    const source = entry.prompt || entry.message || '';
    const limit = variant === 'recent' ? 40 : 56;

    return summarizeText(source, limit) || workflowHistoryLabel(entry.workflow);
  }

  function getHistoryEntryMeta(entry, variant) {
    if (variant === 'recent') {
      return extractScore(entry.message) || workflowHistoryLabel(entry.workflow);
    }

    return timeAgo(entry.createdAt);
  }

  function renderHistoryList(list, variant) {
    const history = safeReadHistory();
    const limit = variant === 'recent' ? 3 : 6;
    const entries = history.slice(0, limit);

    list.innerHTML = '';

    if (!entries.length) {
      const empty = document.createElement('div');
      empty.className = variant === 'recent' ? 'rounded-2xl border border-dashed border-outline-variant/30 bg-surface-container-lowest px-4 py-3 text-xs leading-6 text-on-surface-variant' : 'rounded-xl border border-dashed border-outline-variant/30 bg-surface-container-lowest px-3 py-3 text-sm leading-6 text-on-surface-variant';
      empty.textContent = variant === 'recent' ? 'Recent insights will appear here after your first AI result.' : 'Your AI conversations will appear here in real time.';
      list.appendChild(empty);
      return;
    }

    entries.forEach(function (entry) {
      const button = document.createElement('button');
      button.type = 'button';
      button.className = variant === 'recent' ? 'group w-full rounded-2xl border border-outline-variant/20 bg-white px-4 py-3 text-left shadow-sm transition-all hover:border-primary/30 hover:shadow-md' : 'group w-full rounded-xl border border-outline-variant/10 bg-surface-container-lowest px-3 py-3 text-left transition-all hover:bg-surface-container-high';
      button.dataset.aiHistoryEntry = entry.id;
      button.dataset.aiHistoryPrompt = entry.prompt || '';
      button.innerHTML =
        '<div class="flex items-start gap-3">' +
        '<div class="mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-surface-container-high text-[10px] font-bold uppercase tracking-[0.18em] text-secondary">' +
        escapeHtml((entry.workflow || 'AI').slice(0, 2)) +
        '</div>' +
        '<div class="min-w-0 flex-1">' +
        '<p class="truncate text-sm font-semibold text-primary">' + escapeHtml(getHistoryEntryTitle(entry)) + '</p>' +
        '<p class="mt-1 max-h-10 overflow-hidden text-[11px] leading-5 text-neutral-500">' + escapeHtml(getHistoryEntryPreview(entry, variant)) + '</p>' +
        '</div>' +
        '<span class="shrink-0 text-[10px] font-bold uppercase tracking-[0.16em] text-neutral-400">' + escapeHtml(getHistoryEntryMeta(entry, variant)) + '</span>' +
        '</div>';

      button.addEventListener('click', function () {
        window.dispatchEvent(new CustomEvent('whois-ai-history-selected', { detail: entry }));
      });

      list.appendChild(button);
    });
  }

  function syncHistoryViews() {
    document.querySelectorAll('[data-ai-history-list]').forEach(function (list) {
      renderHistoryList(list, 'history');
    });

    document.querySelectorAll('[data-ai-recent-list]').forEach(function (list) {
      renderHistoryList(list, 'recent');
    });
  }

  function recordHistory(entry) {
    const workflow = entry.workflow || 'brand_assistant';
    const sessionId = entry.sessionId || getSessionId(workflow);
    const suggestedTitle = deriveSessionTitle(entry.prompt || entry.message || '', workflow);
    const normalized = {
      id: sessionId,
      sessionId: sessionId,
      workflow: workflow,
      title: isGenericTitle(entry.title, workflow) ? suggestedTitle : entry.title,
      prompt: entry.prompt || '',
      message: entry.message || '',
      messages: Array.isArray(entry.messages) ? entry.messages.slice() : [],
      status: entry.status || 'done',
      createdAt: entry.createdAt || Date.now(),
      path: entry.path || window.location.pathname + window.location.search,
    };

    const history = safeReadHistory();
    const index = history.findIndex(function (existing) {
      return existing.sessionId === normalized.sessionId || existing.id === normalized.id;
    });

    if (index === -1) {
      history.unshift(normalized);
    } else {
      history[index] = Object.assign({}, history[index], normalized, {
        title: isGenericTitle(history[index].title, workflow) ? normalized.title : history[index].title,
      });
    }

    safeWriteHistory(history);
    syncHistoryViews();

    return history.find(function (existing) {
      return existing.sessionId === normalized.sessionId || existing.id === normalized.id;
    }) || normalized;
  }

  function updateHistory(id, patch) {
    if (!id) {
      return null;
    }

    const history = safeReadHistory();
    const index = history.findIndex(function (entry) {
      return entry.id === id;
    });

    if (index === -1) {
      return null;
    }

    history[index] = Object.assign({}, history[index], patch || {});

    if (patch && patch.title && isGenericTitle(history[index].title, history[index].workflow)) {
      history[index].title = patch.title;
    }

    if (patch && Array.isArray(patch.messages)) {
      history[index].messages = patch.messages.slice();
    }

    safeWriteHistory(history);
    syncHistoryViews();

    return history[index];
  }

  window.WhoisAIHistory = {
    getAll: safeReadHistory,
    record: recordHistory,
    update: updateHistory,
    renderAll: syncHistoryViews,
    summarize: summarizeText,
    workflowLabel: workflowHistoryLabel,
    deriveSessionTitle: deriveSessionTitle,
  };

  function workflowLabel(workflow) {
    const labels = {
      domain_search: 'Domain Search',
      premium_search: 'Premium Search',
      brand_assistant: 'Brand Assistant',
      business_idea: 'Business Idea',
      domain_name_generator: 'Domain Name Generator',
      appraisal: 'Appraisal',
    };

    return labels[workflow] || workflow.replace(/_/g, ' ').replace(/\b\w/g, function (letter) {
      return letter.toUpperCase();
    });
  }

  function createOutputCard(workflow, state) {
    const wrapper = document.createElement('div');
    wrapper.dataset.aiOutput = 'true';
    wrapper.className = 'mt-6 rounded-3xl border border-outline-variant/20 bg-white/95 p-6 shadow-sm backdrop-blur';
    wrapper.innerHTML =
      '<div class="flex items-center justify-between gap-4">' +
      '<p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Live Grok Result</p>' +
      '<span class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-500">' +
      escapeHtml(workflowLabel(workflow)) +
      '</span>' +
      '</div>' +
      '<div class="mt-3 space-y-3">' +
      '<p class="text-lg font-black text-primary">' + escapeHtml(state.title) + '</p>' +
      '<p class="whitespace-pre-wrap text-sm leading-7 text-on-surface-variant">' + escapeHtml(state.message) + '</p>' +
      '</div>';

    return wrapper;
  }

  function setOutput(root, workflow, state) {
    let output = root.querySelector('[data-ai-output]');

    if (!output) {
      output = createOutputCard(workflow, state);
      root.appendChild(output);
      return output;
    }

    output.innerHTML =
      '<div class="flex items-center justify-between gap-4">' +
      '<p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Live Grok Result</p>' +
      '<span class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-500">' +
      escapeHtml(workflowLabel(workflow)) +
      '</span>' +
      '</div>' +
      '<div class="mt-3 space-y-3">' +
      '<p class="text-lg font-black text-primary">' + escapeHtml(state.title) + '</p>' +
      '<p class="whitespace-pre-wrap text-sm leading-7 text-on-surface-variant">' + escapeHtml(state.message) + '</p>' +
      '</div>';

    return output;
  }

  function setLoading(root, workflow) {
    setOutput(root, workflow, {
      title: 'Thinking with Grok...',
      message: 'Fetching a live response for this workflow.',
    });
  }

  async function submitWorkflow(root) {
    const workflow = root.dataset.aiWorkflow;
    const endpoint = root.dataset.aiEndpoint || '/api/ai.php';
    const input = root.querySelector('[data-ai-input]') || root.querySelector('input[type="text"], textarea');
    const submit = root.querySelector('[data-ai-submit]');

    if (!workflow || !submit) {
      return;
    }

    const prompt = (input ? input.value : root.dataset.aiPrompt || '').trim();

    if (prompt === '') {
      setOutput(root, workflow, {
        title: 'Add a prompt first',
        message: 'Type a query and try again.',
      });
      if (input) {
        input.focus();
      }
      return;
    }

    const historyEntry = window.WhoisAIHistory ? window.WhoisAIHistory.record({
      workflow: workflow,
      title: workflowLabel(workflow),
      prompt: prompt,
      message: '',
      status: 'pending',
    }) : null;

    submit.disabled = true;
    submit.setAttribute('aria-busy', 'true');
    setLoading(root, workflow);

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
        }),
      });

      const data = await response.json();

      if (!response.ok || !data.ok) {
        throw new Error(data.error || 'The AI request failed.');
      }

      if (historyEntry && window.WhoisAIHistory) {
        window.WhoisAIHistory.update(historyEntry.id, {
          title: data.label || workflowLabel(workflow),
          message: data.output || 'No response returned.',
          status: 'done',
        });
      }

      setOutput(root, workflow, {
        title: data.label || workflowLabel(workflow),
        message: data.output || 'No response returned.',
      });
    } catch (error) {
      if (historyEntry && window.WhoisAIHistory) {
        window.WhoisAIHistory.update(historyEntry.id, {
          title: 'Grok is unavailable',
          message: error instanceof Error ? error.message : 'The AI request failed.',
          status: 'error',
        });
      }

      setOutput(root, workflow, {
        title: 'Grok is unavailable',
        message: error instanceof Error ? error.message : 'The AI request failed.',
      });
    } finally {
      submit.disabled = false;
      submit.removeAttribute('aria-busy');
    }
  }

  function attachWorkflow(root) {
    const workflow = root.dataset.aiWorkflow;
    const submit = root.querySelector('[data-ai-submit]');
    const input = root.querySelector('[data-ai-input]') || root.querySelector('input[type="text"], textarea');

    if (!workflow || !submit || (!input && !root.dataset.aiPrompt)) {
      return;
    }

    const endpoint = root.dataset.aiEndpoint || '/api/ai.php';
    root.dataset.aiEndpoint = endpoint;

    if (!root.querySelector('[data-ai-output]')) {
      setOutput(root, workflow, {
        title: 'Ready when you are',
        message: 'Type a prompt and press the action button to fetch a live Grok response.',
      });
    }

    submit.addEventListener('click', function (event) {
      event.preventDefault();
      submitWorkflow(root);
    });

    if (input) {
      input.addEventListener('keydown', function (event) {
        if (event.key === 'Enter' && !event.shiftKey) {
          event.preventDefault();
          submitWorkflow(root);
        }
      });
    }

    root.querySelectorAll('[data-ai-fill]').forEach(function (button) {
      button.addEventListener('click', function (event) {
        event.preventDefault();
        const value = button.getAttribute('data-ai-fill') || '';
        input.value = value;

        if (button.hasAttribute('data-ai-autosubmit')) {
          submitWorkflow(root);
        } else {
          input.focus();
        }
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-ai-workflow]').forEach(attachWorkflow);
    syncHistoryViews();
  });
}());