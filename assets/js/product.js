/**
 * Jay Anderson Art — Single Product JavaScript
 *
 * Handles:
 *  - Thumbnail gallery switcher
 *  - Image lightbox (open / close / keyboard)
 */

( function () {
    'use strict';

    /* --------------------------------------------------------
       THUMBNAIL GALLERY SWITCHER
    -------------------------------------------------------- */
    const mainImg   = document.getElementById( 'product-main-img' );
    const thumbs    = document.querySelectorAll( '.product-thumb' );
    const zoomLink  = document.querySelector( '.product-hero__zoom-link' );

    if ( mainImg && thumbs.length ) {

        thumbs.forEach( function ( thumb ) {
            thumb.addEventListener( 'click', function () {
                const newSrc  = thumb.dataset.src;
                const newFull = thumb.dataset.full;
                const newAlt  = thumb.dataset.alt;

                if ( ! newSrc ) return;

                /* Swap main image */
                mainImg.src = newSrc;
                if ( newAlt ) mainImg.alt = newAlt;

                /* Update zoom link href */
                if ( zoomLink && newFull ) {
                    zoomLink.href = newFull;
                }

                /* Update active state */
                thumbs.forEach( function ( t ) {
                    t.classList.remove( 'product-thumb--active' );
                } );
                thumb.classList.add( 'product-thumb--active' );
            } );
        } );
    }

    /* --------------------------------------------------------
       LIGHTBOX
    -------------------------------------------------------- */
    const lightbox   = document.getElementById( 'product-lightbox' );
    const backdrop   = document.getElementById( 'lightbox-backdrop' );
    const lightboxImg = document.getElementById( 'lightbox-img' );
    const closeBtn   = document.querySelector( '.product-lightbox__close' );

    function openLightbox( src, alt ) {
        if ( ! lightbox || ! backdrop || ! lightboxImg ) return;
        lightboxImg.src = src;
        lightboxImg.alt = alt || '';
        lightbox.hidden  = false;
        backdrop.hidden  = false;
        document.body.style.overflow = 'hidden';
        closeBtn && closeBtn.focus();
    }

    function closeLightbox() {
        if ( ! lightbox || ! backdrop ) return;
        lightbox.hidden  = true;
        backdrop.hidden  = true;
        lightboxImg.src  = '';
        document.body.style.overflow = '';
    }

    /* Open on zoom link click */
    if ( zoomLink ) {
        zoomLink.addEventListener( 'click', function ( e ) {
            e.preventDefault();
            const src = zoomLink.href;
            const alt = mainImg ? mainImg.alt : '';
            openLightbox( src, alt );
        } );
    }

    /* Close on backdrop click */
    if ( backdrop ) {
        backdrop.addEventListener( 'click', closeLightbox );
    }

    /* Close on button click */
    if ( closeBtn ) {
        closeBtn.addEventListener( 'click', closeLightbox );
    }

    /* Close on Escape */
    document.addEventListener( 'keydown', function ( e ) {
        if ( e.key === 'Escape' && lightbox && ! lightbox.hidden ) {
            closeLightbox();
        }
    } );

} )();
