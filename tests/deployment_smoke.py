"""Checks links/assets/session paths at a root or subfolder deployment."""
import os
import urllib.parse
import urllib.request
import urllib.error
BASE=os.environ.get('MVC_BASE_URL','http://127.0.0.1:8080').rstrip('/')
prefix=urllib.parse.urlsplit(BASE).path.rstrip('/')
with urllib.request.urlopen(BASE+'/') as response:
    body=response.read().decode()
    for path in ['/css/styles.css','/js/login.js','/img/logo.webp','/public/index.php?route=login.submit']:
        assert prefix+path in body,path
for path in ['/css/styles.css','/js/login.js','/img/logo.webp']:
    with urllib.request.urlopen(BASE+path) as response:assert response.status==200
with urllib.request.urlopen(BASE+'/api/v1/auth/csrf') as response:
    assert 'path='+prefix+'/' in response.headers['Set-Cookie']
for path in ['/config/local.example.php','/storage/uploads/no-file.pdf']:
    try:
        urllib.request.urlopen(BASE+path)
        raise AssertionError('Private path exposed: '+path)
    except urllib.error.HTTPError as error:assert error.code in [403,404]
print('PASS deployment URLs, assets, cookie path and private paths at '+(prefix or '/'))
