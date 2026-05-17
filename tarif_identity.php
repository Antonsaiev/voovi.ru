<?php

if (!function_exists('voovi_tarif_escape')) {
    function voovi_tarif_escape($value)
    {
        return mysql_real_escape_string((string)$value);
    }

    function voovi_tarif_nullable_int($value)
    {
        if ($value === null || $value === '' || $value === '0') {
            return null;
        }

        $value = (int)$value;

        return $value > 0 ? $value : null;
    }

    function voovi_tarif_sql_nullable_int($value)
    {
        $value = voovi_tarif_nullable_int($value);

        return $value === null ? 'NULL' : (string)$value;
    }

    function voovi_tarif_sql_text($value)
    {
        return "'" . voovi_tarif_escape($value) . "'";
    }

    function voovi_tarif_query($sql)
    {
        global $link;

        $result = mysql_query($sql);
        if (!$result) {
            die(mysql_error($link));
        }

        return $result;
    }

    function voovi_tarif_get_parent($parentId)
    {
        $parentId = (int)$parentId;
        $result = voovi_tarif_query("SELECT * FROM tarif_parent WHERE id = " . $parentId . " LIMIT 1");

        return mysql_fetch_assoc($result);
    }

    function voovi_tarif_get_current_version($parentId)
    {
        $parentId = (int)$parentId;
        $result = voovi_tarif_query("SELECT * FROM tarif WHERE sync_id = " . $parentId . " AND del = '0' ORDER BY id DESC LIMIT 1");
        $version = mysql_fetch_assoc($result);

        if (!$version) {
            $result = voovi_tarif_query("SELECT * FROM tarif WHERE sync_id = " . $parentId . " ORDER BY id DESC LIMIT 1");
            $version = mysql_fetch_assoc($result);
        }

        return $version;
    }

    function voovi_tarif_get_by_id($tarifId)
    {
        $tarifId = (int)$tarifId;
        $result = voovi_tarif_query("SELECT * FROM tarif WHERE id = " . $tarifId . " LIMIT 1");

        return mysql_fetch_assoc($result);
    }

    function voovi_tarif_normalize_data($data, $baseVersion, $parent)
    {
        if (!$baseVersion) {
            $baseVersion = array();
        }
        if (!$parent) {
            $parent = array();
        }

        $name = isset($data['name']) ? $data['name'] : (isset($parent['name']) ? $parent['name'] : (isset($baseVersion['name']) ? $baseVersion['name'] : ''));
        $value = isset($data['value']) ? $data['value'] : (isset($data['comment']) ? $data['comment'] : (isset($baseVersion['value']) ? $baseVersion['value'] : ''));
        $productId = isset($data['product_id']) ? $data['product_id'] : (isset($data['parent']) ? $data['parent'] : (isset($parent['product_id']) ? $parent['product_id'] : (isset($baseVersion['parent']) ? $baseVersion['parent'] : '')));
        $price = isset($data['price']) ? $data['price'] : (isset($baseVersion['price']) ? $baseVersion['price'] : '');
        $groupTarif = isset($data['group_tarif']) ? $data['group_tarif'] : (isset($parent['group_tarif']) ? $parent['group_tarif'] : (isset($baseVersion['group_tarif']) ? $baseVersion['group_tarif'] : null));
        $productModel = isset($data['product_model']) ? $data['product_model'] : (isset($parent['product_model']) ? $parent['product_model'] : (isset($baseVersion['product_model']) ? $baseVersion['product_model'] : null));
        $gen = isset($data['gen']) ? (int)$data['gen'] : (isset($baseVersion['gen']) ? (int)$baseVersion['gen'] : 0);

        return array(
            'name' => $name,
            'value' => $value,
            'parent' => $productId,
            'price' => $price,
            'shtuk' => isset($baseVersion['shtuk']) && $baseVersion['shtuk'] !== '' ? $baseVersion['shtuk'] : 'шт',
            'nds' => isset($baseVersion['nds']) ? $baseVersion['nds'] : '0',
            'gen' => $gen ? '1' : '0',
            'install' => isset($baseVersion['install']) ? $baseVersion['install'] : '0',
            'turbo' => isset($baseVersion['turbo']) ? $baseVersion['turbo'] : '0',
            'del' => '0',
            'road' => isset($baseVersion['road']) ? $baseVersion['road'] : '',
            'kkt' => isset($baseVersion['kkt']) ? $baseVersion['kkt'] : '',
            'markirovka' => isset($baseVersion['markirovka']) ? $baseVersion['markirovka'] : '',
            'online_kassi' => isset($baseVersion['online_kassi']) ? $baseVersion['online_kassi'] : '',
            'savoir' => isset($baseVersion['savoir']) ? $baseVersion['savoir'] : '',
            'savoir_dopi' => isset($baseVersion['savoir_dopi']) ? $baseVersion['savoir_dopi'] : '',
            'sklad' => isset($baseVersion['sklad']) ? $baseVersion['sklad'] : '',
            'razrabotka' => isset($baseVersion['razrabotka']) ? $baseVersion['razrabotka'] : '',
            'group_tarif' => voovi_tarif_nullable_int($groupTarif),
            'product_model' => voovi_tarif_nullable_int($productModel),
        );
    }

    function voovi_tarif_insert_version($parentId, $data, $baseVersion, $parent)
    {
        $parentId = (int)$parentId;
        $data = voovi_tarif_normalize_data($data, $baseVersion, $parent);

        $sql = "INSERT INTO tarif (
            sync_id,
            name,
            value,
            parent,
            price,
            shtuk,
            nds,
            gen,
            install,
            turbo,
            del,
            road,
            kkt,
            markirovka,
            online_kassi,
            savoir,
            savoir_dopi,
            sklad,
            razrabotka,
            group_tarif,
            product_model
        ) VALUES (
            " . $parentId . ",
            " . voovi_tarif_sql_text($data['name']) . ",
            " . voovi_tarif_sql_text($data['value']) . ",
            " . voovi_tarif_sql_text($data['parent']) . ",
            " . voovi_tarif_sql_text($data['price']) . ",
            " . voovi_tarif_sql_text($data['shtuk']) . ",
            " . voovi_tarif_sql_text($data['nds']) . ",
            " . voovi_tarif_sql_text($data['gen']) . ",
            " . voovi_tarif_sql_text($data['install']) . ",
            " . voovi_tarif_sql_text($data['turbo']) . ",
            '0',
            " . voovi_tarif_sql_text($data['road']) . ",
            " . voovi_tarif_sql_text($data['kkt']) . ",
            " . voovi_tarif_sql_text($data['markirovka']) . ",
            " . voovi_tarif_sql_text($data['online_kassi']) . ",
            " . voovi_tarif_sql_text($data['savoir']) . ",
            " . voovi_tarif_sql_text($data['savoir_dopi']) . ",
            " . voovi_tarif_sql_text($data['sklad']) . ",
            " . voovi_tarif_sql_text($data['razrabotka']) . ",
            " . ($data['group_tarif'] === null ? 'NULL' : (int)$data['group_tarif']) . ",
            " . ($data['product_model'] === null ? 'NULL' : (int)$data['product_model']) . "
        )";

        voovi_tarif_query($sql);

        return mysql_insert_id();
    }

    function voovi_tarif_parent_update_sql($parentId, $data, $parent, $baseVersion)
    {
        $normalized = voovi_tarif_normalize_data($data, $baseVersion, $parent);
        $comment = isset($data['comment']) ? $data['comment'] : (isset($parent['comment']) ? $parent['comment'] : $normalized['value']);

        return "UPDATE tarif_parent SET
            name = " . voovi_tarif_sql_text($normalized['name']) . ",
            group_tarif = " . ($normalized['group_tarif'] === null ? 'NULL' : (int)$normalized['group_tarif']) . ",
            product_model = " . ($normalized['product_model'] === null ? 'NULL' : (int)$normalized['product_model']) . ",
            product_id = " . voovi_tarif_sql_nullable_int($normalized['parent']) . ",
            comment = " . voovi_tarif_sql_text($comment) . ",
            updated_at = NOW()
        WHERE id = " . (int)$parentId;
    }

    function voovi_tarif_create($data)
    {
        voovi_tarif_query("LOCK TABLES tarif WRITE, tarif_parent WRITE");

        $parentId = 0;
        $tarifId = 0;

        $normalized = voovi_tarif_normalize_data($data, array(), array());
        $comment = isset($data['comment']) ? $data['comment'] : $normalized['value'];

        voovi_tarif_query("INSERT INTO tarif_parent (
            name,
            group_tarif,
            product_model,
            product_id,
            comment,
            active
        ) VALUES (
            " . voovi_tarif_sql_text($normalized['name']) . ",
            " . ($normalized['group_tarif'] === null ? 'NULL' : (int)$normalized['group_tarif']) . ",
            " . ($normalized['product_model'] === null ? 'NULL' : (int)$normalized['product_model']) . ",
            " . voovi_tarif_sql_nullable_int($normalized['parent']) . ",
            " . voovi_tarif_sql_text($comment) . ",
            1
        )");
        $parentId = mysql_insert_id();
        $tarifId = voovi_tarif_insert_version($parentId, $data, array(), array());

        voovi_tarif_query("UNLOCK TABLES");

        return array('parent_id' => $parentId, 'tarif_id' => $tarifId);
    }

    function voovi_tarif_create_version($parentId, $data)
    {
        $parentId = (int)$parentId;

        voovi_tarif_query("LOCK TABLES tarif WRITE, tarif_parent WRITE");

        $parent = voovi_tarif_get_parent($parentId);
        if (!$parent) {
            voovi_tarif_query("UNLOCK TABLES");
            die('tarif_parent not found');
        }

        $current = voovi_tarif_get_current_version($parentId);

        voovi_tarif_query(voovi_tarif_parent_update_sql($parentId, $data, $parent, $current));
        voovi_tarif_query("UPDATE tarif SET del = '1' WHERE sync_id = " . $parentId . " AND del = '0'");

        $parent = voovi_tarif_get_parent($parentId);
        $tarifId = voovi_tarif_insert_version($parentId, $data, $current, $parent);

        voovi_tarif_query("UNLOCK TABLES");

        return array('parent_id' => $parentId, 'tarif_id' => $tarifId);
    }

    function voovi_tarif_archive_parent($parentId)
    {
        $parentId = (int)$parentId;

        voovi_tarif_query("LOCK TABLES tarif WRITE, tarif_parent WRITE");
        voovi_tarif_query("UPDATE tarif_parent SET active = 0, updated_at = NOW() WHERE id = " . $parentId);
        voovi_tarif_query("UPDATE tarif SET del = '1' WHERE sync_id = " . $parentId . " AND del = '0'");
        voovi_tarif_query("UNLOCK TABLES");
    }

    function voovi_tarif_archive_by_tarif_id($tarifId)
    {
        $tarif = voovi_tarif_get_by_id($tarifId);
        if (!$tarif) {
            return;
        }

        if (!empty($tarif['sync_id'])) {
            voovi_tarif_archive_parent($tarif['sync_id']);
        } else {
            voovi_tarif_query("UPDATE tarif SET del = '1' WHERE id = " . (int)$tarifId);
        }
    }

    function voovi_tarif_set_gen_by_tarif_id($tarifId, $gen)
    {
        $tarif = voovi_tarif_get_by_id($tarifId);
        if (!$tarif) {
            die('tarif not found');
        }

        if (empty($tarif['sync_id'])) {
            voovi_tarif_query("UPDATE tarif SET gen = " . voovi_tarif_sql_text($gen ? '1' : '0') . " WHERE id = " . (int)$tarifId);

            return array('parent_id' => 0, 'tarif_id' => (int)$tarifId);
        }

        return voovi_tarif_create_version($tarif['sync_id'], array(
            'gen' => $gen ? 1 : 0,
        ));
    }

    function voovi_tarif_parent_list_query($productId)
    {
        $productId = (int)$productId;

        return "SELECT
            tp.id AS tarif_parent_id,
            tp.name AS parent_name,
            tp.comment,
            tp.group_tarif AS parent_group_tarif,
            tp.product_model AS parent_product_model,
            tp.product_id,
            tp.active,
            t.id AS tarif_id,
            t.name AS tarif_name,
            t.value,
            t.price,
            t.gen,
            t.group_tarif AS tarif_group_tarif,
            t.product_model AS tarif_product_model,
            t.date_create,
            gt.name_group,
            pm.name AS model_name,
            pt.name AS type_name
        FROM tarif_parent tp
        LEFT JOIN tarif t ON t.sync_id = tp.id AND t.del = '0'
        LEFT JOIN group_tarif gt ON gt.id_group = tp.group_tarif
        LEFT JOIN product_model pm ON pm.id = tp.product_model
        LEFT JOIN product_type pt ON pt.id = pm.product_type
        WHERE tp.active = 1
          AND (tp.product_id = " . $productId . " OR (tp.product_id IS NULL AND t.parent = " . voovi_tarif_sql_text($productId) . "))
        ORDER BY tp.id DESC";
    }

    function voovi_tarif_active_for_product_query($productId)
    {
        $productId = (int)$productId;

        return "SELECT t.*
        FROM tarif_parent tp
        INNER JOIN tarif t ON t.sync_id = tp.id AND t.del = '0'
        WHERE tp.active = 1
          AND (tp.product_id = " . $productId . " OR (tp.product_id IS NULL AND t.parent = " . voovi_tarif_sql_text($productId) . "))";
    }

    function voovi_tarif_schet_query($productId, $rand)
    {
        $productId = (int)$productId;
        $rand = (string)$rand;
        $active = voovi_tarif_active_for_product_query($productId);

        if ($rand === '') {
            return $active . " ORDER BY t.id";
        }

        $selected = "SELECT t.*
        FROM schet s
        INNER JOIN tarif t ON t.id = s.prod
        WHERE s.del = '0'
          AND s.rand = " . voovi_tarif_sql_text($rand) . "
          AND s.produkt = " . voovi_tarif_sql_text($productId);

        return "SELECT * FROM (" . $active . " UNION " . $selected . ") AS voovi_tarif_list ORDER BY id";
    }
}
