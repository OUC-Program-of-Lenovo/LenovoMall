<link rel="stylesheet" type="text/css" href="/assets/css/admin.css">
<script type="text/javascript" src="/assets/js/admin.js"></script>
<ul id="toggle" class="slide-bar-left">
    <li class="cd-mine">
        <div class="active border">
            <span class="menu-icons fa fa-home"></span>
            <a href="javascript:get_personal_information();">Profile</a>
        </div>
    </li>

    <li class="cd-management">
        <div class="active border">
            <span class="menu-icons fa fa-wrench"></span>
            <a href="#">Management</a>
            <span class="the-btn fa fa-plus"></span>
        </div>
        <ul>
            <li>
                <a href="javascript:load_users();">Users</a>
            </li>
            <li>
                <a href="javascript:load_items();">Goods</a>
            </li>
            <li>
                <a href="javascript:load_orders();">Orders</a>
            </li>
        </ul>
    </li>

    <li class="cd-category">
        <div>
            <span class="menu-icons  fa fa-list-ul"></span>
            <a href="#">Category</a>
            <span class="the-btn fa fa-plus"></span>
        </div>
        <ul>
            <li>
                <a href="javascript:;">All</a>
            </li>
        </ul>
    </li>

    <li class="cd-write-up">
        <div>
            <span class="menu-icons  fa fa-bookmark"></span>
            <a href="#">Orders</a>
            <span class="the-btn fa fa-plus"></span>
        </div>
        <ul>
            <li>
                <a href="#">All</a>
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
            <span class="menu-icons  fa fa-sign-out"></span>
            <a href="/user/logout">Logout</a>
        </div>
    </li>
</ul>
</div>

<a href="#" class="toggle-nav" id="bars">
    <i class="fa fa-bars"></i>
</a>

<div class="cd-user-modal admin-items-modal admin-items-create-modal">
    <div class="cd-user-modal-container">
        <div id="cd-admin-items">
            <form class="cd-form admin-create-challenge" action="/challenge/create" method="POST">
                <p class="fieldset">
                    <label class="col-lg-2 control-label admin-challenge-label">Name</label>
                    <label class="image-replace admin-challenge-label-name">Name</label>
                    <input name="name" class="full-width2 has-padding has-border admin-challenge-name" type="text"
                           placeholder="Name">
                </p>
                <p class="fieldset">
                    <label class="col-lg-2 control-label admin-challenge-label">Description</label>
                    <textarea name="description" class="form-control admin-challenge-description" rows="3"
                              placeholder="Description"></textarea>
                </p>
                <div class="col-lg-10 admin-challenge-type">
                    <label class="col-lg-2 control-label admin-challenge-label">Type</label>
                    <select name="type" class="admin-challenge-type selectpicker show-tick form-control">
                        <option value="a">游戏本</option>
                        <option value="b">商务本</option>
                        <option value="c">二合一本</option>
                        <option value="d">轻薄本 </option>
                    </select>
                </div>
                <p class="fieldset">
                    <label class="col-lg-2 control-label admin-challenge-label">Number</label>
                    <label class="image-replace admin-challenge-label-score">Number</label>
                    <input name="number" class="full-width2 has-padding has-border admin-challenge-score" type="text"
                           placeholder="Number">
                </p>
                <p class="fieldset">
                    <label class="col-lg-2 control-label admin-challenge-label">Amount</label>
                    <label class="image-replace admin-challenge-label-score">Amount</label>
                    <input name="amount" class="full-width2 has-padding has-border admin-challenge-score" type="text"
                           placeholder="Amount">
                </p>
                <p class="fieldset">
                    <label class="col-lg-2 control-label admin-challenge-label">Price</label>
                    <label class="image-replace admin-challenge-label-score">Price</label>
                    <input name="price" class="full-width2 has-padding has-border admin-challenge-score" type="text"
                           placeholder="Price">
                </p>
                <p class="fieldset">
                    <label class="col-lg-2 control-label admin-challenge-label">Avatar</label>
                    <label class="image-replace admin-challenge-label-score">Avatar</label>
                    <input id="item-avatar" class="full-width2 has-padding has-border admin-challenge-flag" type="file">
                </p>
                <p class="fieldset">
                    <label class="col-lg-2 control-label admin-challenge-label">Size</label>
                    <label class="image-replace admin-challenge-label-flag">Size</label>
                    <input name="size" class="full-width2 has-padding has-border admin-challenge-flag" type="text"
                           placeholder="Size">
                </p>
                <p class="fieldset">
                    <span>Online now</span>
                    <input class="admin-challenge-value-checkbox" type="checkbox" checked>
                </p>
                <p class="fieldset">
                    <input class="full-width2" id="create-challenge" type="submit" value="Create">
                </p>
            </form>
        </div>
    </div>
