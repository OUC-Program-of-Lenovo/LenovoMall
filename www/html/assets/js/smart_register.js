function disable_button_register() {
    $("#register-input-button").css("background-color", "grey").attr("disabled", "disabled");
}

function release_button_register() {
    $("#register-input-button").css("background-color", "green").removeAttr("disabled");
}

function check_captcha_register(captcha) {
    $.ajax({
        type: "POST",
        url: "/user/check/captcha",
        dataType: "json",
        data: {
            "captcha": captcha
        },
        success: function(msg) {
            if (msg.status == 1) {
                trun_green($("#register-captcha"));
                hide_tooltip($("#register-captcha"));
                release_button_register();
            } else {
                show_error($("#register-captcha"), "Wrong Captcha!");
                trun_red($("#register-captcha"));
                disable_button_register();
            }
        }
    });
}

function check_username_existed_register(username) {
    $.ajax({
        type: "POST",
        url: "/user/check/username",
        dataType: "json",
        data: {
            "username": username
        },
        success: function(msg) {
            if (msg.status == 1) {
                trun_green($("#register-username"));
                hide_tooltip($("#register-username"));
            } else {
                show_error($("#register-username"), msg.message);
                trun_red($("#register-username"));
                disable_button_register();
            }
        }
    });
}

function check_email_existed(email) {
    $.ajax({
        type: "POST",
        url: "/user/check/email",
        dataType: "json",
        data: {
            "email": email
        },
        success: function(msg) {
            if (msg.status == 1) {
                trun_green($("#register-email"));
                hide_tooltip($("#register-email"));
                release_button_register();
            } else {
                show_error($("#register-email"), msg.message);
                trun_red($("#register-email"));
                disable_button_register();
            }
        }
    });
}

