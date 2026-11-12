(function () {
	"use strict";

	var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

	/* ---------------- Header scroll state ---------------- */
	var header = document.getElementById("bcclHeader");
	if (header) {
		window.addEventListener(
			"scroll",
			function () {
				header.classList.toggle("is-scrolled", window.scrollY > 12);
			},
			{ passive: true }
		);
	}

	/* ---------------- Mobile nav ---------------- */
	var mobileNav = document.getElementById("bcclMobileNav");
	var openBtn = document.getElementById("bcclOpenMobileNav");
	var closeBtn = document.getElementById("bcclCloseMobileNav");

	function openMobileNav() {
		if (!mobileNav) return;
		mobileNav.classList.add("is-open");
		if (openBtn) openBtn.setAttribute("aria-expanded", "true");
		document.body.style.overflow = "hidden";
		var first = mobileNav.querySelector("a");
		if (first) first.focus();
	}
	function closeMobileNav() {
		if (!mobileNav) return;
		mobileNav.classList.remove("is-open");
		if (openBtn) openBtn.setAttribute("aria-expanded", "false");
		document.body.style.overflow = "";
	}
	if (openBtn) openBtn.addEventListener("click", openMobileNav);
	if (closeBtn) closeBtn.addEventListener("click", closeMobileNav);
	document.addEventListener("keydown", function (e) {
		if (e.key === "Escape") closeMobileNav();
	});
	if (mobileNav) {
		mobileNav.querySelectorAll("a").forEach(function (a) {
			a.addEventListener("click", closeMobileNav);
		});
	}

	/* ---------------- Counters ---------------- */
	function animateCounters(scope) {
		var els = (scope || document).querySelectorAll("[data-count]");
		els.forEach(function (el) {
			if (el.dataset.counted) return;
			var target = parseInt(el.getAttribute("data-count"), 10);
			var suffix = el.getAttribute("data-suffix") || "";
			if (reduceMotion || isNaN(target)) {
				el.textContent = (isNaN(target) ? el.getAttribute("data-count") : target) + suffix;
				el.dataset.counted = "1";
				return;
			}
			var start = null;
			var dur = 1100;
			function step(ts) {
				if (!start) start = ts;
				var p = Math.min((ts - start) / dur, 1);
				var eased = 1 - Math.pow(1 - p, 3);
				el.textContent = Math.round(target * eased) + suffix;
				if (p < 1) {
					requestAnimationFrame(step);
				} else {
					el.dataset.counted = "1";
				}
			}
			requestAnimationFrame(step);
		});
	}

	/* ---------------- Scroll reveal ---------------- */
	var observer =
		"IntersectionObserver" in window
			? new IntersectionObserver(
					function (entries) {
						entries.forEach(function (entry) {
							if (entry.isIntersecting) {
								entry.target.classList.add("is-visible");
								animateCounters(entry.target);
								observer.unobserve(entry.target);
							}
						});
					},
					{ threshold: 0.15 }
			  )
			: null;

	function observeReveals(root) {
		var els = (root || document).querySelectorAll(".bccl-reveal");
		if (observer) {
			els.forEach(function (el) {
				observer.observe(el);
			});
		} else {
			els.forEach(function (el) {
				el.classList.add("is-visible");
			});
			animateCounters(root || document);
		}
	}

	// Safety net: an instant scroll (End key, scrollbar-track click) can jump
	// straight past the observer's trigger point, leaving content stuck at
	// opacity:0. Sweep on scroll/resize and reveal anything already on
	// screen that the observer missed.
	function sweepReveals() {
		document.querySelectorAll(".bccl-reveal:not(.is-visible)").forEach(function (el) {
			var r = el.getBoundingClientRect();
			if (r.top < window.innerHeight * 0.92 && r.bottom > 0) {
				el.classList.add("is-visible");
				animateCounters(el);
			}
		});
	}
	function throttle(fn, wait) {
		var t = 0;
		return function () {
			var now = Date.now();
			if (now - t >= wait) {
				t = now;
				fn();
			}
		};
	}
	window.addEventListener("scroll", throttle(sweepReveals, 150), { passive: true });
	window.addEventListener("resize", throttle(sweepReveals, 150));
	// Belt-and-suspenders: nothing stays invisible forever.
	window.setTimeout(function () {
		document.querySelectorAll(".bccl-reveal:not(.is-visible)").forEach(function (el) {
			el.classList.add("is-visible");
			animateCounters(el);
		});
	}, 4000);

	document.addEventListener("DOMContentLoaded", function () {
		observeReveals(document);
	});

	/* ---------------- Timeline scroll progress ---------------- */
	var scroller = document.getElementById("bcclTimelineScroller");
	var progressBar = document.getElementById("bcclTimelineProgressBar");
	function updateTimelineProgress() {
		if (!scroller || !progressBar) return;
		var max = scroller.scrollWidth - scroller.clientWidth;
		var pct = max > 0 ? (scroller.scrollLeft / max) * 100 : 0;
		progressBar.style.width = pct + "%";
	}
	if (scroller) {
		scroller.addEventListener("scroll", updateTimelineProgress, { passive: true });
		document.addEventListener("DOMContentLoaded", updateTimelineProgress);
	}

	/* ---------------- Join / newsletter form (front-end only, no
	 * subscriber storage exists — matches the reference design's own
	 * client-side-only confirmation). ---------------- */
	var joinForm = document.getElementById("bcclJoinForm");
	if (joinForm) {
		joinForm.addEventListener("submit", function (e) {
			e.preventDefault();
			var msg = document.getElementById("bcclJoinMsg");
			if (msg) msg.classList.add("is-shown");
			var email = document.getElementById("bcclJoinEmail");
			if (email) email.value = "";
		});
	}

	/* ---------------- Apply Now modal (wraps the real Gravity Forms
	 * "Career Application" form). ---------------- */
	function closeAllModals() {
		document.querySelectorAll(".bccl-modal-overlay.is-open").forEach(function (m) {
			m.classList.remove("is-open");
		});
		document.body.style.overflow = "";
	}
	document.querySelectorAll("[data-bccl-modal-open]").forEach(function (btn) {
		btn.addEventListener("click", function (e) {
			e.preventDefault();
			var modal = document.getElementById(btn.getAttribute("data-bccl-modal-open"));
			if (!modal) return;
			modal.classList.add("is-open");
			document.body.style.overflow = "hidden";
			var first = modal.querySelector("input, select, textarea, button");
			if (first) first.focus();
		});
	});
	document.querySelectorAll("[data-bccl-modal-close]").forEach(function (btn) {
		btn.addEventListener("click", closeAllModals);
	});
	document.querySelectorAll(".bccl-modal-overlay").forEach(function (overlay) {
		overlay.addEventListener("click", function (e) {
			if (e.target === overlay) closeAllModals();
		});
	});
	document.addEventListener("keydown", function (e) {
		if (e.key === "Escape") closeAllModals();
	});

	/* ---------------- Careers Marketplace: keep the "N roles found"
	 * counter in sync with AJAX-filtered results — ccjp-frontend.js fires
	 * this event after every search, since it doesn't know this theme's
	 * markup for the counter itself. ---------------- */
	document.addEventListener("ccjp:listing-updated", function (e) {
		var countEl = document.getElementById("bcclResultsCount");
		if (countEl && e.detail && typeof e.detail.found === "number") {
			countEl.textContent = e.detail.found.toLocaleString();
		}
	});

	/* ---------------- Careers Marketplace: checking/unchecking any
	 * "Refine results" checkbox (Location, Experience, Employment Type,
	 * Work Mode) submits the filter form immediately instead of waiting
	 * for "Apply filters" — requestSubmit() (not submit()) so it fires a
	 * real 'submit' event, which ccjp-frontend.js's AJAX intercept picks
	 * up, updating the results in place rather than a full page reload.
	 * ---------------- */
	document.querySelectorAll(".bccl-filter-group input[type=\"checkbox\"]").forEach(function (cb) {
		cb.addEventListener("change", function () {
			var form = cb.form;
			if (!form) return;
			if (form.requestSubmit) {
				form.requestSubmit();
			} else {
				form.submit();
			}
		});
	});

	/* ---------------- Careers Marketplace chip row: each chip is a real,
	 * navigable <a href> (works with JS off), but with JS on the click is
	 * intercepted and routed through the same hidden "category" field +
	 * form.requestSubmit() the checkboxes use, so it gets the same AJAX
	 * in-place update instead of a full page reload. Active/inactive is
	 * read from the chip's *current* .is-active class, not a value baked
	 * into the markup at page-render time — the AJAX update never
	 * reloads the page, so a data attribute set only at render time would
	 * go stale after the first click and re-select instead of toggling
	 * off on the second. ---------------- */
	var chipRow = document.getElementById("bcclChipRow");
	if (chipRow) {
		chipRow.addEventListener("click", function (e) {
			var link = e.target.closest("[data-chip-slug]");
			if (!link) return;
			var categoryInput = document.getElementById("bcclCategoryInput");
			var form = document.getElementById("bcclCareersFilterForm");
			if (!categoryInput || !form) return; // no JS-enhanced path available — let the real href navigate.

			e.preventDefault();
			var wasActive = link.classList.contains("is-active");
			chipRow.querySelectorAll(".bccl-chip").forEach(function (c) {
				c.classList.remove("is-active");
			});
			if (!wasActive) {
				link.classList.add("is-active");
			}
			categoryInput.value = wasActive ? "" : link.getAttribute("data-chip-slug");
			if (form.requestSubmit) {
				form.requestSubmit();
			} else {
				form.submit();
			}
		});
	}
})();
