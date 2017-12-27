jQuery.ajax({
url: 'http://any-site.com/endpoint.json'
}).done( function( data ) {
var link = '<a href="' + data.url + '">' + data.title + '</a>';

jQuery( '#my-div' ).html( link );
});