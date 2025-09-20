/* Schulbanner neutral */
(function () {
  const school = document.getElementById('schoolBanner');
  if (school) school.style.cursor = 'default';
})();

/* Burger-Overlay + Logo-Transfer (overlayBrand und siteLogo optional) */
(function () {
  const burger = document.getElementById('burger');
  const overlay = document.getElementById('menuOverlay');
  const headerLogo = document.getElementById('siteLogo');      // optional
  const overlayBrand = document.getElementById('overlayBrand'); // optional

  // WICHTIG: Nur Burger + Overlay sind zwingend erforderlich
  if (!burger || !overlay) return;

  function syncOverlayLogo() {
    // Logo/Brand nur kopieren, wenn beide Elemente existieren
    if (headerLogo && overlayBrand) {
      overlayBrand.innerHTML = headerLogo.innerHTML;
    }
  }

  function openMenu() {
    syncOverlayLogo();
    document.body.classList.add('menu-open');
    burger.classList.add('active');
    burger.setAttribute('aria-expanded','true');
    // Overlay öffnen
    setTimeout(() => {
      overlay.classList.add('open');
      overlay.setAttribute('aria-hidden','false');
      document.body.classList.add('no-scroll');
    }, 0);
  }

  function closeMenu() {
    overlay.classList.remove('open');
    overlay.setAttribute('aria-hidden','true');
    document.body.classList.remove('no-scroll');
    setTimeout(() => {
      burger.classList.remove('active');
      burger.setAttribute('aria-expanded','false');
      document.body.classList.remove('menu-open');
    }, 120);
  }

  burger.addEventListener('click', (e) => {
    e.preventDefault(); e.stopPropagation();
    overlay.classList.contains('open') ? closeMenu() : openMenu();
  });

  // Links im Overlay schließen das Menü
  overlay.querySelectorAll('a').forEach(a => a.addEventListener('click', closeMenu));

  // Klick neben das Panel (auf den Overlay-Hintergrund) schließt ebenfalls
  overlay.addEventListener('click', (e) => { if (e.target === overlay) closeMenu(); });

  // ESC schließt
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && overlay.classList.contains('open')) closeMenu(); });
})();

