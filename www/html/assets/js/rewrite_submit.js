$(document).ready(function() {
    $("form").submit(function(e) {
        e.preventDefault();
        var length = e.target.children.length;
        var type = e.target.children[length - 1].children[0].id;
        if (startswith(type, "login")) {
            var username = e.target.children[0].children[1].value;
            var password = e.target.children[1].children[1].value;
            var captcha = e.target.children[2].children[1].value;
            login(username, password, captcha);
        } else if (startswith(type, "register")) {
            var username = e.target.children[0].children[1].value;
            var password = e.target.children[1].children[1].value;
            var email = e.target.children[2].children[1].value;
            var phone = e.target.children[3].children[1].value;;
            var receiver = e.target.children[4].children[1].value;
            var address = e.target.children[5].children[1].value;
            var captcha = e.target.children[4].children[1].value;
            register(username, password, email, phone, receiver, address, captcha);
        } /*else if (startswith(type, "forget")) {
            var email = e.target.children[0].children[1].value;
            var captcha = e.target.children[1].children[1].value;
            forget(email, captcha);
        } else if (startswith(type, "reset")) {
            var password = e.target.children[0].children[1].value;
            var reset_code = e.target.children[1].children[0].value;
            reset(password, reset_code);
        }*/
    });
});


function startswith(father, son) {
    return (father.indexOf(son) == 0);
}


function login(username, password, captcha) {
    $.ajax({
        type: "POST",
        url: "/user/login",
        dataType: "json",
        data: {
            "username": username,
            "password": password,
            "captcha": captcha,
        },
        beforeSend: function() {
            disable_button_login();
            // display = none , alert dialog
            $('.cd-user-modal').removeClass('is-visible');
            NProgress.start();
        },
        complete: function() {
            NProgress.done();
        },
        success: function(msg) {
            if (msg.status == 1) {
                show_pnotify("Success!", msg.message, "success");
                location.reload();
            } else {
                show_pnotify("Failed!", msg.message, "error");
                // play the sound
                // $("#error_sound")[0].play()
            }
        }
    });
}

function register(username, password, email, phone, receiver, address, captcha) {
    $.ajax({
        type: "POST",
        url: "/user/register",
        dataType: "json",
        data: {
            "username": username,
            "password": password,
            "email": email,
            "phone": phone,
            "receiver": receiver,
            "address": address,
            "captcha": captcha,
        },
        beforeSend: function() {
            disable_button_register();
            // display = none
            $('.cd-user-modal').removeClass('is-visible');
            NProgress.start();
        },
        complete: function() {
            NProgress.done();
        },
        success: function(msg) {
            if (msg.status == 1) {
                show_pnotify("Success!", msg.message, "success");
            } else {
                show_pnotify("Failed!", msg.message, "error");
                // play the sound
                // $("#error_sound")[0].play()
            }
        }
    });
}

function show_pnotify(title, text, type) {
    new PNotify({
        title: title,
        text: text,
        type: type,
        delay: 2000,
        addclass: "stack-topleft",
    });
}

/*
function forget(email, captcha) {
    $.ajax({
        type: "POST",
        url: "/user/forget",
        dataType: "json",
        data: {
            "email": email,
            "captcha": captcha,
        },
        beforeSend: function() {
            disable_button_register();
            // display = none
            $('.cd-user-modal').removeClass('is-visible');
            NProgress.start();
        },
        complete: function() {
            NProgress.done();
        },
        success: function(msg) {
            if (msg.status == 1) {
                show_pnotify("Success!", msg.message, "success");
            } else {
                show_pnotify("Failed!", msg.message, "error");
                // play the sound
                // $("#error_sound").play()
            }
        }
    });
}
*/

/*
function reset(password, reset_code) {
    $.ajax({
        type: "POST",
        url: "/user/reset",
        dataType: "json",
        data: {
            "password": password,
            "reset_code": reset_code,
        },
        beforeSend: function() {
            disable_button_register();
            // display = none
            $('.cd-user-modal').removeClass('is-visible');
            NProgress.start();
        },
        complete: function() {
            NProgress.done();
        },
        success: function(msg) {
            if (msg.status == 1) {
                show_pnotify("Success!", msg.message, "success");
                // redirect to user login success page
                window.location.href = '/';
            } else {
                show_pnotify("Failed!", msg.message, "error");
                // play the sound
                // $("#error_sound").play()
            }
        }
    });
}
*/