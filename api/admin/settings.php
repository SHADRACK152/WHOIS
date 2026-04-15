<?php
declare(strict_types=1);

require __DIR__ . '/_layout.php';

whois_admin_render_page([
    'title' => 'WHOIS.ARCHITECT | Admin Settings',
    'active' => 'settings',
    'eyebrow' => 'Admin Side',
    'headline' => 'Settings.',
    'description' => '',
], function (): void {
    $defaultSettings = [
      'sidebarMode' => 'Collapsible',
      'density' => 'Compact',
      'cardStyle' => 'Layered',
        'emailAlerts' => 'On',
        'reviewReminders' => 'On',
        'escrowAlerts' => 'Off',
        'dailySummary' => 'On',
        'autoQueue' => 'On',
        'sellerUpdates' => 'On',
        'manualApproval' => 'Yes',
        'autoPublish' => 'No',
        'domainLookup' => 'Live',
        'premiumOffers' => 'Live',
        'analyticsFeed' => 'Planned',
        'escrowApi' => 'Planned',
    ];
    ?>
    <style>
      .setting-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        border-radius: 1rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.65);
        border: 1px solid rgba(198, 198, 198, 0.42);
        transition: border-color 180ms ease, background-color 180ms ease, transform 180ms ease, box-shadow 180ms ease;
      }

      .setting-row:hover {
        border-color: rgba(198, 198, 198, 0.7);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.04);
      }

      .setting-toggle {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem;
        border-radius: 999px;
        border: 1px solid rgba(198, 198, 198, 0.32);
        background: rgba(0, 0, 0, 0.03);
        flex-shrink: 0;
      }

      .setting-option {
        min-width: 4.4rem;
        border-radius: 999px;
        padding: 0.6rem 0.9rem;
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: #616161;
        background: transparent;
        transition: background-color 180ms ease, color 180ms ease, box-shadow 180ms ease, transform 180ms ease;
      }

      .setting-option:hover {
        transform: translateY(-1px);
      }

      .setting-option[data-active='true'] {
        background: #111111;
        color: #ffffff;
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.14);
      }

      html.dark .setting-row {
        background: linear-gradient(180deg, #2b2b2b 0%, #242424 100%);
        border-color: rgba(255, 255, 255, 0.08);
      }

      html.dark .setting-row:hover {
        border-color: rgba(255, 255, 255, 0.12);
        box-shadow: 0 16px 34px rgba(0, 0, 0, 0.24);
      }

      html.dark .setting-toggle {
        background: rgba(255, 255, 255, 0.04);
        border-color: rgba(255, 255, 255, 0.08);
      }

      html.dark .setting-option {
        color: #d8d8d8;
      }

      html.dark .setting-option[data-active='true'] {
        background: #f4f4f4;
        color: #171717;
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.24);
      }

      @media (max-width: 640px) {
        .setting-row {
          align-items: stretch;
          flex-direction: column;
        }

        .setting-toggle {
          width: 100%;
          justify-content: space-between;
        }

        .setting-option {
          flex: 1 1 0;
        }
      }
    </style>

    <?php
    $renderSettingRow = static function (string $key, string $label, string $description, array $options): void {
        ?>
        <div class="setting-row">
          <div class="min-w-0">
            <p class="font-semibold text-on-surface"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></p>
            <p class="mt-1 text-xs leading-relaxed text-on-surface-variant"><?php echo htmlspecialchars($description, ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
          <div class="setting-toggle" data-setting-control data-setting-key="<?php echo htmlspecialchars($key, ENT_QUOTES, 'UTF-8'); ?>">
            <?php foreach ($options as $option): ?>
              <button class="setting-option" type="button" data-setting-option="<?php echo htmlspecialchars($option, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($option, ENT_QUOTES, 'UTF-8'); ?></button>
            <?php endforeach; ?>
          </div>
        </div>
        <?php
    };
    ?>

    <section class="grid gap-6 xl:grid-cols-3">
      <div class="rounded-[1.5rem] admin-panel p-6 xl:col-span-2">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Appearance</p>
        <h2 class="text-2xl font-extrabold tracking-tight mt-2 mb-4">Theme</h2>
        <div class="grid gap-3 sm:grid-cols-2">
          <button class="rounded-2xl bg-surface-container-low p-5 border border-outline-variant/20 text-left hover:bg-surface-container transition-colors" type="button" data-theme-toggle="light">
            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary mb-2">Light</p>
            <p class="font-semibold">Default daytime mode</p>
          </button>
          <button class="rounded-2xl bg-surface-container-low p-5 border border-outline-variant/20 text-left hover:bg-surface-container transition-colors" type="button" data-theme-toggle="dark">
            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary mb-2">Dark</p>
            <p class="font-semibold">Low-light operations mode</p>
          </button>
        </div>
        <div class="mt-6 space-y-3">
          <?php $renderSettingRow('sidebarMode', 'Sidebar', 'Choose whether the navigation can collapse.', ['Collapsible', 'Fixed']); ?>
          <?php $renderSettingRow('density', 'Density', 'Choose how tight the admin surfaces should feel.', ['Compact', 'Comfortable']); ?>
          <?php $renderSettingRow('cardStyle', 'Cards', 'Choose between layered depth or flatter surfaces.', ['Layered', 'Flat']); ?>
        </div>
      </div>
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Access</p>
        <h2 class="text-2xl font-extrabold tracking-tight mt-2 mb-4">Roles</h2>
        <div class="space-y-3 text-sm text-on-surface-variant">
          <div class="flex items-center justify-between rounded-xl bg-surface-container-low p-4 border border-outline-variant/20">
            <span>Auth</span>
            <span class="text-secondary font-bold">Required</span>
          </div>
          <div class="flex items-center justify-between rounded-xl bg-surface-container-low p-4 border border-outline-variant/20">
            <span>Moderators</span>
            <span class="text-secondary font-bold">2</span>
          </div>
          <div class="flex items-center justify-between rounded-xl bg-surface-container-low p-4 border border-outline-variant/20">
            <span>Admins</span>
            <span class="text-secondary font-bold">1</span>
          </div>
          <div class="flex items-center justify-between rounded-xl bg-surface-container-low p-4 border border-outline-variant/20">
            <span>Timeout</span>
            <span class="text-secondary font-bold">24h</span>
          </div>
        </div>
      </div>
    </section>

    <section class="mt-8 grid gap-6 lg:grid-cols-3">
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Notifications</p>
        <h2 class="text-2xl font-extrabold tracking-tight mt-2 mb-4">Alerts</h2>
        <div class="space-y-3 text-sm text-on-surface-variant">
          <?php $renderSettingRow('emailAlerts', 'Email alerts', 'Send email notifications when the queue changes.', ['On', 'Off']); ?>
          <?php $renderSettingRow('reviewReminders', 'Review reminders', 'Prompt admins when items are waiting.', ['On', 'Off']); ?>
          <?php $renderSettingRow('escrowAlerts', 'Escrow alerts', 'Notify the team about escrow milestones.', ['On', 'Off']); ?>
          <?php $renderSettingRow('dailySummary', 'Daily summary', 'Send one digest at the end of the day.', ['On', 'Off']); ?>
        </div>
      </div>
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Workflow</p>
        <h2 class="text-2xl font-extrabold tracking-tight mt-2 mb-4">Defaults</h2>
        <div class="space-y-3 text-sm text-on-surface-variant">
          <?php $renderSettingRow('autoQueue', 'Auto-queue new submissions', 'Place new submissions into the review queue.', ['On', 'Off']); ?>
          <?php $renderSettingRow('sellerUpdates', 'Send seller updates', 'Keep sellers posted during review.', ['On', 'Off']); ?>
          <?php $renderSettingRow('manualApproval', 'Manual approval required', 'Require a human decision before publishing.', ['Yes', 'No']); ?>
          <?php $renderSettingRow('autoPublish', 'Auto-publish listings', 'Publish approved listings automatically.', ['Yes', 'No']); ?>
        </div>
      </div>
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Data</p>
        <h2 class="text-2xl font-extrabold tracking-tight mt-2 mb-4">Integrations</h2>
        <div class="space-y-3 text-sm text-on-surface-variant">
          <?php $renderSettingRow('domainLookup', 'Domain lookup', 'Availability checks are connected to the live resolver.', ['Live', 'Planned']); ?>
          <?php $renderSettingRow('premiumOffers', 'Premium offers', 'Verified premium results are pulled from live sources.', ['Live', 'Planned']); ?>
          <?php $renderSettingRow('analyticsFeed', 'Analytics feed', 'Switch this on once the feed is connected.', ['Live', 'Planned']); ?>
          <?php $renderSettingRow('escrowApi', 'Escrow API', 'Enable when escrow integration is ready.', ['Live', 'Planned']); ?>
        </div>
      </div>
    </section>
    <section class="mt-8 grid gap-6 lg:grid-cols-2">
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Profile</p>
        <h2 class="text-2xl font-extrabold tracking-tight mt-2 mb-4">Account</h2>
        <div class="grid gap-3 sm:grid-cols-2">
          <div class="rounded-xl bg-surface-container-low p-4 border border-outline-variant/20">
            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary mb-2">Name</p>
            <p class="font-semibold">Admin User</p>
          </div>
          <div class="rounded-xl bg-surface-container-low p-4 border border-outline-variant/20">
            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary mb-2">Role</p>
            <p class="font-semibold">Owner</p>
          </div>
          <div class="rounded-xl bg-surface-container-low p-4 border border-outline-variant/20">
            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary mb-2">Email</p>
            <p class="font-semibold">admin@whois.architect</p>
          </div>
          <div class="rounded-xl bg-surface-container-low p-4 border border-outline-variant/20">
            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary mb-2">Status</p>
            <p class="font-semibold">Active</p>
          </div>
        </div>
      </div>
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Save</p>
        <h2 class="text-2xl font-extrabold tracking-tight mt-2 mb-4">Actions</h2>
        <p class="mb-4 text-sm leading-relaxed text-on-surface-variant">Toggles update instantly and stay in this browser until the backend save flow is added.</p>
        <div class="flex flex-wrap gap-3">
          <button class="rounded-full bg-primary px-5 py-3 text-xs font-bold uppercase tracking-[0.2em] text-on-primary hover:bg-primary-container transition-colors" type="button" data-save-settings>Save settings</button>
          <button class="rounded-full border border-outline-variant/40 bg-surface-container-lowest px-5 py-3 text-xs font-bold uppercase tracking-[0.2em] text-black hover:bg-surface-container-low transition-colors" type="button" data-reset-settings>Reset</button>
        </div>
      </div>
    </section>

    <script>
      (() => {
        const storageKey = 'whois.admin.settings.v1';
        const defaults = <?php echo json_encode($defaultSettings, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
        let state = { ...defaults };

        try {
          const stored = JSON.parse(localStorage.getItem(storageKey) || '{}');
          if (stored && typeof stored === 'object' && !Array.isArray(stored)) {
            state = { ...defaults, ...stored };
          }
        } catch (error) {
          state = { ...defaults };
        }

        const controls = document.querySelectorAll('[data-setting-control]');
        const saveButton = document.querySelector('[data-save-settings]');
        const resetButton = document.querySelector('[data-reset-settings]');
        const body = document.body;
        const html = document.documentElement;

        const writeLayoutSetting = (key, value) => {
          try {
            localStorage.setItem(key, value);
          } catch (error) {
            // Ignore storage errors in restricted environments.
          }
        };

        const applyAppearanceState = () => {
          const sidebarMode = state.sidebarMode === 'Fixed' ? 'Fixed' : 'Collapsible';
          const density = state.density === 'Comfortable' ? 'Comfortable' : 'Compact';
          const cardStyle = state.cardStyle === 'Flat' ? 'Flat' : 'Layered';

          body.classList.toggle('admin-sidebar-fixed', sidebarMode === 'Fixed');
          body.classList.toggle('admin-density-compact', density === 'Compact');
          body.classList.toggle('admin-density-comfortable', density === 'Comfortable');
          body.classList.toggle('admin-cards-layered', cardStyle === 'Layered');
          body.classList.toggle('admin-cards-flat', cardStyle === 'Flat');

          if (sidebarMode === 'Fixed') {
            body.classList.remove('admin-sidebar-collapsed');
            writeLayoutSetting('whois-admin-sidebar-collapsed', '0');
          }

          writeLayoutSetting('whois-admin-sidebar-mode', sidebarMode === 'Fixed' ? 'fixed' : 'collapsible');
          writeLayoutSetting('whois-admin-density', density === 'Comfortable' ? 'comfortable' : 'compact');
          writeLayoutSetting('whois-admin-card-style', cardStyle === 'Flat' ? 'flat' : 'layered');
        };

        const persist = () => {
          try {
            localStorage.setItem(storageKey, JSON.stringify(state));
          } catch (error) {
            // Ignore storage errors in restricted environments.
          }
        };

        const syncControl = (control) => {
          const key = control.dataset.settingKey;
          const value = state[key];

          control.querySelectorAll('[data-setting-option]').forEach((button) => {
            const isActive = button.dataset.settingOption === value;
            button.dataset.active = isActive ? 'true' : 'false';
            button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
          });
        };

        const syncAll = () => {
          controls.forEach(syncControl);
        };

        applyAppearanceState();
        syncAll();

        document.addEventListener('click', (event) => {
          const optionButton = event.target.closest('[data-setting-option]');

          if (optionButton && optionButton.closest('[data-setting-control]')) {
            const control = optionButton.closest('[data-setting-control]');
            const key = control.dataset.settingKey;
            const value = optionButton.dataset.settingOption;

            if (key && value) {
              state[key] = value;
              if (key === 'sidebarMode' && value === 'Fixed') {
                body.classList.remove('admin-sidebar-collapsed');
              }
              persist();
              applyAppearanceState();
              syncControl(control);
            }

            return;
          }

          if (event.target.closest('[data-save-settings]')) {
            persist();

            if (saveButton) {
              const originalLabel = saveButton.textContent;
              saveButton.textContent = 'Saved';
              window.setTimeout(() => {
                saveButton.textContent = originalLabel;
              }, 1100);
            }

            return;
          }

          if (event.target.closest('[data-reset-settings]')) {
            state = { ...defaults };
            persist();
            applyAppearanceState();
            syncAll();
          }
        });
      })();
    </script>

    <?php
  });