/* i18n (DE/EN) */
(function () {
  const dict = {
    de: {
      title_home: "Startseite",
      title_project: "Projekt",
      title_minigames: "Mini-Spiele",
      title_contact: "Kontakt",
      nav_home: "Home",
      nav_minigames: "Mini-Spiele",
      nav_project: "Projekt",
      nav_contact: "Kontakt",
      nav_paypal: "PayPal",
      label_school: "Unsere Schule",
      home_intro:
        'In der <a href="https://www.erasmus-kittler-schule.de">Erasmus-Kittler-Schule</a> lernt ihr vieles über Datenbanken, Netzwerke, Webanwendungen und Projekte.<br>Mit geschulten Lehrkräften werdet ihr unterstützt und auf Prüfungen vorbereitet.<br>Mit ihnen erstellt ihr <a href="projekt.html">Projekte</a> wie dieses hier.',
      idea: "Die Idee", planning: "Planung", implementation: "Umsetzung", problems: "Probleme", solutions: "Lösungen", tests: "Tests", end: "Ende",
      panel: {
        idee:{title:"Die Idee",text:"Am Anfang steht die Grundidee des Projekts."},
        planung:{title:"Planung",text:"Hier wird die Struktur, Zeitplan und Aufgabenverteilung definiert."},
        umsetzung:{title:"Umsetzung",text:"Das Projekt wird Schritt für Schritt realisiert."},
        probleme:{title:"Probleme",text:"Herausforderungen und Hindernisse, die auftreten."},
        loesungen:{title:"Lösungen",text:"Wie die Probleme überwunden werden."},
        tests:{title:"Tests",text:"Überprüfung und Validierung des Projekts."},
        ende:{title:"Ende",text:"Das Projekt wird abgeschlossen und präsentiert."}
      }
    },
    en: {
      title_home:"Home",
      title_project:"Project",
      title_minigames:"Mini Games",
      title_contact:"Contact",
      nav_home:"Home",
      nav_minigames:"Mini Games",
      nav_project:"Project",
      nav_contact:"Contact",
      nav_paypal:"PayPal",
      label_school:"Our School",
      home_intro:
        'At the <a href="https://www.erasmus-kittler-schule.de">Erasmus-Kittler School</a> you learn about databases, networks, web applications and projects.<br>Experienced teachers support you and prepare you for exams.<br>Together you build <a href="projekt.html">projects</a> like this one.',
      idea:"The Idea", planning:"Planning", implementation:"Implementation", problems:"Challenges", solutions:"Solutions", tests:"Tests", end:"Finish",
      panel: {
        idee:{title:"The Idea",text:"Everything starts with the project’s core idea."},
        planung:{title:"Planning",text:"Define structure, timeline and responsibilities."},
        umsetzung:{title:"Implementation",text:"Build the project step by step."},
        probleme:{title:"Challenges",text:"Issues and obstacles that appear."},
        loesungen:{title:"Solutions",text:"How we overcome these problems."},
        tests:{title:"Testing",text:"Verification and validation of the project."},
        ende:{title:"Finish",text:"The project is wrapped up and presented."}
      }
    }
  };

  const state = { lang: localStorage.getItem('lp_lang') || 'de' };

  function applyDomTexts() {
    document.documentElement.lang = state.lang;
    document.querySelectorAll('[data-i18n]').forEach(el => {
      const key = el.getAttribute('data-i18n');
      const t = dict[state.lang][key];
      if (typeof t === 'string') el.textContent = t;
    });
    document.querySelectorAll('[data-i18n-html]').forEach(el => {
      const key = el.getAttribute('data-i18n-html');
      const t = dict[state.lang][key];
      if (typeof t === 'string') el.innerHTML = t;
    });
    const titleEl = document.querySelector('title');
    if (titleEl) {
      const k = titleEl.getAttribute('data-i18n');
      if (k && dict[state.lang][k]) document.title = dict[state.lang][k];
    }
    const langBtn = document.getElementById('langToggle');
    if (langBtn) langBtn.textContent = state.lang.toUpperCase();
  }

  function setLang(next) {
    if (!dict[next]) return;
    state.lang = next; localStorage.setItem('lp_lang', state.lang);
    applyDomTexts();
    document.dispatchEvent(new CustomEvent('lp:langchanged', { detail:{ lang: state.lang } }));
  }
  function toggleLang(){ setLang(state.lang === 'de' ? 'en' : 'de'); }

  window.LP = window.LP || {};
  window.LP.getLang = () => state.lang;
  window.LP.getPanelDict = () => dict[state.lang].panel;

  applyDomTexts();
  const langBtn = document.getElementById('langToggle');
  if (langBtn) langBtn.addEventListener('click', toggleLang);
})();

