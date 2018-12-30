function check_score(elm) {
    if (trim_space(elm.value).length == 0) {
        show_error(elm, "Please input score!");
        trun_red(elm);
    } else if (check_number_bad_chars(trim_space(elm.value)) == false) {
        show_error(elm, "Invalid score! Only numbers are allowed!");
        trun_red(elm);
    } else {
        trun_gray(elm);
        hide_tooltip(elm);
    }
}

function check_challenge_name_bad_chars(challenge_name) {
    return check_bad_chars(challenge_name, "");
}

function challenge_keyup(elm, msg) {
    if (trim_space(elm.value).length == 0) {
        show_error(elm, "Please input " + msg + "!");
        trun_red(elm);
    } else if (check_challenge_name_bad_chars(trim_space(elm.value)) == false) {
        show_error(elm, "Invalid " + msg + "!");
        trun_red(elm);
    } else {
        trun_gray(elm);
        hide_tooltip(elm);
    }
}

function challenge_blur(elm, msg) {
    if (trim_space(elm.value).length == 0) {
        show_error(elm, "Please input " + msg + "!");
        trun_red(elm);
    } else if (check_challenge_name_bad_chars(trim_space(elm.value)) == false) {
        show_error(elm, "Invalid " + msg + "!");
        trun_red(elm);
    } else {
        trun_gray(elm);
        hide_tooltip(elm);
    }
    /*else if (msg == 'challenge name') {
            if (elm.id == 'admin-challenge-update-name') {
        elm.oldval = elm.value;
    } else {
        check_challenge_name_existed(trim_space(elm.value));
    }
        } */
}

function disable_challenge_button() {
    $("#create-challenge").css("background-color", "grey").attr("disabled", "disabled");
    $("#update-challenge").css("background-color", "grey").attr("disabled", "disabled");
}

function release_challenge_button() {
    $("#create-challenge").css("background-color", "#2f889a").removeAttr("disabled");
    $("#update-challenge").css("background-color", "#2f889a").removeAttr("disabled");
}

function check_challenge_name_existed(challenge_name) {
    $.ajax({
        type: "POST",
        url: "/admin/challenge/check/name",
        dataType: "json",
        data: {
            "challenge_name": challenge_name
        },
        success: function(msg) {
            if (msg.status == 0) {
                show_error($(".admin-challenge-name"), "Challenge name has existed!");
                trun_red($(".admin-challenge-name"));
            } else {
                trun_gray($(".admin-challenge-name"));
                hide_tooltip($(".admin-challenge-name"));
            }
        }
    });
}

