(function(){
    var el = document.querySelector(".myclass"),
        badHTML = '<b>Some random HTML</b>';
    el.innerHTML = '<b>Hand written HTML with no variables.</b>'; // OK.
    el.innerHTML = badHTML;
})();