var testString = '?url=www.evil.com';
var url = "javascript:alert( 'xss' )";

window; // Ok.
window.location.testing; // Ok.
window.test = 'http://www.google.com' + testString; // Ok.

window.location; // Error.
window.name; // Error.
window.status; // Error.
window.location.href = 'http://www.google.com/' + testString; // Error.
window.location.hostname; // Error.
window.location.pathname; // Error.
window.location.protocol; // Error.
window.location.assign; // Error.

document.theform.reference.onchange = function( url ) {
    var id = document.theform.reference.selectedIndex;
    window.location.href = url; // Error.
}
