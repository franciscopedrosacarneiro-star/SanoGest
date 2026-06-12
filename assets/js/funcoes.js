document.addEventListener("DOMContentLoaded", function() {
    
    // 1. Gráfico de Barras: Equipamentos por Serviço
    const ctxServicos = document.getElementById('graficoServicos');
    if (ctxServicos) {
        new Chart(ctxServicos.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Urgências', 'Bloco Operatório', 'Pediatria', 'C. Intensivos', 'Radiologia'],
                datasets: [{
                    label: 'Nº de Equipamentos',
                    data: [35, 42, 15, 28, 22],
                    backgroundColor: '#0d6efd',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    // 2. Gráfico Doughnut (Donut): Distribuição por Categoria (SEM GRELHA)
    const ctxCategorias = document.getElementById('graficoCategorias');
    if (ctxCategorias) {
        new Chart(ctxCategorias.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Imagem Médica', 'Suporte Vida', 'Monitorização', 'Laboratório', 'Gerais'],
                datasets: [{
                    data: [25, 20, 45, 15, 37],
                    backgroundColor: ['#6f42c1', '#dc3545', '#198754', '#ffc107', '#0dcaf0'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                },
                // Força o Chart.js a Ocultar os eixos e remover a "tabela" de fundo
                scales: {
                    x: { display: false },
                    y: { display: false }
                }
            }
        });
    }

    // 3. Gráfico de Barras Horizontais: Suporte de Vida por Serviço
    const ctxSuporte = document.getElementById('graficoSuporteVida');
    if (ctxSuporte) {
        new Chart(ctxSuporte.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Urgências', 'Bloco Operatório', 'C. Intensivos', 'Cardiologia'],
                datasets: [{
                    label: 'Qtd. Equipamentos de Suporte de Vida',
                    data: [8, 12, 15, 5],
                    backgroundColor: '#dc3545',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y', // Inverte para horizontal
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
});
document.addEventListener("DOMContentLoaded", function () {
    // =====================================================
// 5. ALTERAR PALAVRA-PASSE
// =====================================================

const formAlterarSenha = document.getElementById("formAlterarSenha");
const senhaAtual = document.getElementById("senhaAtual");
const novaSenha = document.getElementById("novaSenha");
const confirmarSenha = document.getElementById("confirmarSenha");
const mensagemSenha = document.getElementById("mensagemSenha");

if (formAlterarSenha && senhaAtual && novaSenha && confirmarSenha && mensagemSenha) {

    function validarSenhas() {
        novaSenha.setCustomValidity("");
        confirmarSenha.setCustomValidity("");

        if (novaSenha.value.length < 6 || novaSenha.value.length > 12) {
            mensagemSenha.textContent = "A nova palavra-passe deve ter entre 6 e 12 caracteres.";
            mensagemSenha.className = "form-text text-danger";
            novaSenha.setCustomValidity("A nova palavra-passe deve ter entre 6 e 12 caracteres.");
            return false;
        }

        if (confirmarSenha.value.length < 6 || confirmarSenha.value.length > 12) {
            mensagemSenha.textContent = "A confirmação deve ter entre 6 e 12 caracteres.";
            mensagemSenha.className = "form-text text-danger";
            confirmarSenha.setCustomValidity("A confirmação deve ter entre 6 e 12 caracteres.");
            return false;
        }

        if (novaSenha.value !== confirmarSenha.value) {
            mensagemSenha.textContent = "As palavras-passe não coincidem.";
            mensagemSenha.className = "form-text text-danger";
            confirmarSenha.setCustomValidity("As palavras-passe não coincidem.");
            return false;
        }

        if (senhaAtual.value !== "" && senhaAtual.value === novaSenha.value) {
            mensagemSenha.textContent = "A nova palavra-passe deve ser diferente da atual.";
            mensagemSenha.className = "form-text text-danger";
            novaSenha.setCustomValidity("A nova palavra-passe deve ser diferente da atual.");
            return false;
        }

        mensagemSenha.textContent = "As palavras-passe coincidem.";
        mensagemSenha.className = "form-text text-success";
        novaSenha.setCustomValidity("");
        confirmarSenha.setCustomValidity("");
        return true;
    }

    novaSenha.addEventListener("input", validarSenhas);
    confirmarSenha.addEventListener("input", validarSenhas);
    senhaAtual.addEventListener("input", validarSenhas);

    formAlterarSenha.addEventListener("submit", function(event) {
        if (!validarSenhas()) {
            event.preventDefault();
            formAlterarSenha.reportValidity();
        }
    });
}
// Filtros da página de Fornecedores
const pesquisaFornecedor = document.getElementById("pesquisaFornecedor");
const filtroTipoFornecedor = document.getElementById("filtroTipoFornecedor");
const filtroEstadoFornecedor = document.getElementById("filtroEstadoFornecedor");
const linhasFornecedores = document.querySelectorAll("#tabelaFornecedores tbody tr");

if (
    pesquisaFornecedor &&
    filtroTipoFornecedor &&
    filtroEstadoFornecedor &&
    linhasFornecedores.length > 0
) {
    function filtrarFornecedores() {
        const textoPesquisa = pesquisaFornecedor.value.toLowerCase();
        const tipoSelecionado = filtroTipoFornecedor.value;
        const estadoSelecionado = filtroEstadoFornecedor.value;

        linhasFornecedores.forEach(function(linha) {
            const textoLinha = linha.innerText.toLowerCase();
            const tipoLinha = linha.getAttribute("data-tipo");
            const estadoLinha = linha.getAttribute("data-estado");

            const correspondePesquisa = textoLinha.includes(textoPesquisa);
            const correspondeTipo = tipoSelecionado === "" || tipoLinha === tipoSelecionado;
            const correspondeEstado = estadoSelecionado === "" || estadoLinha === estadoSelecionado;

            if (
                correspondePesquisa &&
                correspondeTipo &&
                correspondeEstado
            ) {
                linha.style.display = "";
            } else {
                linha.style.display = "none";
            }
        });
    }

    pesquisaFornecedor.addEventListener("input", filtrarFornecedores);
    filtroTipoFornecedor.addEventListener("change", filtrarFornecedores);
    filtroEstadoFornecedor.addEventListener("change", filtrarFornecedores);
}
    const pesquisaEquipamento = document.getElementById("pesquisaEquipamento");
    const filtroCategoria = document.getElementById("filtroCategoria");
    const filtroEstado = document.getElementById("filtroEstado");
    const filtroCriticidade = document.getElementById("filtroCriticidade");
    const linhasEquipamentos = document.querySelectorAll("#tabelaEquipamentos tbody tr");

    function filtrarEquipamentos() {
        const textoPesquisa = pesquisaEquipamento.value.toLowerCase();
        const categoriaSelecionada = filtroCategoria.value;
        const estadoSelecionado = filtroEstado.value;
        const criticidadeSelecionada = filtroCriticidade.value;

        linhasEquipamentos.forEach(function (linha) {
            const textoLinha = linha.innerText.toLowerCase();
            const categoriaLinha = linha.getAttribute("data-categoria");
            const estadoLinha = linha.getAttribute("data-estado");
            const criticidadeLinha = linha.getAttribute("data-criticidade");

            const correspondePesquisa = textoLinha.includes(textoPesquisa);
            const correspondeCategoria = categoriaSelecionada === "" || categoriaLinha === categoriaSelecionada;
            const correspondeEstado = estadoSelecionado === "" || estadoLinha === estadoSelecionado;
            const correspondeCriticidade = criticidadeSelecionada === "" || criticidadeLinha === criticidadeSelecionada;

            if (
                correspondePesquisa &&
                correspondeCategoria &&
                correspondeEstado &&
                correspondeCriticidade
            ) {
                linha.style.display = "";
            } else {
                linha.style.display = "none";
            }
        });
    }

    pesquisaEquipamento.addEventListener("input", filtrarEquipamentos);
    filtroCategoria.addEventListener("change", filtrarEquipamentos);
    filtroEstado.addEventListener("change", filtrarEquipamentos);
    filtroCriticidade.addEventListener("change", filtrarEquipamentos);

});
// Formulário por passos - Novo Equipamento
const formNovoEquipamento = document.getElementById("formNovoEquipamento");
const passosFormulario = document.querySelectorAll(".passo-formulario");
const botoesPassos = document.querySelectorAll("[data-passo]");
const btnAnterior = document.getElementById("btnAnterior");
const btnSeguinte = document.getElementById("btnSeguinte");
const btnGuardar = document.getElementById("btnGuardar");

if (formNovoEquipamento && passosFormulario.length > 0) {
    let passoAtual = 1;

    function mostrarPasso(numeroPasso) {
        passosFormulario.forEach(function(passo) {
            passo.classList.add("d-none");
        });

        document.getElementById("passo" + numeroPasso).classList.remove("d-none");

        botoesPassos.forEach(function(botao) {
            botao.classList.remove("active");
            if (parseInt(botao.getAttribute("data-passo")) === numeroPasso) {
                botao.classList.add("active");
            }
        });

        btnAnterior.disabled = numeroPasso === 1;

        if (numeroPasso === passosFormulario.length) {
            btnSeguinte.classList.add("d-none");
            btnGuardar.classList.remove("d-none");
        } else {
            btnSeguinte.classList.remove("d-none");
            btnGuardar.classList.add("d-none");
        }
    }

    function validarPassoAtual() {
        const passoVisivel = document.getElementById("passo" + passoAtual);
        const campos = passoVisivel.querySelectorAll("input, select, textarea");

        for (let campo of campos) {
            if (!campo.checkValidity()) {
                campo.reportValidity();
                return false;
            }
        }

        return true;
    }

    btnSeguinte.addEventListener("click", function() {
        if (validarPassoAtual() && passoAtual < passosFormulario.length) {
            passoAtual++;
            mostrarPasso(passoAtual);
        }
    });

    btnAnterior.addEventListener("click", function() {
        if (passoAtual > 1) {
            passoAtual--;
            mostrarPasso(passoAtual);
        }
    });

    botoesPassos.forEach(function(botao) {
        botao.addEventListener("click", function() {
            const passoDestino = parseInt(this.getAttribute("data-passo"));

            if (passoDestino < passoAtual || validarPassoAtual()) {
                passoAtual = passoDestino;
                mostrarPasso(passoAtual);
            }
        });
    });

    formNovoEquipamento.addEventListener("submit", function(event) {
        if (!formNovoEquipamento.checkValidity()) {
            event.preventDefault();
            formNovoEquipamento.reportValidity();
        }
    });

    mostrarPasso(passoAtual);
}

