--TEST--
suhosin input filter (suhosin.post.disallow_nul - RFC1867 version)
--INI--
suhosin.log.syslog=0
suhosin.log.sapi=0
suhosin.log.stdout=255
suhosin.log.script=0
suhosin.request.disallow_nul=0
suhosin.post.disallow_nul=1
--SKIPIF--
<?php include('skipif.inc'); ?>
--COOKIE--
--GET--
--POST_RAW--
Content-Type: multipart/form-data; boundary=---------------------------20896060251896012921717172737
-----------------------------20896060251896012921717172737
Content-Disposition: form-data; name="var1"

xx 1
-----------------------------20896060251896012921717172737
Content-Disposition: form-data; name="var2"

2
-----------------------------20896060251896012921717172737
Content-Disposition: form-data; name="var3"

xx 3
-----------------------------20896060251896012921717172737
Content-Disposition: form-data; name="var4"

4
-----------------------------20896060251896012921717172737--
--FILE--
<?php
var_dump($_POST);
?>
--EXPECTF--
array(2) {
  ["var2"]=>
  string(1) "2"
  ["var4"]=>
  string(1) "4"
}
ALERT - ASCII-NUL chars not allowed within POST variables - dropped variable 'var1' (attacker 'REMOTE_ADDR not set', file '%s')
ALERT - ASCII-NUL chars not allowed within POST variables - dropped variable 'var3' (attacker 'REMOTE_ADDR not set', file '%s')
ALERT - dropped 2 request variables - (0 in GET, 2 in POST, 0 in COOKIE) (attacker 'REMOTE_ADDR not set', file '%s')
