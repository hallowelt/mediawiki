var i, chars = [];

for ( i = 0; i <= 0x10ffff; i++ ) {
	chars.push( String.fromCodePoint( i ).toUpperCase() );
}
console.log( JSON.stringify( chars ) );
