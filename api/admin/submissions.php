<?php
declare(strict_types=1);

require __DIR__ . '/_layout.php';
require_once __DIR__ . '/../../app/db-client.php';

$submissions = whois_db_list_submissions(['limit' => 50]);
$pendingCount = count(array_filter($submissions, static function (array $submission): bool {
  return (string) ($submission['status'] ?? 'new') === 'new';
}));

if (!function_exists('whois_admin_submission_badge')) {
  function whois_admin_submission_badge(string $status): array
  {
    return match ($status) {
      'approved' => ['label' => 'Approved', 'class' => 'bg-emerald-100 text-emerald-700'],
      'rejected' => ['label' => 'Rejected', 'class' => 'bg-rose-100 text-rose-700'],
      default => ['label' => 'Pending', 'class' => 'bg-amber-100 text-amber-800'],
    };
  }
}

whois_admin_render_page([
    'title' => 'WHOIS.ARCHITECT | Admin Submissions',
    'active' => 'submissions',
    'eyebrow' => 'Admin Side',
  'headline' => 'Submission queue.',
  'description' => '',
], function () use ($submissions, $pendingCount): void {
    ?>
    <section class="grid gap-6 xl:grid-cols-[1.4fr_0.6fr]">
      <div class="rounded-[1.5rem] admin-panel p-6">
        <div class="flex items-center justify-between gap-4 mb-6">
          <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Queue</p>
            <h2 class="text-2xl font-extrabold tracking-tight mt-2">Pending</h2>
          </div>
          <span class="rounded-full bg-surface-container-low px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-secondary"><?php echo (int) $pendingCount; ?> open</span>
        </div>
        <div class="space-y-3" id="submission-list">
          <?php foreach ($submissions as $submission): ?>
            <?php
              $status = strtolower((string) ($submission['status'] ?? 'new'));
              $badge = whois_admin_submission_badge($status);
            ?>
            <div class="rounded-xl bg-surface-container-lowest p-4 border border-outline-variant/20 grid gap-4 md:grid-cols-[1.2fr_0.8fr_auto] md:items-center" data-submission-card data-submission-id="<?php echo (int) ($submission['id'] ?? 0); ?>">
              <div>
                <p class="font-bold text-lg"><?php echo htmlspecialchars((string) ($submission['domain_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                <p class="text-sm text-secondary mt-1"><?php echo htmlspecialchars(trim((string) ($submission['category'] ?? '') . ' ' . (string) ($submission['keywords'] ?? '')), ENT_QUOTES, 'UTF-8'); ?></p>
              </div>
              <div class="text-sm text-on-surface-variant">
                <p class="font-semibold text-black">Status</p>
                <p><?php echo htmlspecialchars((string) ($submission['status'] ?? 'new'), ENT_QUOTES, 'UTF-8'); ?> · Reserve <?php echo $submission['reserve_price'] !== null ? '$' . number_format((float) $submission['reserve_price']) : 'n/a'; ?></p>
              </div>
              <div class="flex flex-wrap gap-2">
                <span class="rounded-full px-3 py-2 text-[10px] font-bold uppercase tracking-[0.2em] <?php echo htmlspecialchars($badge['class'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($badge['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                <?php if ($status === 'new'): ?>
                  <button class="rounded-full bg-primary px-4 py-2 text-[10px] font-bold uppercase tracking-[0.2em] text-on-primary" type="button" data-submission-action="approve">Approve</button>
                  <button class="rounded-full border border-outline-variant/40 bg-surface-container-lowest px-4 py-2 text-[10px] font-bold uppercase tracking-[0.2em] text-black" type="button" data-submission-action="reject">Reject</button>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
          <?php if ($submissions === []): ?>
            <div class="rounded-xl border border-dashed border-outline-variant/40 bg-surface-container-lowest p-6 text-sm text-secondary">No submissions yet.</div>
          <?php endif; ?>
        </div>
      </div>
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Actions</p>
        <h2 class="text-2xl font-extrabold tracking-tight mt-2 mb-4">Tools</h2>
        <div class="space-y-3 text-sm text-on-surface-variant">
          <p>• Ownership verification</p>
          <p>• Auction routing</p>
          <p>• Marketplace eligibility</p>
          <p>• Seller follow-up notes</p>
        </div>
      </div>
    </section>

    <script>
      (() => {
        const list = document.getElementById('submission-list');

        async function updateSubmission(submissionId, action) {
          const response = await fetch('../api/submissions.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({
              action,
              submission_id: submissionId,
            }),
          });

          const result = await response.json();

          if (!response.ok || !result.ok) {
            throw new Error(result.error || 'Unable to update submission.');
          }

          return result;
        }

        list?.addEventListener('click', async (event) => {
          const button = event.target.closest('[data-submission-action]');

          if (!button) {
            return;
          }

          const card = button.closest('[data-submission-card]');
          const submissionId = Number(card?.dataset.submissionId || 0);
          const action = button.dataset.submissionAction || 'approve';

          if (!submissionId) {
            return;
          }

          button.disabled = true;

          try {
            await updateSubmission(submissionId, action);
            window.location.reload();
          } catch (error) {
            button.disabled = false;
            window.alert(error instanceof Error ? error.message : 'Unable to update submission.');
          }
        });
      })();
    </script>
    <?php
});
