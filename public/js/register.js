/*
 * Author : Martin Lavalais
 * Web site : kms.atlas-eternal.com
 * Description : For register page, check content of input and send data to the api
 */

var registerTextHTML = null;
var registerIconHTML = null;
var buttonRegisterHTML = null;

var registerButtonEnabled = false;

/**
 * Initialisation of register page
 */
function initRegister()
{
    buttonRegisterHTML = document.getElementById("confirm-button");
    registerTextHTML = document.getElementById("confirm-text");
    registerIconHTML = document.getElementById("confirm-icon");
    document.getElementById("confirm-button").addEventListener("click", registerButtonClickEvent);
    const inputsHTML = document.getElementsByClassName("input");
    for(let i = 0; i < inputsHTML.length; i++)
    {
        let inputHTML = inputsHTML[i];
        inputHTML.addEventListener("keyup", inputChangeEvent);
    }
}

/**
 * Event for change content of input
 */
function inputChangeEvent()
{
    let inputsFull = true;
    const inputsHTML = document.getElementsByClassName("input");
    for(let i = 0; i < inputsHTML.length; i++)
    {
        let inputHTML = inputsHTML[i];
        if (inputHTML.value === "")
            inputsFull = false;
    };

    if (inputsFull)
    {
        registerTextHTML.innerHTML = "SEND";
        registerIconHTML.innerHTML = "";
        console.log("[INFO] Condition for register are complete !")
        let color = "#02c2fc";
        registerButtonEnabled = true;
        buttonRegisterHTML.style.borderColor = color;
        buttonRegisterHTML.style.color = color;
    }
    else
    {
        registerTextHTML.innerHTML = "";
        registerIconHTML.innerHTML = "<svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"currentColor\" class=\"bi bi-x\" viewBox=\"0 0 16 16\"><path d=\"M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708\"/></svg>";
        console.log("[INFO] Condition for register are not complete !");
        let color = "#fc0228";
        registerButtonEnabled = false;
        buttonRegisterHTML.style.borderColor = color;
        buttonRegisterHTML.style.color = color;
    }
}

async function registerButtonClickEvent()
{
    if (registerButtonEnabled)
    {
        registerTextHTML.innerHTML = "";
        registerIconHTML.innerHTML = "<div class=\"spinner-border text-info\" role=\"status\"><span class=\"visually-hidden\">Loading...</span></div>";
        let username = document.getElementById("register-username").value;
        let email = document.getElementById("register-email").value;
        let phone = document.getElementById("register-phone").value;
        let code = document.getElementById("register-code").value;
        let key = document.getElementById("register-key").value;
        const body = JSON.stringify({
            "username": username,
            "email": email,
            "phone": phone,
            "public_key": key,
            "code": code
        });
        
        const json = await fetch("/api/user/", {method: "POST", body: body});
        const response = await json.json();

        if (response.status === "ok")
        {
            registerIconHTML.innerHTML = "<svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"currentColor\" class=\"bi bi-check\" viewBox=\"0 0 16 16\"><path d=\"M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z\"/></svg>";
            setTimeout(() => { window.location.href = "/"; }, 3000);
        }
        else
        {
            registerIconHTML.innerHTML = "<svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"currentColor\" class=\"bi bi-x\" viewBox=\"0 0 16 16\"><path d=\"M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708\"/></svg>";
            let color = "#fc0228";
            buttonRegisterHTML.style.borderColor = color;
            buttonRegisterHTML.style.color = color;
            alert(response.result)
        }
    }
    else
    {
        alert("Field(s) are missing !");
    }
}

initRegister();