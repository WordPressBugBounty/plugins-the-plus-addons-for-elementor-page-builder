"use strict";
function onDocumentLoaded(cb){
	if (/comp|inter|loaded/.test(document.readyState)) {
		cb()
	} else {
		document.addEventListener('DOMContentLoaded', cb, false)
	}
}

let lz = null
let tp_lazy_bg_observer = null

onDocumentLoaded(() => {
	if (document.querySelector('img.tp-lazyload')) {
		tp_lazy_load()
	}
	tp_lazy_background();
	tp_lazy_watch();
});

function tp_lazy_background(){
	var lazyBackgrounds = [].slice.call(document.querySelectorAll(".lazy-background"));

	if (!lazyBackgrounds.length || !("IntersectionObserver" in window && "IntersectionObserverEntry" in window && "intersectionRatio" in window.IntersectionObserverEntry.prototype)) {
		return
	}

	if (!tp_lazy_bg_observer) {
		tp_lazy_bg_observer = new IntersectionObserver(function(entries, observer) {
		  entries.forEach(function(entry) {
			if (entry.isIntersecting) {
			  entry.target.classList.remove("lazy-background");
			  tp_lazy_bg_observer.unobserve(entry.target);
			}
		  });
		});
	}

	// Observing an element twice is a no-op, so the ones already watched are safe to pass again.
	lazyBackgrounds.forEach(function(lazyBackground) {
	  tp_lazy_bg_observer.observe(lazyBackground);
	});
}

/*
 * Load more, filters, infinite scroll, carousel clones and popups add markup after
 * load, and the lazy load script only scanned the page once, so their images stayed
 * hidden. Pick up the lazy images and backgrounds they bring, once per frame.
 */
function tp_lazy_watch(){
	if (!("MutationObserver" in window) || !document.body) {
		return
	}

	var queued = false;
	var selector = 'img.tp-lazyload, .lazy-background';

	new MutationObserver(function(mutations) {
		if (queued) {
			return
		}

		var found = mutations.some(function(mutation) {
			return [].some.call(mutation.addedNodes, function(node) {
				return node.nodeType === 1 && (node.matches(selector) || null !== node.querySelector(selector));
			});
		});

		if (!found) {
			return
		}

		queued = true;
		requestAnimationFrame(function() {
			queued = false;
			if (document.querySelector('img.tp-lazyload')) {
				tp_lazy_load()
			}
			tp_lazy_background();
		});
	}).observe(document.body, { childList: true, subtree: true });
}

function tp_lazy_load(){
	if (lz) {
		lz.update()
		return
	}
	var lazy_enter = function ( e ) {
		e.classList.add('tp-lazy-start');
	};
	var lazy_loading = function ( e ) {
		e.classList.remove('tp-lazy-start');
		e.classList.add('tp-lazy-loading');
	};
	var lazy_loaded = function ( e ) {
		e.classList.remove('tp-lazy-loading');
		e.classList.add('tp-lazy-loaded');
		delete e.dataset.srcset;
	};

	lz = new LazyLoad({
		elements_selector: "img.tp-lazyload",
		callback_enter: lazy_enter,
		callback_loading: lazy_loading,
		callback_loaded: lazy_loaded,
	});

};