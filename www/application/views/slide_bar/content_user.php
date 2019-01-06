<ul id="toggle" class="slide-bar-left">
    <li class="cd-mine">
        <div class="active border">
            <span class="menu-icons fa fa-home"></span>
            <a href="javascript:get_personal_information();">Profile</a>
        </div>
    </li>

    <li class="cd-category">
        <div>
            <span class="menu-icons  fa fa-list-ul"></span>
            <a href="#">Category</a>
            <span class="the-btn fa fa-plus"></span>
        </div>
        <ul>
            <li class="goods-all">
                <a href="#">All</a>
            </li>
        </ul>
    </li>

    <li class="cd-write-up">
        <div>
            <span class="menu-icons fa fa-bookmark"></span>
            <a href="#">Side Bar 1</a>
            <span class="the-btn fa fa-plus"></span>
        </div>
        <ul>
            <li>
                <a href="javascript:load_personal_orders();">All</a>
            </li>
        </ul>
    </li>

    <li class="cd-tutorials">
        <div>
            <span class="menu-icons  fa fa-book"></span>
            <a href="#">Side Bar 2</a>
            <span class="the-btn fa fa-plus"></span>
        </div>
        <ul>
            <li>
                <a href="#">None</a>
            </li>
        </ul>
    </li>

    <li class="cd-logout">
        <div>
            <span class="menu-icons  glyphicon glyphicon-log-out"></span>
            <a href="/user/logout">Logout</a>
        </div>
    </li>
</ul>
</div>

<a href="#" class="toggle-nav" id="bars">
    <i class="fa fa-bars"></i>
</a>

<i class="hide-cart-container menu-icons glyphicon glyphicon-eye-open"></i>

<div class="cart-container">
    <h1>Cart</h1>
    <div class="scrolbar-container" style="'height: 100px; width: 100px;">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Number</th>
            </tr>
            </thead>
            <tbody class="cart-tbody">
                <tr>
                    <td>笔记本1</td>
                    <td>￥5000.00</td>
                    <td>2</td>
                </tr>
            </tbody>
        </table>
        <button id="cart_submit">提交</button>
    </div>
</div>