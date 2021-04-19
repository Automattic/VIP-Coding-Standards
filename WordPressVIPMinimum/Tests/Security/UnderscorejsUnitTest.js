
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

// Recognize escaping.
var html = _.template('<li><%= _.escape(name) %></li>', { name: 'John Smith' }); // OK.

var html = _.template(
	"<pre>The \"<% __p+=_.escape(o.text) %>\" is the same<br />" + // OK.
		"as the  \"<%= _.escape(o.text) %>\" and the same<br />" + // OK.
		"as the \"<%- o.text %>\"</pre>", // OK.
	{
		text: "<b>some text</b> and \n it's a line break"
	},
	{
		variable: "o"
	}
);

var compiled = _.template("<% print('Hello ' + _.escape(epithet)); %>"); /* OK */
var compiled = _.template("<% print('Hello ' + epithet); %>"); /* NOK */
var compiled = _.template("<% __p+=o.text %>"); /* NOK */
