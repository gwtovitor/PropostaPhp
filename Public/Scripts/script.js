let plansData;
let pricesData;

async function displayPlansAndPrices() {
  try {
    const response = await fetch("http://localhost:8001/Server/index/planos");
    const data = await response.json();
    plansData = data;
    pricesData = data;

    exibePrecosePlanos();
  } catch (error) {
    console.error("Erro ao obter os dados do servidor:", error);
    const errorMessage = document.getElementById("error-message");
    errorMessage.textContent =
      "Ocorreu um erro ao obter os dados do servidor. Por favor, tente novamente mais tarde.";
    errorMessage.style.display = "block";
  }
}

function exibePrecosePlanos() {
  const plansContainer = document.getElementById("plans-container");
  plansContainer.innerHTML = "";

  for (const key in plansData) {
    if (plansData.hasOwnProperty(key)) {
      const plansArray = plansData[key];

      plansArray.forEach((plan) => {
        const matchingPrices = pricesData[key];

        const card = document.createElement("div");
        card.className = "plan-card";

        const header = document.createElement("h3");
        header.textContent = key;
        card.appendChild(header);

        matchingPrices.forEach((price) => {
          const priceInfo = document.createElement("p");
          priceInfo.innerHTML = `Mínimo de Usuários: ${price.minimo_vidas},<br>0-17 anos: R$${price.faixa1},00<br>18-40 anos: R$${price.faixa2},00<br>+41 anos: R$${price.faixa3},00`;

          card.appendChild(priceInfo);
        });

        plansContainer.appendChild(card);
      });
    }
  }

  const plansSelect = document.getElementById("plans-select");
  plansSelect.innerHTML = "";

  const planKeys = Object.keys(plansData);

  for (let i = 0; i < planKeys.length; i++) {
    const key = planKeys[i];
    if (plansData.hasOwnProperty(key)) {
      const option = document.createElement("option");
      option.value = i; 
      option.textContent = key;
      plansSelect.appendChild(option);
    }
  }
}




function gerarUsuarios() {
  const numUsers = document.getElementById("num-users").value;
  const usersFieldsContainer = document.getElementById("users-fields-container");
  usersFieldsContainer.innerHTML = "";

  if (numUsers === "0" || numUsers.trim() === "") {
    const errorMessage = document.getElementById("error-message");
    errorMessage.textContent = "Preencha o numero de usuários";
    errorMessage.style.display = "block";
    return;
  } else if (numUsers > 10) {
    const errorMessage = document.getElementById("error-message");
    errorMessage.textContent = "O numero máximo de usuários por proposta é de 10";
    errorMessage.style.display = "block";
    return;
  }

  for (let i = 0; i < numUsers; i++) {
    const userField = document.createElement("div");
    userField.className = "user-field";

    const nameLabel = document.createElement("label");
    nameLabel.textContent = `Nome do Usuário ${i + 1}: `;
    const nameInput = document.createElement("input");
    nameInput.type = "text";
    nameInput.id = `nome-${i}`;
    nameInput.required = true;

    const ageLabel = document.createElement("label");
    ageLabel.textContent = `Idade do Usuário ${i + 1}: `;
    const ageInput = document.createElement("input");
    ageInput.type = "number";
    ageInput.id = `idade-${i}`;
    ageInput.required = true;

    userField.appendChild(nameLabel);
    userField.appendChild(nameInput);
    userField.appendChild(ageLabel);
    userField.appendChild(ageInput);

    usersFieldsContainer.appendChild(userField);
  }

  const enviarFormularioBtn = document.getElementById("enviar-formulario");
  enviarFormularioBtn.style.display = "block";
}
function submitForm() {
  const numUsers = document.getElementById("num-users").value;
  const planSelect = document.getElementById("plans-select");
  const planoSelecionado = planSelect.selectedIndex + 1;

  const formData = [];
  let isFormValid = true;

  for (let i = 0; i < numUsers; i++) {
    const nomeInput = document.getElementById(`nome-${i}`).value.trim();
    const idadeInput = document.getElementById(`idade-${i}`).value.trim();

    if (nomeInput === "" || idadeInput === "") {
      isFormValid = false;
      break;
    }

    formData.push({ nome: nomeInput, idade: idadeInput });
  }

  if (!isFormValid) {
    const errorMessage = document.getElementById("error-message");
    errorMessage.textContent = "Por favor, preencha todos os campos.";
    errorMessage.style.display = "block";
    return;
  }

  const xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        const response = JSON.parse(xhr.responseText);
        const dadosContainer = document.getElementById("dados-container");
        dadosContainer.innerHTML = `
          <table id="dadosTabela">
            <thead>
              <tr>
                <th>Plano</th>
                <th>Preço Total</th>
                <th>Beneficiários</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>${response.plano_nome}</td>
                <td>R$${response.preco_total},00</td>
                <td id="coluna-beneficiarios">
                  <ul>
                    ${response.beneficiarios
                      .map(
                        (beneficiario) =>
                          `<p id='response-proposta'>Nome: ${beneficiario.nome}, </br>Idade: ${beneficiario.idade},</br> Preço Individual: R$${beneficiario.preco},00 </br</p></hr></br>`
                      )
                      .join("")}
                  </ul>
                </td>
              </tr>
            </tbody>
          </table>
        `;

        const errorMessage = document.getElementById("error-message");
        errorMessage.style.display = "none";
      } else {
        console.error("Erro ao obter os dados do servidor:", xhr.status);
        const errorMessage = document.getElementById("error-message");
        errorMessage.textContent =
          "Ocorreu um erro ao processar a solicitação. Por favor, tente novamente mais tarde.";
        errorMessage.style.display = "block";
      }
    }
  };

  const url = "http://localhost:8001/Server/index.php";
  xhr.open("POST", url, true);
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.send(JSON.stringify({ beneficiarios: formData, planId: planoSelecionado }));
}



window.onload = displayPlansAndPrices;
