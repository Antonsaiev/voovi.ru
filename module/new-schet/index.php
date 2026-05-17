<div class="bs-example" data-example-id="embedded-scrollspy"> 
<nav class="navbar navbar-default navbar-static" id="navbar-example2"> 
	<div class="container-fluid"> 
		<div class="navbar-header"> 
			<button class="collapsed navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-example-js-navbar-scrollspy"> 
				<span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> 
				<span class="icon-bar"></span> <span class="icon-bar"></span> 
			</button> 
			<a href="#" class="navbar-brand">Project Name</a> 
		</div> 
		<div class="collapse navbar-collapse bs-example-js-navbar-scrollspy"> 
			<ul class="nav navbar-nav"> 
				<li class="active"><a href="#fat">@fat</a></li> 
				<li class=""><a href="#mdo">@mdo</a></li> 
				<li class="dropdown"> 
				<a href="#" class="dropdown-toggle" id="navbarDrop1" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a> 
				<ul class="dropdown-menu" aria-labelledby="navbarDrop1"> 
					<li class=""><a href="#one">one</a></li> 
					<li class=""><a href="#two">two</a></li> 
					<li role="separator" class="divider"></li> 
					<li class=""><a href="#three">three</a></li> 
				</ul> 
				</li> 
			</ul> 
		</div> 
	</div> 
</nav> 
<div class="scrollspy-example" data-spy="scroll" data-target="#navbar-example2" data-offset="0"> 
	<h4 id="fat">@fat</h4> 
	<div class="payerSelectionStep-requisites" id="PayerStepRequisites">
            <div class="payerSelectionStep-field-wrapper">
                <label class="payerSelectionStep-field-label inlineBlock" for="Forms_Payer__Inn">ИНН:</label>
                <span class="inputWrapper inlineBlock"><span class="inputPlaceholderWrapper placeholderWrapper" style="display: inline;"><span class="placeholder">10/12 цифр</span></span><input class="payerSelectionStep-inn payerSelectionStep-requisite textInput inlineBlock input-validation-error" data-val="true" data-val-regex="The field Inn must match the regular expression '^\d{10}$|^\d{12}$'." data-val-regex-pattern="^\d{10}$|^\d{12}$" data-val-required="The Inn field is required." id="Forms_Payer__Inn" maxlength="12" name="Forms[Payer].Inn" type="text" value=""></span>

                <a class="inlineBlock payerSelectionStep-focusSearch-link hidden" id="FocusSearchButton" tabindex="-4" href="/FocusRequisites/Find" data-validate-inn-url="/FocusRequisites/ValidateInn">
                    <span class="payerSelectionStep-focusSearch-icon focus inlineBlock"></span>
                    <span class="payerSelectionStep-focusSearch-text inlineBlock">Найти в Фокусе</span>
                </a>
            </div>

            <div class="payerSelectionStep-field-wrapper js-payerSelectionStep-kpp-wrapper">
                <label class="payerSelectionStep-field-label inlineBlock" for="Forms_Payer__Kpp">КПП:</label>
                <span class="inputWrapper inlineBlock"><span class="inputPlaceholderWrapper placeholderWrapper" style="display: inline;"><span class="placeholder">9 цифр</span></span><input class="payerSelectionStep-kpp payerSelectionStep-requisite textInput inlineBlock" data-val="true" data-val-regex="The field Kpp must match the regular expression '^$|^\d{9}$'." data-val-regex-pattern="^$|^\d{9}$" id="Forms_Payer__Kpp" maxlength="9" name="Forms[Payer].Kpp" type="text" value=""></span>
            </div>

            <div class="payerSelectionStep-field-wrapper js-payerSelectionStep-clientType-wrapper" style="display: none;">
                <label class="payerSelectionStep-field-label inlineBlock" for="Forms_Payer__ClientType">Тип клиента:</label>
                <span class="inlineBlock">
