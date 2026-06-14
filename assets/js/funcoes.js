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
// FILTROS DA PÁGINA DOCUMENTAÇÃO
// =====================================================

const pesquisaDocumento = document.getElementById("pesquisaDocumento");
const filtroTipoDocumento = document.getElementById("filtroTipoDocumento");
const filtroEstadoDocumento = document.getElementById("filtroEstadoDocumento");
const filtroAssociacaoDocumento = document.getElementById("filtroAssociacaoDocumento");
const linhasDocumentos = document.querySelectorAll("#tabelaDocumentos tbody tr");

if (
    pesquisaDocumento &&
    filtroTipoDocumento &&
    filtroEstadoDocumento &&
    filtroAssociacaoDocumento &&
    linhasDocumentos.length > 0
) {
    function filtrarDocumentos() {
        const textoPesquisa = pesquisaDocumento.value.toLowerCase().trim();
        const tipoSelecionado = filtroTipoDocumento.value;
        const estadoSelecionado = filtroEstadoDocumento.value;
        const associacaoSelecionada = filtroAssociacaoDocumento.value;

        linhasDocumentos.forEach(function(linha) {
            const textoLinha = linha.innerText.toLowerCase();
            const tipoLinha = linha.getAttribute("data-tipo");
            const estadoLinha = linha.getAttribute("data-estado");
            const associacaoLinha = linha.getAttribute("data-associacao");

            const correspondePesquisa = textoLinha.includes(textoPesquisa);
            const correspondeTipo = tipoSelecionado === "" || tipoLinha === tipoSelecionado;
            const correspondeEstado = estadoSelecionado === "" || estadoLinha === estadoSelecionado;
            const correspondeAssociacao = associacaoSelecionada === "" || associacaoLinha === associacaoSelecionada;

            if (
                correspondePesquisa &&
                correspondeTipo &&
                correspondeEstado &&
                correspondeAssociacao
            ) {
                linha.style.display = "";
            } else {
                linha.style.display = "none";
            }
        });
    }

    pesquisaDocumento.addEventListener("input", filtrarDocumentos);
    filtroTipoDocumento.addEventListener("change", filtrarDocumentos);
    filtroEstadoDocumento.addEventListener("change", filtrarDocumentos);
    filtroAssociacaoDocumento.addEventListener("change", filtrarDocumentos);
}

    // =====================================================
    // 1. GRÁFICOS DO DASHBOARD
    // =====================================================

    const ctxServicos = document.getElementById("graficoServicos");

    if (ctxServicos && typeof Chart !== "undefined") {
        new Chart(ctxServicos.getContext("2d"), {
            type: "bar",
            data: {
                labels: ["Urgências", "Bloco Operatório", "Pediatria", "C. Intensivos", "Radiologia"],
                datasets: [{
                    label: "Nº de Equipamentos",
                    data: [35, 42, 15, 28, 22],
                    backgroundColor: "#0d6efd",
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: "bottom" }
                }
            }
        });
    }

    const ctxCategorias = document.getElementById("graficoCategorias");

    if (ctxCategorias && typeof Chart !== "undefined") {
        new Chart(ctxCategorias.getContext("2d"), {
            type: "doughnut",
            data: {
                labels: ["Imagem Médica", "Suporte Vida", "Monitorização", "Laboratório", "Gerais"],
                datasets: [{
                    data: [25, 20, 45, 15, 37],
                    backgroundColor: ["#6f42c1", "#dc3545", "#198754", "#ffc107", "#0dcaf0"],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: "bottom" }
                }
            }
        });
    }

    const ctxSuporte = document.getElementById("graficoSuporteVida");

    if (ctxSuporte && typeof Chart !== "undefined") {
        new Chart(ctxSuporte.getContext("2d"), {
            type: "bar",
            data: {
                labels: ["Urgências", "Bloco Operatório", "C. Intensivos", "Cardiologia"],
                datasets: [{
                    label: "Qtd. Equipamentos de Suporte de Vida",
                    data: [8, 12, 15, 5],
                    backgroundColor: "#dc3545",
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: "y",
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }


    // =====================================================
    // 2. FILTROS DA PÁGINA EQUIPAMENTOS
    // =====================================================

    const pesquisaEquipamento = document.getElementById("pesquisaEquipamento");
    const filtroCategoria = document.getElementById("filtroCategoria");
    const filtroEstado = document.getElementById("filtroEstado");
    const filtroCriticidade = document.getElementById("filtroCriticidade");
    const linhasEquipamentos = document.querySelectorAll("#tabelaEquipamentos tbody tr");

    if (
        pesquisaEquipamento &&
        filtroCategoria &&
        filtroEstado &&
        filtroCriticidade &&
        linhasEquipamentos.length > 0
    ) {
        function filtrarEquipamentos() {
            const textoPesquisa = pesquisaEquipamento.value.toLowerCase().trim();
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

                linha.style.display =
                    correspondePesquisa &&
                    correspondeCategoria &&
                    correspondeEstado &&
                    correspondeCriticidade
                        ? ""
                        : "none";
            });
        }

        pesquisaEquipamento.addEventListener("input", filtrarEquipamentos);
        filtroCategoria.addEventListener("change", filtrarEquipamentos);
        filtroEstado.addEventListener("change", filtrarEquipamentos);
        filtroCriticidade.addEventListener("change", filtrarEquipamentos);
    }


    // =====================================================
    // 3. FILTROS DA PÁGINA FORNECEDORES
    // =====================================================

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
            const textoPesquisa = pesquisaFornecedor.value.toLowerCase().trim();
            const tipoSelecionado = filtroTipoFornecedor.value;
            const estadoSelecionado = filtroEstadoFornecedor.value;

            linhasFornecedores.forEach(function (linha) {
                const textoLinha = linha.innerText.toLowerCase();
                const tipoLinha = linha.getAttribute("data-tipo");
                const estadoLinha = linha.getAttribute("data-estado");

                const correspondePesquisa = textoLinha.includes(textoPesquisa);
                const correspondeTipo = tipoSelecionado === "" || tipoLinha === tipoSelecionado;
                const correspondeEstado = estadoSelecionado === "" || estadoLinha === estadoSelecionado;

                linha.style.display =
                    correspondePesquisa &&
                    correspondeTipo &&
                    correspondeEstado
                        ? ""
                        : "none";
            });
        }

        pesquisaFornecedor.addEventListener("input", filtrarFornecedores);
        filtroTipoFornecedor.addEventListener("change", filtrarFornecedores);
        filtroEstadoFornecedor.addEventListener("change", filtrarFornecedores);
    }


    // =====================================================
    // 4. FILTROS DA PÁGINA LOCALIZAÇÕES
    // =====================================================

    const pesquisaLocalizacao = document.getElementById("pesquisaLocalizacao");
    const filtroEdificio = document.getElementById("filtroEdificio");
    const filtroPiso = document.getElementById("filtroPiso");
    const filtroServico = document.getElementById("filtroServico");
    const linhasLocalizacoes = document.querySelectorAll("#tabelaLocalizacoes tbody tr");

    if (
        pesquisaLocalizacao &&
        filtroEdificio &&
        filtroPiso &&
        filtroServico &&
        linhasLocalizacoes.length > 0
    ) {
        function filtrarLocalizacoes() {
            const textoPesquisa = pesquisaLocalizacao.value.toLowerCase().trim();
            const edificioSelecionado = filtroEdificio.value;
            const pisoSelecionado = filtroPiso.value;
            const servicoSelecionado = filtroServico.value;

            linhasLocalizacoes.forEach(function (linha) {
                const textoLinha = linha.innerText.toLowerCase();
                const edificioLinha = linha.getAttribute("data-edificio");
                const pisoLinha = linha.getAttribute("data-piso");
                const servicoLinha = linha.getAttribute("data-servico");

                const correspondePesquisa = textoLinha.includes(textoPesquisa);
                const correspondeEdificio = edificioSelecionado === "" || edificioLinha === edificioSelecionado;
                const correspondePiso = pisoSelecionado === "" || pisoLinha === pisoSelecionado;
                const correspondeServico = servicoSelecionado === "" || servicoLinha === servicoSelecionado;

                linha.style.display =
                    correspondePesquisa &&
                    correspondeEdificio &&
                    correspondePiso &&
                    correspondeServico
                        ? ""
                        : "none";
            });
        }

        pesquisaLocalizacao.addEventListener("input", filtrarLocalizacoes);
        filtroEdificio.addEventListener("change", filtrarLocalizacoes);
        filtroPiso.addEventListener("change", filtrarLocalizacoes);
        filtroServico.addEventListener("change", filtrarLocalizacoes);
    }


    // =====================================================
    // 5. FILTROS DA PÁGINA GARANTIAS
    // =====================================================

    const pesquisaGarantia = document.getElementById("pesquisaGarantia");
    const filtroEstadoGarantia = document.getElementById("filtroEstadoGarantia");
    const filtroTipoContrato = document.getElementById("filtroTipoContrato");
    const filtroCriticidadeGarantia = document.getElementById("filtroCriticidadeGarantia");
    const linhasGarantias = document.querySelectorAll("#tabelaGarantias tbody tr");

    if (
        pesquisaGarantia &&
        filtroEstadoGarantia &&
        filtroTipoContrato &&
        filtroCriticidadeGarantia &&
        linhasGarantias.length > 0
    ) {
        function filtrarGarantias() {
            const textoPesquisa = pesquisaGarantia.value.toLowerCase().trim();
            const estadoSelecionado = filtroEstadoGarantia.value;
            const tipoSelecionado = filtroTipoContrato.value;
            const criticidadeSelecionada = filtroCriticidadeGarantia.value;

            linhasGarantias.forEach(function (linha) {
                const textoLinha = linha.innerText.toLowerCase();
                const estadoLinha = linha.getAttribute("data-estado");
                const tipoLinha = linha.getAttribute("data-tipo");
                const criticidadeLinha = linha.getAttribute("data-criticidade");

                const correspondePesquisa = textoLinha.includes(textoPesquisa);
                const correspondeEstado = estadoSelecionado === "" || estadoLinha === estadoSelecionado;
                const correspondeTipo = tipoSelecionado === "" || tipoLinha === tipoSelecionado;
                const correspondeCriticidade = criticidadeSelecionada === "" || criticidadeLinha === criticidadeSelecionada;

                linha.style.display =
                    correspondePesquisa &&
                    correspondeEstado &&
                    correspondeTipo &&
                    correspondeCriticidade
                        ? ""
                        : "none";
            });
        }

        pesquisaGarantia.addEventListener("input", filtrarGarantias);
        filtroEstadoGarantia.addEventListener("change", filtrarGarantias);
        filtroTipoContrato.addEventListener("change", filtrarGarantias);
        filtroCriticidadeGarantia.addEventListener("change", filtrarGarantias);
    }


    // =====================================================
    // 6. FORMULÁRIO POR PASSOS - NOVO EQUIPAMENTO
    // =====================================================

    const formNovoEquipamento = document.getElementById("formNovoEquipamento");
    const passosFormulario = document.querySelectorAll(".passo-formulario");
    const botoesPassos = document.querySelectorAll("[data-passo]");
    const btnAnterior = document.getElementById("btnAnterior");
    const btnSeguinte = document.getElementById("btnSeguinte");
    const btnGuardar = document.getElementById("btnGuardar");

    if (
        formNovoEquipamento &&
        passosFormulario.length > 0 &&
        btnAnterior &&
        btnSeguinte &&
        btnGuardar
    ) {
        let passoAtual = 1;

        function mostrarPasso(numeroPasso) {
            passosFormulario.forEach(function (passo) {
                passo.classList.add("d-none");
            });

            const passoSelecionado = document.getElementById("passo" + numeroPasso);

            if (passoSelecionado) {
                passoSelecionado.classList.remove("d-none");
            }

            botoesPassos.forEach(function (botao) {
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

            if (!passoVisivel) {
                return false;
            }

            const campos = passoVisivel.querySelectorAll("input, select, textarea");

            for (let campo of campos) {
                if (!campo.checkValidity()) {
                    campo.reportValidity();
                    return false;
                }
            }

            return true;
        }

        btnSeguinte.addEventListener("click", function () {
            if (validarPassoAtual() && passoAtual < passosFormulario.length) {
                passoAtual++;
                mostrarPasso(passoAtual);
            }
        });

        btnAnterior.addEventListener("click", function () {
            if (passoAtual > 1) {
                passoAtual--;
                mostrarPasso(passoAtual);
            }
        });

        botoesPassos.forEach(function (botao) {
            botao.addEventListener("click", function () {
                const passoDestino = parseInt(this.getAttribute("data-passo"));

                if (passoDestino < passoAtual || validarPassoAtual()) {
                    passoAtual = passoDestino;
                    mostrarPasso(passoAtual);
                }
            });
        });

        formNovoEquipamento.addEventListener("submit", function (event) {
            if (!formNovoEquipamento.checkValidity()) {
                event.preventDefault();
                formNovoEquipamento.reportValidity();
            }
        });

        mostrarPasso(passoAtual);
    }


    // =====================================================
    // 7. FORMULÁRIO POR PASSOS - NOVO FORNECEDOR
    // =====================================================

    const formNovoFornecedor = document.getElementById("formNovoFornecedor");
    const passosFornecedor = document.querySelectorAll(".passo-fornecedor");
    const botoesPassosFornecedor = document.querySelectorAll("[data-passo-fornecedor]");
    const btnAnteriorFornecedor = document.getElementById("btnAnteriorFornecedor");
    const btnSeguinteFornecedor = document.getElementById("btnSeguinteFornecedor");
    const btnGuardarFornecedor = document.getElementById("btnGuardarFornecedor");

    if (
        formNovoFornecedor &&
        passosFornecedor.length > 0 &&
        btnAnteriorFornecedor &&
        btnSeguinteFornecedor &&
        btnGuardarFornecedor
    ) {
        let passoAtualFornecedor = 1;

        function mostrarPassoFornecedor(numeroPasso) {
            passosFornecedor.forEach(function (passo) {
                passo.classList.add("d-none");
            });

            const passoSelecionado = document.getElementById("passoFornecedor" + numeroPasso);

            if (passoSelecionado) {
                passoSelecionado.classList.remove("d-none");
            }

            botoesPassosFornecedor.forEach(function (botao) {
                botao.classList.remove("active");

                if (parseInt(botao.getAttribute("data-passo-fornecedor")) === numeroPasso) {
                    botao.classList.add("active");
                }
            });

            btnAnteriorFornecedor.disabled = numeroPasso === 1;

            if (numeroPasso === passosFornecedor.length) {
                btnSeguinteFornecedor.classList.add("d-none");
                btnGuardarFornecedor.classList.remove("d-none");
            } else {
                btnSeguinteFornecedor.classList.remove("d-none");
                btnGuardarFornecedor.classList.add("d-none");
            }
        }

        function validarPassoFornecedorAtual() {
            const passoVisivel = document.getElementById("passoFornecedor" + passoAtualFornecedor);

            if (!passoVisivel) {
                return false;
            }

            const campos = passoVisivel.querySelectorAll("input, select, textarea");

            for (let campo of campos) {
                if (!campo.checkValidity()) {
                    campo.reportValidity();
                    return false;
                }
            }

            return true;
        }

        btnSeguinteFornecedor.addEventListener("click", function () {
            if (validarPassoFornecedorAtual() && passoAtualFornecedor < passosFornecedor.length) {
                passoAtualFornecedor++;
                mostrarPassoFornecedor(passoAtualFornecedor);
            }
        });

        btnAnteriorFornecedor.addEventListener("click", function () {
            if (passoAtualFornecedor > 1) {
                passoAtualFornecedor--;
                mostrarPassoFornecedor(passoAtualFornecedor);
            }
        });

        botoesPassosFornecedor.forEach(function (botao) {
            botao.addEventListener("click", function () {
                const passoDestino = parseInt(this.getAttribute("data-passo-fornecedor"));

                if (passoDestino < passoAtualFornecedor || validarPassoFornecedorAtual()) {
                    passoAtualFornecedor = passoDestino;
                    mostrarPassoFornecedor(passoAtualFornecedor);
                }
            });
        });

        formNovoFornecedor.addEventListener("submit", function (event) {
            if (!formNovoFornecedor.checkValidity()) {
                event.preventDefault();
                formNovoFornecedor.reportValidity();
            }
        });

        mostrarPassoFornecedor(passoAtualFornecedor);
    }


    // =====================================================
    // 8. FORMULÁRIO POR PASSOS - EDITAR FORNECEDOR
    // =====================================================

    const formEditarFornecedor = document.getElementById("formEditarFornecedor");
    const passosEditarFornecedor = document.querySelectorAll(".passo-editar-fornecedor");
    const botoesPassosEditarFornecedor = document.querySelectorAll("[data-passo-editar-fornecedor]");
    const btnAnteriorEditarFornecedor = document.getElementById("btnAnteriorEditarFornecedor");
    const btnSeguinteEditarFornecedor = document.getElementById("btnSeguinteEditarFornecedor");
    const btnGuardarEditarFornecedor = document.getElementById("btnGuardarEditarFornecedor");
    const btnReporEditarFornecedor = document.getElementById("btnReporEditarFornecedor");

    if (
        formEditarFornecedor &&
        passosEditarFornecedor.length > 0 &&
        btnAnteriorEditarFornecedor &&
        btnSeguinteEditarFornecedor &&
        btnGuardarEditarFornecedor &&
        btnReporEditarFornecedor
    ) {
        let passoAtualEditarFornecedor = 1;

        function mostrarPassoEditarFornecedor(numeroPasso) {
            passosEditarFornecedor.forEach(function (passo) {
                passo.classList.add("d-none");
            });

            const passoSelecionado = document.getElementById("passoEditarFornecedor" + numeroPasso);

            if (passoSelecionado) {
                passoSelecionado.classList.remove("d-none");
            }

            botoesPassosEditarFornecedor.forEach(function (botao) {
                botao.classList.remove("active");

                if (parseInt(botao.getAttribute("data-passo-editar-fornecedor")) === numeroPasso) {
                    botao.classList.add("active");
                }
            });

            btnAnteriorEditarFornecedor.disabled = numeroPasso === 1;

            if (numeroPasso === passosEditarFornecedor.length) {
                btnSeguinteEditarFornecedor.classList.add("d-none");
                btnGuardarEditarFornecedor.classList.remove("d-none");
                btnReporEditarFornecedor.classList.remove("d-none");
            } else {
                btnSeguinteEditarFornecedor.classList.remove("d-none");
                btnGuardarEditarFornecedor.classList.add("d-none");
                btnReporEditarFornecedor.classList.add("d-none");
            }
        }

        function validarPassoEditarFornecedorAtual() {
            const passoVisivel = document.getElementById("passoEditarFornecedor" + passoAtualEditarFornecedor);

            if (!passoVisivel) {
                return false;
            }

            const campos = passoVisivel.querySelectorAll("input, select, textarea");

            for (let campo of campos) {
                if (!campo.checkValidity()) {
                    campo.reportValidity();
                    return false;
                }
            }

            return true;
        }

        btnSeguinteEditarFornecedor.addEventListener("click", function () {
            if (
                validarPassoEditarFornecedorAtual() &&
                passoAtualEditarFornecedor < passosEditarFornecedor.length
            ) {
                passoAtualEditarFornecedor++;
                mostrarPassoEditarFornecedor(passoAtualEditarFornecedor);
            }
        });

        btnAnteriorEditarFornecedor.addEventListener("click", function () {
            if (passoAtualEditarFornecedor > 1) {
                passoAtualEditarFornecedor--;
                mostrarPassoEditarFornecedor(passoAtualEditarFornecedor);
            }
        });

        botoesPassosEditarFornecedor.forEach(function (botao) {
            botao.addEventListener("click", function () {
                const passoDestino = parseInt(this.getAttribute("data-passo-editar-fornecedor"));

                if (passoDestino < passoAtualEditarFornecedor || validarPassoEditarFornecedorAtual()) {
                    passoAtualEditarFornecedor = passoDestino;
                    mostrarPassoEditarFornecedor(passoAtualEditarFornecedor);
                }
            });
        });

        formEditarFornecedor.addEventListener("submit", function (event) {
            if (!formEditarFornecedor.checkValidity()) {
                event.preventDefault();
                formEditarFornecedor.reportValidity();
            }
        });

        mostrarPassoEditarFornecedor(passoAtualEditarFornecedor);
    }


    // =====================================================
    // 9. ALTERAR PALAVRA-PASSE
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

        formAlterarSenha.addEventListener("submit", function (event) {
            if (!validarSenhas()) {
                event.preventDefault();
                formAlterarSenha.reportValidity();
            }
        });
    }

});