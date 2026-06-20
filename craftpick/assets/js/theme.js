(() => {
  const html = document.documentElement;
  const body = document.body;

  function getCookie(name) {
    const prefix = `${name}=`;
    return document.cookie
      .split(';')
      .map((part) => part.trim())
      .find((part) => part.startsWith(prefix))
      ?.slice(prefix.length) || null;
  }

  function currentTheme() {
    return (
      body?.getAttribute('data-bs-theme') ||
      html?.getAttribute('data-bs-theme') ||
      getCookie('theme') ||
      'dark'
    );
  }

  function applyTheme(theme) {
    if (!theme) return;

    if (html?.getAttribute('data-bs-theme') !== theme) {
      html?.setAttribute('data-bs-theme', theme);
    }

    if (body?.getAttribute('data-bs-theme') !== theme) {
      body?.setAttribute('data-bs-theme', theme);
    }

    if (getCookie('theme') !== theme) {
      document.cookie = `theme=${theme}; path=/; max-age=31536000; SameSite=Lax`;
    }

    document.querySelectorAll('[data-theme-value]').forEach((element) => {
      const isActive = element.getAttribute('data-theme-value') === theme;
      element.classList.toggle('d-none', isActive);
    });
  }

  function copy(text) {
    if (navigator.clipboard?.writeText) {
      return navigator.clipboard.writeText(text);
    }

    const el = document.createElement('textarea');
    el.value = text;
    el.setAttribute('readonly', '');
    el.style.position = 'absolute';
    el.style.left = '-9999px';
    document.body.appendChild(el);
    el.select();
    document.execCommand('copy');
    document.body.removeChild(el);
    return Promise.resolve();
  }

  function bindCopyButtons() {
    document.querySelectorAll('[data-craftpick-copy]').forEach((btn) => {
      if (btn.dataset.craftpickBound === '1') return;
      btn.dataset.craftpickBound = '1';

      btn.addEventListener('click', async () => {
        const value = btn.getAttribute('data-craftpick-copy') || '';
        if (!value) return;
        try {
          await copy(value);
          btn.classList.add('craftpick-copied');
          window.setTimeout(() => btn.classList.remove('craftpick-copied'), 1200);
        } catch (_) {
        }
      });
    });
  }

  function animationsDisabled() {
    const level = body?.dataset.animationLevel || 'full';
    return level === 'off' || window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  }

  function initLoader() {
    const loader = document.getElementById('craftpickLoader');
    if (!loader) return;

    const closeLoader = () => {
      loader.classList.add('is-hidden');
      window.setTimeout(() => loader.remove(), 700);
    };

    window.addEventListener('load', () => {
      const delay = body?.dataset.loaderStyle === 'cinematic' ? 450 : 180;
      window.setTimeout(closeLoader, delay);
    }, { once: true });
  }

  function initPageTransitions() {
    const mode = body?.dataset.pageTransition || 'none';
    if (mode === 'none' || animationsDisabled()) return;

    document.querySelectorAll('a[href]').forEach((link) => {
      if (link.dataset.craftpickTransitionBound === '1') return;
      link.dataset.craftpickTransitionBound = '1';

      link.addEventListener('click', (event) => {
        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;
        if (link.target === '_blank' || link.hasAttribute('download')) return;
        if (link.hasAttribute('data-theme-value') || link.closest('[data-theme-switcher]')) return;

        const url = new URL(href, window.location.origin);
        if (url.origin !== window.location.origin) return;
        if (url.pathname === window.location.pathname && url.search === window.location.search && url.hash === window.location.hash) return;

        event.preventDefault();
        body.classList.add(mode === 'slide' ? 'craftpick-transition-slide' : 'craftpick-transition-fade');
        window.setTimeout(() => {
          window.location.href = url.toString();
        }, 180);
      });
    });
  }

  function initTiltCards() {
    if (body?.dataset.craftpickTilt !== '1' || animationsDisabled()) return;

    document.querySelectorAll('[data-craftpick-tilt-card]').forEach((card) => {
      card.addEventListener('mousemove', (event) => {
        const rect = card.getBoundingClientRect();
        const px = (event.clientX - rect.left) / rect.width;
        const py = (event.clientY - rect.top) / rect.height;
        const rotateY = (px - 0.5) * 10;
        const rotateX = (0.5 - py) * 10;
        card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-4px)`;
      });

      card.addEventListener('mouseleave', () => {
        card.style.transform = '';
      });
    });
  }

  function initParallax() {
    if (body?.dataset.craftpickParallax !== '1' || animationsDisabled()) return;

    const items = document.querySelectorAll('[data-craftpick-parallax]');
    if (!items.length) return;

    window.addEventListener('mousemove', (event) => {
      const x = (event.clientX / window.innerWidth) - 0.5;
      const y = (event.clientY / window.innerHeight) - 0.5;

      items.forEach((item) => {
        const depth = Number(item.getAttribute('data-craftpick-parallax') || '12');
        item.style.transform = `translate3d(${x * depth}px, ${y * depth}px, 0)`;
      });
    });
  }

  function initCinematicIntro() {
    if (body?.dataset.craftpickCinematic !== '1' || animationsDisabled()) return;
    body.classList.add('craftpick-cinematic-ready');
    window.setTimeout(() => body.classList.add('craftpick-cinematic-in'), 80);
  }

  function initThemeSync() {
    applyTheme(getCookie('theme') || currentTheme());

    document.querySelectorAll('[data-theme-value]').forEach((link) => {
      if (link.dataset.craftpickThemeBound === '1') return;
      link.dataset.craftpickThemeBound = '1';

      link.addEventListener('click', (event) => {
        const theme = link.getAttribute('data-theme-value');
        if (!theme) return;

        event.preventDefault();
        event.stopImmediatePropagation();
        applyTheme(theme);
      });
    });

    const observer = new MutationObserver(() => {
      const theme = html?.getAttribute('data-bs-theme') || body?.getAttribute('data-bs-theme');
      if (theme) applyTheme(theme);
    });

    if (html) {
      observer.observe(html, { attributes: true, attributeFilter: ['data-bs-theme'] });
    }

    if (body) {
      observer.observe(body, { attributes: true, attributeFilter: ['data-bs-theme'] });
    }
  }

  function initHomeDesigner() {
    const panel = document.querySelector('[data-craftpick-designer]');
    const toggleButton = document.querySelector('[data-craftpick-designer-toggle]');
    const closeButton = document.querySelector('[data-craftpick-designer-close]');

    if (!panel || !body) return;

    const storageKey = 'craftpick-home-designer';
    const defaultState = {
      primary: panel.dataset.defaultPrimary || '#7c3aed',
      secondary: panel.dataset.defaultSecondary || '#22d3ee',
      surface: panel.dataset.defaultSurface || '#1f115c',
      background: panel.dataset.defaultBackground || '#14082e',
      radius: Number(panel.dataset.defaultRadius || '22'),
      displayName: panel.dataset.defaultDisplayName || 'Craftpick MC',
      heroTitle: panel.dataset.defaultHeroTitle || 'Bienvenue sur Craftpick',
      surfaceAlpha: 82,
      glow: true,
      compactSidebar: true,
    };

    const colorInputs = {
      primary: panel.querySelector('[data-craftpick-color="primary"]'),
      secondary: panel.querySelector('[data-craftpick-color="secondary"]'),
      surface: panel.querySelector('[data-craftpick-color="surface"]'),
      background: panel.querySelector('[data-craftpick-color="background"]'),
    };

    const textInputs = {
      displayName: panel.querySelector('[data-craftpick-text="displayName"]'),
      heroTitle: panel.querySelector('[data-craftpick-text="heroTitle"]'),
    };

    const rangeInputs = {
      radius: panel.querySelector('[data-craftpick-range="radius"]'),
      surfaceAlpha: panel.querySelector('[data-craftpick-range="surfaceAlpha"]'),
    };

    const toggles = {
      glow: panel.querySelector('[data-craftpick-toggle="glow"]'),
      compactSidebar: panel.querySelector('[data-craftpick-toggle="compactSidebar"]'),
    };

    function parseState() {
      try {
        const raw = localStorage.getItem(storageKey);
        return raw ? { ...defaultState, ...JSON.parse(raw) } : { ...defaultState };
      } catch (_) {
        return { ...defaultState };
      }
    }

    function syncControls(state) {
      Object.entries(colorInputs).forEach(([key, input]) => {
        if (input) input.value = state[key];
      });

      Object.entries(textInputs).forEach(([key, input]) => {
        if (input) input.value = state[key];
      });

      Object.entries(rangeInputs).forEach(([key, input]) => {
        if (input) input.value = String(state[key]);
      });

      Object.entries(toggles).forEach(([key, input]) => {
        if (input) input.checked = Boolean(state[key]);
      });
    }

    function applyState(state) {
      body.style.setProperty('--craftpick-primary', state.primary);
      body.style.setProperty('--craftpick-secondary', state.secondary);
      body.style.setProperty('--craftpick-surface-custom', state.surface);
      body.style.setProperty('--craftpick-background-custom', state.background);
      body.style.setProperty('--craftpick-radius-lg', `${state.radius}px`);
      body.style.setProperty('--craftpick-designer-surface-alpha', String(state.surfaceAlpha / 100));
      body.classList.toggle('craftpick-designer-no-glow', !state.glow);
      body.classList.toggle('craftpick-designer-sidebar-wide', !state.compactSidebar);

      document.querySelectorAll('[data-craftpick-live-name]').forEach((element) => {
        element.textContent = state.displayName;
      });

      document.querySelectorAll('[data-craftpick-live-hero-title]').forEach((element) => {
        element.textContent = state.heroTitle;
      });
    }

    function saveState(state) {
      localStorage.setItem(storageKey, JSON.stringify(state));
    }

    let state = parseState();
    syncControls(state);
    applyState(state);

    function updateState(partial) {
      state = { ...state, ...partial };
      applyState(state);
      saveState(state);
    }

    toggleButton?.addEventListener('click', () => {
      panel.classList.toggle('is-open');
      panel.setAttribute('aria-hidden', panel.classList.contains('is-open') ? 'false' : 'true');
    });

    closeButton?.addEventListener('click', () => {
      panel.classList.remove('is-open');
      panel.setAttribute('aria-hidden', 'true');
    });

    Object.entries(colorInputs).forEach(([key, input]) => {
      input?.addEventListener('input', () => updateState({ [key]: input.value }));
    });

    Object.entries(textInputs).forEach(([key, input]) => {
      input?.addEventListener('input', () => updateState({ [key]: input.value }));
    });

    Object.entries(rangeInputs).forEach(([key, input]) => {
      input?.addEventListener('input', () => updateState({ [key]: Number(input.value) }));
    });

    Object.entries(toggles).forEach(([key, input]) => {
      input?.addEventListener('change', () => updateState({ [key]: input.checked }));
    });

    panel.querySelectorAll('[data-craftpick-preset]').forEach((button) => {
      button.addEventListener('click', () => {
        updateState({
          primary: button.dataset.primary || defaultState.primary,
          secondary: button.dataset.secondary || defaultState.secondary,
          surface: button.dataset.surface || defaultState.surface,
          background: button.dataset.background || defaultState.background,
        });
        syncControls(state);
      });
    });

    panel.querySelector('[data-craftpick-designer-reset]')?.addEventListener('click', () => {
      state = { ...defaultState };
      syncControls(state);
      applyState(state);
      saveState(state);
    });

    panel.querySelector('[data-craftpick-designer-copy]')?.addEventListener('click', async () => {
      const payload = JSON.stringify(state, null, 2);

      try {
        await copy(payload);
      } catch (_) {
      }
    });
  }

  function initTheme() {
    initThemeSync();
    bindCopyButtons();
    initLoader();
    initPageTransitions();
    initTiltCards();
    initParallax();
    initCinematicIntro();
    initHomeDesigner();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTheme);
  } else {
    initTheme();
  }
})();
