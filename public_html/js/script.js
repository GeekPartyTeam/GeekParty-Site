!function () {
    var fbAuthStatus = null
        , urlPrefix = document.location.href.replace(/http(s)?:\/\/[^\/]+(\/app(_dev)?.php)?.*/, '$2')
        ;

    function goLogIn(){
        window.location.href = urlPrefix + "/login_check";
    }

    window.onFbInit = function () {
        FB.getLoginStatus(function (r) {
            FB.Event.subscribe('auth.statusChange', function(response) {
                if (response.session || response.authResponse) {
                    setTimeout(goLogIn, 500);
                } else {
                    window.location.href = urlPrefix + "/logout";
                }
            });

            fbAuthStatus = r;

            $(function () {
                $('.facebook-login').show().click(fbSignIn);
                $('.facebook-wait').hide();
            })
        })
    }

    function fbSignIn() {
        if (fbAuthStatus.status != 'connected') {
            FB.login(function () {}, {scope: 'public_profile,email'})
        } else {
            goLogIn()
        }
    }
}()
