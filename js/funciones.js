window.onload = function () {
    var fecha = new Date();
    var mes = fecha.getMonth() + 1;
    var dia = fecha.getDate();
    var ano = fecha.getFullYear();
    if (dia < 10)
        dia = '0' + dia;
    if (mes < 10)
        mes = '0' + mes
    document.getElementById('fecha').value = ano + "-" + mes + "-" + dia;
}


function validoLogin(elform) {
    bandera = "0";
    if (elform.txtUser.value == "" && elform.txtPwd.value == "") {
        alert("Usuario o contraseña no valido");
        bandera = "1";
        elform.txtNombre.style.background = "#ff0000"
    }


    if (bandera == "1") {
        return false;
    }
}