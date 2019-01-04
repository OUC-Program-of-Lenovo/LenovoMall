var win = new function() {
    this.width = 300;
    this.height = 172;

    this.close = function() {
        $('.win iframe').remove();
        $('.win').remove();
    };

    this.open = function(width, height, title, url, closed) {
        this._close = function() {
            this.close();
            if ($.isFunction(closed)) closed();
        };

        var html = '<div class="win"><div class="mask-layer"></div><div class="window-panel"><iframe class="title-panel" frameborder="0" marginheight="0" marginwidth="0" scrolling="no"></iframe><div class="title"><h3></h3></div><a href="javascript:void(0)" onclick="win._close();" class="close-btn" title="Close">×</a><iframe class="body-panel" frameborder="0" marginheight="0" marginwidth="0" scrolling="auto" src=""></iframe></div></div>';
        var jq = $(html);
        jq.find(".window-panel").height(height).width(width).css("margin-left", -width / 2).css("margin-top", -height / 2);
        jq.find(".title").find(":header").html(title);
        jq.find(".body-panel").height(height - 36).attr("src", url);
        jq.appendTo('body').fadeIn();
        $(".win .window-panel").focus();
    };

    function messageBox(html, title, message) {
        win.close();
        var jq = $(html);

        jq.find(".window-panel").height(win.height).width(win.width).css("margin-left", -win.width / 2).css("margin-top", -win.height / 2);
        jq.find(".title-panel").height(win.height);
        jq.find(".title").find(":header").html(title);
        jq.find(".body-panel").height(win.height - 36);
        jq.find(".content").html(message.replace('\r\n', '<br/>'));
        jq.appendTo('body').show();
        $(".win .w-btn:first").focus();
    }

    this.confirm = function(title, message, selected) {
        this._close = function(r) {
            this.close();
            if ($.isFunction(selected)) selected(r);
        };

        var html = '<div class="win"><div class="mask-layer"></div><div class="window-panel"><iframe class="title-panel" frameborder="0" marginheight="0" marginwidth="0" scrolling="no"></iframe><div class="title"><h3></h3></div><a href="javascript:void(0)" onclick="win._close(false);" class="close-btn" title="Close">×</a><div class="body-panel"><p class="content"></p><p class="btns"><button class="w-btn" tabindex="1" onclick="win._close(true);">Yes</button><button class="w-btn" onclick="win._close(false);">No</button></p></div></div></div>';
        messageBox(html, title, message);
    };

    this.alert = function(title, message, closed) {
        this._close = function() {
            this.close();
            if ($.isFunction(closed)) closed();
        };

        var html = '<div class="win"><div class="mask-layer"></div><div class="window-panel">';
        html += '<iframe class="title-panel" frameborder="0" marginheight="0" marginwidth="0" scrolling="no"></iframe>';
        html += '<div class="title"><h3></h3></div>';
        html += '<a href="javascript:void(0)" onclick="win._close();" class="close-btn" title="Close">×</a>';
        html += '<div class="body-panel"><p class="content"></p>';
        html += '<p class="btns"><button class="w-btn" tabindex="1" onclick="win._close();">Yes</button></p></div></div></div>';
        messageBox(html, title, message);
    }

    this.alertEx = function(message) {
        this.alert('Alert', message);
    }
};

function flush_data() {
    function animate(item, x, y, index) {
        dynamics.animate(item, {
            translateX: x,
            translateY: y,
            opacity: 1
        }, {
            type: dynamics.spring,
            duration: 800,
            frequency: 120,
            delay: 100 + index * 30
        });
    }
    minigrid('.grid', '.grid-item', 6, animate);
    window.addEventListener('resize', function() {
        minigrid('.grid', '.grid-item', 6, animate);
    });
}

function Date2TimeStamp(date) {
    var time_stamp = new Date(date.replace(/-/g, '/'));
    return time_stamp.getTime();
}

function AddZero(time, special = false) {
    var t = special ? time + 1 : time;
    return t < 10 ? '0' + t : t;
}

function TimeStamp2Date(time_stamp) {
    var date = new Date(parseInt(time_stamp) * 1000);
    var year = date.getFullYear();
    var month = AddZero(date.getMonth(), true);
    var day = AddZero(date.getDate());
    var hours = AddZero(date.getHours());
    var minutes = AddZero(date.getMinutes());
    var seconds = AddZero(date.getSeconds());
    //var milliseconds = date.getMilliseconds();
    result = year + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds; /* + ':' + milliseconds*/
    return result;
}

function trim_space(str) {
    return str.replace(new RegExp(" ", "gm"), "");
}

function clear_attr(elem) {
    $(elem).removeAttr("data-toggle").removeAttr("title").removeAttr("data-original-title").removeClass("tooltip-show");
}

function show_error(elem, message) {
    clear_attr(elem);
    $(elem).addClass("tooltip-show").attr("data-toggle", "tooltip").attr("data-original-title", message);
    $(function() { $(elem).tooltip('show'); });
}

function hide_tooltip(elem) {
    $(function() { $(elem).tooltip('hide'); });
}

function trun_green(elem) {
    $(elem).css("border-color", "#00FF00");
}

function trun_red(elem) {
    $(elem).css("border-color", "#FF0000");
}

function trun_gray(elem) {
    $(elem).css("border-color", "#d2d8d8");
}

function check_length(word, min, max) {
    return (word.length <= max && word.length >= min);
}

function check_username_length(username) {
    return check_length(username, 4, 16);
}

function check_password_length(password) {
    return check_length(password, 6, 16);
}

function check_phone_length(phone) {
    return check_length(phone, 11, 11);
}

function check_captcha_length(captcha) {
    return check_length(captcha, 4, 4);
}

function check_bad_chars(word, bad_chars) {
    var default_bad_chars = "";
    default_bad_chars += bad_chars;
    for (var i = default_bad_chars.length - 1; i >= 0; i--) {
        for (var j = word.length - 1; j >= 0; j--) {
            if (default_bad_chars[i] == word[j]) {
                return false;
            }
        }
    }
    return true;
}

function check_username_bad_chars(username) {
    return check_bad_chars(username, "~!@#$%^&*()_+`-={}|[]\\:\";',./<>?");
}

function check_password_bad_chars(password) {
    return check_bad_chars(password, "");
}

function check_number_bad_chars(phnoe) {
    for (var i = phnoe.length - 1; i >= 0; i--) {
        var ascii = phnoe.charCodeAt(i);
        if (ascii > "9".charCodeAt(0) || ascii < "0".charCodeAt(0)) {
            return false;
        }
    }
    return true;
}

function check_email(email) {
    var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;
    if (!reg.test(email)) {
        return false;
    }
    return true;
}