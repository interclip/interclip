var feedback = function(res) {
    if (res.success === true) {
        var get_link = res.data.link.replace(/^http:\/\//i, 'https://');
        document.querySelector('.status').classList.add('bg-success');

           $.post("api.php",
           {
             url: get_link,
           },
           function(data,status){
            console.log("Data: " + data + "\nStatus: " + status);
            if(status == "success") {
              $("img").attr("src", get_link);
              $("#code").text(data);
              $("#myModal").show();
            }
 
           });
    }
};

new Imgur({
    clientid: '4409588f10776f7', 
    callback: feedback
});