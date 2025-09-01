// Hero Section Slider Animation
document.addEventListener('DOMContentLoaded', function() {
	let currentSlide = 0;
	const slides = document.querySelectorAll('.hero-slide');
	const dots = document.querySelectorAll('.slider-dot');
	const caption = document.querySelector('.hero-caption');
	if (!slides.length || !dots.length || !caption) return;
	function showSlide(idx) {
		slides.forEach((el, i) => {
			el.classList.toggle('active', i === idx);
		});
		dots.forEach((el, i) => {
			el.classList.toggle('active', i === idx);
		});
		caption.classList.remove('animated-fadein');
		void caption.offsetWidth;
		caption.classList.add('animated-fadein');
	}
	function nextSlide() {
		currentSlide = (currentSlide + 1) % slides.length;
		showSlide(currentSlide);
	}
	dots.forEach((dot, i) => {
		dot.addEventListener('click', () => {
			currentSlide = i;
			showSlide(currentSlide);
		});
	});
	setInterval(nextSlide, 4000);
});