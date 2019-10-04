var str = $("#urlLink").text();
var str_pos = str.indexOf("s.put.re/delete");
if (str_pos > -1) {
    var splitted = str.split("/");
    var actUrl = "https://s.put.re/"+splitted[4];
    $("#urlLink").html(actUrl);
    console.log("CHanged text");
    document.getElementById("urlLink").href = actUrl;
    console.log("Its an upload");
    $("#output").html("<a href='"+str+"'> Delete file </a>");
} else {
}