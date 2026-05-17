<html xmlns="http://www.w3.org/1999/xhtml"><head>
    <title> Пример использования КриптоПро ЭЦП Browser plug-in </title>
    <meta name="robots" content="noindex, nofollow" charset="utf-8">
    <link href="https://www.cryptopro.ru/sites/default/files/products/cades/demopage/demopage.css" rel="stylesheet" type="text/css">
    <script language="javascript" src="js/es6-promise.min.js"></script>
    <script language="javascript" src="js/ie_eventlistner_polyfill.js"></script>
    <script language="javascript">window.allow_firefox_cadesplugin_async=1</script>
    <script language="javascript" src="js/cadesplugin_api.js"></script>
	<script type="text/javascript" src="chrome-extension://iifchhfnnmpdbibifmljnfjhpififfog/nmcades_plugin_api.js"></script>
	<script type="text/javascript" src="chrome-extension://epebfcehmdedogndhlcacafjaacknbcm/nmcades_plugin_api.js"></script>
    <script language="javascript" src="js/Code.js"></script>
<script type="text/javascript" src="js/async_code.js"></script></head>

<body>
    <div id="min-width">
        <div id="container">
            <table>
                <tbody><tr>
                    <td>
                        <div id="header">
                            <a href="/" title="Главная">
                                <img src="https://www.cryptopro.ru/sites/default/files/products/cades/demopage/Img/key.png" alt="Главная" class="logo"></a>
                            <h1>Пример cоздания подписи CAdES-BES с иcпользованием Рутокен PinPad</h1>
                            <div id="promo"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="mainContent">
                            <div id="left-col">
                                <div id="info">
                                    <img id="info_img" src="https://www.cryptopro.ru/sites/default/files/products/cades/demopage/Img/pinpad.png" alt="" style="height:91px;">
                                    <div id="info_msg" style="text-align:center;">
                                        <span id="PlugInEnabledTxt">Плагин загружен.</span>
                                        <img src="https://www.cryptopro.ru/sites/default/files/products/cades/demopage/Img/green_dot.png" width="10" height="10" alt="Плагин не загружен" id="PluginEnabledImg">
                                        <br>
                                        <span id="PlugInVersionTxt" lang="ru">Версия плагина: 2.0.13064</span>
                                        <span id="CSPVersionTxt" lang="ru">Версия криптопровайдера: 4.0.9842</span>
                                        <br>
                                        <span id="CSPNameTxt" lang="ru">Криптопровайдер: Crypto-Pro GOST R 34.10-2001 Cryptographic Service Provider</span>
                                    </div>
                                    <div id="boxdiv" style="display:none">
                                        <span id="errorarea">
                                            У вас отсутствуют личные сертификаты. Вы можете 
                                            <a href="#" onclick="Common_RetrieveCertificate();" style="color:#0837ff"> получить</a> 
                                            сертификат от тестового УЦ, предварительно установив 
                                            <a href="https://www.cryptopro.ru/sites/default/files/products/cades/demopage/certsrv/certnew.cer?ReqID=CACert&amp;Enc=bin" style="color:#0837ff">корневой сертификат тестового УЦ</a> 
                                            в доверенные.
                                        </span>
                                    </div>
                                </div>
                                <p id="info_msg" name="CertificateTitle">Сертификат:</p>
                                <div id="item_border" name="CertListBoxToHide">
                                    <select size="4" name="CertListBox" id="CertListBox" style="width:100%;resize:none;border:0;">
									</select>
                                </div>

                                <div id="cert_info" style="display:none">
                                    <h2>Информация о сертификате</h2>
                                    <p class="info_field" id="subject"></p>
                                    <p class="info_field" id="issuer"></p>
                                    <p class="info_field" id="from"></p>
                                    <p class="info_field" id="till"></p>
                                    <p class="info_field" id="provname"></p>
                                    <p class="info_field" id="algorithm"></p>
                                </div>

                                <p id="info_msg">Пример сообщения об авторизации на сайте:</p>

                                <div id="item_border">
                                    <input type="text" value="Иванов Иван" id="Login" name="Войти" style="margin:.3em;">
                                    
                                    <button onclick="ShowPinPadelogin()">Войти</button>
                                </div>

                                <p id="info_msg">Пример подписи платежного поручения:</p>

                                <div id="item_border" style="padding:.5em;">
                                    <b>Сумма</b>
                                    <input type="text" value="1000000" id="_summ" style="margin:.3em;">
                                    <br>
                                    <b>Дата</b>
                                    <input type="text" value="17.03.2016" id="_date" style="margin:.3em;">
                                    <br>
                                    <b>Получатель</b>
                                    <input type="text" value="Иван Иванов" id="_to" style="margin:.3em;">
                                    <br>
                                    <b>Инн </b>102125125212
                                    <br>
                                    <b>КПП </b>1254521521
                                    <br>
                                    <b>Назначение платежа </b>За телематические услуги
                                    <br>
                                    <b>Банк получателя </b>Сбербанк
                                    <br>
                                    <b>БИК </b>5005825
                                    <br>
                                    <b>Номер счета получателя </b>1032221122214422
                                    <br>
                                    <b>Плательщик </b>ЗАО "Актив-софт"
                                    <br>
                                    <b>Банк плательщика </b>Банк ВТБ (открытое акционерное общество)
                                    <br>
                                    <b>БИК </b>044525187
                                    <br>
                                    <b>Номер счета плательщика </b>30101810700000000187
                                    <br>
                                    <br>
                                    
                                </div>
                                <p></p>
                                <button onclick="Common_SignCadesBES('CertListBox', MakePayment(document.getElementById('_summ').value, document.getElementById('_date').value,document.getElementById('_to').value), 1)">Подписать платежное поручение</button>
                                <p id="info_msg">Пример подписи произвольных данных:</p>

                                <div id="item_border">
                                    <textarea id="DataToSignTxtBox" style="height:200px;width:100%;resize:none;border:0;">&lt;!PINPADFILE UTF8&gt;&lt;N&gt;Платежное поручение&lt;V&gt;500
