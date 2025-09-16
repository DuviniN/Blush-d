
// Hero Section Slider Animation (Legacy support)
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


async function loadReviews() {
	try{
		const response = await fetch('./server/api.php?endpoint=reviews');
		const result = await response.json();
		
		if (result.success && result.data) {
			const reviews = result.data;
			let html = "";
			reviews.forEach(r => {
				html += `
					<div class="review-card">
						<div class="review-header">
							<h3>${r.username}</h3>
							<div class="rating">${'★'.repeat(r.rating)}${'☆'.repeat(5-r.rating)}</div>
						</div>
						<p>"${r.comment}"</p>
						<small>Product: ${r.product_name}</small>
					</div>
				`;
			});
			document.getElementById("review-list").innerHTML = html;
		} else {
			document.getElementById("review-list").innerHTML = '<p>No reviews available.</p>';
		}
	}
	catch (error) {
		console.error('Failed to load reviews:', error);
		document.getElementById("review-list").innerHTML = '<p>Failed to load reviews.</p>';
	}
}

// Load reviews when page loads
document.addEventListener('DOMContentLoaded', function() {
	loadReviews();
});
		