function load_users() {
    var container = $(".content-container");
    container.html('');
    var url = "/admin/user/all";
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
                var available = {
                    'user_id': 'ID',
                    'username': 'Username',
                    'email': 'Email',
                    'phone': 'Phone',
                    'registe_time': 'Registe Time',
                    'registe_ip': 'Registe IP',
                    'actived': 'Actived',
                    'usertype': 'Admin',
                    'ban': 'Ban',
                };
                var available_keys = Object.keys(available);
                var user_info = msg.value;
                var checkbox = ['actived', 'usertype', 'ban'];
                var html = '';
                html += '<nav class="navbar navbar-default" role="navigation">';
                html += '<div class="container-fluid"><div class="navbar-header">';
                html += '<a class="navbar-brand" href="#">Users</a></div></div></nav>';
                html += '<div class="table-responsive admin-users"><table class="table table-hover">';
                html += '<thead><tr>';
                for (var i = 0; i < available_keys.length; i++) {
                    html += '<th><span class="admin-users-key">' + available[available_keys[i]] + '</th>';
                }
                html += '<th><span class="admin-users-key">' + 'Delete' + '</th>';
                html += '</span></tr></thead><tbody>';
                for (var i = 0; i < user_info.length; i++) {
                    html += '<tr>';
                    for (var j = 0; j < available_keys.length; j++) {
                        html += '<td>';
                        if (available_keys.length - j > 3) {
                            html += '<span class="admin-users-value">';
                            if (j == 9) {
                                html += TimeStamp2Date(user_info[i][available_keys[j]]);
                            } else {
                                html += user_info[i][available_keys[j]];
                            }
                            html += '</span>';
                        } else {
                            html += '<input class="admin-users-value-checkbox ';
                            if (j == 11) {
                                html += 'actived-checkbox';
                            } else if (j == 12) {
                                html += 'admin-checkbox';
                            } else if (j == 13) {
                                html += 'ban-checkbox';
                            }
                            html += '" type="checkbox" ';
                            if (user_info[i][available_keys[j]] == 1) {
                                html += 'checked';
                            }
                            html += '/>';
                        }
                        html += '</td>';
                    }
                    html += '<td><input class="admin-users-del" type="button" value="Delete"/></td>';
                    html += '</tr>';
                }
                html += '</tbody></table></div>';
                container.html(html);
                flush_data();
                $('.admin-users-value-checkbox').on('click', function() {
                    var check = 0;
                    var user_id = $(this).parent().parent().children('td:first-child').text();
                    if (this.checked == true) {
                        check = 1;
                    }
                    var type = '';
                    if ($(this).hasClass('actived-checkbox')) {
                        type = 'actived';
                    } else if ($(this).hasClass('admin-checkbox')) {
                        type = 'usertype';
                    } else if ($(this).hasClass('ban-checkbox')) {
                        type = 'ban';
                    }
                    $.ajax({
                        type: 'POST',
                        url: '/admin/user/update/' + user_id,
                        dataType: 'json',
                        data: {
                            'type': type,
                            'value': check
                        },
                        beforeSend: function() {
                            NProgress.start();
                        },
                        complete: function() {
                            NProgress.done();
                        },
                        success: function(msg) {
                            if (msg.status == 1) {
                                show_pnotify("Update success!", msg.message, "success");
                            } else {
                                show_pnotify("Update failed!", msg.message, "error");
                            }
                        }
                    });
                });
                $('.admin-users-del').on('click', function() {
                    var user_id = $(this).parent().parent().children('td:first-child').text();
                    win.confirm(
                        'warrning',
                        'Do you really want to delete this user?',
                        function(r) {
                            if (r == false) return;
                            $.ajax({
                                url: '/admin/user/delete/' + user_id,
                                type: 'GET',
                                dataType: 'json',
                                beforeSend: function() {
                                    NProgress.start();
                                },
                                complete: function() {
                                    NProgress.done();
                                },
                                success: function(msg) {
                                    if (msg.status == 1) {
                                        show_pnotify("Delete success!", msg.message, "success");
                                        load_users();
                                    } else {
                                        show_pnotify("Delete failed!", msg.message, "error");
                                    }
                                }
                            });
                        }
                    );
                });
            }
        }
    });
}

