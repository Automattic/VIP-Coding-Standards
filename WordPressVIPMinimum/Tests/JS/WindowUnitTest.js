var testString = '?url=www.evil.com';
var url = "javascript:alert( 'xss' )";

window; // Ok.
window.location.testing; // Ok.
window.test = 'http://www.google.com' + testString; // Ok.
const foo = window.location.testing; // Ok.

var bar = window.location.href; // Warning - variable assignment.
var c=window.location.hash.split("#")[0]; // Warning - variable assignment.

window.location; // Error.
window.name; // Error.
window.status; // Error.
window.location.href = 'http://www.google.com/' + testString; // Error.
window.location.protocol; // Error.
window.location.host; // Error.
window.location.hostname; // Error.
window.location.pathname; // Error.
window.location.search; // Error.
window.location.hash; // Error.
window.location.username; // Error.
window.location.password; // Error.
window.location.port; // Error.

document.theform.reference.onchange = function( url ) {
    var id = document.theform.reference.selectedIndex;
    window.name = url; // Error.
}

window['location']; // Error.
window.location['href']; // Error.
window['location']['protocol']; // Error.
window['location'].host; // Error.

window['location']['test']; // Ok.
window.location['test']; // Ok.
window['test'].location; // Ok.
