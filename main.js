(function() {
  const layout = document.getElementById('roadmap');
  if (!layout) return;
  
  const circles = Array.from(layout.querySelectorAll('.circle'));
  const panel = layout.querySelector('.roadmap-right');
  const titleEl = panel.querySelector('.panel-title');
  const textEl = panel.querySelector('.panel-text');
  const btnClose = panel.querySelector('.panel-close');
  
  const content = {
    idee: { title: 'Die Idee', text: 'Am Anfang steht die Grundidee des Projekts.' },
    planung: { title: 'Planung', text: 'Hier wird die Struktur, Zeitplan und Aufgabenverteilung definiert.' },
    umsetzung: { title: 'Umsetzung', text: 'Das Projekt wird Schritt für Schritt realisiert.' },
    probleme: { title: 'Probleme', text: 'Herausforderungen und Hindernisse, die auftreten.' },
    loesungen: { title: 'Lösungen', text: 'Wie die Probleme überwunden werden.' },
    tests: { title: 'Tests', text: 'Überprüfung und Validierung des Projekts.' },
    ende: { title: 'Ende', text: 'Das Projekt wird abgeschlossen und präsentiert.' }
  };
  
  function getShiftPx() {
    const raw = getComputedStyle(layout).getPropertyValue('--shift').trim();
    const num = parseFloat(raw);
    return isNaN(num) ? -130 : num;
  }
  
  function positionPanelNextTo(circleBtn) {
    const layoutRect = layout.getBoundingClientRect();
    const circleRect = circleBtn.getBoundingClientRect();
    const gap = 20;
    const currentShift = getShiftPx();
    const targetShift = -260;
    const delta = (-currentShift) - (-targetShift);
    const left = (circleRect.right - layoutRect.left) + delta + gap;
    const top = Math.max(0, (circleRect.top - layoutRect.top) - 10);
    panel.style.left = left + 'px';
    panel.style.top = top + 'px';
  }
  
  function openPanel(id, circleBtn) {
    circles.forEach(c => c.classList.remove('active'));
    circleBtn.classList.add('active');
    
    const data = content[id] || { title: 'Schritt', text: 'Noch keine Beschreibung.' };
    titleEl.textContent = data.title;
    textEl.textContent = data.text;
    
    requestAnimationFrame(() => {
      requestAnimationFrame(() => {
        positionPanelNextTo(circleBtn);
        panel.classList.add('open');
      });
    });
  }
  
  function closePanel() {
    panel.classList.remove('open');
    circles.forEach(c => c.classList.remove('active'));
  }
  
  circles.forEach(btn => {
    btn.addEventListener('click', () => {
      if (btn.classList.contains('active') && panel.classList.contains('open')) {
        closePanel();
      } else {
        openPanel(btn.dataset.id, btn);
      }
    });
  });
  
  btnClose.addEventListener('click', closePanel);
  
  window.addEventListener('resize', () => {
    const active = layout.querySelector('.circle.active');
    if (active && panel.classList.contains('open')) {
      positionPanelNextTo(active);
    }
  });
})();