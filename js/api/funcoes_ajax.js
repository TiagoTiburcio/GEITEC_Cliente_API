function todosServidores(tabela) {
    var servidores = getTodos('servidor');
    servidores.done(function (data) {
        for (let index = 0; index < data.length; index++) {
            const element = data[index];
            var linha = $("<tr>");
            var nome = $("<td>").text(element.nome);
            var desc = $("<td>").text(element.descricao);            
            var so = $("<td>").text(element.so.nome);
            var pool = $("<td>").text(element.pool.nome);
            var link = $("<a>").text("Edit "+element.codigo).attr("class", "btn btn-warning").attr("href","servidor.php?codigo="+element.codigo);
            var edit = $("<td>").append(link);

            linha.append(nome).append(desc).append(so).append(pool).append(edit);
            tabela.append(linha);
        }
    });
}

function getTodos(categoria) {
    var settings = {
        "async": true,
        "url": "/api/index.php/infraestrutura/" + categoria,
        "headers": {
            "Content-Type": "application/x-www-form-urlencoded",
            "Authorization": "Bearer " + token
        }
    }
    

    var dados = $.get(settings, function (data) {
        return data;
    });
    return dados;
}
