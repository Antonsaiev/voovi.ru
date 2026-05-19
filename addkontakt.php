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
$primaryPhone = count($contactPhones) ? voovi_contact_format_phone($contactPhones[0]) : '';
$contactContext = array(
    'kli' => isset($_GET['kli']) ? $_GET['kli'] : '',
    'rand' => isset($_GET['rand']) ? $_GET['rand'] : ''
);
?>
    <span id="tele" style="display:none;"><?php echo htmlspecialchars($primaryPhone, ENT_QUOTES, 'UTF-8'); ?></span>

    <select name="kontakti" class="form-control komnete-contact-select" id="kontakti" onchange="konTakti(this.value)">
        <option value="0" <?php if ($contactId === 0) { echo 'selected'; } ?>>Выберите контакт</option>
        <?php
        $contactsCount = 0;
        $query2 = mysql_query("SELECT * from klient_ogrn WHERE idkli = '".mysql_real_escape_string(isset($_GET['kli']) ? $_GET['kli'] : '')."' ORDER BY id DESC");
        while($row2 = mysql_fetch_array($query2)) {
            $query3 = mysql_query("SELECT * from klient WHERE id = '".$row2['klient']."' ORDER BY id DESC");
            while($row3 = mysql_fetch_array($query3)) {
                $contactsCount++;
                $selected = intval($row3['id']) === $contactId ? ' selected' : '';
                echo '<option value="'.intval($row3['id']).'"'.$selected.'>';
                echo htmlspecialchars($row3['fio'], ENT_QUOTES, 'UTF-8')," (",htmlspecialchars($row3['dol'], ENT_QUOTES, 'UTF-8'),":",htmlspecialchars($row3['tel'], ENT_QUOTES, 'UTF-8'),")";
                echo '</option>';
            }
        }
        if ($contactsCount === 0) {
            echo '<option value="0" disabled>Контактов нет</option>';
        }
        ?>
    </select>

    <div class="komnete-contact-empty" id="spisecho" style="<?php if ($contactId > 0) { echo 'display:none;'; } ?>">Выберите контакт из списка.</div>
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

function vooviSetContactState(contact) {
    vooviContactState = vooviNormalizeContact(contact);
    vooviContactWidget().data('contact-id', vooviContactState.id);
    vooviRenderContactView(vooviContactState);
}

function vooviSetContactMode() {
    var saved = parseInt(vooviContactWidget().data('contact-id'), 10) > 0;
    vooviContactWidget().find('.komnete-contact-view').toggle(saved);
    vooviContactWidget().find('#spisecho').toggle(!saved);
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
            vooviSetContactMode();
            vooviContactWidget().find('#kontakti').val(vooviContactState.id);
        }
    });
}

$(function() {
    vooviContactState = vooviNormalizeContact(vooviContactState);
    vooviSetContactState(vooviContactState);
    vooviSetContactMode();
});
</script>
