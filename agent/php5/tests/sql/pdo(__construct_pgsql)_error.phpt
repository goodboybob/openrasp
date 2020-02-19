--TEST--
hook PDO::__construct
--SKIPIF--
<?php 
$plugin = <<<EOF
RASP.algorithmConfig = {
    sql_exception: {
        name:      '算法3 - 记录数据库异常',
        action:    'log',
        reference: 'https://rasp.baidu.com/doc/dev/official.html#sql-exception',
        pgsql: {
            error_code: [
                '08006'
            ]
        }
    }
}
plugin.register('sql_exception', params => {
    assert(params.error_code == '08006')
    return block
})
EOF;
include(__DIR__.'/../skipif.inc');
if (!extension_loaded("pgsql")) die("Skipped: pgsql extension required.");
if (!extension_loaded("pdo")) die("Skipped: pdo extension required.");
?>
--INI--
openrasp.root_dir=/tmp/openrasp
--FILE--
<?php
include(__DIR__.'/../timezone.inc');
new PDO('pgsql:host=127.0.0.1;port=5432;user=nonexist;password=nonexist');
?>
--EXPECTREGEX--
<\/script><script>location.href="http[s]?:\/\/.*?request_id=[0-9a-f]{32}"<\/script>