</div>

<div class="cd-user-modal admin-items-modal admin-items-update-modal">
    <div class="cd-user-modal-container">
        <div id="cd-admin-items">
            <form class="cd-form admin-update-challenge" action="/challenge/update/" method="POST">
                <p class="fieldset">
                    <label class="col-lg-2 control-label admin-challenge-label">Name</label>
                    <label class="image-replace admin-challenge-label-name">Name</label>
                    <input name="name" class="full-width2 has-padding has-border admin-challenge-name update"
                           id="admin-challenge-update-name" type="text" placeholder="Name">
                </p>
                <p class="fieldset">
                    <label class="col-lg-2 control-label admin-challenge-label">Description</label>
                    <textarea name="description" class="form-control admin-challenge-description"
                              id="admin-challenge-update-description" rows="3" placeholder="Description"></textarea>
                </p>
                <div class="col-lg-10 admin-challenge-type">
                    <label class="col-lg-2 control-label admin-challenge-label">Type</label>
                    <select name="type" id="admin-challenge-update-type"
                            class="admin-challenge-type-select selectpicker show-tick form-control">
                        <option value="web">Web</option>
                        <option value="pwn">Pwn</option>
                        <option value="reverse">Reverse</option>
                        <option value="crypto">Crypto</option>
                        <option value="misc">Misc</option>
                        <option value="stego">Stego</option>
                        <option value="forensics">Forensics</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <p class="fieldset">
                    <label class="col-lg-2 control-label admin-challenge-label">Score</label>
                    <label class="image-replace admin-challenge-label-score">Score</label>
                    <input name="score" class="full-width2 has-padding has-border admin-challenge-score" type="text"
                           id="admin-challenge-update-score" placeholder="Score">
                </p>
                <p class="fieldset">
                    <label class="col-lg-2 control-label admin-challenge-label">Resource</label>
                    <label class="image-replace admin-challenge-label-resource">Resource</label>
                    <input name="resource" class="full-width2 has-padding has-border admin-challenge-resource"
                           id="admin-challenge-update-resource" type="text" placeholder="Resource">
                </p>
                <p class="fieldset">
                    <label class="col-lg-2 control-label admin-challenge-label">Flag</label>
                    <label class="image-replace admin-challenge-label-flag">Flag</label>
                    <input name="flag" class="full-width2 has-padding has-border admin-challenge-flag" type="text"
                           id="admin-challenge-update-flag" placeholder="Flag">
                </p>
                <p class="fieldset">
                    <input class="full-width2" id="update-challenge" type="submit" value="Update">
                </p>
            </form>
        </div>
    </div>
</div>
<!--
<i class="hide-cart-container menu-icons glyphicon glyphicon-eye-open"></i>
<div class="cart-container">
  <h1>Scoreboard</h1>
  <div class="scrolbar-container" style="'height: 100px; width: 100px;">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Place</th>
          <th>Username</th>
          <th>Score</th>
        </tr>
      </thead>
      <tbody class="cart-tbody">
      </tbody>
    </table>
  </div>
</div>
-->