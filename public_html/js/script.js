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

    var vkFunctions = []
        , vkInitialized = false;
    window.runAfterVkInit = function (fun) {
        if (!vkInitialized) {
            vkFunctions.push(fun);
        } else {
            fun();
        }
    };
    window.vkAsyncInit = function() {
        vkInitialized = true;
        for (var i in vkFunctions) {
            vkFunctions[i]();
        }
    };
    setTimeout(function() {
        var el = document.createElement("script");
        el.type = "text/javascript";
        el.src = "//vk.com/js/api/openapi.js";
        el.async = true;
        document.getElementById("vk_api_transport").appendChild(el);
    }, 0);

    // VK polls
    window.runAfterVkInit(function () {
        $('[data-vk-poll]').each(function () {
            var pollId = $(this).data('vk-poll');
            $(this).attr('id', pollId);
            VK.Widgets.Poll(pollId, {width: "300"}, pollId);
        })
    })
}();
