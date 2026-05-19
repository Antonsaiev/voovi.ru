<div class="komnete-contact-widget" data-contact-id="<?php echo intval($schet['lico']); ?>">
<?php
if (!function_exists('voovi_contact_normalize_phone')) {
    function voovi_contact_normalize_phone($phone)
    {
        $phone = trim((string)$phone);
        if ($phone === '') {
            return '';
        }

        return preg_replace('/\s+/u', ' ', $phone);
    }
}

if (!function_exists('voovi_contact_split_phones')) {
    function voovi_contact_split_phones($phones)
    {
        $phones = trim((string)$phones);
        if ($phones === '') {
            return array();
        }

        $phones = preg_replace('/\r\n?|\n/u', "\n", $phones);
        $parts = preg_split('~(?:\n+|[;|]+|\t+(?=\s*(?:\+?\d|8\s*\(|7\s*\())|,\s*(?=[+0-9])| {2,}(?=(?:\+?\d|8\s*\(|7\s*\()))~u', $phones);
        if ($parts === false) {
            $parts = array($phones);
        }

        $result = array();
        foreach ($parts as $phone) {
            $phone = voovi_contact_normalize_phone($phone);
            if ($phone !== '') {
                $result[] = $phone;
            }
        }

        return $result;
    }
}

if (!function_exists('voovi_contact_format_phone')) {
    function voovi_contact_format_phone($phone)
    {
        $phone = voovi_contact_normalize_phone($phone);
        $compactPhone = preg_replace('/\s+/u', '', $phone);
        $digits = preg_replace('/\D+/', '', $phone);
        $isCompactPhone = preg_match('/^\+?\d{10,11}$/', $compactPhone);

        if (!$isCompactPhone) {
            return $phone;
        }

        if (strlen($digits) === 11 && preg_match('/^[78]9/', $digits)) {
            return substr($digits, 0, 1).' ('.substr($digits, 1, 3).') '.substr($digits, 4, 3).'-'.substr($digits, 7, 2).'-'.substr($digits, 9, 2);
        }

        if (strlen($digits) === 10 && substr($digits, 0, 1) === '9') {
            return '8 ('.substr($digits, 0, 3).') '.substr($digits, 3, 3).'-'.substr($digits, 6, 2).'-'.substr($digits, 8, 2);
        }

        return $phone;
    }
}

if (!function_exists('voovi_contact_pol_label')) {
    function voovi_contact_pol_label($pol)
    {
        if ((string)$pol === '1') {
            return 'Мужской';
        }

        if ((string)$pol === '2') {
            return 'Женский';
        }

        return '';
    }
}

$contactId = intval($schet['lico']);
$personlis = array();
if ($contactId > 0) {
    $lis = "SELECT * FROM klient WHERE id =".$contactId;
    $resultlis = mysql_query($lis);
    $personlis = mysql_fetch_array($resultlis);
}

$contactData = array(
    'id' => $contactId,
    'fio' => isset($personlis['fio']) ? $personlis['fio'] : '',
    'tel' => isset($personlis['tel']) ? $personlis['tel'] : '',
    'dol' => isset($personlis['dol']) ? $personlis['dol'] : '',
    'email' => isset($personlis['email']) ? $personlis['email'] : '',
    'pol' => isset($personlis['pol']) ? $personlis['pol'] : ''
);