/* Roadmap (Projekt) – Panel sofort, exakt rechts neben der Endposition */
(function () {
  const layout = document.getElementById('roadmap');
  if (!layout) return;

  const steps = Array.from(layout.querySelectorAll('.step'));
  const circles = Array.from(layout.querySelectorAll('.circle'));
  const panel = layout.querySelector('.roadmap-right');
  const titleEl = panel.querySelector('.panel-title');
  const textEl = panel.querySelector('.panel-text');
  const btnClose = panel.querySelector('.panel-close');

  const LEFT_MARGIN = 8;
  const PANEL_GAP  = 6;

  function resetState(){
    circles.forEach(c => { c.classList.remove('active'); c.style.setProperty('--shift','0px'); });
    layout.querySelectorAll('.line').forEach(l => l.classList.remove('line--hidden'));
  }

  function hideAdjacentLines(stepIndex){
    const isFirst = stepIndex === 0;
    const isLast = stepIndex === steps.length - 1;
    const curLower = steps[stepIndex].querySelector('.line');
    if (!isLast && curLower) curLower.classList.add('line--hidden');
    if (!isFirst){
      const prevLower = steps[stepIndex - 1].querySelector('.line');
      if (prevLower) prevLower.classList.add('line--hidden');
    }
  }

  function shiftCircleLeft(circleBtn){
    const layoutRect = layout.getBoundingClientRect();
    const circleRect = circleBtn.getBoundingClientRect();
    const desiredLeft = layoutRect.left + LEFT_MARGIN;
    const shift = desiredLeft - circleRect.left;
    circleBtn.style.setProperty('--shift', `${shift}px`);
    return { desiredLeft, circleWidth: circleRect.width, shift };
  }

  function positionPanel(circleBtn, desiredLeft, circleWidth){
    const layoutRect = layout.getBoundingClientRect();
    const circleRect = circleBtn.getBoundingClientRect();
    let left = (desiredLeft - layoutRect.left) + circleWidth + PANEL_GAP;
    const rawTop = (circleRect.top - layoutRect.top) - 10;
    const panelHeight = panel.getBoundingClientRect().height || 200;
    const maxTop = Math.max(0, layoutRect.height - panelHeight - 8);
    const top = Math.max(0, Math.min(rawTop, maxTop));
    const panelWidth = parseFloat(getComputedStyle(panel).width) || 220;
    const maxLeft = (layoutRect.width - panelWidth - 8);
    left = Math.min(left, maxLeft);
    panel.style.left = `${left}px`;
    panel.style.top  = `${top}px`;
  }

  function openPanel(id, btn, idx){
    const dict = window.LP && window.LP.getPanelDict ? window.LP.getPanelDict() : null;
    const data = (dict && dict[id]) || { title:'Schritt', text:'Noch keine Beschreibung.' };
    titleEl.textContent = data.title;
    textEl.textContent  = data.text;
    const { desiredLeft, circleWidth } = shiftCircleLeft(btn);
    panel.classList.add('open');
    positionPanel(btn, desiredLeft, circleWidth);
    hideAdjacentLines(idx);
  }

  function closePanel(){
    panel.classList.remove('open');
    resetState();
  }

  circles.forEach((btn, idx) => {
    btn.addEventListener('click', () => {
      if (btn.classList.contains('active') && panel.classList.contains('open')) { closePanel(); return; }
      resetState();
      btn.classList.add('active');
      openPanel(btn.dataset.id, btn, idx);
    });
  });
  btnClose.addEventListener('click', closePanel);

  document.addEventListener('lp:langchanged', () => {
    const active = layout.querySelector('.circle.active');
    if (!active || !panel.classList.contains('open')) return;
    const d = window.LP && window.LP.getPanelDict ? window.LP.getPanelDict() : null;
    const id = active.dataset.id;
    if (d && d[id]) { titleEl.textContent = d[id].title; textEl.textContent = d[id].text; }
  });

  window.addEventListener('resize', () => {
    const active = layout.querySelector('.circle.active');
    if (!active || !panel.classList.contains('open')) return;
    const layoutRect = layout.getBoundingClientRect();
    const desiredLeft = layoutRect.left + LEFT_MARGIN;
    positionPanel(active, desiredLeft, active.getBoundingClientRect().width);
  });
})();

/* Header ein-/ausblenden beim Scrollen */
(function () {
  const nav = document.querySelector('.menunav');
  if (!nav) return;
  let lastY = window.scrollY || 0, ticking = false;
  const down = 6, up = 6;

  function onScroll(){
    const y = window.scrollY || 0;
    if (y <= 0){ nav.classList.remove('nav-hidden'); lastY = y; ticking = false; return; }
    if (y > lastY + down){ nav.classList.add('nav-hidden'); lastY = y; }
    else if (y < lastY - up){ nav.classList.remove('nav-hidden'); lastY = y; }
    ticking = false;
  }
  window.addEventListener('scroll', () => { if (!ticking){ requestAnimationFrame(onScroll); ticking = true; } }, { passive:true });
})();