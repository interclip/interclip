let str = $("#urlLink").text();
const https = str.indexOf("https");
const http = str.indexOf("http");

if (https > -1) {
    str = str.split("https://").pop();
    $("#urlLink").text(str);
} else if(http > -1){
    str = str.split("http://").pop();
    $("#urlLink").text(str);
}
