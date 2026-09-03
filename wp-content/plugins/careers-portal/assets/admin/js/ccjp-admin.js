/**
 * Injects the Careers Portal custom post statuses into the Quick Edit /
 * Bulk Edit status <select> on the Jobs and Applications list tables.
 * WP clones the hidden #inline-edit template's DOM (including whatever
 * <option> elements are present at the time) into each opened row, so a
 * single injection on page load is enough to cover every row.
 */
( function () {
	document.addEventListener( 'DOMContentLoaded', function () {
		if ( typeof window.CCJP_ADMIN === 'undefined' ) {
			return;
		}

		var statuses = window.CCJP_ADMIN.statuses || {};
		var selects  = document.querySelectorAll( 'select[name="_status"]' );

		selects.forEach( function ( select ) {
			Object.keys( statuses ).forEach( function ( slug ) {
				if ( select.querySelector( 'option[value="' + slug + '"]' ) ) {
					return;
				}
				var opt = document.createElement( 'option' );
				opt.value = slug;
				opt.textContent = statuses[ slug ];
				select.appendChild( opt );
			} );
		} );
	} );
} )();