$contactPhones = voovi_contact_split_phones($contactData['tel']);
$editorPhones = count($contactPhones) ? $contactPhones : array('');
$primaryPhone = count($contactPhones) ? voovi_contact_format_phone($contactPhones[0]) : '';
$phoneOriginalValue = implode(', ', $contactPhones);
$dolOptions = array('Сотрудник', 'Заместитель', 'Секретарь', 'Бухгалтер', 'Программист', 'Юрист', 'Другой');
$contactContext = array(
    'kli' => isset($_GET['kli']) ? $_GET['kli'] : '',
    'ogrn' => isset($_GET['ogrn']) ? $_GET['ogrn'] : '',
    'rand' => isset($_GET['rand']) ? $_GET['rand'] : ''
);
?>
    <span id="tele" style="display:none;"><?php echo htmlspecialchars($primaryPhone, ENT_QUOTES, 'UTF-8'); ?></span>

    <div class="komnete-contact-actions">
        <button type="button" class="komnete-link-button komnete-contact-select-toggle">Выбрать из списка</button>
        <button type="button" class="komnete-contact-edit" style="<?php if ($contactId === 0) { echo 'display:none;'; } ?>">Редактировать контакт</button>
    </div>

    <select name="kontakti" class="form-control komnete-contact-select" id="kontakti" onchange="konTakti(this.value)" style="<?php if ($contactId > 0) { echo 'display:none;'; } ?>">
        <option value="0"></option>
        <?php
        $query2 = mysql_query("SELECT * from klient_ogrn WHERE idkli = '".mysql_real_escape_string(isset($_GET['kli']) ? $_GET['kli'] : '')."' ORDER BY id DESC");
        while($row2 = mysql_fetch_array($query2)) {
            $query3 = mysql_query("SELECT * from klient WHERE id = '".$row2['klient']."' ORDER BY id DESC");
            while($row3 = mysql_fetch_array($query3)) {
                echo '<option value="'.intval($row3['id']).'">';
                echo htmlspecialchars($row3['fio'], ENT_QUOTES, 'UTF-8')," (",htmlspecialchars($row3['dol'], ENT_QUOTES, 'UTF-8'),":",htmlspecialchars($row3['tel'], ENT_QUOTES, 'UTF-8'),")";
                echo '</option>';
            }
        }
        ?>
        <option value="0"></option>
    </select>

    <div class="komnete-contact-empty" id="spisecho" style="<?php if ($contactId > 0) { echo 'display:none;'; } ?>">Можно выбрать контакт из списка или заполнить нового.</div>
    <div id="konactinfo"></div>

    <div class="komnete-contact-view" style="<?php if ($contactId === 0) { echo 'display:none;'; } ?>">
        <div class="form-group komnete-contact-row">
            <label class="col-sm-3 control-label">ФИО:</label>
            <div class="col-sm-9">
                <p class="form-control-static komnete-contact-static" id="contact_fio_view"><?php echo htmlspecialchars($contactData['fio'] !== '' ? $contactData['fio'] : 'Не указано', ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        </div>

        <div class="form-group komnete-contact-row">
            <label class="col-sm-3 control-label">Тел.:</label>
            <div class="col-sm-9">
                <div class="komnete-phone-view">
                    <?php if (count($contactPhones)) { ?>
                        <?php foreach ($contactPhones as $phone) { ?>
                            <div class="komnete-phone-display"><?php echo htmlspecialchars(voovi_contact_format_phone($phone), ENT_QUOTES, 'UTF-8'); ?></div>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="komnete-contact-empty">Телефон не указан.</p>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="form-group komnete-contact-row">
            <label class="col-sm-3 control-label">Должность:</label>
            <div class="col-sm-9">
                <p class="form-control-static komnete-contact-static" id="contact_dol_view"><?php echo htmlspecialchars($contactData['dol'] !== '' ? $contactData['dol'] : 'Не указано', ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        </div>

        <div class="form-group komnete-contact-row">
            <label class="col-sm-3 control-label">E-mail:</label>
            <div class="col-sm-9">
                <p class="form-control-static komnete-contact-static" id="contact_email_view"><?php echo htmlspecialchars($contactData['email'] !== '' ? $contactData['email'] : 'Не указано', ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        </div>

        <div class="form-group komnete-contact-row">
            <label class="col-sm-3 control-label">Пол:</label>
            <div class="col-sm-9">
                <p class="form-control-static komnete-contact-static" id="contact_pol_view"><?php echo htmlspecialchars(voovi_contact_pol_label($contactData['pol']) !== '' ? voovi_contact_pol_label($contactData['pol']) : 'Не указано', ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        </div>
    </div>

    <div class="komnete-contact-editor" style="<?php if ($contactId > 0) { echo 'display:none;'; } ?>">
        <div class="form-group komnete-contact-row">
            <label class="col-sm-3 control-label">ФИО:</label>
            <div class="col-sm-9" id="fio">
                <input class="form-control" id="name" type="text" value="<?php echo htmlspecialchars($contactData['fio'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="Петров Петр Петрович" autocomplete="off">
            </div>
        </div>

        <div class="form-group komnete-contact-row">
            <label class="col-sm-3 control-label">Тел.:</label>
            <div class="col-sm-9" id="tel">
                <div id="addbsq" class="komnete-phone-list" data-original-phones="<?php echo htmlspecialchars($phoneOriginalValue, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php
                    foreach ($editorPhones as $index => $phone) {
                        $phoneId = $index === 0 ? ' id="user_phone2"' : '';
                        echo '<div class="komnete-phone-row">';
                        echo '<input'.$phoneId.' class="form-control komnete-phone-input" type="tel" value="'.htmlspecialchars(voovi_contact_format_phone($phone), ENT_QUOTES, 'UTF-8').'" placeholder="8(123) 456-78-90" autocomplete="off">';
                        echo '<button type="button" class="komnete-phone-remove" title="Удалить телефон">&times;</button>';
                        echo '</div>';
                    }
                    ?>
                </div>
                <button type="button" class="komnete-phone-add" onclick="addbq()">+ телефон</button>
            </div>
        </div>

        <div class="form-group komnete-contact-row">
            <label class="col-sm-3 control-label">Должность:</label>
            <div class="col-sm-9" id="dol">
                <select id="dolrr" class="form-control">
                    <option value=""></option>
                    <?php foreach ($dolOptions as $dolOption) { ?>
                        <option value="<?php echo htmlspecialchars($dolOption, ENT_QUOTES, 'UTF-8'); ?>" <?php if ($contactData['dol'] === $dolOption) { echo 'selected'; } ?>><?php echo htmlspecialchars($dolOption, ENT_QUOTES, 'UTF-8'); ?></option>
                    <?php } ?>
                    <?php if ($contactData['dol'] !== '' && !in_array($contactData['dol'], $dolOptions)) { ?>
                        <option value="<?php echo htmlspecialchars($contactData['dol'], ENT_QUOTES, 'UTF-8'); ?>" selected><?php echo htmlspecialchars($contactData['dol'], ENT_QUOTES, 'UTF-8'); ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group komnete-contact-row">
            <label class="col-sm-3 control-label">E-mail:</label>
            <div class="col-sm-9" id="email">
                <input class="form-control" id="contact_email" type="email" value="<?php echo htmlspecialchars($contactData['email'], ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off">
            </div>
        </div>

        <div class="form-group komnete-contact-row komnete-contact-row-inline">
            <label class="col-sm-3 control-label">Пол:</label>
            <div class="col-sm-9" id="pol">
                <label class="sgt-granular_label komnete-contact-radio">
                    <input type="radio" name="pol" id="polrr" value="1" class="inline" <?php if ((string)$contactData['pol'] === '1') { echo 'checked'; } ?>> Мужской
                </label>
                <label class="sgt-granular_label komnete-contact-radio">
                    <input type="radio" name="pol" id="fullname-gender-female" value="2" class="inline" <?php if ((string)$contactData['pol'] === '2') { echo 'checked'; } ?>> Женский
                </label>
            </div>
        </div>

        <div class="komnete-contact-edit-actions">
            <button type="button" class="komnete-contact-save" id="newkli"><?php echo $contactId > 0 ? 'Сохранить контакт' : 'Создать контакт'; ?></button>
            <button type="button" class="komnete-contact-cancel" style="<?php if ($contactId === 0) { echo 'display:none;'; } ?>">Отмена</button>
        </div>
    </div>
</div>

<script>
var vooviKontaktContext = <?php echo json_encode($contactContext); ?>;
var vooviContactState = <?php echo json_encode($contactData); ?>;

function vooviEscapeHtml(value) {
    return String(value || '').replace(/[&<>"']/g, function(symbol) {
        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[symbol];
    });
}

function vooviNormalizePhone(phone) {
    return $.trim(String(phone || '').replace(/\s+/g, ' '));
}

function vooviSplitPhones(phones) {
    if (!phones) {
        return [];
    }

    return String(phones).replace(/\r\n?|\n/g, '\n').split(/\n+|[;|]+|\t+(?=\s*(?:\+?\d|8\s*\(|7\s*\())|,\s*(?=[+0-9])| {2,}(?=(?:\+?\d|8\s*\(|7\s*\())/g).map(function(phone) {
        return vooviNormalizePhone(phone);
    }).filter(function(phone) {
        return phone.length > 0;
    });
}

function vooviFormatPhone(phone) {
    phone = vooviNormalizePhone(phone);
    var compactPhone = phone.replace(/\s+/g, '');
    var digits = phone.replace(/\D+/g, '');

    if (!/^\+?\d{10,11}$/.test(compactPhone)) {
        return phone;
    }

    if (digits.length === 11 && /^[78]9/.test(digits)) {
        return digits.substr(0, 1) + ' (' + digits.substr(1, 3) + ') ' + digits.substr(4, 3) + '-' + digits.substr(7, 2) + '-' + digits.substr(9, 2);
    }

    if (digits.length === 10 && digits.substr(0, 1) === '9') {
        return '8 (' + digits.substr(0, 3) + ') ' + digits.substr(3, 3) + '-' + digits.substr(6, 2) + '-' + digits.substr(8, 2);
    }

    return phone;
}

function vooviPolLabel(pol) {
    if (String(pol) === '1') {
        return 'Мужской';
    }

    if (String(pol) === '2') {
        return 'Женский';
    }

    return '';
}

function vooviContactWidget() {
    return $('.komnete-contact-widget');
}

function vooviDisplayValue(value) {
    value = $.trim(String(value || ''));
    return value.length ? vooviEscapeHtml(value) : '<span class="komnete-contact-muted">Не указано</span>';
}

function vooviIsSavedContact() {
    return parseInt(vooviContactWidget().data('contact-id'), 10) > 0;
}

function vooviApplyPhoneMask(input) {
    var currentValue = $.trim($(input).val());
    var compactValue = currentValue.replace(/\s+/g, '');
    var digits = currentValue.replace(/\D+/g, '');

    if (currentValue.length > 0) {
        if (!/^\+?\d{10,11}$/.test(compactValue)) {
            return;
        }
        if (!((digits.length === 11 && /^[78]9/.test(digits)) || (digits.length === 10 && digits.substr(0, 1) === '9'))) {
            return;
        }
    }

    if (digits.length > 0 && digits.length < 10) {
        return;
    }

    if (typeof is_mobile === 'function' && !is_mobile() && $.fn.mask) {
        $(input).mask('9(999) 999-99-99')
            .removeAttr('required')
            .removeAttr('pattern')
            .removeAttr('title')
            .attr('placeholder', '8(123) 456-78-90');
    }
}

function vooviCollectPhones() {
    var phones = [];
    vooviContactWidget().find('.komnete-phone-input').each(function() {
        var phone = vooviNormalizePhone($(this).val());
        if (phone.length > 0) {
            phones.push(phone);
        }
    });
    return phones;
}

function vooviGetOriginalPhones() {
    return vooviSplitPhones(vooviContactWidget().find('#addbsq').attr('data-original-phones') || '');
}

function vooviSetOriginalPhones(phones) {
    vooviContactWidget().find('#addbsq').attr('data-original-phones', vooviSplitPhones(phones).join(', '));
}

function vooviSyncPrimaryPhone() {
    var phones = vooviCollectPhones();
    if (!phones.length) {
        phones = vooviGetOriginalPhones();
    }
    vooviContactWidget().find('#tele').text(phones.length ? phones[0] : '');
}

function vooviRenderPhoneDisplay(phones) {
    var list = vooviContactWidget().find('.komnete-phone-view');
    phones = vooviSplitPhones(phones);
    list.empty();

    if (!phones.length) {
        list.append('<p class="komnete-contact-empty">Телефон не указан.</p>');
        return;
    }

    $.each(phones, function(index, phone) {
        list.append('<div class="komnete-phone-display">' + vooviEscapeHtml(vooviFormatPhone(phone)) + '</div>');
    });
}

function vooviCreatePhoneRow(phone, shouldFocus) {
    var row = $('<div class="komnete-phone-row"></div>');
    var input = $('<input class="form-control komnete-phone-input" type="tel" placeholder="8(123) 456-78-90" autocomplete="off">').val(vooviFormatPhone(phone));
    var remove = $('<button type="button" class="komnete-phone-remove" title="Удалить телефон">&times;</button>');
    row.append(input).append(remove);
    vooviContactWidget().find('#addbsq').append(row);
    vooviApplyPhoneMask(input);
    if (shouldFocus) {
        input.focus();
    }
}

function vooviRenderPhoneEditor(phones) {
    var list = vooviContactWidget().find('#addbsq');
    list.empty();
    phones = vooviSplitPhones(phones);
    if (!phones.length) {
        phones = [''];
    }
    $.each(phones, function(index, phone) {
        vooviCreatePhoneRow(phone, false);
        if (index === 0) {
            list.find('.komnete-phone-input').last().attr('id', 'user_phone2');
        }
    });
    vooviSyncPrimaryPhone();
}

function vooviEnsureDolOption(value) {
    value = $.trim(String(value || ''));
    if (!value.length) {
        return;
    }

    var exists = false;
    vooviContactWidget().find('#dolrr option').each(function() {
        if ($(this).val() === value) {
            exists = true;
        }
    });

    if (!exists) {
        vooviContactWidget().find('#dolrr').append($('<option></option>').val(value).text(value));
    }
}

function vooviSetDolValue(value) {
    value = $.trim(String(value || ''));
    vooviEnsureDolOption(value);
    vooviContactWidget().find('#dolrr').val(value);
}

function vooviNormalizeContact(contact) {
    contact = $.extend({
        id: 0,
        fio: '',
        tel: '',
        dol: '',
        email: '',
        pol: ''
    }, contact || {});
    contact.id = parseInt(contact.id, 10) || 0;
    contact.fio = $.trim(String(contact.fio || ''));
    contact.tel = vooviSplitPhones(contact.tel || '').join(', ');
    contact.dol = $.trim(String(contact.dol || ''));
    contact.email = $.trim(String(contact.email || ''));
    contact.pol = $.trim(String(contact.pol || ''));
    return contact;
}

function vooviRenderContactView(contact) {
    contact = vooviNormalizeContact(contact);
    var widget = vooviContactWidget();
    widget.find('#contact_fio_view').html(vooviDisplayValue(contact.fio));
    widget.find('#contact_dol_view').html(vooviDisplayValue(contact.dol));
    widget.find('#contact_email_view').html(vooviDisplayValue(contact.email));
    widget.find('#contact_pol_view').html(vooviDisplayValue(vooviPolLabel(contact.pol)));
    vooviRenderPhoneDisplay(contact.tel);
    widget.find('#tele').text(vooviSplitPhones(contact.tel)[0] || '');
}

function vooviRenderContactEditor(contact) {
    contact = vooviNormalizeContact(contact);
    var widget = vooviContactWidget();
    widget.find('#name').val(contact.fio);
    vooviRenderPhoneEditor(contact.tel);
    vooviSetDolValue(contact.dol);
    widget.find('#contact_email').val(contact.email);
    widget.find('input[name="pol"]').prop('checked', false);
    if (contact.pol) {
        widget.find('input[name="pol"][value="' + contact.pol + '"]').prop('checked', true);
    }
}

function vooviSetContactState(contact) {
    vooviContactState = vooviNormalizeContact(contact);
    vooviContactWidget().data('contact-id', vooviContactState.id);
    vooviSetOriginalPhones(vooviContactState.tel);
    vooviRenderContactView(vooviContactState);
    vooviRenderContactEditor(vooviContactState);
}

function vooviSetContactMode(mode) {
    var saved = vooviIsSavedContact();
    var editing = mode === 'edit' || !saved;

    var widget = vooviContactWidget();
    widget.find('.komnete-contact-view').toggle(saved && !editing);
    widget.find('.komnete-contact-editor').toggle(editing);
    widget.find('.komnete-contact-edit').toggle(saved && !editing);
    widget.find('.komnete-contact-cancel').toggle(saved && editing);
    widget.find('.komnete-contact-save').text(saved ? 'Сохранить контакт' : 'Создать контакт');
    widget.find('#spisecho').toggle(!saved);

    if (!saved) {
        widget.find('#kontakti').show();
    } else if (editing) {
        widget.find('#kontakti').hide();
    }
}

function vooviCollectContact() {
    var widget = vooviContactWidget();
    return vooviNormalizeContact({
        id: widget.data('contact-id'),
        fio: widget.find('#name').val(),
        tel: vooviCollectPhones().join(', '),
        dol: widget.find('#dolrr').val(),
        email: widget.find('#contact_email').val(),
        pol: widget.find('input[name="pol"]:checked').val() || ''
    });
}

function addbq() {
    if (vooviIsSavedContact() && !vooviContactWidget().find('.komnete-contact-editor').is(':visible')) {
        vooviRenderContactEditor(vooviContactState);
        vooviSetContactMode('edit');
    }
    vooviCreatePhoneRow('', true);
}

function addb() {
    addbq();
}

function konttakt() {
    vooviContactWidget().find('#kontakti').toggle().focus();
}

function konTakti(str) {
    $.ajax({
        type: 'GET',
        url: 'pusya.php',
        dataType: 'json',
        data: {
            lico: str,
            tip: 'konttakt',
            rand: vooviKontaktContext.rand
        },
        success: function(obj) {
            vooviSetContactState(obj);
            vooviSetContactMode(vooviIsSavedContact() ? 'view' : 'edit');
            if (vooviIsSavedContact()) {
                vooviContactWidget().find('#kontakti').hide();
            }
        }
    });
}

$(function() {
    vooviContactState = vooviNormalizeContact(vooviContactState);
    vooviSetContactState(vooviContactState);
    vooviSetContactMode(vooviIsSavedContact() ? 'view' : 'edit');

    $(document).on('click', '.komnete-contact-select-toggle', function() {
        konttakt();
    });

    $(document).on('click', '.komnete-contact-edit', function() {
        vooviRenderContactEditor(vooviContactState);
        vooviSetContactMode('edit');
    });

    $(document).on('click', '.komnete-contact-cancel', function() {
        vooviRenderContactEditor(vooviContactState);
        vooviSetContactMode('view');
    });

    $(document).on('click', '.komnete-phone-remove', function() {
        if (vooviIsSavedContact() && !window.confirm('Удалить телефон из списка? Изменение применится только после кнопки "Сохранить контакт".')) {
            return;
        }

        var rows = vooviContactWidget().find('.komnete-phone-row');
        if (rows.length > 1) {
            $(this).closest('.komnete-phone-row').remove();
        } else {
            $(this).closest('.komnete-phone-row').find('.komnete-phone-input').val('');
        }
        vooviSyncPrimaryPhone();
    });

    $(document).on('input change', '.komnete-phone-input', function() {
        vooviSyncPrimaryPhone();
    });

    $(document).on('click', '.komnete-contact-save', function() {
        var contact = vooviCollectContact();
        var isSaved = vooviIsSavedContact();
        var action = isSaved ? 'kontaktupdate' : 'addkontakt';

        $.ajax({
            type: 'GET',
            url: 'pusya.php',
            dataType: 'json',
            data: {
                tip: action,
                lico: contact.id,
                kli: vooviKontaktContext.kli,
                ogrn: vooviKontaktContext.ogrn,
                rand: vooviKontaktContext.rand,
                fio: contact.fio,
                tel: contact.tel,
                email: contact.email,
                dol: contact.dol,
                pol: contact.pol
            },
            success: function(obj) {
                vooviSetContactState(obj);
                vooviSetContactMode('view');
                vooviContactWidget().find('#kontakti').hide();
            }
        });
    });
});
</script>
