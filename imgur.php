<script>
$('#filebrow').change(function() {
   var reader = new FileReader();
   reader.onload = function(e) {
   //this next line separates url from data
   var iurl = e.target.result.substr(e.target.result.indexOf(",") + 1, e.target.result.length);
   var clientId = "CLIENT ID HERE";               
   $.ajax({
    url: "https://api.imgur.com/3/upload",
    type: "POST",
    datatype: "json",
    data: {
    'image': iurl,
    'type': 'base64'
    },
    success: fdone,//calling function which displays url
    error: function(){alert("failed")},
    beforeSend: function (xhr) {
        xhr.setRequestHeader("Authorization", "Client-ID " + clientId);
    }
});
};
 reader.readAsDataURL(this.files[0]);
});
function fdone(data) //this function is called on success from ajax
{
   alert(data.data.link);     
}
</script>