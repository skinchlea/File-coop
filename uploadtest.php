<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Page title</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>

<h1> upload </h1>

<input id="newFile" type="file" name="newfile" />
<button id="upload">Upload</button>

</body>
</html>

<script>

$('#upload').on('click', function() {
    var file_data = $('#newFile').prop('files')[0];
    var form_data = new FormData();
    form_data.append('file', file_data);
    console.log(form_data);
    $.ajax({
        url: 'uploadfile.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
          if (data == "Uploaded"){
            console.log(data);
          } else {
            console.log("error: " + data);
          }

        }
     });
});

</script>
