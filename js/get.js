let str = document.getElementById("urlLink").innerText;
const https = str.indexOf("https");
const http = str.indexOf("http");

if (https > -1) {
    str = str.split("https://").pop();
    document.getElementById("urlLink").innerText = str;
} else if(http > -1){
    str = str.split("http://").pop();
    document.getElementById("urlLink").innerText = str;
}
