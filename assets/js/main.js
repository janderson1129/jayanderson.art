/**
 * Jay Anderson Art — Main JavaScript
 *
 * Handles:
 *  - Sticky header scroll state
 *  - Mobile menu toggle
 *  - Scroll-triggered fade-up animations
 *  - WooCommerce mini-cart AJAX fragment refresh
 */

( function () {
    'use strict';

    /* --------------------------------------------------------
       DOM REFERENCES
    -------------------------------------------------------- */
    const header      = document.getElementById( 'site-header' );
    const menuToggle  = document.querySelector( '.menu-toggle' );
    const mobileNav   = document.getElementById( 'mobile-nav' );
    const overlay     = document.getElementById( 'mobile-nav-overlay' );

    /* --------------------------------------------------------
       STICKY HEADER — add .scrolled class after 10px
    -------------------------------------------------------- */
    function handleHeaderScroll() {
        if ( ! header ) return;
        if ( window.scrollY > 10 ) {
            header.classList.add( 'scrolled' );
        } else {
            header.classList.remove( 'scrolled' );
        }
    }

    window.addEventListener( 'scroll', handleHeaderScroll, { passive: true } );
    handleHeaderScroll(); // run on load

    /* --------------------------------------------------------
       MOBILE MENU TOGGLE
    -------------------------------------------------------- */
    function openMobileNav() {
        if ( ! menuToggle || ! mobileNav ) return;
        menuToggle.classList.add( 'active' );
        menuToggle.setAttribute( 'aria-expanded', 'true' );
        mobileNav.classList.add( 'active' );
        mobileNav.setAttribute( 'aria-hidden', 'false' );
        if ( overlay ) overlay.classList.add( 'active' );
        document.body.style.overflow = 'hidden';
    }

    function closeMobileNav() {
        if ( ! menuToggle || ! mobileNav ) return;
        menuToggle.classList.remove( 'active' );
        menuToggle.setAttribute( 'aria-expanded', 'false' );
        mobileNav.classList.remove( 'active' );
        mobileNav.setAttribute( 'aria-hidden', 'true' );
        if ( overlay ) overlay.classList.remove( 'active' );
        document.body.style.overflow = '';
    }

    if ( menuToggle ) {
        menuToggle.addEventListener( 'click', function () {
            const isOpen = menuToggle.classList.contains( 'active' );
            isOpen ? closeMobileNav() : openMobileNav();
        } );
    }

    if ( overlay ) {
        overlay.addEventListener( 'click', closeMobileNav );
    }

    /* Close on Escape key */
    document.addEventListener( 'keydown', function ( e ) {
        if ( e.key === 'Escape' ) closeMobileNav();
    } );

    /* Close if nav link is tapped on mobile */
    if ( mobileNav ) {
        mobileNav.querySelectorAll( 'a' ).forEach( function ( link ) {
            link.addEventListener( 'click', closeMobileNav );
        } );
    }

    /* --------------------------------------------------------
       SCROLL-TRIGGERED ANIMATIONS
       Elements with [data-animate] fade up when entering viewport
    -------------------------------------------------------- */
    if ( 'IntersectionObserver' in window ) {
        const animateEls = document.querySelectorAll( '[data-animate]' );

        if ( animateEls.length ) {
            const observer = new IntersectionObserver(
                function ( entries ) {
                    entries.forEach( function ( entry ) {
                        if ( entry.isIntersecting ) {
                            entry.target.classList.add( 'is-visible' );
                            observer.unobserve( entry.target );
                        }
                    } );
                },
                {
                    threshold: 0.12,
                    rootMargin: '0px 0px -40px 0px',
                }
            );

            animateEls.forEach( function ( el ) {
                observer.observe( el );
            } );
        }
    } else {
        /* Fallback: just show everything */
        document.querySelectorAll( '[data-animate]' ).forEach( function ( el ) {
            el.classList.add( 'is-visible' );
        } );
    }

    /* --------------------------------------------------------
       PORTFOLIO HOVER — preload images on hover for faster
       lightbox/page transitions
    -------------------------------------------------------- */
    document.querySelectorAll( '.portfolio-item a' ).forEach( function ( link ) {
        link.addEventListener( 'mouseenter', function () {
            const href = link.getAttribute( 'href' );
            if ( href && ! link.dataset.prefetched ) {
                const prefetch = document.createElement( 'link' );
                prefetch.rel  = 'prefetch';
                prefetch.href = href;
                document.head.appendChild( prefetch );
                link.dataset.prefetched = '1';
            }
        } );
    } );

    /* --------------------------------------------------------
       WOOCOMMERCE — refresh cart fragments on add-to-cart
    -------------------------------------------------------- */
    document.body.addEventListener( 'added_to_cart', function () {
        if ( typeof wc_cart_fragments_params !== 'undefined' ) {
            jQuery( document.body ).trigger( 'wc_fragment_refresh' );
        }
    } );

} )();
