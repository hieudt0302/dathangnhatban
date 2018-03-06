var system = require('system');
var page   = require('webpage').create();
var url    = system.args[1];
// render the page, and run the callback function

page.open(url, function(status) {
	if(status !== 'success'){
        console.log('URL : '+url+' - Unable to load page!');
    } 
	else{
		var content = page.evaluate(function() {
			return document.getElementsByTagName('body')[0].innerHTML;
		});
		console.log(content); 
	}
    phantom.exit();
});
