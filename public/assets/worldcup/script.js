function toFaDigits(value){
  return String(value ?? '').replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[d]);
}
function pad2(n){ return String(n).padStart(2,'0'); }

function updateCountdowns(){
  document.querySelectorAll('.countdown-wrap[data-countdown]').forEach(el=>{
    const target = new Date(el.dataset.countdown).getTime();
    const diff = target - Date.now();
    const timeEl = el.querySelector('.countdown-time');
    const lblEl  = el.querySelector('.countdown-lbl');
    if(!timeEl || Number.isNaN(target)) return;
    if(diff <= 0){
      timeEl.textContent = 'در حال برگزاری';
      if(lblEl) lblEl.style.display = 'none';
      return;
    }
    const h = Math.floor(diff / 3600000);
    const m = Math.floor((diff % 3600000) / 60000);
    const s = Math.floor((diff % 60000) / 1000);
    timeEl.textContent = toFaDigits(pad2(h)) + ' : ' + toFaDigits(pad2(m)) + ' : ' + toFaDigits(pad2(s));
  });
}
updateCountdowns();
setInterval(updateCountdowns, 1000);

document.querySelectorAll('.score-inp, .live-score-inp').forEach(inp=>{
  inp.addEventListener('input',()=>{
    inp.value = inp.value.replace(/[^0-9]/g,'').slice(0,2);
  });
  inp.addEventListener('keydown',e=>{
    if(['e','E','+','-','.'].includes(e.key)) e.preventDefault();
  });
});

function showPredictionTab(tab, btn){
  document.querySelectorAll('#predictionTabs .tab').forEach(t=>t.classList.remove('active'));
  btn.classList.add('active');
  const upcoming = document.getElementById('upcomingPanel');
  const past = document.getElementById('pastPanel');
  if(upcoming) upcoming.style.display = tab === 'upcoming' ? 'block' : 'none';
  if(past) past.style.display = tab === 'past' ? 'block' : 'none';
}

document.querySelectorAll('.faq-item').forEach(item=>{
  item.addEventListener('click',()=>item.classList.toggle('open'));
});

let liveBase = Number(document.getElementById('liveNum')?.dataset.base || 0);
const liveEl = document.getElementById('liveNum');
if(liveEl){
  setInterval(()=>{
    liveBase += Math.floor(Math.random()*7)-2;
    liveEl.textContent = liveBase.toLocaleString('fa-IR');
  }, 3500);
}
