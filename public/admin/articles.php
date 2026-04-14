<?php
declare(strict_types=1);

require __DIR__ . '/_layout.php';
require_once __DIR__ . '/../../app/db-client.php';

$articles = whois_db_fetch_all('SELECT * FROM articles ORDER BY created_at DESC');

whois_admin_render_page([
    'title' => 'WHOIS.ARCHITECT | Insights CMS',
    'active' => 'articles',
    'eyebrow' => 'Content Management',
    'headline' => 'Industry Insights CMS.',
    'description' => 'Manage published articles and draft publications.',
], function () use ($articles): void {
    ?>
    <section class="grid gap-6 xl:grid-cols-[1fr_2fr]" id="app-root">
      
      <!-- Article List Column -->
      <div class="rounded-[1.5rem] admin-panel p-6 flex flex-col h-[800px]">
        <div class="flex items-center justify-between gap-4 mb-6">
          <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Directory</p>
            <h2 class="text-2xl font-extrabold tracking-tight mt-2">Articles</h2>
          </div>
          <button id="btn-new-article" class="rounded-full bg-black text-white px-4 py-2 text-[10px] font-bold uppercase tracking-widest hover:bg-neutral-800 transition-colors">
             + New Article
          </button>
        </div>
        
        <div class="flex-grow overflow-y-auto space-y-3 pr-2 scrollbar-thin">
          <?php foreach ($articles as $index => $article): ?>
            <?php 
               $isPublished = $article['status'] === 'published';
               $badgeClass = $isPublished ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-800';
            ?>
            <div class="cursor-pointer border border-outline-variant/30 rounded-xl p-4 bg-surface hover:bg-surface-container-lowest transition-colors article-row" 
                 data-article-json="<?php echo htmlspecialchars(json_encode($article, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8'); ?>">
                <div class="flex justify-between items-start mb-2">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-secondary"><?php echo htmlspecialchars((string)$article['category'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full <?php echo $badgeClass; ?>"><?php echo $article['status']; ?></span>
                </div>
                <h3 class="font-bold text-sm leading-tight text-black mb-1 line-clamp-2"><?php echo htmlspecialchars((string)$article['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                <p class="text-xs text-on-surface-variant line-clamp-1"><?php echo htmlspecialchars((string)$article['excerpt'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
          <?php endforeach; ?>
          <?php if ($articles === []): ?>
                <div class="text-xs text-secondary italic">No articles found. Create one to begin.</div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Editor Column -->
      <div class="rounded-[1.5rem] admin-panel p-6 overflow-y-auto h-[800px] bg-white">
        <div class="flex items-center justify-between mb-6">
           <div>
               <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Active File</p>
               <h2 class="text-2xl font-extrabold tracking-tight mt-2" id="editor-heading">Draft Editor</h2>
           </div>
           <div class="flex space-x-2">
               <button id="btn-delete" class="hidden rounded-full border border-rose-200 text-rose-600 px-4 py-2 text-[10px] font-bold uppercase tracking-widest hover:bg-rose-50 transition-colors">Delete</button>
               <button id="btn-save" class="rounded-full bg-primary text-on-primary px-6 py-2 text-[10px] font-bold uppercase tracking-widest hover:brightness-110 transition-all">Save Changes</button>
           </div>
        </div>

        <form id="editor-form" class="space-y-5" onsubmit="return false;">
            <input type="hidden" id="field-id" value="0">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-secondary mb-2">Title</label>
                    <input type="text" id="field-title" class="w-full text-base bg-surface border border-outline-variant/30 rounded-lg py-2 px-3 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" required>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-secondary mb-2">Category</label>
                    <input type="text" id="field-category" class="w-full text-base bg-surface border border-outline-variant/30 rounded-lg py-2 px-3 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" value="Industry News" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-secondary mb-2">Author</label>
                    <input type="text" id="field-author" class="w-full text-base bg-surface border border-outline-variant/30 rounded-lg py-2 px-3 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" value="System Administrator">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-secondary mb-2">Status</label>
                    <select id="field-status" class="w-full text-base bg-surface border border-outline-variant/30 rounded-lg py-2 px-3 focus:border-primary outline-none text-black">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-secondary mb-2">Featured Image URL (Optional)</label>
                <input type="text" id="field-image" class="w-full text-sm font-mono text-secondary bg-surface border border-outline-variant/30 rounded-lg py-2 px-3 outline-none">
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-secondary mb-2">Excerpt (Short Meta Description)</label>
                <textarea id="field-excerpt" rows="2" class="w-full text-sm bg-surface border border-outline-variant/30 rounded-lg py-2 px-3 focus:border-primary outline-none resize-none"></textarea>
            </div>

            <div class="flex-grow flex flex-col pt-4 border-t border-outline-variant/30">
                <label class="block text-[10px] font-bold uppercase tracking-widest text-secondary mb-2">Full Content (HTML Supported)</label>
                <textarea id="field-content" rows="14" class="w-full text-sm font-mono bg-surface border border-outline-variant/30 rounded-lg py-3 px-4 focus:border-black outline-none resize-y" placeholder="<p>Article body here...</p>" required></textarea>
            </div>
        </form>
      </div>

    </section>

    <script>
      (() => {
        const rows = document.querySelectorAll('.article-row');
        const form = document.getElementById('editor-form');
        const btnNew = document.getElementById('btn-new-article');
        const btnSave = document.getElementById('btn-save');
        const btnDelete = document.getElementById('btn-delete');
        
        const fId = document.getElementById('field-id');
        const fTitle = document.getElementById('field-title');
        const fCategory = document.getElementById('field-category');
        const fAuthor = document.getElementById('field-author');
        const fStatus = document.getElementById('field-status');
        const fImage = document.getElementById('field-image');
        const fExcerpt = document.getElementById('field-excerpt');
        const fContent = document.getElementById('field-content');
        const heading = document.getElementById('editor-heading');

        function loadEditor(data) {
            fId.value = data.id || '0';
            fTitle.value = data.title || '';
            fCategory.value = data.category || 'Industry News';
            fAuthor.value = data.author_string || 'System Administrator';
            fStatus.value = data.status || 'draft';
            fImage.value = data.image_url || '';
            fExcerpt.value = data.excerpt || '';
            fContent.value = data.content || '';
            
            if (data.id) {
                heading.innerText = 'Editing Article';
                btnDelete.classList.remove('hidden');
            } else {
                heading.innerText = 'New Draft';
                btnDelete.classList.add('hidden');
            }
        }

        btnNew.addEventListener('click', () => loadEditor({}));

        rows.forEach(row => {
            row.addEventListener('click', () => {
                const data = JSON.parse(row.getAttribute('data-article-json') || '{}');
                loadEditor(data);
                
                rows.forEach(r => r.classList.remove('border-primary', 'bg-surface-container-lowest'));
                row.classList.add('border-primary', 'bg-surface-container-lowest');
            });
        });

        async function submitAction(actionData) {
            const btnOrig = btnSave.innerText;
            btnSave.innerText = 'Processing...';
            btnSave.disabled = true;

            try {
                const res = await fetch('../api/articles.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(actionData)
                });
                const result = await res.json();
                if (!result.ok) throw new Error(result.error || 'Failed');
                window.location.reload();
            } catch (err) {
                alert(err.message);
                btnSave.innerText = btnOrig;
                btnSave.disabled = false;
            }
        }

        btnSave.addEventListener('click', () => {
            if (!fTitle.value || !fContent.value) return alert('Title and Content are required.');
            submitAction({
                action: 'save',
                id: fId.value,
                title: fTitle.value,
                category: fCategory.value,
                author_string: fAuthor.value,
                status: fStatus.value,
                image_url: fImage.value,
                excerpt: fExcerpt.value,
                content: fContent.value
            });
        });

        btnDelete.addEventListener('click', () => {
            if (confirm('Are you sure you want to completely delete this article?')) {
                submitAction({action: 'delete', id: fId.value});
            }
        });

      })();
    </script>
    <?php
});
