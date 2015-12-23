<h2>Введите информацию о клиенте</h2>

<div style="display: block;">
    <div id="bobroidsTabs">
        <div class="tabsLinks">
            <div tabname="ext" class="oneTabItem active">Поиск</div>
            <div subclass="blue" tabname="new" class="oneTabItem blue">Новый</div>
            <!--<div subclass="blue" tabname="editUser" class="oneTabItem blue">Редактировать</div>-->
        </div>
        <div
            style="width: 100%; padding-top: 10px; box-sizing: border-box; margin-top: -1px; border-top: 1px solid #ddd;"
            class="tabInfo">
            <div style="display: block;" id="ext">
                <h3>Выбор существующего клиента:</h3>
                <br>

                <div>
                    <div style="display: block;" class="oneInput">
                        <input id="clientcardnumber" type="text"><label for="clientcardnumber">Номер
                            карты</label>
                    </div>
                    или
                    <br>

                    <div style="display: block;" class="oneInput">
                        <input id="oldPhone" type="text"><label for="oldPhone">Телефон клиента</label>
                    </div>
                    или
                    <br>

                    <div style="display: block;" class="oneInput">
                        <input id="SearchSurname" type="text"><label for="SearchSurname">Фамилия</label>
                    </div>
                    <center>
                        <button onclick="findSomeUser(this)" class="coolButton green">Искать</button>
                    </center>
                </div>
            </div>
            <div style="display: none;" id="new">
                <h3>Добавление нового клиента:</h3>
                <br>

                <div>
                    <div style="display: block;" class="oneInput">
                        <input id="newSurname" type="text"><label for="newSurname">Фамилия</label>
                    </div>
                    <div style="display: block;" class="oneInput">
                        <input id="newName" type="text"><label for="newName">Имя</label>
                    </div>
                    <div style="display: block;" class="oneInput">
                        <input id="newCity" type="text"><label for="newCity">Город, Область</label>
                    </div>
                    <div style="display: block;" class="oneInput">
                        <input id="newPhone" type="text"><label for="newPhone">Номер телефона</label>
                    </div>
                    <div style="display: block;" class="oneInput">
                        <input id="newEmail" type="text"><label for="newEmail">Электронная почта</label>
                    </div>
                    <div style="display: block;" class="oneInput">
                        <input id="newClientCardNumber" type="text"><label for="newClientCardNumber">Номер
                            карты</label>
                    </div>
                    <center>
                        <button onclick="addNewUser()" class="coolButton green">Добавить</button>
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>