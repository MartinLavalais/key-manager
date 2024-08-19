/*
 * Author : Martin Lavalais
 * Web site : kms.atlas-eternal.com
 * Description : For register page, check content of input and send data to the api
 */

var registerTextHTML = null;
var registerIconHTML = null;

/**
 * Enable the register button
 */
var registerButtonEnabled = false;

/**
 * Initialisation of register page
 */
function initRegister()
{
    registerTextHTML = document.getElementById("confirm-text");
    registerIconHTML = document.getElementById("confirm-icon");
    document.getElementById("confirm-button").addEventListener("click", registerButtonClickEvent);
    const inputsHTML = document.getElementsByClassName("input");
    for(let i = 0; i < inputsHTML.length; i++)
    {
        let inputHTML = inputsHTML[i];
        inputHTML.addEventListener("change", inputChangeEvent);
    }
}

/**
 * Event for change content of input
 */
function inputChangeEvent()
{
    const buttonRegisterHTML = document.getElementById("confirm-button");

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
        console.log("[INFO] Condition for register are complete !")
        let color = "#02c2fc";
        registerButtonEnabled = true;
        buttonRegisterHTML.style.borderColor = color;
        buttonRegisterHTML.style.color = color;
    }
    else
    {
        console.log("[INFO] Condition for register are not complete !")
        let color = "#fc0228";
        registerButtonEnabled = true;
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
        let username = document.getElementById("register-username");
        let email = document.getElementById("register-email");
        let phone = document.getElementById("register-phone");
        let code = document.getElementById("register-code");
        let key = document.getElementById("register-key");
        const body = JSON.stringify({
            "username": username,
            "email": email,
            "phone": phone,
            "public_key": key,
            "code": code
        });
        /*
        const json = await fetch("/api/user", {method: "POST", body: body});
        const response = await json.json();
        */
        await new Promise(r => setTimeout(r, 2000));
        let response = {"status":"ok","result":"This is a test"};

        if (response.status === "ok")
        {
            registerIconHTML.innerHTML = "<svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"currentColor\" class=\"bi bi-check\" viewBox=\"0 0 16 16\"><path d=\"M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z\"/></svg>";
            setTimeout(() => { window.location.href = "/"; }, 3000);
        }
        else
        {
            alert(response.result);
        }
    }
    else
    {
        alert("Field(s) are missing !");
    }
}

initRegister();