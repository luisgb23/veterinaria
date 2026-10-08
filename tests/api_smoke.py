"""API HTTP integration suite. Uses a development DB and removes its fixtures."""
import base64
import http.cookiejar
import json
import os
from pathlib import Path
import secrets
import subprocess
import urllib.request
import urllib.error
PHP=os.environ.get('PHP_BINARY','php')
ROOT=Path(__file__).resolve().parents[1]
BASE=os.environ.get('MVC_BASE_URL','http://127.0.0.1:8080')
class NoRedirect(urllib.request.HTTPRedirectHandler):
 def redirect_request(self,*args):return None

def client():return urllib.request.build_opener(NoRedirect(),urllib.request.HTTPCookieProcessor(http.cookiejar.CookieJar()))
def sql(code,*args):return subprocess.check_output([PHP,'-r','require "vendor/autoload.php"; $db=App\\Models\\Database::connect(); '+code,*map(str,args)],cwd=ROOT,text=True).strip()
def call(c,path,method='GET',body=None,csrf=None,headers=None,raw=None):
 h={'Accept':'application/json',**(headers or {})}
 if csrf:h['X-CSRF-Token']=csrf
 if body is not None:h['Content-Type']='application/json';raw=json.dumps(body).encode()
 req=urllib.request.Request(BASE+'/api/v1/'+path,data=raw,method=method,headers=h)
 try:r=c.open(req)
 except urllib.error.HTTPError as e:r=e
 with r:
  content=r.read()
  if r.headers.get('Content-Type','').startswith('application/json'):content=json.loads(content)
  return r.status,content,r.headers
