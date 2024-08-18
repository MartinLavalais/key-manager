/**
 * Author : Martin Lavalais
 * Website : kms.atlas-eternal.com
 * Description : Just set the year in the copyright text
 */

function setCopyrightYear()
{
    const date = new Date(Date.now());
    const year = date.getFullYear();
    document.getElementById("footer-copyright-year").innerText = year;
    console.log("[INFO] Copyright year set !");
}

setCopyrightYear();