&lt;N&gt;Сумма&lt;V&gt;500
&lt;N&gt;Дата&lt;V&gt;17.03.2016
&lt;N&gt;Получатель&lt;V&gt;Иванов Иван Иванович
&lt;N&gt;Инн&lt;V&gt;102125125212
&lt;N&gt;КПП&lt;V&gt;1254521521
&lt;N&gt;Назначение платежа&lt;V&gt;За робототехнические услуги
&lt;N&gt;Банк получателя&lt;V&gt;Сбербанк
&lt;N&gt;БИК&lt;V&gt;5005825
&lt;N&gt;Номер счета получателя&lt;V&gt;1032221122214422
&lt;N&gt;Плательщик&lt;V&gt;ЗАО "Скайнет"
&lt;N&gt;Банк плательщика&lt;V&gt;Банк ВТБ (открытое акционерное общество)
&lt;N&gt;БИК&lt;V&gt;044525187
&lt;N&gt;Номер счета плательщика&lt;V&gt;30101810700000000187</textarea>
                                </div>
                                <p></p>
                                <button onclick="Common_SignCadesBES('CertListBox', document.getElementById('DataToSignTxtBox').value, 1)">Подписать</button>
                                <p id="info_msg" name="SignatureTitle">Подпись:</p>
                                <div id="item_border">
                                    <textarea id="SignatureTxtBox" readonly="" style="font-size:9pt;height:600px;width:100%;resize:none;border:0;"></textarea>
                                    <script language="javascript">
                                        document.getElementById("SignatureTxtBox").innerHTML = "";
                                        var canPromise = !!window.Promise;
                                        if(canPromise) {
                                            cadesplugin.then(function () {
                                                    Common_CheckForPlugIn();
                                                   },
                                                   function(error) {
                                                       document.getElementById('PluginEnabledImg').setAttribute("src", "Img/red_dot.png");
                                                       document.getElementById('PlugInEnabledTxt').innerHTML = error;
                                                   }
                                           );
                                        } else {
                                            window.addEventListener("message", function (event){
                                                if (event.data == "cadesplugin_loaded") {
                                                    CheckForPlugIn_NPAPI();
                                                } else if(event.data == "cadesplugin_load_error") {
                                                       document.getElementById('PluginEnabledImg').setAttribute("src", "Img/red_dot.png");
                                                       document.getElementById('PlugInEnabledTxt').innerHTML = "Плагин не загружен";
                                                }
                                                },
                                            false);
                                            window.postMessage("cadesplugin_echo_request", "*");
                                        }
                                    </script>
                                </div>
                            </div>
                            <div id="right-col">
                                <ul>
                                    <li><a class="active" href="https://www.cryptopro.ru/sites/default/files/products/cades/demopage/main.html">О КриптоПро ЭЦП Browser plug-in</a></li>
                                    <li><a class="active" href="http://cpdn.cryptopro.ru/default.asp?url=content/cades/plugin-installation.html">Инструкция по работе с плагином</a></li>
                                    <li><a class="active" href="https://www.cryptopro.ru/sites/default/files/products/cades/demopage/products/cades/plugin/get_2_0">Скачать плагин</a></li>
                                </ul>
<!--
                                <div style="padding: 40px;">
                                    <a href="https://www.surfpatrol.ru/ru/Report" target="_blank">
                                        <img id="SPcheck" alt="SurfPatrol" src="https://www.surfpatrol.ru/content/images/banner/ru/180x80_default.png" width="180"
                                        height="80" border="0" /></a>
                                    <script type="text/javascript">
                                        (function () {
                                            protocol = (document.location.protocol == 'https:' ? 'https://' : 'http://');
                                            okImg = protocol + 'www.surfpatrol.ru/content/images/banner/ru/180x80_success.png';
                                            badImg = protocol + 'www.surfpatrol.ru/content/images/banner/ru/180x80_alarm.png';
                    					    clientCode = '4977594d-b112-4669-ab56-81267a730e89';
                                            sp = document.createElement('script'); sp.type = 'text/javascript';
                                            sp.src = protocol + 'www.surfpatrol.ru/scripts/SPimage.min.js';
                                            s = document.getElementsByTagName('script')[0];
                                            s.parentNode.insertBefore(sp, s);
                                        })();
                                    </script>
                                </div> -->
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody></table>
        </div>
        <div id="footer">
 © ООО "КРИПТО-ПРО", 2001-<script>document.write(new Date().getFullYear())</script>2018<br>
            <br> +7 (495) 995-48-20
        </div>
    </div>



</body></html>