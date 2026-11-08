<?php
// Only the dedicated scratch database. No production data or credentials in this file.
$database = getenv('DB_NAME');
if ($database !== 'voovi_tariff_versions_test') { throw new RuntimeException('Dedicated test database required'); }
mysql_connect(getenv('DB_HOST'), getenv('DB_LOGIN'), getenv('DB_PASSWORD')) or die('Test database unavailable');
mysql_select_db($database);
mysql_query('SET NAMES utf8');
mysql_query("SET SESSION sql_mode='STRICT_ALL_TABLES'");
require dirname(__DIR__).'/tarif_identity.php';

function check($condition, $message) {
    if (!$condition) { throw new RuntimeException($message); }
}
function row($sql) { return mysql_fetch_assoc(voovi_tarif_query($sql)); }
function fixture() {
    voovi_tarif_query('DELETE FROM tarif');
    voovi_tarif_query('DELETE FROM tarif_parent');
    return voovi_tarif_create(array('name'=>'Fixture', 'value'=>'original', 'comment'=>'original',
        'product_id'=>424, 'price'=>'2900', 'group_tarif'=>3, 'product_model'=>7, 'gen'=>0));
}

$tests = 0;
try {
    $created = fixture();
    $parent = $created['parent_id']; $old = $created['tarif_id'];
    $same = voovi_tarif_create_version($parent, array('price'=>'2900.00'));
    check(!$same['changed'] && $same['tarif_id']==$old, 'Unchanged price creates a version');
    check(row('SELECT COUNT(*) AS n FROM tarif')['n']==1, 'Noop changed row count'); $tests++;

    $new = voovi_tarif_create_version($parent, array('price'=>'3100','group_tarif'=>8,'product_model'=>9,'value'=>'updated'));
    check($new['changed'] && $new['tarif_id']!=$old, 'Price change did not create version');
    $previous=row('SELECT * FROM tarif WHERE id='.$old); $latest=row('SELECT * FROM tarif WHERE id='.$new['tarif_id']);
    check($previous['price']=='2900' && $previous['group_tarif']==3 && $previous['product_model']==7 && $previous['del']=='1', 'Old invoice metadata changed');
    $p=row('SELECT * FROM tarif_parent WHERE id='.$parent);
    check($p['group_tarif']==8 && $p['product_model']==9 && $p['comment']=='updated' && $latest['value']=='updated', 'Parent and version diverged'); $tests++;

    $again = voovi_tarif_create_version($parent, array('price'=>'3100','group_tarif'=>8,'product_model'=>9,'value'=>'updated'));
    check(!$again['changed'] && $again['tarif_id']==$new['tarif_id'], 'Repeated POST created duplicate'); $tests++;

    voovi_tarif_query('UPDATE tarif_parent SET group_tarif=99,product_model=99,comment=\'stale\' WHERE id='.$parent);
    $fixed=voovi_tarif_create_version($parent,array());
    $p=row('SELECT * FROM tarif_parent WHERE id='.$parent);
    check(!$fixed['changed'] && $p['group_tarif']==8 && $p['product_model']==9 && $p['comment']=='updated','Noop did not repair parent from current version'); $tests++;

    $cleared=voovi_tarif_create_version($parent,array('product_model'=>null));
    check(row('SELECT product_model FROM tarif WHERE id='.$cleared['tarif_id'])['product_model']===null && row('SELECT product_model FROM tarif_parent WHERE id='.$parent)['product_model']===null, 'Explicit NULL not synchronized'); $tests++;

    $beforeParent=row('SELECT * FROM tarif_parent WHERE id='.$parent);
    $beforeRows=row('SELECT COUNT(*) AS n FROM tarif')['n'];
    try {
        voovi_tarif_create_version($parent,array('price'=>str_repeat('x',367),'group_tarif'=>18,'comment'=>'must rollback'));
        throw new LogicException('Expected real INSERT failure');
    } catch (RuntimeException $error) {}
    check(row('SELECT * FROM tarif_parent WHERE id='.$parent)==$beforeParent, 'Parent not rolled back after INSERT failure');
    check(row('SELECT COUNT(*) AS n FROM tarif')['n']==$beforeRows, 'Failed save left a row');
    check(row('SELECT COUNT(*) AS n FROM tarif WHERE sync_id='.$parent." AND del='0'")['n']==1,'Failed save lost active version'); $tests++;

    // Simulate a database failure after INSERT, at archive step.
    voovi_tarif_query("CREATE TRIGGER tariff_test_update_failure BEFORE UPDATE ON tarif FOR EACH ROW SET NEW.price=NULL");
    try {
        try {
            voovi_tarif_create_version($parent,array('price'=>'3300','comment'=>'must rollback too'));
            throw new LogicException('Expected real archive UPDATE failure');
        } catch (RuntimeException $error) {}
    } finally { voovi_tarif_query('DROP TRIGGER tariff_test_update_failure'); }
    check(row('SELECT * FROM tarif_parent WHERE id='.$parent)==$beforeParent,'Parent not rolled back after archive failure');
    check(row('SELECT COUNT(*) AS n FROM tarif')['n']==$beforeRows,'New version survived failed archive');
    check(row('SELECT COUNT(*) AS n FROM tarif WHERE sync_id='.$parent." AND del='0'")['n']==1,'Archive failure changed active versions'); $tests++;

    voovi_tarif_query('ALTER TABLE tarif ENGINE=MyISAM');
    try {
        try { voovi_tarif_create_version($parent,array('price'=>'3400')); throw new LogicException('MyISAM allowed'); }
        catch (RuntimeException $error) {}
        check(row('SELECT COUNT(*) AS n FROM tarif')['n']==$beforeRows,'MyISAM guard wrote data');
    } finally { voovi_tarif_query('ALTER TABLE tarif ENGINE=InnoDB'); } $tests++;

    echo 'PASS: '.$tests." PHP tariff transaction scenarios\n";
} catch (Exception $error) {
    mysql_query('ROLLBACK');
    fwrite(STDERR, $error->getMessage()."\n");
    exit(1);
}
