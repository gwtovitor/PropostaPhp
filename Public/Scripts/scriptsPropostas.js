 async function displayPropostas() {
  try {
    const response = await fetch("http://localhost:8001/Server/index/propostas");
    const data = await response.json();

   
    function criarLinhaTabela(dados) {
      const linha = document.createElement("tr");
      linha.id = `linha-${dados.id}`; 

      const colunaPlano = document.createElement("td");
      colunaPlano.textContent = dados.plano_nome;
      colunaPlano.id = `coluna-plano`; 

      const colunaPrecoTotal = document.createElement("td");
      colunaPrecoTotal.textContent = 'R$ ' + dados.preco_total +',00';
      colunaPrecoTotal.id = `coluna-preco-total`; 

      const colunaBeneficiarios = document.createElement("td");
      colunaBeneficiarios.id = `coluna-beneficiarios`;

      const beneficiarios = dados.beneficiarios.map(beneficiario => `Nome: ${beneficiario.nome}, Idade: ${beneficiario.idade}, Preço: R$${beneficiario.preco}`+',00').join('<br>');
      colunaBeneficiarios.innerHTML = beneficiarios;

      linha.appendChild(colunaPlano);
      linha.appendChild(colunaPrecoTotal);
      linha.appendChild(colunaBeneficiarios);

      return linha;
    }

    const tabela = document.getElementById("dadosTabela");
    const tbody = tabela.getElementsByTagName("tbody")[0];


    tbody.innerHTML = "";


    data.forEach((dados) => {
      const linha = criarLinhaTabela(dados);
      tbody.appendChild(linha);
    });

  } catch (error) {
    console.error("Erro ao obter os dados do servidor:", error);

    const errorMessage = document.getElementById("error-message");
    errorMessage.textContent =
      "Ocorreu um erro ao obter os dados do servidor. Por favor, tente novamente mais tarde.";
    errorMessage.style.display = "block";
  }
}


document.addEventListener("DOMContentLoaded", displayPropostas);