<span class="dds-select-default dds-select inlineBlock base-unselectable payerSelectionStep-clientType" id="Forms_Payer__ClientType" tabindex="0" data-options-popup-id="Forms_Payer__ClientType_Popup" data-ft-popup-id="ac105b97-a65c-bb16-d7da-02bbde66f997"><span class="dds-select-icon"></span><span class="dds-select-input">юр. лицо</span></span><input data-val="true" data-val-required="The ClientType field is required." id="Forms_Payer__ClientType_Hidden" name="Forms[Payer].ClientType" type="hidden" value="LegalEntity"><div class="dds-options popup js-popup-base" id="Forms_Payer__ClientType_Popup" data-ft-id="ac105b97-a65c-bb16-d7da-02bbde66f997"><div class="dds-option"><a class="dds-option-link" data-value="IndividualBusinessman" href="javascript:void(0)"><span class="dds-caption">ИП</span></a></div><div class="dds-option"><a class="dds-option-link" data-value="PhysicalPerson" href="javascript:void(0)"><span class="dds-caption">физ. лицо</span></a></div></div>                </span>
            </div>


            <div class="payerSelectionStep-field-wrapper">
                <label class="payerSelectionStep-field-label js-payerSelectionStep-name-label inlineBlock" for="Forms_Payer__Name">Наименование:</label>
                <input class="payerSelectionStep-name textInput" data-val="true" data-val-length="Максимальная длина не должна превышать 255 символов" data-val-length-max="255" data-val-regex="Недопустимые символы в наименовании" data-val-regex-pattern="^[^<>]*$" data-val-required="Заполните наименование плательщика" id="Forms_Payer__Name" name="Forms[Payer].Name" type="text" value="">
                <span class="field-validation-valid payerSelectionStep-validation-wrapper" data-valmsg-for="Forms[Payer].Name" data-valmsg-replace="true"></span>
            </div>

            <div class="payerSelectionStep-field-wrapper" style="display: none;" id="PayerStepBlock_Principal">
                <span class="payerSelectionStep-field-label inlineBlock">Регион:</span>
                <span class=""></span>
                <input class="js-payerSelectionStep-principalFio" id="Forms_Payer__PrincipalFio" name="Forms[Payer].PrincipalFio" type="hidden" value="">
                <input class="js-payerSelectionStep-principalPost" id="Forms_Payer__PrincipalPost" name="Forms[Payer].PrincipalPost" type="hidden" value="">
            </div>

            <div class="payerSelectionStep-field-wrapper" style="display: none;" id="PayerStepBlock_Address">
                <span class="payerSelectionStep-field-label inlineBlock">Адрес:</span>
                <span class=""></span>
                <input class="js-payerSelectionStep-address" id="Forms_Payer__Address" name="Forms[Payer].Address" type="hidden" value="">
            </div>

            <div class="payerSelectionStep-field-wrapper" style="display: none;" id="PayerStepBlock_Region">
                <span class="payerSelectionStep-field-label inlineBlock">Регион:</span>
                <span class=""></span>
                <input class="js-payerSelectionStep-regionCode" id="Forms_Payer__RegionCode" name="Forms[Payer].RegionCode" type="hidden" value="">
            </div>
            <input class="js-payerSelectionStep-filledFromFocus" data-val="true" data-val-required="The FilledFromFocus field is required." id="Forms_Payer__FilledFromFocus" name="Forms[Payer].FilledFromFocus" type="hidden" value="false">
        </div> 
	<h4 id="mdo">@mdo</h4> 
	<p>Veniam marfa mustache skateboard, adipisicing fugiat velit pitchfork beard. Freegan beard aliqua cupidatat mcsweeney's vero. Cupidatat four loko nisi, ea helvetica nulla carles. Tattooed cosby sweater food truck, mcsweeney's quis non freegan vinyl. Lo-fi wes anderson +1 sartorial. Carles non aesthetic exercitation quis gentrify. Brooklyn adipisicing craft beer vice keytar deserunt.</p> 
	<h4 id="one">one</h4> 
	<p>Occaecat commodo aliqua delectus. Fap craft beer deserunt skateboard ea. Lomo bicycle rights adipisicing banh mi, velit ea sunt next level locavore single-origin coffee in magna veniam. High life id vinyl, echo park consequat quis aliquip banh mi pitchfork. Vero VHS est adipisicing. Consectetur nisi DIY minim messenger bag. Cred ex in, sustainable delectus consectetur fanny pack iphone.</p> 
	<h4 id="two">two</h4> 
	<p>In incididunt echo park, officia deserunt mcsweeney's proident master cleanse thundercats sapiente veniam. Excepteur VHS elit, proident shoreditch +1 biodiesel laborum craft beer. Single-origin coffee wayfarers irure four loko, cupidatat terry richardson master cleanse. Assumenda you probably haven't heard of them art party fanny pack, tattooed nulla cardigan tempor ad. Proident wolf nesciunt sartorial keffiyeh eu banh mi sustainable. Elit wolf voluptate, lo-fi ea portland before they sold out four loko. Locavore enim nostrud mlkshk brooklyn nesciunt.</p> 
	<h4 id="three">three</h4> 
	<p>Ad leggings keytar, brunch id art party dolor labore. Pitchfork yr enim lo-fi before they sold out qui. Tumblr farm-to-table bicycle rights whatever. Anim keffiyeh carles cardigan. Velit seitan mcsweeney's photo booth 3 wolf moon irure. Cosby sweater lomo jean shorts, williamsburg hoodie minim qui you probably haven't heard of them et cardigan trust fund culpa biodiesel wes anderson aesthetic. Nihil tattooed accusamus, cred irony biodiesel keffiyeh artisan ullamco consequat.</p> 
	<p>Keytar twee blog, culpa messenger bag marfa whatever delectus food truck. Sapiente synth id assumenda. Locavore sed helvetica cliche irony, thundercats you probably haven't heard of them consequat hoodie gluten-free lo-fi fap aliquip. Labore elit placeat before they sold out, terry richardson proident brunch nesciunt quis cosby sweater pariatur keffiyeh ut helvetica artisan. Cardigan craft beer seitan readymade velit. VHS chambray laboris tempor veniam. Anim mollit minim commodo ullamco thundercats. </p> 
</div> 
</div>