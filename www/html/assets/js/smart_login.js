function disable_button_login() {
    $("#login-input-button").css("background-color", "grey").attr("disabled", "disabled");
}

function release_button_login() {
    $("#login-input-button").css("background-color", "green").removeAttr("disabled");
}

function disable_button_forget() {
    $("#forget-input-button").css("background-color", "grey").attr("disabled", "disabled");
}

function release_button_forget() {
    $("#forget-input-button").css("background-color", "green").removeAttr("disabled");
}

function check_username_existed_login(username) {
    $.ajax({
        type: "POST",
        url: "/user/check/username",
        dataType: "json",
        data: {
            "username": username
        },
        success: function(msg) {
            if (msg.status == 1) {
                show_error($("#login-username"), "Username does not exist!");
                trun_red($("#login-username"));
                disable_button_login();
            } else {
                trun_green($("#login-username"));
                hide_tooltip($("#login-username"));
            }
        }
    });
}

function check_captcha_forget(captcha) {
    $.ajax({
        type: "POST",
        url: "/user/check/captcha",
        dataType: "json",
        data: {
            "captcha": captcha
        },
        success: function(msg) {
            if (msg.status == 1) {
                trun_green($("#forget-captcha"));
                hide_tooltip($("#forget-captcha"));
                release_button_forget();
            } else {
                show_error($("#forget-captcha"), "Wrong Captcha!");
                trun_red($("#forget-captcha"));
                disable_button_forget();
            }
        }
    });
}

function check_captcha_login(captcha) {
    $.ajax({
        type: "POST",
        url: "/user/check/captcha",
        dataType: "json",
        data: {
            "captcha": captcha
        },
        success: function(msg) {
            if (msg.status == 1) {
                trun_green($("#login-captcha"));
                hide_tooltip($("#login-captcha"));
                release_button_login();
            } else {
                show_error($("#login-captcha"), "Wrong Captcha!");
                trun_red($("#login-captcha"));
                disable_button_login();
            }
        }
    });
}

$(document).ready(function() {
    disable_button_login();
    disable_button_forget();
    $("#login-username").keyup(function() {
        if (check_username_length(this.value) == false) {
            show_error(this, "Username must be equal 4 or more and equal 16 or less characters!");
            trun_red(this);
            disable_button_login();
        } else if (check_username_bad_chars(this.value) == false) {
            show_error(this, "Invalid username! Only allowed letters and numbers!");
            trun_red(this);
            disable_button_login();
        } else {
            trun_green(this);
            hide_tooltip(this);
        }
    });

    $("#login-username").blur(function() {
        if (this.value.length == 0) {
            show_error(this, "Please input Username!");
            trun_red(this);
            disable_button_login();
        } else if (check_username_length(this.value) == false) {
            show_error(this, "Username must be equal 4 or more and equal 16 or less characters!");
            trun_red(this);
            disable_button_login();
        } else if (check_username_bad_chars(this.value) == false) {
            show_error(this, "Invalid username! Only allowed letters and numbers!");
            trun_red(this);
            disable_button_login();
        } else {
            check_username_existed_login(this.value);
        }
    });

    $("#login-password").keyup(function() {
        if (check_password_length(this.value) == false) {
            show_error(this, "Password must be equal 6 or more and equal 16 or less characters!");
            trun_red(this);
            disable_button_login();
        } else if (check_password_bad_chars(this.value) == false) {
            show_error(this, "Invalid password! Please do not use special characters in password!");
            trun_red(this);
            disable_button_login();
        } else {
            trun_green(this);
            hide_tooltip(this);
        }
    });

    $("#login-password").blur(function() {
        if (this.value.length == 0) {
            show_error(this, "Please input Password!");
            trun_red(this);
            disable_button_login();
        } else if (check_password_length(this.value) == false) {
            show_error(this, "Password must be equal 6 or more and equal 16 or less characters!");
            trun_red(this);
            disable_button_login();
        } else if (check_password_bad_chars(this.value) == false) {
            show_error(this, "Invalid password! Please do not use special characters in password!");
            trun_red(this);
            disable_button_login();
        } else {
            trun_green(this);
            hide_tooltip(this);
        }
    });

    $("#login-captcha").keyup(function() {
        if (check_captcha_length(this.value) == false || check_number_bad_chars(this.value) == false) {
            show_error(this, "Captcha should be 4 numbers!");
            trun_red(this);
            disable_button_login();
        } else {
            check_captcha_login(this.value);
        }
    });

    $("#login-captcha").blur(function() {
        if (this.value.length == 0) {
            show_error(this, "Please input Captcha!");
            trun_red(this);
            disable_button_login();
        } else {
            check_captcha_login(this.value);
        }
    });

    $("#forget-captcha").keyup(function() {
        if (check_captcha_length(this.value) == false || check_number_bad_chars(this.value) == false) {
            show_error(this, "Captcha should be 4 numbers!");
            trun_red(this);
            disable_button_forget();
        } else {
            check_captcha_forget(this.value);
        }
    });
});