<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 Error</title>
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
    <img src="{{asset('/public/503.png')}}" alt="error" width="400">
    <h2>Service Unavailable</h2>
    <p>The server is temporally busy. try again later </p>
    <button onClick="window.location.reload();">Relaod</button>
</div>
</body>
</html>