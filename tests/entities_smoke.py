"""Runs the MVC baseline and CRUD integration tests for all migrated entities.
Requires database/migrate.php and a running development instance.
"""
import base64
import json
import secrets
import urllib.request
import urllib.error
import urllib.parse
from mvc_smoke import client, request, token, sql, BASE
marker='entities-'+secrets.token_hex(8)
password=secrets.token_hex(16)
ids={}
uploads=[]
def dbrow(entity,prefix,where,args):
    return json.loads(sql('$s=$db->prepare("SELECT * FROM '+entity+' WHERE '+where+'"); $s->execute(array_slice($argv,1)); echo json_encode($s->get_result()->fetch_assoc());', *map(str,args)))
def upload(auth,route,data,content,name,mime):
    boundary='mvc'+secrets.token_hex(8)
    parts=[]
    for key,value in data.items():
        parts.append(f'--{boundary}\r\nContent-Disposition: form-data; name="{key}"\r\n\r\n{value}\r\n'.encode())
    parts.append(f'--{boundary}\r\nContent-Disposition: form-data; name="archivo1"; filename="{name}"\r\nContent-Type: {mime}\r\n\r\n'.encode()+content+b'\r\n')
    parts.append(f'--{boundary}--\r\n'.encode())
    req=urllib.request.Request(BASE+'/public/index.php?route='+route,data=b''.join(parts),headers={'Content-Type':'multipart/form-data; boundary='+boundary})
    try:r=auth.open(req)
    except urllib.error.HTTPError as e:r=e
    with r:return r.status,r.read()

