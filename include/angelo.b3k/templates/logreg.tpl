<div class="alert alert-info">
    <i class="fa fa-info-circle fa-3x pull-left"></i>
    <div class="pull-left">
        Sie sind nicht eingeloggt, bitte Regestrieren Sie sich oder Loggen Sie sich ein.
    </div>
    <br style="clear: both;" />
</div>

<form class="form-horizontal" method="post">
    <div class="input-group">
        <span class="input-group-btn">
            <button class="btn btn-success" type="button" style="height:34px;width:40px"><i class="fa fa-user"></i></button>
        </span>
        <input name="name" type="text" class="form-control" placeholder="Benutzername">
    </div>
    <div class="input-group" style="margin-top:5px">
        <span class="input-group-btn">
            <button class="btn btn-success" type="button" style="height:34px;width:40px"><i class="fa fa-key"></i></button>
        </span>
        <input name="pass" type="password" class="form-control" placeholder="Passwort">
    </div></br>
    <div class="btn-group">
        <button name="user_login_sub" type="submit" class="btn btn-success" value="login">Login&nbsp;<i class="fa fa-sign-in"></i></button>
        <a href="?user-regist" class="btn btn-info" data-toggle="tooltip" data-placement="top"  title="Kostenlosen Account erstellen">Registrieren&nbsp;<i class="fa fa-user"></i></a>
        <a href="?user-remind" class="btn btn-default" data-toggle="tooltip" data-placement="top"  title="neues Passwort zusenden">Passwort vergessen&nbsp;<i class="fa fa-key"></i></a>
    </div>
</form>