"""HTTP integration tests against a running development instance and its database.
Creates isolated fixtures and removes them, including after assertion failures.
Run: PHP_BINARY=/path/to/php python3 tests/mvc_smoke.py
"""
import http.cookiejar
import json
import os
from pathlib import Path
import re
import secrets
import subprocess
import urllib.error
import urllib.parse
import urllib.request

ROOT = Path(__file__).resolve().parents[1]
PHP = os.environ.get('PHP_BINARY', 'php')
BASE = os.environ.get('MVC_BASE_URL', 'http://127.0.0.1:8080')
class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, *args):
        return None

def client():
    return urllib.request.build_opener(NoRedirect(), urllib.request.HTTPCookieProcessor(http.cookiejar.CookieJar()))

def request(opener, route, data=None, legacy=None):
    url = BASE + (legacy if legacy else '/public/index.php?' + urllib.parse.urlencode({'route': route}))
    req = urllib.request.Request(url, data=None if data is None else urllib.parse.urlencode(data).encode())
    try:
        response = opener.open(req)
    except urllib.error.HTTPError as error:
        response = error
    with response:
        body = response.read().decode()
        assert 'Fatal error' not in body and 'Warning:' not in body, body
        return response.status, body, response.headers

def token(body):
    return re.search(r'name="_token" value="([a-f0-9]+)"', body).group(1)

def sql(code, *args):
    return subprocess.check_output([PHP, '-r', 'require "vendor/autoload.php"; $db=App\\Models\\Database::connect(); ' + code, *args], cwd=ROOT, text=True).strip()

username = 'mvc-test-' + secrets.token_hex(8)
password = secrets.token_hex(16)
name = 'MVC test <script>' + username
sql('$s=$db->prepare("INSERT INTO usuario (UsuarioNombre,UsuarioUser,UsuarioPwd,UsuarioEstado) VALUES (?, ?, SHA1(?), 1)"); $s->bind_param("sss",$argv[1],$argv[1],$argv[2]); $s->execute();', username, password)
try:
    guest = client()
    assert request(guest, 'especies')[0] == 303
    assert request(guest, 'missing')[0] == 404
    assert request(guest, 'especies.store')[0] == 405
    assert request(guest, 'login.submit', {'txtUser': username, 'txtPwd': password})[0] == 403
    auth = client()
    status, body, _ = request(auth, 'login')
    assert status == 200
    csrf = token(body)
    assert request(auth, 'login.submit', {'_token': csrf, 'txtUser': "' OR 1=1 --", 'txtPwd': password})[0] == 422
    status, _, headers = request(auth, 'login.submit', {'_token': csrf, 'txtUser': username, 'txtPwd': password})
    assert status == 303 and 'route=especies' in headers['Location']
    assert sql('$s=$db->prepare("SELECT UsuarioPwd FROM usuario WHERE UsuarioUser=?"); $s->bind_param("s",$argv[1]); $s->execute(); echo password_verify($argv[2],$s->get_result()->fetch_assoc()["UsuarioPwd"]) ? "OK" : "FAIL";', username, password) == 'OK'
    status, body, _ = request(auth, 'especies')
    assert status == 200
    csrf = token(body)
    assert request(auth, 'especies.store', {'txtNombre': name})[0] == 403
    for bad_name in ['', 'x' * 101]:
        assert request(auth, 'especies.store', {'_token': csrf, 'txtNombre': bad_name})[0] == 422
    assert request(auth, 'especies.store', {'_token': csrf, 'txtNombre': name})[0] == 303
    row = json.loads(sql('$s=$db->prepare("SELECT * FROM especies WHERE EspecieNombre=?"); $s->bind_param("s",$argv[1]); $s->execute(); echo json_encode($s->get_result()->fetch_assoc());', name))
    species_id = row['EspecieId']
    body = request(auth, 'especies')[1]
    assert '&lt;script&gt;' in body and name not in body
    assert request(auth, 'unused', legacy='/frmEditarEspecie.php?id=' + str(species_id))[0] == 200
    assert request(auth, 'unused', legacy='/eliminarEspecie.php?id=' + str(species_id))[0] == 405
    assert request(auth, 'especies.update', {'_token': csrf, 'txtId': species_id, 'txtNombre': name + '-edited'})[0] == 303
    assert name.replace('<script>', '&lt;script&gt;') + '-edited' in request(auth, 'especies')[1]
    assert request(auth, 'especies.destroy', {'_token': csrf, 'txtId': species_id})[0] == 303
    assert sql('$s=$db->prepare("SELECT EspecieEstado FROM especies WHERE EspecieId=?"); $s->bind_param("i",$argv[1]); $s->execute(); echo $s->get_result()->fetch_assoc()["EspecieEstado"];', str(species_id)) == '0'
    assert request(auth, 'unused', legacy='/frmEditarEspecie.php?id=' + str(species_id))[0] == 404
    assert request(auth, 'logout', {'_token': csrf})[0] == 303
    assert request(auth, 'especies')[0] == 303
    # A second login exercises bcrypt hashes after legacy SHA-1 migration.
    _, body, _ = request(auth, 'login')
    assert request(auth, 'login.submit', {'_token': token(body), 'txtUser': username, 'txtPwd': password})[0] == 303
    assert request(auth, 'unused', legacy='/dashboard.php')[0] == 200
    print('PASS: routing, auth, SHA-1 migration, bcrypt login, CSRF, validation, escaping, species CRUD, soft delete and legacy compatibility')
finally:
    sql('$s=$db->prepare("DELETE FROM especies WHERE EspecieNombre IN (?, ?)"); $edited=$argv[1]."-edited"; $s->bind_param("ss",$argv[1],$edited); $s->execute(); $s=$db->prepare("DELETE FROM usuario WHERE UsuarioUser=?"); $s->bind_param("s",$argv[2]); $s->execute();', name, username)
