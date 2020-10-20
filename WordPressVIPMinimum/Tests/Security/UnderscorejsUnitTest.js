
var html = _.template('<li><%- name %></li>', { name: 'John Smith' }); // OK.

var html = _.template('<li><%= name %></li>', { name: 'John Smith' }); // NOK.
var html = _.template('<li><%=type.item%></li>', { name: 'John Smith' }); // NOK.

_.templateSettings.interpolate = /\{\{(.+?)\}\}/g; /* NOK */
_.templateSettings = {
	interpolate: /\{\{(.+?)\}\}/g /* NOK */
};

options.interpolate=_.templateSettings.interpolate; /* NOK */
var interpolate = options.interpolate || reNoMatch, /* Ignore */
	source = "__p += '";

var template = _.template('<li>{{ name }}</li>'); /* NOK, due to the interpolate, but not flagged. */

// Prevent false positives on "interpolate".
var preventMisidentification = 'text interpolate text'; // OK.
var interpolate = THREE.CurveUtils.interpolate; // OK.

var p = function(f, d) {
	return s.interpolate(m(f), _(d), 0.5, e.color_space) // OK.
}

y.interpolate.bezier = b; // OK.
