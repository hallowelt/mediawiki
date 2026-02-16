const { test } = require( '../../../resources/src/mediawiki.page.ready/updateThumbnailsToPreferredSize.js' );
const { updateThumbnailToPreferredSize } = test;

const runTest = ( srcset ) => {
	const img = document.createElement( 'img' );
	img.srcset = srcset;
	updateThumbnailToPreferredSize( img );
	return img.srcset;
};

describe( 'updateThumbnailToPreferredSize', () => {
	it( 'should shuffle srcset correctly', () => {
		const shuffled = runTest( 'a.jpg 1x, b.jpg 1.5x, c.jpg 2x, d.jpg 4x' );
		expect( shuffled ).toBe( 'c.jpg 1x, d.jpg 3x' );
	} );

	it( 'will not shuffle srcset where none exists', () => {
		const shuffled = runTest( '' );
		expect( shuffled ).toBe( '' );
	} );

	it( 'retain existing srcset if no better options exist', () => {
		const shuffled = runTest( 'a.jpg 1x, b.jpg 1.5x' );
		expect( shuffled ).toBe( 'a.jpg 1x, b.jpg 1.5x' );
	} );

	it( 'only shuffles retina', () => {
		const shuffled = runTest( 'new-york-skyline-wide.jpg 3724w, new-york-skyline-4by3.jpg 1961w, new-york-skyline-tall.jpg 1060w' );
		expect( shuffled ).toBe( 'new-york-skyline-wide.jpg 3724w, new-york-skyline-4by3.jpg 1961w, new-york-skyline-tall.jpg 1060w' );
	} );
} );
