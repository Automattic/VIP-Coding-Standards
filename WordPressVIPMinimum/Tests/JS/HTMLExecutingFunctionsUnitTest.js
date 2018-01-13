(function(){
    var el = document.querySelector(".myclass");
    el.html(''); // OK.
    el.html('<b>Hand written HTML</b>'); // OK.
    el.html( '<b>' + variable + '</b>' ); // NOK.
    el.html( variable ); // NOK.
    el.append( variable ); // NOK.
    el.append( '<b>Hand written HTML</b>' ); // OK.
    el.append( '<b>' + variable + '</b>' ); // NOK.
    document.write( '<script>console.log("hello")</script>' ); // OK. No variable, conscious.
    document.write( hello ); // NOK.
    document.writeln( hey ); // NOK.
})();