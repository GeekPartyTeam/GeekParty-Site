window.fbAsyncInit = function() {
    FB.init({
        appId      : fbApiId,
        cookie     : true,
        xfbml      : true,
        status     : true,
        version    : 'v2.0'
    });
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
};

(function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

function statusChangeCallback(response) {
    if (response.status === 'connected') {
        FB.api('/me', function(response) {
            console.log('Successful login for: ' + response.name);
        });
    } else if (response.status === 'not_authorized') {
        console.log('logged out of app');
    } else {
        console.log('logged out of fb');
    }
}
