<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <input type="date" id="tanggal">


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#tanggal").on("change", function() {
                let val = $(this).val();
                $("#hasil").text("Tanggal yang dipilih: " + val);
                console.log("Tanggal berubah:", val);
            });
        });
    </script>
</body>

</html>