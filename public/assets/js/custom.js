(function () {
    'use strict';

    const toggles = {
        notifications: {
            button: document.querySelector('[data-toggle="notifications"]'),
            panel: document.getElementById('notificationDropdown')
        },
        profile: {
            button: document.querySelector('[data-toggle="profile-menu"]'),
            panel: document.getElementById('profileDropdown')
        }
    };

    const panels = Object.values(toggles).map(item => item.panel).filter(Boolean);
    const buttons = Object.values(toggles).map(item => item.button).filter(Boolean);

    function closeAll() {
        panels.forEach(panel => panel?.classList.remove('is-open'));
        buttons.forEach(button => button?.setAttribute('aria-expanded', 'false'));
        panels.forEach(panel => panel?.setAttribute('aria-hidden', 'true'));
    }

    function togglePanel(button, panel) {
        if (!button || !panel) {
            return;
        }

        const isOpen = panel.classList.contains('is-open');
        closeAll();

        if (!isOpen) {
            panel.classList.add('is-open');
            panel.setAttribute('aria-hidden', 'false');
            button.setAttribute('aria-expanded', 'true');
            const firstFocusable = panel.querySelector('[role="menuitem"], .dropdown-link, button');
            firstFocusable?.focus();
        }
    }

    buttons.forEach(button => {
        button?.addEventListener('click', (event) => {
            const key = button.dataset.toggle === 'notifications' ? 'notifications' : 'profile';
            event.stopPropagation();
            togglePanel(toggles[key].button, toggles[key].panel);
        });

        button?.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeAll();
            }
        });
    });

    panels.forEach(panel => {
        panel?.addEventListener('click', event => event.stopPropagation());
    });

    document.addEventListener('click', () => closeAll());

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeAll();
        }
    });
})();
