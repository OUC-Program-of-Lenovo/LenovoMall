function get_personal_information() {
    var information_container = $(".content-container");
    information_container.html('');
    var url = "/user/info";
    $.ajax({
        type: "GET",
        url: url,
        dataType: "json",
        beforeSend: function() {
            NProgress.start();
        },
        complete: function() {
            NProgress.done();
        },
        success: function(msg) {
            if (msg.status == 1) {
                var user_info = msg.value;
                var d = new Date();
                var html = '';
                var keys = Object.keys(user_info);
                var available = {
                    "email": "email",
                    "phone": "phone",
                };
                var available_keys = Object.keys(available);
                var disable = ['email'];
                var able = ['phone'];
                html += '<div class="profile"><div class="profile-head">';
                html += '<h2 class="profile-head-username">' + user_info.username + '</h2>';
                html += '</div><form enctype="multipart/form-data" class="profile-update" action="/user/update" method="POST"><div class="profile-body">';
                html += '<table border="0" class="profile-body-table">';
                for (var i = 0; i < keys.length; i++) {
                    if (available_keys.indexOf(keys[i]) != -1) {
                        html += '<tr><td><span class="profile-body-key">';
                        html += available[keys[i]];
                        html += '</span></td><td><input class="profile-body-value ';

                        if (disable.indexOf(keys[i]) != -1) {
                            html += 'profile-disable" readonly ';
                        } else if (able.indexOf(keys[i] != -1)) {
                            html += 'profile-able" id="profile-' + keys[i] + '" ';
                        } else {
                            continue;
                        }

                        if (user_info[keys[i]] != 0 && user_info[keys[i]] != null) {
                            html += 'value="' + user_info[keys[i]];
                        }

                        html += '"/>';
                        html += '</td></tr>';
                    }
                }
                html += '</table>';
                html += '<div class="profile-body-avatar">';
                html += '<img class="profile-body-avatar-img" src="/upload/images/avatar/' + user_info.avatar + '?' + d.getTime()  +'"/>'
                html += '<a href="javascript:;" class="update-input-avatar-button">Change<input id="upavatar" type="file" name="avatar"/></a>';
                html += '</div>';
                html += '</div>';
                html += '<div class="profile-foot"><input type="submit" id="update-input-button"  value="Update"></div></form></div>';
                information_container.html(html);

                $('#upavatar').on('change', function () {
                   update_user_avatar();
                })

                flush_data();
                $('#profile-phone').keyup(function() {
                    if (check_phone_length(trim_space(this.value)) == false) {
                        show_error(this, "Phone number length should equal 11!");
                    } else if (check_number_bad_chars(trim_space(this.value)) == false) {
                        show_error(this, "Invalid Phone number! Only numbers are allowed!");
                    } else {
                        hide_tooltip(this);
                    }
                });
                $('#profile-phone').blur(function() {
                    if (trim_space(this.value).length == 0) {
                        show_error(this, "Please input Phone number!");
                    } else if (check_phone_length(trim_space(this.value)) == false) {
                        show_error(this, "Phone number length should equal 11!");
                    } else if (check_number_bad_chars(trim_space(this.value)) == false) {
                        show_error(this, "Invalid Phone number! Only numbers are allowed!");
                    } else {
                        hide_tooltip(this);
                    }
                });

                $('.profile-update').submit(function(e) {
                    e.preventDefault();
                    var phone = e.target.children[0].children[0].children[0].children[1].children[1].children[0].value;
                    update_user_info(phone);
                });
            }
        }
    });
}

function update_user_avatar() {
    var formData = new FormData();
    formData.append('avatar', $('#upavatar')[0].files[0]);
    $.ajax({
        url: '/user/update/avatar',
        type: 'POST',
        cache: false,
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function() {
            NProgress.start();
        },
        complete: function() {
            NProgress.done();
        },
        success: function(msg) {
            msg = JSON.parse(msg);
            if (msg.status == 1) {
                show_pnotify("Success!", msg.message, "success");
                get_personal_information();
            } else {
                show_pnotify("Failed!", msg.message, "error");
            }
        }
    })
}

function update_user_info(phone) {
    $.ajax({
        type: "POST",
        url: "/user/update/info",
        dataType: "json",
        data: {
            "phone": phone
        },
        beforeSend: function() {
            NProgress.start();
        },
        complete: function() {
            NProgress.done();
        },
        success: function(msg) {
            if (msg.status == 1) {
                show_pnotify("Success!", msg.message, "success");
                get_personal_information();
            } else {
                show_pnotify("Failed!", msg.message, "error");
            }
        }
    });
}