$(document).ready(function() {
    disable_button_register();
    $("#register-username").keyup(function() {
        if (check_username_length(trim_space(this.value)) == false) {
            show_error(this, "Username must be equal 4 or more and equal 16 or less characters!");
            trun_red(this);
            disable_button_register();
        } else if (check_username_bad_chars(trim_space(this.value)) == false) {
            show_error(this, "Invalid username! Only allowed letters and numbers!");
            trun_red(this);
            disable_button_register();
        } else {
            trun_green(this);
            hide_tooltip(this);
        }
    });

    $("#register-username").blur(function() {
        if (trim_space(this.value).length == 0) {
            show_error(this, "Please input Username!");
            trun_red(this);
            disable_button_register();
        } else if (check_username_length(trim_space(this.value)) == false) {
            show_error(this, "Username must be equal 4 or more and equal 16 or less characters!");
            trun_red(this);
            disable_button_register();
        } else if (check_username_bad_chars(trim_space(this.value)) == false) {
            show_error(this, "Invalid username! Only allowed letters and numbers!");
            trun_red(this);
            disable_button_register();
        } else {
            check_username_existed_register(trim_space(this.value));
        }
    });

    $("#register-password").keyup(function() {
        if (check_password_length(trim_space(this.value)) == false) {
            show_error(this, "Password must be equal 6 or more and equal 16 or less characters!");
            trun_red(this);
            disable_button_register();
        } else if (check_password_bad_chars(trim_space(trim_space(this.value))) == false) {
            show_error(this, "Invalid password! Please do not use special characters in password!");
            trun_red(this);
            disable_button_register();
        } else {
            trun_green(this);
            hide_tooltip(this);
        }
    });

    $("#reset-password").keyup(function() {
        if (check_password_length(trim_space(this.value)) == false) {
            show_error(this, "Password must be equal 6 or more and equal 16 or less characters!");
            trun_red(this);
            disable_button_register();
        } else if (check_password_bad_chars(trim_space(this.value)) == false) {
            show_error(this, "Invalid password! Please do not use special characters in password!");
            trun_red(this);
            disable_button_register();
        } else {
            trun_green(this);
            hide_tooltip(this);
        }
    });

    $("#register-password").blur(function() {
        if (trim_space(this.value).length == 0) {
            show_error(this, "Please input Password!");
            trun_red(this);
            disable_button_register();
        } else if (check_password_length(trim_space(this.value)) == false) {
            show_error(this, "Password must be equal 6 or more and equal 16 or less characters!");
            trun_red(this);
            disable_button_register();
        } else if (check_password_bad_chars(trim_space(this.value)) == false) {
            show_error(this, "Invalid password! Please do not use special characters in password!");
            trun_red(this);
            disable_button_register();
        } else {
            trun_green(this);
            hide_tooltip(this);
        }
    });

    $("#register-email").keyup(function() {
        if (check_email(trim_space(this.value)) == false) {
            show_error(this, "Invalid email format!");
            trun_red(this);
            disable_button_register();
        } else {
            trun_green(this);
            hide_tooltip(this);
        }
    });

    $("#forget-email").keyup(function() {
        if (check_email(trim_space(this.value)) == false) {
            show_error(this, "Invalid email format!");
            trun_red(this);
            disable_button_register();
        } else {
            trun_green(this);
            hide_tooltip(this);
        }
    });

    $("#register-email").blur(function() {
        if (trim_space(this.value).length == 0) {
            show_error(this, "Please input Email!");
            trun_red(this);
            disable_button_register();
        } else if (check_email(trim_space(this.value)) == false) {
            show_error(this, "Invalid email format!");
            trun_red(this);
            disable_button_register();
        } else {
            check_email_existed(trim_space(this.value));
        }
    });

    $("#register-student_id").keyup(function() {
        if (check_student_id_length(trim_space(this.value)) == false) {
            show_error(this, "Student ID length should equal 11!");
            trun_red(this);
            disable_button_register();
        } else if (check_number_bad_chars(trim_space(this.value)) == false) {
            show_error(this, "Invalid Student ID! Only numbers are allowed!");
            trun_red(this);
            disable_button_register();
        } else {
            trun_green(this);
            hide_tooltip(this);
        }
    });

    $("#register-student_id").blur(function() {
        if (trim_space(this.value).length == 0) {
            show_error(this, "Please input Student ID!");
            trun_red(this);
            disable_button_register();
        } else if (check_student_id_length(trim_space(this.value)) == false) {
            show_error(this, "Student ID length should equal 11!");
            trun_red(this);
            disable_button_register();
        } else if (check_number_bad_chars(trim_space(this.value)) == false) {
            show_error(this, "Invalid Student ID! Only numbers are allowed!");
            trun_red(this);
            disable_button_register();
        } else {
            trun_green(this);
            hide_tooltip(this);
        }
    });

    $("#register-grade").keyup(function() {
        if (check_grade(trim_space(this.value)) == false) {
            show_error(this, "Invalid grade format!");
            trun_red(this);
            disable_button_register();
        } else {
            trun_green(this);
            hide_tooltip(this);
        }
    });

    $("#register-grade").blur(function() {
        if (trim_space(this.value).length == 0) {
            show_error(this, "Please input grade!");
            trun_red(this);
            disable_button_register();
        } else if (check_grade(trim_space(this.value)) == false) {
            show_error(this, "Invalid grade format!");
            trun_red(this);
            disable_button_register();
        } else {
            trun_green(this);
            hide_tooltip(this);
        }
    });

    $("#register-phone").keyup(function() {
        if (check_phone_length(trim_space(this.value)) == false) {
            show_error(this, "Phone number length should equal 11!");
            trun_red(this);
            disable_button_register();
        } else if (check_number_bad_chars(trim_space(this.value)) == false) {
            show_error(this, "Invalid Phone number! Only numbers are allowed!");
            trun_red(this);
            disable_button_register();
        } else {
            trun_green(this);
            hide_tooltip(this);
        }
    });

    $("#register-phone").blur(function() {
        if (trim_space(this.value).length == 0) {
            show_error(this, "Please input Phone number!");
            trun_red(this);
            disable_button_register();
        } else if (check_phone_length(trim_space(this.value)) == false) {
            show_error(this, "Phone number length should equal 11!");
            trun_red(this);
            disable_button_register();
        } else if (check_number_bad_chars(trim_space(this.value)) == false) {
            show_error(this, "Invalid Phone number! Only numbers are allowed!");
            trun_red(this);
            disable_button_register();
        } else {
            trun_green(this);
            hide_tooltip(this);
        }
    });

    $("#register-qq").keyup(function() {
        if (trim_space(this.value).length != 0) {
            if (check_number_bad_chars(trim_space(this.value)) == false) {
                show_error(this, "Invalid QQ number! Only numbers are allowed!");
                trun_red(this);
                disable_button_register();
            } else {
                trun_green(this);
                hide_tooltip(this);
            }
        } else {
            trun_gray(this);
            hide_tooltip(this);
        }
    });

    $("#register-wechat").keyup(function() {
        if (trim_space(this.value).length != 0) {
            trun_green(this);
            hide_tooltip(this);
        } else {
            trun_gray(this);
            hide_tooltip(this);
        }
    });

    $("#register-real_name").keyup(function() {
        if (trim_space(this.value).length != 0) {
            trun_green(this);
            hide_tooltip(this);
        } else {
            trun_red(this);
            show_error(this, "Please input actual name!");
            disable_button_register();
        }
    });

    $("#register-captcha").keyup(function() {
        if (check_captcha_length(trim_space(this.value)) == false || check_number_bad_chars(trim_space(this.value)) == false) {
            show_error(this, "Captcha should be 4 numbers!");
            trun_red(this);
            disable_button_register();
        } else {
            check_captcha_register(trim_space(this.value));
        }
    });

    $("#register-captcha").blur(function() {
        if (trim_space(this.value).length == 0) {
            show_error(this, "Please input Captcha!");
            trun_red(this);
            disable_button_register();
        } else {
            check_captcha_register(trim_space(this.value));
        }
    });
});