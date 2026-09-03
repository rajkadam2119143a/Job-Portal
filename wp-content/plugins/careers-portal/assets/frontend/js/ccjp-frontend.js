/**
 * Careers Portal — namespaced frontend JS (window.CCJP).
 *
 * Progressive enhancement only: every form here already works as a plain
 * GET submit with zero JavaScript (see the widgets' render() output). This
 * file intercepts those submits, when possible, to update a Job Listing on
 * the same page via AJAX instead of a full navigation, and drives the
 * Load More button.
 */
window.CCJP = window.CCJP || {};

( function ( CCJP ) {
	'use strict';

	function findListing() {
		return document.querySelector( '[data-ccjp-listing]' );
	}

	function toggleNoResults( found ) {
		document.querySelectorAll( '.ccjp-no-results' ).forEach( function ( el ) {
			el.style.display = found > 0 ? 'none' : '';
		} );
	}

	function toggleEmptyMessage( listing, found ) {
		var empty = listing.querySelector( '.ccjp-job-listing__empty' );
		if ( empty ) {
			empty.style.display = found > 0 ? 'none' : '';
		}
	}

	function request( body, onSuccess ) {
		if ( typeof window.CCJP_DATA === 'undefined' ) {
			return;
		}

		var params = new URLSearchParams( body );
		params.set( 'action', 'ccjp_query_jobs' );
		params.set( 'nonce', window.CCJP_DATA.nonce );

		fetch( window.CCJP_DATA.ajaxUrl, {
			method: 'POST',
			credentials: 'same-origin',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: params.toString(),
		} )
			.then( function ( response ) { return response.json(); } )
			.then( function ( response ) {
				if ( response && response.success ) {
					onSuccess( response.data );
				}
			} );
	}

	function paramsFromListingSettings( listing ) {
		try {
			return JSON.parse( listing.getAttribute( 'data-ccjp-settings' ) ) || {};
		} catch ( e ) {
			return {};
		}
	}

	function initSearchForms() {
		document.querySelectorAll( '.ccjp-job-search-form' ).forEach( function ( form ) {
			form.addEventListener( 'submit', function ( event ) {
				var listing = findListing();
				if ( ! listing ) {
					return; // No listing on this page — let the browser navigate normally.
				}

				event.preventDefault();

				var formData = new FormData( form );
				var body = paramsFromListingSettings( listing );
				delete body.paged;
				body.page = 1;

				formData.forEach( function ( value, key ) {
					if ( value !== '' ) {
						body[ key ] = value;
					}
				} );

				listing.classList.add( 'ccjp-job-listing--loading' );

				request( body, function ( data ) {
					listing.classList.remove( 'ccjp-job-listing--loading' );
					var grid = listing.querySelector( '.ccjp-job-listing__grid' );
					if ( grid ) {
						grid.innerHTML = data.html;
					}
					listing.setAttribute( 'data-ccjp-found', data.found );
					toggleEmptyMessage( listing, data.found );
					toggleNoResults( data.found );

					if ( window.history && window.history.pushState ) {
						var url = new URL( window.location.href );
						formData.forEach( function ( value, key ) {
							if ( value !== '' ) {
								url.searchParams.set( key, value );
							} else {
								url.searchParams.delete( key );
							}
						} );
						window.history.pushState( {}, '', url.toString() );
					}
				} );
			} );
		} );
	}

	function initLoadMore() {
		document.querySelectorAll( '.ccjp-job-listing__load-more-button' ).forEach( function ( button ) {
			button.addEventListener( 'click', function () {
				var listing = button.closest( '[data-ccjp-listing]' );
				if ( ! listing ) {
					return;
				}

				var nextPage = parseInt( button.getAttribute( 'data-page' ), 10 ) + 1;
				var maxPages = parseInt( button.getAttribute( 'data-max-pages' ), 10 );
				var body     = paramsFromListingSettings( listing );
				body.page    = nextPage;

				button.setAttribute( 'data-loading', '1' );
				button.disabled = true;

				request( body, function ( data ) {
					var grid = listing.querySelector( '.ccjp-job-listing__grid' );
					if ( grid ) {
						grid.insertAdjacentHTML( 'beforeend', data.html );
					}

					button.setAttribute( 'data-page', nextPage );
					button.removeAttribute( 'data-loading' );
					button.disabled = false;

					if ( nextPage >= maxPages ) {
						button.parentElement.style.display = 'none';
					}
				} );
			} );
		} );
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		initSearchForms();
		initLoadMore();
	} );

	CCJP.request = request;
} )( window.CCJP );
