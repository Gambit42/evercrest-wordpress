/* Evercrest front page behaviour:
   - scroll reveal: .ec-reveal rises + fades in once; .ec-reveal-group staggers its children
   - stats (.ec-stat) count up from 0 when their group is revealed
   - residence filter tabs, index region hover, carousels, viewing form */
( () => {
	const COUNT_MS = 1000;
	const reduceMotion = matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	// Screen readers get the real value; the visible copy starts at 0 ("100%" → "0%").
	const counters = reduceMotion
		? []
		: [ ...document.querySelectorAll( '.ec-stat' ) ].flatMap( ( stat ) => {
				const text = stat.textContent.trim();
				const m = text.match( /^(\D*)(\d+)(.*)$/ );
				if ( ! m ) return [];
				const sr = document.createElement( 'span' );
				const shown = document.createElement( 'span' );
				sr.className = 'ec-sr';
				sr.textContent = text;
				shown.setAttribute( 'aria-hidden', 'true' );
				shown.textContent = m[ 1 ] + '0' + m[ 3 ];
				stat.replaceChildren( sr, shown );
				return [ { stat, shown, prefix: m[ 1 ], to: +m[ 2 ], suffix: m[ 3 ] } ];
		  } );

	// Ease-out: flies through the low numbers, settles on the value.
	const countUp = ( { shown, prefix, to, suffix }, delay ) => {
		let start;
		const tick = ( now ) => {
			start ??= now + delay;
			const t = Math.min( Math.max( ( now - start ) / COUNT_MS, 0 ), 1 );
			shown.textContent = prefix + Math.round( to * ( 1 - ( 1 - t ) ** 3 ) ) + suffix;
			if ( t < 1 ) requestAnimationFrame( tick );
		};
		requestAnimationFrame( tick );
	};

	const io = new IntersectionObserver(
		( entries ) => {
			for ( const entry of entries ) {
				if ( ! entry.isIntersecting ) continue;
				entry.target.classList.add( 'is-in' );
				io.unobserve( entry.target );
				counters
					.filter( ( c ) => entry.target.contains( c.stat ) )
					.forEach( ( c, i ) => countUp( c, 150 + i * 100 ) );
			}
		},
		{ rootMargin: '0px 0px -12% 0px' }
	);

	document.querySelectorAll( '.ec-reveal, .ec-reveal-group' ).forEach( ( el ) => {
		if ( el.classList.contains( 'ec-reveal-group' ) ) {
			[ ...el.children ].forEach( ( child, i ) => child.style.setProperty( '--ec-i', i ) );
		}
		io.observe( el );
	} );

	// Residence filter: tabs toggle [hidden]; CSS re-flows the editorial spans and renumbers the badges.
	const tabs = document.querySelectorAll( '[data-ec-filter]' );
	tabs.forEach( ( tab ) =>
		tab.addEventListener( 'click', () => {
			const filter = tab.dataset.ecFilter;
			tabs.forEach( ( t ) => t.setAttribute( 'aria-pressed', t === tab ) );
			document.querySelectorAll( '.ec-res-card' ).forEach( ( card ) => {
				card.hidden = filter !== 'all' && ! card.classList.contains( `ec-cat-${ filter }` );
			} );
		} )
	);

	// Mobile nav overlay: core only closes it from the X, so tapping an in-page link would scroll behind an open menu.
	document.querySelectorAll( '.ec-pill .wp-block-navigation-item__content' ).forEach( ( link ) =>
		link.addEventListener( 'click', () =>
			link.closest( '.is-menu-open' )?.querySelector( '.wp-block-navigation__responsive-container-close' ).click()
		)
	);

	// Logo / wordmark on the home page: scroll to top and drop the #section hash instead of reloading.
	document.querySelectorAll( '.ec-brand a' ).forEach( ( link ) =>
		link.addEventListener( 'click', ( e ) => {
			if ( link.pathname !== location.pathname ) return;
			e.preventDefault();
			history.replaceState( null, '', link.pathname );
			scrollTo( { top: 0 } ); // smooth via the html scroll-behavior rule
		} )
	);

	// Index: hovering/focusing a region makes it active and swaps the photo (data-img), label + caption.
	const regions = [ ...document.querySelectorAll( '.ec-region' ) ];
	const imgLabel = document.querySelector( '.ec-index-img p' );
	let pic = document.querySelector( '.ec-index-img img' );
	const [ caption, total ] = document.querySelectorAll( '.ec-index-caption p' );
	regions.forEach( ( row ) => {
		if ( row.dataset.img ) new Image().src = row.dataset.img; // preload so the fade doesn't wait on the network
		const activate = () => {
			if ( row.classList.contains( 'is-active' ) ) return;
			const name = row.querySelector( '.ec-region-name' ).textContent.trim();
			regions.forEach( ( r ) => r.classList.toggle( 'is-active', r === row ) );
			imgLabel.textContent = `${ name.toLowerCase() } · landscape photo`;
			// Crossfade: stack the new photo on top, fade it in, then drop the ones beneath.
			const next = pic.cloneNode();
			next.removeAttribute( 'src' );
			next.classList.add( 'is-entering' );
			pic.after( next );
			pic = next;
			const clear = () => {
				while ( next.previousElementSibling?.tagName === 'IMG' ) next.previousElementSibling.remove();
			};
			if ( ! row.dataset.img ) {
				clear();
			} else {
				next.src = row.dataset.img;
				next.addEventListener( 'transitionend', clear, { once: true } );
				next.decode().catch( () => {} ).then( () => {
					next.classList.remove( 'is-entering' );
					if ( reduceMotion ) clear();
				} );
			}
			caption.textContent = name;
			total.textContent = row.querySelector( '.ec-region-count' ).textContent;
		};
		row.addEventListener( 'mouseenter', activate );
		row.addEventListener( 'focusin', activate );
	} );

	// Carousels: Swiper (vendored, enqueued in functions.php) handles drag, momentum and looping.
	document.querySelectorAll( '.ec-carousel' ).forEach( ( root ) => {
		const count = root.querySelector( '[data-ec-count]' );
		const pad = ( n ) => String( n ).padStart( 2, '0' );
		const journal = root.classList.contains( 'ec-journal' );
		const swiper = new Swiper( root.querySelector( '.ec-track' ), {
			loop: true,
			grabCursor: true,
			speed: reduceMotion ? 0 : 600,
			spaceBetween: 24,
			slidesPerView: 1,
			breakpoints: journal
				? { 0: { slidesPerView: 1.18, spaceBetween: 16 }, 641: { slidesPerView: 2 }, 1101: { slidesPerView: 3 } }
				: {},
			on: {
				slideChange: ( s ) => count && ( count.textContent = `${ pad( s.realIndex + 1 ) } / ${ pad( s.slides.length ) }` ),
			},
		} );
		root.querySelectorAll( '[data-ec-dir]' ).forEach( ( btn ) =>
			btn.addEventListener( 'click', () => ( btn.dataset.ecDir > 0 ? swiper.slideNext() : swiper.slidePrev() ) )
		);
	} );

	// Viewing form chips: multi-select by default, single-select inside .ec-chips-single.
	document.querySelectorAll( '.ec-chips' ).forEach( ( group ) =>
		group.addEventListener( 'click', ( e ) => {
			const btn = e.target.closest( 'button' );
			if ( ! btn ) return;
			if ( group.classList.contains( 'ec-chips-single' ) ) {
				group.querySelectorAll( 'button' ).forEach( ( b ) => b.setAttribute( 'aria-pressed', b === btn ) );
			} else {
				btn.setAttribute( 'aria-pressed', btn.getAttribute( 'aria-pressed' ) !== 'true' );
			}
		} )
	);

	// ponytail: visual only, the enquiry is not sent anywhere. Wire to wp_mail() or a form plugin before launch.
	document.querySelectorAll( '.ec-form' ).forEach( ( form ) =>
		form.addEventListener( 'submit', ( e ) => {
			e.preventDefault();
			form.hidden = true;
			form.nextElementSibling.hidden = false;
		} )
	);
} )();
