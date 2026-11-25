(() => {
  const slides = [...document.querySelectorAll('.slide')];
  const dots = [...document.querySelectorAll('.dot')];
  if (!slides.length) return;
  let current = 0;
  const show = (n) => { current = (n + slides.length) % slides.length; slides.forEach((s,i)=>s.classList.toggle('active',i===current)); dots.forEach((d,i)=>d.classList.toggle('active',i===current)); };
  document.querySelector('.prev').addEventListener('click',()=>show(current-1));
  document.querySelector('.next').addEventListener('click',()=>show(current+1));
  dots.forEach((dot,i)=>dot.addEventListener('click',()=>show(i)));
  setInterval(()=>show(current+1),3000);
})();
