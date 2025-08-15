<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 Error</title>
</head>
<style>
    *{
        margin:0;
        padding: 0;
    }
    body{
        display: flex;
        justify-content: center; 
        align-items: center;
        background:#FDF9FF;
        height:100vh;
    }
    button{
        background:#4A006D;
        padding:8px 10px;
        border:transparent;
        width:60%;
        margin:10px auto;
        color:white;
        font-size:18px;
        border-radius:5px;
    }
    h2{
        margin: 10px 0px;
    }
</style>
<body>
<div style="text-align: center;">
    <img src="{{asset('/public/500.png')}}" alt="error" width="400">
    <h2>Error 500: Internal Server Error </h2>
    <p>Please Try again later or feel free to contact us if the problem persist.</p>
    <button onClick="window.location.reload();">Relaod</button>
</div>
</body>
</html>