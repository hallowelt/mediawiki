( function () {
	/**
	 * @class mw.GallerySlideshow
	 * @classdesc Interface controls for the slideshow gallery. To use, first load
	 * the `mediawiki.page.gallery.slideshow` ResourceLoader module.
	 * @uses mw.Title
	 * @uses mw.Api
	 *
	 * @constructor
	 * @description Encapsulates the user interface of the slideshow galleries.
	 * An object is instantiated for each `.mw-gallery-slideshow` element.
	 * @param {jQuery} gallery The `<ul>` element of the gallery.
	 */
	mw.GallerySlideshow = function ( gallery ) {
		// Properties
		/**
		 * The `<ul>` element of the gallery.
		 *
		 * @type {jQuery}
		 */
		this.$gallery = $( gallery );
		/**
		 * The `<li>` that has the gallery caption.
		 *
		 * @type {jQuery}
		 */
		this.$galleryCaption = this.$gallery.find( '.gallerycaption' );
		/**
		 * Selection of `<li>` elements that have thumbnails.
		 *
		 * @type {jQuery}
		 */
		this.$galleryBox = this.$gallery.find( '.gallerybox' );
		/**
		 * The `<li>` element of the current image.
		 *
		 * @type {jQuery}
		 */
		this.$currentImage = null;
		/**
		 * A key value pair of thumbnail URLs and image info.
		 *
		 * @type {Object.<string,jQuery.Promise>}
		 */
		this.imageInfoCache = {};

		/* Properties */
		/**
		 * The `<li>` element that contains the carousel.
		 *
		 * @name $carousel
		 * @memberof mw.GallerySlideshow.prototype
		 * @type {jQuery|null}
		 */

		/**
		 * The `<div>` element that contains the interface buttons.
		 *
		 * @name $interface
		 * @memberof mw.GallerySlideshow.prototype
		 * @type {jQuery}
		 */

		/**
		 * The `<img>` element that'll display the current image.
		 *
		 * @name $img
		 * @memberof mw.GallerySlideshow.prototype
		 * @type {jQuery}
		 */

		/**
		 * The `<p>` element that holds the image caption.
		 *
		 * @name $imgCaption
		 * @memberof mw.GallerySlideshow.prototype
		 * @type {jQuery}
		 */

		/**
		 * The `<div>` element that contains the image.
		 *
		 * @name $imgContainer
		 * @memberof mw.GallerySlideshow.prototype
		 * @type {jQuery}
		 */

		/**
		 * Width of the image based on viewport size.
		 *
		 * @name imageWidth
		 * @memberof mw.GallerySlideshow.prototype
		 * @type {number}
		 */

		/**
		 * Height of the image based on viewport size the URLs in the required size.
		 *
		 * @name imageHeight
		 * @memberof mw.GallerySlideshow.prototype
		 * @type {number}
		 */

		// Initialize
		this.drawCarousel();
		this.setSizeRequirement();
		this.toggleThumbnails( !!this.$gallery.attr( 'data-showthumbnails' ) );
		this.showCurrentImage( true );

		// Events
		$( window ).on(
			'resize',
			OO.ui.debounce(
				this.setSizeRequirement.bind( this ),
				100
			)
		);

		// Disable thumbnails' link, instead show the image in the carousel
		this.$galleryBox.on( 'click', ( e ) => {
			this.$currentImage = $( e.currentTarget );
			this.showCurrentImage();
			return false;
		} );
	};

	/* Setup */
	OO.initClass( mw.GallerySlideshow );

	/* Methods */
	/**
	 * Draws the carousel and the interface around it.
	 */
	mw.GallerySlideshow.prototype.drawCarousel = function () {
		this.$carousel = $( '<li>' ).addClass( 'gallerycarousel' );

		// Buttons for the interface
		const prevButton = new OO.ui.ButtonWidget( {
			framed: false,
			icon: 'previous'
		} ).connect( this, { click: 'prevImage' } );

		const nextButton = new OO.ui.ButtonWidget( {
			framed: false,
			icon: 'next'
		} ).connect( this, { click: 'nextImage' } );

		const toggleButton = new OO.ui.ButtonWidget( {
			framed: false,
			icon: 'imageGallery',
			title: mw.msg( 'gallery-slideshow-toggle' )
		} ).connect( this, { click: 'toggleThumbnails' } );

		const interfaceElements = new OO.ui.PanelLayout( {
			expanded: false,
			classes: [ 'mw-gallery-slideshow-buttons' ],
			$content: $( '<div>' ).append(
				prevButton.$element,
				toggleButton.$element,
				nextButton.$element
			)
		} );
		this.$interface = interfaceElements.$element;

		// Containers for the current image, caption etc.
		this.$imgCaption = $( '<p>' ).attr( 'class', 'mw-gallery-slideshow-caption' );
		this.$imgContainer = $( '<div>' )
			.attr( 'class', 'mw-gallery-slideshow-img-container' );

		const carouselStack = new OO.ui.StackLayout( {
			continuous: true,
			expanded: false,
			items: [
				interfaceElements,
				new OO.ui.PanelLayout( {
					expanded: false,
					$content: this.$imgContainer
				} ),
				new OO.ui.PanelLayout( {
					expanded: false,
					$content: this.$imgCaption
				} )
			]
		} );
		this.$carousel.append( carouselStack.$element );

		// Append below the caption or as the first element in the gallery
		if ( this.$galleryCaption.length !== 0 ) {
			this.$galleryCaption.after( this.$carousel );
		} else {
			this.$gallery.prepend( this.$carousel );
		}
	};

	/**
	 * Sets the {@link mw.GallerySlideshow#imageWidth imageWidth} and
	 * {@link mw.GallerySlideshow#imageHeight imageHeight} properties based on the size of the
	 * window. Also flushes the {@link mw.GallerySlideshow#imageInfoCache imageInfoCache} as we'll
	 * now need URLs for a different size.
	 */
	mw.GallerySlideshow.prototype.setSizeRequirement = function () {
		let w = this.$imgContainer.width(),
			h = Math.min( $( window ).height() * ( 3 / 4 ), this.$imgContainer.width() ) - this.getChromeHeight();

		// Round values in case the user's browser is returning non-integer values.
		w = Math.round( w );
		h = Math.round( h );

		// Only update and flush the cache if the size changed
		if ( w !== this.imageWidth || h !== this.imageHeight ) {
			this.imageWidth = w;
			this.imageHeight = h;
			this.imageInfoCache = {};
			this.setImageSize();
		}
	};

	/**
	 * Gets the height of the interface elements and the
	 * gallery's caption.
	 *
	 * @return {number} Height
	 */
	mw.GallerySlideshow.prototype.getChromeHeight = function () {
		return this.$interface.outerHeight() + ( this.$galleryCaption.outerHeight() || 0 );
	};

	/**
	 * Sets the height and width of {@link mw.GallerySlideshow#$img $img} based on the
	 * proportion of the image and the values generated by
	 * {@link mw.GallerySlideshow#setSizeRequirement setSizeRequirement}.
	 */
	mw.GallerySlideshow.prototype.setImageSize = function () {
		if ( this.$img === undefined || this.$thumbnail === undefined ) {
			return;
		}

		// Reset height and width
		this.$img
			.removeAttr( 'width' )
			.removeAttr( 'height' );

		// Stretch image to take up the required size
		this.$img.attr( 'height', ( this.imageHeight - this.$imgCaption.outerHeight() ) + 'px' );

		// Make the image smaller in case the current image
		// size is larger than the original file size.
		this.getImageInfo( this.$thumbnail ).then( ( info ) => {
			// NOTE: There will be a jump when resizing the window
			// because the cache is cleared and this a new network request.
			if (
				info.thumbwidth < this.$img.width() ||
				info.thumbheight < this.$img.height()
			) {
				this.$img.attr( {
					width: info.thumbwidth + 'px',
					height: info.thumbheight + 'px'
				} );
			}
		} );
	};

	/**
	 * Displays the image set as {@link mw.GallerySlideshow#$currentImage $currentImage} in the
	 * carousel.
	 *
	 * @param {boolean} init Image being shown during gallery init (i.e. first image)
	 */
	mw.GallerySlideshow.prototype.showCurrentImage = function ( init ) {
		const $imageLi = this.getCurrentImage();
		const $caption = $imageLi.find( '.gallerytext' );

		// The order of the following is important for size calculations
		// 1. Highlight current thumbnail
		this.$gallery
			.find( '.gallerybox.slideshow-current' )
			.removeClass( 'slideshow-current' );
		$imageLi.addClass( 'slideshow-current' );

		this.$thumbnail = $imageLi.find( 'img' );
		if ( this.$thumbnail.length ) {
			// 2. Create and show thumbnail
			this.$img = $( '<img>' ).attr( {
				src: this.$thumbnail.attr( 'src' ),
				alt: this.$thumbnail.attr( 'alt' )
			} );
			// 'image' class required for detection by MultimediaViewer
			const $imgLink = $( '<a>' ).addClass( 'image' )
				.attr( 'href', $imageLi.find( 'a' ).eq( 0 ).attr( 'href' ) )
				.append( this.$img );

			this.$imgContainer.empty().append( $imgLink );
		} else {
			// 2b. No image found (e.g. file doesn't exist)
			this.$imgContainer.text( $imageLi.find( '.thumb' ).text() );
		}

		// 3. Copy caption
		this.$imgCaption
			.empty()
			.append( $caption.clone() );

		if ( !this.$thumbnail.length ) {
			return;
		}

		// 4. Stretch thumbnail to correct size
		this.setImageSize();

		const $thumbnail = this.$thumbnail;
		// 5. Load image at the required size
		this.loadImage( this.$thumbnail ).done( ( info ) => {
			// Show this image to the user only if its still the current one
			if ( this.$thumbnail.attr( 'src' ) === $thumbnail.attr( 'src' ) ) {
				this.$img.attr( 'src', info.thumburl );
				this.setImageSize();
				// Don't fire hook twice during init
				if ( !init ) {
					mw.hook( 'wikipage.content' ).fire( this.$imgContainer );
				}

				// Pre-fetch the next image
				this.loadImage( this.getNextImage().find( 'img' ) );
			}
		} ).fail( () => {
			// Image didn't load
			const title = mw.Title.newFromImg( this.$img );
			this.$imgContainer.text( title ? title.getMainText() : '' );
		} );
	};

	/**
	 * Loads the full image given the `<img>` element of the thumbnail.
	 *
	 * @param {jQuery} $img
	 * @return {jQuery.Promise} Resolves with the image's URL and original
	 *  element once the image has loaded.
	 */
	mw.GallerySlideshow.prototype.loadImage = function ( $img ) {
		return this.getImageInfo( $img ).then( ( info ) => {
			const d = $.Deferred();
			const img = new Image();
			img.src = info.thumburl;
			img.onload = function () {
				d.resolve( info );
			};
			img.onerror = function () {
				d.reject();
			};
			return d.promise();
		} );
	};

	/**
	 * Gets the image's info given an `<img>` element.
	 *
	 * @param {Object} $img
	 * @return {jQuery.Promise} Resolves with the image's info.
	 */
	mw.GallerySlideshow.prototype.getImageInfo = function ( $img ) {
		const imageSrc = $img.attr( 'src' );

		// Reject promise if there is no thumbnail image
		if ( $img[ 0 ] === undefined ) {
			return $.Deferred().reject();
		}

		if ( this.imageInfoCache[ imageSrc ] === undefined ) {
			const api = new mw.Api();
			// TODO: This supports only gallery of images
			const title = mw.Title.newFromImg( $img );
			const params = {
				action: 'query',
				formatversion: 2,
				titles: title.toString(),
				prop: 'imageinfo',
				iiprop: 'url'
			};

			// Check which dimension we need to request, based on
			// image and container proportions.
			if ( this.getDimensionToRequest( $img ) === 'height' ) {
				params.iiurlheight = this.imageHeight;
			} else {
				params.iiurlwidth = this.imageWidth;
			}

			this.imageInfoCache[ imageSrc ] = api.get( params ).then( ( data ) => {
				if ( OO.getProp( data, 'query', 'pages', 0, 'imageinfo', 0, 'thumburl' ) !== undefined ) {
					return data.query.pages[ 0 ].imageinfo[ 0 ];
				} else {
					return $.Deferred().reject();
				}
			} );
		}

		return this.imageInfoCache[ imageSrc ];
	};

	/**
	 * Given an image, the method checks whether to use the height
	 * or the width to request the larger image.
	 *
	 * @param {jQuery} $img
	 * @return {string}
	 */
	mw.GallerySlideshow.prototype.getDimensionToRequest = function ( $img ) {
		const ratio = $img.width() / $img.height();

		if ( this.imageHeight * ratio <= this.imageWidth ) {
			return 'height';
		} else {
			return 'width';
		}
	};

	/**
	 * Toggles visibility of the thumbnails.
	 *
	 * @param {boolean} show Optional argument to control the state
	 */
	mw.GallerySlideshow.prototype.toggleThumbnails = function ( show ) {
		this.$galleryBox.toggle( show );
		this.$carousel.toggleClass( 'mw-gallery-slideshow-thumbnails-toggled', show );
	};

	/**
	 * Getter method for {@link mw.GallerySlideshow#$currentImage $currentImage}.
	 *
	 * @return {jQuery}
	 */
	mw.GallerySlideshow.prototype.getCurrentImage = function () {
		this.$currentImage = this.$currentImage || this.$galleryBox.eq( 0 );
		return this.$currentImage;
	};

	/**
	 * Gets the image after the current one. Returns the first image if
	 * the current one is the last.
	 *
	 * @return {jQuery}
	 */
	mw.GallerySlideshow.prototype.getNextImage = function () {
		// Not the last image in the gallery
		if ( this.$currentImage.next( '.gallerybox' )[ 0 ] !== undefined ) {
			return this.$currentImage.next( '.gallerybox' );
		} else {
			return this.$galleryBox.eq( 0 );
		}
	};

	/**
	 * Gets the image before the current one. Returns the last image if
	 * the current one is the first.
	 *
	 * @return {jQuery}
	 */
	mw.GallerySlideshow.prototype.getPrevImage = function () {
		// Not the first image in the gallery
		if ( this.$currentImage.prev( '.gallerybox' )[ 0 ] !== undefined ) {
			return this.$currentImage.prev( '.gallerybox' );
		} else {
			return this.$galleryBox.last();
		}
	};

	/**
	 * Sets the {@link mw.GallerySlideshow#$currentImage $currentImage} to the next one and shows
	 * it in the carousel.
	 */
	mw.GallerySlideshow.prototype.nextImage = function () {
		this.$currentImage = this.getNextImage();
		this.showCurrentImage();
	};

	/**
	 * Sets the {@link mw.GallerySlideshow#$currentImage $currentImage} to the previous one and
	 * shows it in the carousel.
	 */
	mw.GallerySlideshow.prototype.prevImage = function () {
		this.$currentImage = this.getPrevImage();
		this.showCurrentImage();
	};

	// Bootstrap all slideshow galleries
	mw.hook( 'wikipage.content' ).add( ( $content ) => {
		$content.find( '.mw-gallery-slideshow' ).filter( function () {
			// This gallery slideshow feature depends on img tags being present in the DOM.
			// This might not be true - for example in MobileFrontend - where images are lazy loaded.
			// The filter statement can be removed when T194887 is resolved.
			return $( this ).find( 'img' ).length > 0;
		} ).each( function () {
			// eslint-disable-next-line no-new
			new mw.GallerySlideshow( this );
		} );
	} );

}() );
