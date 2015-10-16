<span class="close"></span>
<div class="block">
    <span class="label">Вход в личный кабинет:</span>
    <div class="cap">
        Пользователь с указанным e-mail или телефоном уже зарегистрирован. </div>
    <div class="row">
        <div class="phone">
            <span class="title">Ваш телефон</span>
            <span class="flag"></span>
            <input placeholder="+_(___)___-____" class="input_phone" name="phone" onchange="iWannaLogin(this)" onkeyup="iWannaLogin(this)" onkeydown="iWannaLogin(this)" value="" type="text">
        </div>
    </div>
    <div class="row">
        <div class="password">
            <span class="title">Пароль</span><input onchange="iWannaLogin(this)" onkeyup="iWannaLogin(this)" onkeydown="iWannaLogin(this)" id="input_password" name="passwd" type="password">
        </div>
    </div>
    <div class="line"></div>
    <div class="row">
        <input class="yellowButton largeButton" disabled="" id="loginMeButton" value="Войти" name="login" onclick="loginMe(this)" type="button">
    </div>
    <div class="row center">
        <span class="recovery link-hide blue" data-href="/cabinet/recovery">Восстановить пароль</span><span> | </span><span class="registration link-hide blue" data-href="/cabinet/registration">Регистрация</span>
    </div>
</div>