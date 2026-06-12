
(function initScrollAnimations() {
	function applyAnimation(el) {
		if (el.matches && el.matches('img, picture, svg, video')) return;
		let type = el.getAttribute('data-animate');
		if (!type) {
			const match = Array.from(el.classList).join(' ').match(/\banim-([a-z0-9\-]+)/i);
			if (match) type = match[1];
		}
		type = type || 'slide-down';

		const delay = el.getAttribute('data-animate-delay') || el.getAttribute('data-delay') || '0s';
		el.style.animationDelay = delay;

		const animClass = 'anim-' + type.replace(/[^a-z0-9\-]/gi, '');
		if (!el.classList.contains(animClass)) el.classList.add(animClass);
		el.classList.add('animated');
	}

	function observe() {
		const selector = '[data-animate]:not(img):not(picture):not(svg):not(video), [class*="anim-"]:not(img):not(picture):not(svg):not(video), .keunggulan-grid';
		const nodeList = document.querySelectorAll(selector);
		const targets = Array.from(nodeList);
		if (!targets.length) return;

		if ('IntersectionObserver' in window) {
			const io = new IntersectionObserver((entries, observer) => {
				entries.forEach(entry => {
					if (!entry.isIntersecting) return;

					const target = entry.target;

					if (target.matches && target.matches('.keunggulan-grid')) {
						const cards = Array.from(target.querySelectorAll('.keunggulan-card'));
						cards.forEach((card, idx) => {
							if (card.classList.contains('animated')) return;
							const delay = (idx) * 0.12; //Atur delay bertahap untuk setiap card atau section
							card.style.animationDelay = delay + 's';
							card.classList.add('anim-slide-right');
							card.classList.add('animated');
						});
						observer.unobserve(target);
						return;
					}

					applyAnimation(target);
					observer.unobserve(target);
				});
			}, { threshold: 0.15, rootMargin: '0px 0px -10% 0px' });

			targets.forEach(t => io.observe(t));
		} else {
			targets.forEach(applyAnimation);
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', observe);
	} else {
		observe();
	}
})();