sql('$s=$db->prepare("INSERT INTO usuario (UsuarioNombre,UsuarioUser,UsuarioPwd,UsuarioEstado) VALUES (?, ?, SHA1(?), 1)"); $s->execute([$argv[1],$argv[1],$argv[2]]);',marker,password)
species_id=int(sql('$s=$db->prepare("INSERT INTO especies (EspecieNombre,EspecieEstado) VALUES (?,1)"); $s->execute([$argv[1]]); echo $db->insert_id;',marker))
try:
    auth=client(); csrf=token(request(auth,'login')[1])
    assert request(auth,'login.submit',{'_token':csrf,'txtUser':marker,'txtPwd':password})[0]==303
    csrf=token(request(auth,'especies')[1])
    cases=[
        ('propietarios','Propietario',{'txtNombre':marker,'txtApellido':'Prueba','txtTelefono':'123456789','txtDireccion':'<script>alert(1)</script>','txtEmail':'test@example.com'},'PropietarioNombre=?',[marker]),
        ('mascotas','Mascota',{'txtNombre':marker,'txtEspecie':species_id,'txtFechaNac':'2020-01-01'},'MascotaNombre=?',[marker]),
        ('cuotas','Cuota',{'txtFecha':'2026-01-01','txtValor':'250','txtObservacion':marker},'MascotaId=?',None),
        ('consultas','Consulta',{'txtFechaConsulta':'2026-01-01','txtMotivo':marker,'txtDiagnostico':'<script>prueba</script>','txtTratamiento':'Observación'},'MascotaId=?',None),
        ('vacunas','Vacuna',{'txtFecha':'2026-01-01','txtFchVenc':'2026-12-01'},'MascotaId=?',None),
    ]
    for entity,prefix,data,where,args in cases:
        if entity=='mascotas': data['txtPropietario']=ids['propietarios']
        if entity in ['cuotas','consultas','vacunas']:data['txtMascotaId']=ids['mascotas'];args=[ids['mascotas']]
        data['_token']=csrf
        assert request(client(),entity)[0]==303
        assert request(auth,entity+'.create')[0]==200
        assert request(auth,entity+'.store',{**data,'_token':'invalid'})[0]==403
        assert request(auth,entity+'.store',{'_token':csrf})[0]==422
        assert request(auth,entity+'.store',data)[0]==303,entity
        row=dbrow(entity,prefix,where,args);ids[entity]=row[prefix+'Id']
        assert request(auth,entity+'.show',legacy=None)[0]==404
        status,body,_=request(auth,'unused',legacy='/'+entity+'.php')
        assert status==200
        assert '<script>alert(1)</script>' not in body and '<script>prueba</script>' not in body
        assert request(auth,'unused',legacy='/frmEditar'+prefix+'.php?id='+str(ids[entity]))[0]==200
        assert request(auth,'unused',legacy='/eliminar'+prefix+'.php?id='+str(ids[entity]))[0]==405
        data['txtId']=ids[entity]
        assert request(auth,entity+'.update',data)[0]==303
        assert request(auth,entity+'.update',{**data,'txtId':'-1'})[0]==404
        print('PASS create/edit/list and validation:',entity)
    assert request(auth,'mascotas.store',{'_token':csrf,'txtNombre':'invalid','txtEspecie':species_id,'txtPropietario':999999999,'txtFechaNac':'2020-01-01'})[0]==422
    assert request(auth,'cuotas.store',{'_token':csrf,'txtFecha':'2026-02-31','txtMascotaId':ids['mascotas'],'txtValor':'2.5'})[0]==422
    assert request(auth,'vacunas.store',{'_token':csrf,'txtFecha':'2026-12-01','txtFchVenc':'2026-01-01','txtMascotaId':ids['mascotas']})[0]==422
    history=request(auth,'vencimientos')
    assert history[0]==200 and marker in history[1]
    data={'_token':csrf,'txtId':ids['consultas'],'txtFechaConsulta':'2026-01-01','txtMascotaId':ids['mascotas'],'txtMotivo':marker,'txtDiagnostico':'Prueba','txtTratamiento':'Control'}
    assert upload(auth,'consultas.update',data,b'<?php echo 1; ?>','attack.php','application/pdf')[0]==422
    png=base64.b64decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+a5XsAAAAASUVORK5CYII=')
    assert upload(auth,'consultas.update',data,png,'test.png','image/png')[0]==303
    row=dbrow('consultas','Consulta','ConsultaId=?',[ids['consultas']]);uploads.append(row['ConsultaArchivo1'])
    assert request(auth,'consultas.update',data)[0]==303
    assert dbrow('consultas','Consulta','ConsultaId=?',[ids['consultas']])['ConsultaArchivo1']==uploads[0]
    for route,expected in [('consultas.file&id='+str(ids['consultas'])+'&slot=1',png),('consultas.pdf&id='+str(ids['consultas']),b'%PDF-')]:
        with auth.open(BASE+'/public/index.php?route='+route) as response:
            body=response.read();assert body.startswith(expected)
    assert request(client(),'unused',legacy='/reportePDF.php?id='+str(ids['consultas']))[0]==303
    print('PASS history, attachments, rejected executable upload, preserved attachment and PDF')
    for entity,prefix,*_ in reversed(cases):
        assert request(auth,entity+'.destroy',{'_token':csrf,'txtId':ids[entity]})[0]==303
        row=dbrow(entity,prefix,prefix+'Id=?',[ids[entity]])
        assert row[prefix+'Estado']==0
        assert request(auth,'unused',legacy='/frmEditar'+prefix+'.php?id='+str(ids[entity]))[0]==404
    print('PASS soft delete for all entities')
finally:
    for entity,prefix in [('vacunas','Vacuna'),('consultas','Consulta'),('cuotas','Cuota'),('mascotas','Mascota'),('propietarios','Propietario')]:
        if entity in ids:sql('$s=$db->prepare("DELETE FROM '+entity+' WHERE '+prefix+'Id=?"); $s->execute([$argv[1]]);',str(ids[entity]))
    sql('$s=$db->prepare("DELETE FROM especies WHERE EspecieId=?"); $s->execute([$argv[1]]); $s=$db->prepare("DELETE FROM usuario WHERE UsuarioUser=?"); $s->execute([$argv[2]]);',str(species_id),marker)
    if uploads:
        sql('(new App\\Services\\ArchivoService())->remove(array_slice($argv,1));',*uploads)
