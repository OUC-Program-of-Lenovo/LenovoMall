<ul id="toggle" class="slide-bar-left">
    <li class="cd-write-up">
        <div>
            <span class="menu-icons  fa fa-bookmark"></span>
            <a href="#">Sidebar item 1</a><span class="the-btn fa fa-plus"></span>
        </div>
        <ul>
            <li>
                <a href="#">Coming soon</a>
            </li>
        </ul>
    </li>
    <li class="cd-tutorials">
        <div>
            <span class="menu-icons  fa fa-book"></span>
            <a href="#">Sidebar item 2</a><span class="the-btn fa fa-plus"></span>
        </div>
        <ul>
            <li>
                <a href="#">Coming soon</a>
            </li>
        </ul>
    </li>

    <li class="cd-login">
        <div>
            <span class="menu-icons  glyphicon glyphicon-log-in"></span>
            <a>Login</a>
        </div>
    </li>

    <li class="cd-register">
        <div>
            <span class="menu-icons  glyphicon glyphicon-log-out"></span>
            <a>Register</a>
        </div>
    </li>
</ul>
</div>

<a href="#" class="toggle-nav" id="bars"><i class="fa fa-bars"></i></a>

<script type="text/javascript" src="/assets/js/smart_login.js"></script>
<script type="text/javascript" src="/assets/js/smart_register.js"></script>
<div class="cd-user-modal">
    <div class="cd-user-modal-container">
        <ul class="cd-switcher">
            <li><a>Login</a></li>
            <li><a>Register</a></li>
<!--            <li><a>Forget Password</a></li>-->
<!--            <li style="display: none;"><a>Reset Password</a></li> -->
        </ul>
        <div id="cd-login">
            <form class="cd-form" action="/user/login" method="POST">
                <p class="fieldset">
                    <label class="image-replace form-username-label">Username</label>
                    <input  name="username" class="full-width2 has-padding has-border" id="login-username" type="text" placeholder="Username">
                </p>
                <p class="fieldset">
                    <label class="image-replace form-password-label">Password</label>
                    <input name="password" class="full-width2 has-padding has-border" id="login-password" type="password"  placeholder="Password">
                </p>
                <p class="fieldset form-captcha">
                    <label class="image-replace form-captcha-label">Captcha</label>
                    <input name="captcha" class="full-width2 has-padding has-border" id="login-captcha" type="text" placeholder="Captcha">
                </p>
                <p class="captcha" onclick="javascript:get_captcha()">
                </p><br>
                <p class="fieldset">
                    <input class="full-width2" id="login-input-button" type="submit" value="Login">
                </p>
            </form>
        </div>

        <div id="cd-register">
            <form class="cd-form" action="/user/register" method="POST">
                <p class="fieldset">
                    <label class="image-replace form-username-label">Username</label>
                    <input name="username" class="full-width2 has-padding has-border" id="register-username" type="text" placeholder="Username">
                </p>
                <p class="fieldset">
                    <label class="image-replace form-password-label">Password</label>
                    <input name="password" class="full-width2 has-padding has-border" id="register-password" type="password"  placeholder="Password">
                </p>
                <p class="fieldset">
                    <label class="image-replace form-email-label">Email</label>
                    <input name="email" class="full-width2 has-padding has-border" id="register-email" type="email" placeholder="Email">
                </p>
                <p class="fieldset">
                    <label class="image-replace form-phone-label">Phone</label>
                    <input name="phone" class="full-width2 has-padding has-border" id="register-phone" type="tel" placeholder="Phone">
                </p>
                <p class="fieldset form-captcha">
                    <label class="image-replace form-captcha-label">Captcha</label>
                    <input name="captcha" class="full-width2 has-padding has-border" id="register-captcha" type="text" placeholder="Captcha">
                </p>
                <p class="captcha" onclick="javascript:get_captcha()">
                </p><br>
                <p class="fieldset">
                    <input class="full-width2" id="register-input-button"  type="submit" value="Register">
                </p>
            </form>
        </div>

<!-- Unfinished
        <div id="cd-forget">
            <form class="cd-form" action="/user/forget" method="POST">
                <p class="fieldset">
                    <label class="image-replace form-email-label">Email</label>
                    <input name="email" class="full-width2 has-padding has-border" id="forget-email" type="email" placeholder="Email">
                </p>
                <p class="fieldset form-captcha">
                    <label class="image-replace form-captcha-label">Captcha</label>
                    <input name="captcha" class="full-width2 has-padding has-border" id="forget-captcha" type="text" placeholder="Captcha">
                </p>
                <p class="captcha" onclick="javascript:get_captcha()">
                </p><br>
                <p class="fieldset">
                    <input class="full-width2" id="forget-input-button" type="submit" value="Forget Password">
                </p>
            </form>
        </div>


        <div id="cd-reset">
            <form class="cd-form" action="/user/reset" method="POST">
                <p class="fieldset">
                    <label class="image-replace form-password-label">Password</label>
                    <input name="password" class="full-width has-padding has-border" id="reset-password" type="password"  placeholder="New Password">
                </p>
                <p class="fieldset">
                    <input name="reset_code" type="hidden" id="reset-code-input">
                </p>
                <p class="fieldset">
                    <input class="full-width2" id="reset-input-button" type="submit" value="Reset Password">
                </p>
            </form>
        </div>
-->

    </div>
</div>