function load_challenges() {
    var container = $(".content-container");
    container.html('');
    html = '<div class="admin-challenge"><nav class="navbar navbar-default" role="navigation"><div class="container-fluid">';
    html += '<div class="navbar-header"><a class="navbar-brand" href="#">Challenges</a></div>';
    html += '<div><button type="button" class="admin-challenge-create btn btn-default navbar-btn">Create</button></div></div></nav></div>';
    var url = '/admin/challenge/all';
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
            var available = {
                'challenge_id': 'ID',
                'name': 'Name',
                'description': 'Description',
                'resource': 'Resource',
                'flag_text': 'Flag',
                'score': 'Score',
                'type': 'Type',
                'online_time': 'Online time',
                'visit_times': 'Visit',
                'solved_times': 'Solved',
                'submit_times': 'Submit',
                'author_name': 'Author',
                'fixing': 'Online'
            };
            var available_keys = Object.keys(available);
            var challenge_info = msg.value;
            var checkbox = ['fixing'];
            html += '<div class="table-responsive admin-challenges"><table class="table table-hover">';
            html += '<thead><tr>';
            for (var i = 0; i < available_keys.length; i++) {
                html += '<th><span class="admin-challenges-key">' + available[available_keys[i]] + '</th>';
            }
            html += '<th><span class="admin-challenges-key">' + 'Edit' + '</th>';
            html += '<th><span class="admin-challenges-key">' + 'Del' + '</th>';
            html += '</span></tr></thead><tbody>';
            if (challenge_info != null) {
                for (var i = 0; i < challenge_info.length; i++) {
                    html += '<tr>';
                    for (var j = 0; j < available_keys.length; j++) {
                        html += '<td>';
                        if (available_keys.length - j > 1) {
                            html += '<span class="admin-challenges-value">';
                            html += challenge_info[i][available_keys[j]];
                            html += '</span>';
                        } else {
                            html += '<input class="admin-challenges-value-checkbox online-checkbox" type="checkbox" ';
                            if (challenge_info[i][available_keys[j]] == 0) {
                                html += 'checked';
                            }
                            html += '/>';
                        }
                        html += '</td>';
                    }
                    html += '<td><input class="admin-challenges-edit" type="button" value="Edit"/></td>';
                    html += '<td><input class="admin-challenges-del" type="button" value="Delete"/></td>';
                    html += '</tr>';
                }
            }
            html += '</tbody></table></div>';
            container.html(html);
            flush_data();

            $('.admin-challenge-name').keyup(function() {
                challenge_keyup(this, 'challenge name');
            });
            $('.admin-challenge-name').blur(function() {
                challenge_blur(this, 'challenge name');
            });
            $('.admin-challenge-description').keyup(function() {
                challenge_keyup(this, 'description');
            });
            $('.admin-challenge-description').blur(function() {
                challenge_blur(this, 'description');
            });
            $('.admin-challenge-score').keyup(function() {
                check_score(this);
            });
            $('.admin-challenge-score').blur(function() {
                check_score(this);
            });
            $('.admin-challenge-resource').keyup(function() {
                challenge_keyup(this, 'resource');
            });
            $('.admin-challenge-resource').blur(function() {
                challenge_blur(this, 'resource');
            });
            $('.admin-challenge-flag').keyup(function() {
                challenge_keyup(this, 'flag');
            });
            $('.admin-challenge-flag').blur(function() {
                challenge_blur(this, 'flag');
            });

            $('.admin-challenges-value-checkbox').on('click', function() {
                var url = '';
                var challenge_id = $(this).parent().parent().children('td:first-child').text();
                if (this.checked == true) {
                    url = '/admin/challenge/online/';
                } else {
                    url = '/admin/challenge/offline/';
                }
                $.ajax({
                    type: 'GET',
                    url: url + challenge_id,
                    dataType: 'json',
                    beforeSend: function() {
                        NProgress.start();
                    },
                    complete: function() {
                        NProgress.done();
                    },
                    success: function(msg) {
                        if (msg.status == 1) {
                            show_pnotify("Update success!", msg.message, "success");
                        } else {
                            show_pnotify("Update failed!", msg.message, "error");
                        }
                    }
                });
            });
            $('.admin-challenges-edit').on('click', function() {
                var online = $(this).parent().parent().children('td:nth-child(13)').children()[0].checked;
                if (online == true) {
                    show_pnotify("Error!", "Please offline this challenge before update it!", "error");
                } else {
                    var challenge_id = $(this).parent().parent().children('td:first-child').text();
                    var name = $(this).parent().parent().children('td:nth-child(2)').text();
                    var description = $(this).parent().parent().children('td:nth-child(3)').text();
                    var resource = $(this).parent().parent().children('td:nth-child(4)').text();
                    var flag = $(this).parent().parent().children('td:nth-child(5)').text();
                    var score = $(this).parent().parent().children('td:nth-child(6)').text();
                    var type = $(this).parent().parent().children('td:nth-child(7)').text();
                    $('#admin-challenge-update-name')[0].value = name;
                    $('#admin-challenge-update-type-select').value = type;
                    $('#admin-challenge-update-resource')[0].value = resource;
                    $('#admin-challenge-update-description')[0].value = description;
                    $('#admin-challenge-update-score')[0].value = score;
                    $('#admin-challenge-update-flag')[0].value = flag;
                    $('.admin-challenges-update-modal').addClass('is-visible');
                    $('.admin-update-challenge').submit(function(e) {
                        e.preventDefault();
                        if ($('.admin-challenges-update-modal').hasClass('is-visible') == false) return;
                        var id = challenge_id;
                        var name_new = e.target.children[0].children[2].value;
                        var description_new = e.target.children[1].children[1].value;
                        var type_new = e.target.children[2].children[1].children[2].value;
                        var score_new = e.target.children[3].children[2].value;
                        var resource_new = e.target.children[4].children[2].value;
                        var flag_new = e.target.children[5].children[2].value;
                        update_challenge(id, name_new, description_new, type_new, score_new, resource_new, flag_new);
                    });
                }
            });
            $('.admin-challenges-del ').on('click', function() {
                var challenge_id = $(this).parent().parent().children('td:first-child').text();
                win.confirm(
                    'Warring',
                    'Do you really want to delete this challenge?',
                    function(r) {
                        if (r == false) return;
                        $.ajax({
                            url: '/admin/challenge/delete/' + challenge_id,
                            type: 'GET',
                            dataType: 'json',
                            beforeSend: function() {
                                NProgress.start();
                            },
                            complete: function() {
                                NProgress.done();
                            },
                            success: function(msg) {
                                if (msg.status == 1) {
                                    show_pnotify("Success!", msg.message, "success");
                                    load_challenges();
                                } else {
                                    show_pnotify("Failed!", msg.message, "error");
                                }
                            }
                        });
                    }
                );
            });
            $('.admin-challenge-create').on('click', function() {
                $('.admin-challenges-create-modal').addClass('is-visible');
            });
        }
    });
}

