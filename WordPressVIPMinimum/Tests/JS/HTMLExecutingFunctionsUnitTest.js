(function(){
    var el = document.querySelector(".myclass");
	el.after(''); // OK.
	el.after('<b>Hand written HTML</b>'); // OK.
	el.after( '<b>' + variable + '</b>' ); // Warning.
	el.after( variable ); // Warning.
    el.append( '<b>Hand written HTML</b>' ); // OK.
    el.append( '<b>' + variable + '</b>' ); // Warning.
    el.append( variable ); // Warning.
	el.before('<b>Hand written HTML</b>'); // OK.
	el.before( '<b>' + variable + '</b>' ); // Warning.
	el.before( variable ); // Warning.
    el.html('<b>Hand written HTML</b>'); // OK.
    el.html( '<b>' + variable + '</b>' ); // Warning.
    el.html( variable ); // Warning.
	el.prepend('<b>Hand written HTML</b>'); // OK.
	el.prepend( '<b>' + variable + '</b>' ); // Warning.
	el.prepend( variable ); // Warning.
	el.replaceWith('<b>Hand written HTML</b>'); // OK.
	el.replaceWith( '<b>' + variable + '</b>' ); // Warning.
	el.replaceWith( variable ); // Warning.
    document.write( '<script>console.log("hello")</script>' ); // OK. No variable, conscious.
    document.write( hello ); // Warning.
    document
		.	writeln( hey ); // Warning.

	$('').appendTo(el); // OK.
	$('<b>Hand written HTML</b>').appendTo( el ); // OK.
	$( '<b>' + variable + '</b>' ).appendTo( el ); // Warning.
	$( variable ).appendTo( el ); // Warning.
	$('<b>Hand written HTML</b>').insertAfter( el ); // OK.
	$( '<b>' + variable + '</b>' ).insertAfter( el ); // Warning.
	$( variable ).insertAfter( el ); // Warning.
	$('<b>Hand written HTML</b>').insertBefore( el ); // OK.
	$( '<b>' + variable + '</b>' ).insertBefore( el ); // Warning.
	$( variable ).insertBefore( el ); // Warning.
	$('<b>Hand written HTML</b>').prependTo( el ); // OK.
	$( '<b>' + variable + '</b>' ).prependTo( el ); // Warning.
	$( variable ).prependTo( el ); // Warning.
	$('<b>Hand written HTML</b>').replaceAll( el ); // OK.
	$( '<b>' + variable + '</b>' ).replaceAll( el ); // Warning.
	$( variable )
		.		replaceAll( el ); // Warning.
})();

	$( foo_that_contains_script_element() ).appendTo( el ); // Warning.
	var $foo = $( '.my-selector' );
	$foo.appendTo( el ); // Warning.
