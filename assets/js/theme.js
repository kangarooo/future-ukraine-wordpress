/* Future Ukraine theme — mobile nav, donate amounts, copy buttons. No dependencies. */
( function () {
	'use strict';

	function ready( fn ) {
		if ( document.readyState !== 'loading' ) {
			fn();
		} else {
			document.addEventListener( 'DOMContentLoaded', fn );
		}
	}

	ready( function () {
		// Mobile navigation.
		document.querySelectorAll( '[data-fu-nav]' ).forEach( function ( nav ) {
			var btn = nav.querySelector( '.fu-nav__toggle' );
			var panel = nav.querySelector( '.fu-nav__mobile' );
			if ( ! btn || ! panel ) {
				return;
			}
			function set( open ) {
				nav.classList.toggle( 'is-open', open );
				btn.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
				panel.hidden = ! open;
			}
			btn.addEventListener( 'click', function () {
				set( panel.hidden );
			} );
			panel.addEventListener( 'click', function ( e ) {
				if ( e.target.closest( 'a' ) ) {
					set( false );
				}
			} );
		} );

		// Quick donate.
		document.querySelectorAll( '[data-fu-donate]' ).forEach( function ( box ) {
			var url = box.getAttribute( 'data-url' ) || '';
			var cur = box.getAttribute( 'data-currency' ) || '€';
			var input = box.querySelector( 'input[type=number]' );
			var go = box.querySelector( '.fu-donate__go' );
			var label = box.querySelector( '[data-fu-amount-label]' );
			var buttons = box.querySelectorAll( '.fu-amount' );

			function update( val ) {
				var n = parseInt( val, 10 );
				if ( isNaN( n ) || n < 0 ) {
					n = 0;
				}
				if ( label ) {
					label.textContent = cur + n;
				}
				buttons.forEach( function ( b ) {
					b.classList.toggle( 'is-active', parseInt( b.getAttribute( 'data-amount' ), 10 ) === n );
				} );
				if ( go && url ) {
					try {
						var u = new URL( url, window.location.href );
						u.searchParams.set( 'amount', String( n ) );
						go.href = u.toString();
					} catch ( e ) {
						go.href = url;
					}
				}
			}

			buttons.forEach( function ( b ) {
				b.addEventListener( 'click', function () {
					var v = b.getAttribute( 'data-amount' );
					if ( input ) {
						input.value = v;
					}
					update( v );
				} );
			} );
			if ( input ) {
				input.addEventListener( 'input', function () {
					update( input.value );
				} );
				update( input.value );
			}
		} );

		// Copy to clipboard.
		document.addEventListener( 'click', function ( e ) {
			var btn = e.target.closest( '[data-fu-copy]' );
			if ( ! btn ) {
				return;
			}
			var text = btn.getAttribute( 'data-fu-copy' );
			function done() {
				btn.classList.add( 'is-copied' );
				setTimeout( function () {
					btn.classList.remove( 'is-copied' );
				}, 2000 );
			}
			if ( navigator.clipboard && window.isSecureContext ) {
				navigator.clipboard.writeText( text ).then( done );
			} else {
				var ta = document.createElement( 'textarea' );
				ta.value = text;
				ta.style.position = 'fixed';
				ta.style.opacity = '0';
				document.body.appendChild( ta );
				ta.select();
				try {
					document.execCommand( 'copy' );
					done();
				} catch ( err ) {}
				document.body.removeChild( ta );
			}
		} );
	} );
} )();
