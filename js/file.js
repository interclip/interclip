(function(window) {
    function triggerCallback(e, callback) {
      if(!callback || typeof callback !== 'function') {
        return;
      }
      var files;
      if(e.dataTransfer) {
        files = e.dataTransfer.files;
      } else if(e.target) {
        files = e.target.files;
      }
      callback.call(null, files);
    }
    function makeDroppable(ele, callback) {
      var input = document.createElement('input');
      input.setAttribute('type', 'file');
      input.setAttribute('multiple', true);
      input.style.display = 'none';
      input.addEventListener('change', function(e) {
        triggerCallback(e, callback);
      });
      ele.appendChild(input);
      
      ele.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        ele.classList.add('dragover');
      });

      ele.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        ele.classList.remove('dragover');
      });

      ele.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        ele.classList.remove('dragover');
        triggerCallback(e, callback);
      });
      
      ele.addEventListener('click', function() {
        input.value = null;
        input.click();
      });
    }
    window.makeDroppable = makeDroppable;
  })(this);
  (function(window) {
    makeDroppable(window.document.querySelector('.demo-droppable'), function(files) {
      console.log(files);
      var output = document.querySelector('.output');
      output.innerHTML = '';
      for(var i=0; i<files.length; i++) {
        if(files[i].type.indexOf('image/') === 0) {
          output.innerHTML += '<img width="200" src="' + URL.createObjectURL(files[i]) + '" />';
        }
        output.innerHTML += '<p>'+files[i].name+'</p>';
      }
      
      uploadRe(putRe(files[0]));
    });
  })(this);
  function putRe(file) {
    return file;
}
function uploadRe($files) {
    
      //var $files = $(this).get(0).files;

        console.log($files);
        /*
      if ($files.length) {
    
        // Reject big files
        if ($files[0].size > $(this).data("max-size") * 4096) {
          console.log("Please select a smaller file");
          return false;
        }
    */
        // Begin file upload
        console.log("Uploading file to put.re..");
    
        // Replace ctrlq with your own API key
        var apiUrl = 'https://api.put.re/upload';
    
        var settings = {
          async: false,
          crossDomain: true,
          processData: false,
          contentType: false,
          type: 'POST',
          url: apiUrl,
          mimeType: 'multipart/form-data'
        };
    
        var formData = new FormData();
        formData.append("image", $files);
        settings.data = formData;
    
        // Response contains stringified JSON
        // Image URL available at response.data.link
        
        $.ajax(settings).done(function(response) {
          console.log(response);
        });
    
      }