marker='api-'+secrets.token_hex(8);password=secrets.token_hex(16);ids={};uploads=[]
user_id=int(sql('$s=$db->prepare("INSERT INTO usuario (UsuarioNombre,UsuarioUser,UsuarioPwd,UsuarioEstado) VALUES (?,?,SHA1(?),1)"); $s->execute([$argv[1],$argv[1],$argv[2]]); echo $db->insert_id;',marker,password))
try:
 guest=client();auth=client()
 assert call(guest,'mascotas')[0]==401
 assert call(guest,'auth/me')[0]==401
 assert call(guest,'no-existe')[0]==404
 assert call(guest,'especies/1','PUT')[0]==405
 assert call(guest,'auth/csrf',headers={'Origin':'https://untrusted.invalid'})[0]==403
 _,body,_=call(auth,'auth/csrf',headers={'Origin':BASE});csrf=body['data']['csrf_token']
 assert call(auth,'auth/login','POST',{'usuario':marker,'password':password})[0]==403
 assert call(auth,'auth/login','POST',{'usuario':marker,'password':'wrong'},csrf)[0]==401
 assert call(auth,'auth/login','POST',csrf=csrf,raw=b'{',headers={'Content-Type':'application/json'})[0]==400
 assert call(auth,'auth/login','POST',csrf=csrf,raw=b'[]',headers={'Content-Type':'application/json'})[0]==400
 assert call(auth,'auth/login','POST',csrf=csrf,raw=b'x=1',headers={'Content-Type':'application/x-www-form-urlencoded'})[0]==415
 status,body,_=call(auth,'auth/login','POST',{'usuario':marker,'password':password},csrf)
 assert status==200;csrf=body['csrf_token']
 assert call(auth,'auth/me')[1]['data']['id']==user_id
 cases=[
 ('especies',{'nombre':marker}),
 ('propietarios',{'nombre':marker,'apellido':'Prueba','email':'api@example.com','telefono':'123456789','direccion':'<script>texto</script>'}),
 ('mascotas',{'nombre':marker,'fecha_nacimiento':'2020-01-01'}),
 ('cuotas',{'fecha':'2026-01-01','valor':'250','observacion':marker}),
 ('consultas',{'fecha':'2026-01-01','motivo':marker,'diagnostico':'Control','tratamiento':'Reposo'}),
 ('vacunas',{'fecha_ingreso':'2026-01-01','fecha_vencimiento':'2026-12-01'})]
 for entity,data in cases:
  if entity=='mascotas':data.update(especie_id=ids['especies'],propietario_id=ids['propietarios'])
  if entity in ['cuotas','consultas','vacunas']:data['mascota_id']=ids['mascotas']
  assert call(auth,entity,'POST',{},csrf)[0]==422
  assert call(auth,entity,'POST',data,'invalid')[0]==403
  status,body,headers=call(auth,entity,'POST',data,csrf)
  assert status==201,(entity,status,body)
  row=body['data'];ids[entity]=row['id']
  if entity=='cuotas':assert row['valor']=='250'
  assert headers['Location'].endswith('/'+str(row['id']))
  assert call(auth,entity+'/'+str(row['id']))[1]['data']['id']==row['id']
  assert call(auth,entity+'?page=1&per_page=1')[1]['meta']['per_page']==1
  assert call(auth,entity+'?per_page=101')[0]==422
  assert call(auth,entity+'?unknown=x')[0]==422
  key=next(k for k in data if not k.endswith('_id'))
  assert call(auth,entity+'/'+str(row['id']),'PATCH',{key:data[key]},csrf)[0]==200
  assert call(auth,entity+'/'+str(row['id']),'PATCH',{},csrf)[0]==422
  assert call(auth,entity+'/'+str(row['id']),'PATCH',{'injected_column':1},csrf)[0]==422
  assert call(auth,entity+'/999999999')[0]==404
  print('PASS API CRUD, pagination, validation:',entity)
 assert call(auth,'mascotas?propietario_id='+str(ids['propietarios']))[1]['meta']['total']==1
 assert call(auth,'especies?buscar='+marker)[1]['meta']['total']==1
 assert call(auth,'especies','POST',{'nombre':123},csrf)[0]==422
 assert call(auth,'cuotas?mascota_id='+str(ids['mascotas'])+'&desde=2027-01-01')[1]['meta']['total']==0
 assert call(auth,'consultas?desde=2026-02-31')[0]==422
 assert call(auth,'vacunas?buscar=texto')[0]==422
 assert call(auth,'vacunas/'+str(ids['vacunas']),'PATCH',{'fecha_vencimiento':'2025-01-01'},csrf)[0]==422
 assert call(auth,'mascotas/'+str(ids['mascotas']),'PATCH',{'especie_id':99999999},csrf)[0]==422
 assert call(auth,'propietarios/'+str(ids['propietarios']),'PATCH',{'email':False},csrf)[0]==422
 assert call(auth,'dashboard/resumen')[1]['data']['mascotas']>=1
 assert call(auth,'vacunas/vencimientos?mascota_id='+str(ids['mascotas']))[1]['data'][0]['id']==ids['vacunas']
 status,pdf,headers=call(auth,'consultas/'+str(ids['consultas'])+'/pdf')
 assert status==200 and pdf.startswith(b'%PDF-') and headers['Content-Type'].startswith('application/pdf')
 png=base64.b64decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+a5XsAAAAASUVORK5CYII=')
 boundary='api'+secrets.token_hex(8)
 raw=(f'--{boundary}\r\nContent-Disposition: form-data; name="slot"\r\n\r\n1\r\n--{boundary}\r\nContent-Disposition: form-data; name="archivo"; filename="test.png"\r\nContent-Type: image/png\r\n\r\n'.encode()+png+f'\r\n--{boundary}--\r\n'.encode())
 path='consultas/'+str(ids['consultas'])+'/adjuntos'
 status,body,_=call(auth,path,'POST',csrf=csrf,raw=raw,headers={'Content-Type':'multipart/form-data; boundary='+boundary})
 assert status==201,(status,body)
 uploads.append(sql('$s=$db->prepare("SELECT ConsultaArchivo1 FROM consultas WHERE ConsultaId=?"); $s->execute([$argv[1]]); echo $s->get_result()->fetch_assoc()["ConsultaArchivo1"];',ids['consultas']))
 assert call(auth,path+'/1')[1]==png
 assert call(guest,path+'/1')[0]==401
 assert call(auth,path+'/4')[0]==404
 assert call(auth,path+'/1','DELETE',csrf=csrf)[0]==204
 assert call(auth,path+'/1')[0]==404
 for entity,_ in reversed(cases):
  assert call(auth,entity+'/'+str(ids[entity]),'DELETE',csrf=csrf)[0]==204
  assert call(auth,entity+'/'+str(ids[entity]))[0]==404
 assert call(auth,'auth/logout','POST',csrf=csrf)[0]==204
 assert call(auth,'auth/me')[0]==401
 print('PASS API filters, JSON errors, session auth, CSRF, PDF, attachment upload/download/delete and logout')
finally:
 for entity,prefix in [('vacunas','Vacuna'),('consultas','Consulta'),('cuotas','Cuota'),('mascotas','Mascota'),('propietarios','Propietario'),('especies','Especie')]:
  if entity in ids:sql('$s=$db->prepare("DELETE FROM '+entity+' WHERE '+prefix+'Id=?"); $s->execute([$argv[1]]);',ids[entity])
 sql('$s=$db->prepare("DELETE FROM usuario WHERE UsuarioId=?"); $s->execute([$argv[1]]);',user_id)
 if uploads:sql('(new App\\Services\\ArchivoService())->remove(array_slice($argv,1));',*uploads)