function create_challenge(name, description, type, score, resource, flag, fixing) {
    $.ajax({
        type: "POST",
        url: "/admin/challenge/create",
        dataType: "json",
        data: {
            "name": name,
            "description": description,
            "type": type,
            "score": score,
            "resource": resource,
            "flag": flag,
            "fixing": fixing
        },
        beforeSend: function() {
            $('.admin-challenges-create-modal').removeClass('is-visible');
            NProgress.start();
        },
        complete: function() {
            NProgress.done();
        },
        success: function(msg) {
            if (msg.status == 1) {
                show_pnotify("Success!", msg.message, "success");
                load_challenges();
            } else {
                show_pnotify("Failed!", msg.message, "error");
            }
        }
    });
}

function update_challenge(id, name, description, type, score, resource, flag) {
    $.ajax({
        url: '/admin/challenge/update/' + id,
        type: 'POST',
        dataType: 'json',
        data: {
            'name': name,
            'description': description,
            'type': type,
            'score': score,
            'resource': resource,
            'flag': flag
        },
        beforeSend: function() {
            $('.admin-challenges-update-modal').removeClass('is-visible');
            NProgress.start();
        },
        complete: function() {
            NProgress.done();
        },
        success: function(msg) {
            if (msg.status == 1) {
                show_pnotify("Success!", msg.message, "success");
                load_challenges();
            } else {
                show_pnotify("Failed!", msg.message, "error");
            }
        }
    });
}

$(document).ready(function() {
    $(".admin-create-challenge").submit(function(e) {
        e.preventDefault();
        var name = e.target.children[0].children[2].value;
        var description = e.target.children[1].children[1].value;
        var type = e.target.children[2].children[1].children[2].value;
        var score = e.target.children[3].children[2].value;
        var resource = e.target.children[4].children[2].value;
        var flag = e.target.children[5].children[2].value;
        var fixing = 1;
        if (e.target.children[6].children[1].checked == true) {
            fixing = 0;
        }
        create_challenge(name, description, type, score, resource, flag, fixing);
    });
});