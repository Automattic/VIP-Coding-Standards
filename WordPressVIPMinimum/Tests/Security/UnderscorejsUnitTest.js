
var html = _.template('<li><%- name %></li>', { name: 'John Smith' }); // OK.

var html = _.template('<li><%= name %></li>', { name: 'John Smith' }); // NOK.
var html = _.template('<li><%=type.item%></li>', { name: 'John Smith' }); // NOK.

