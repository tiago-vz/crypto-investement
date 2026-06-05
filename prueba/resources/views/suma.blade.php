<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>suma de dos numeros</h2>
    <form action="/suma" method="post">
        @csrf
        <label for="num1">numero 1</label>
        <input type="number" name="num1" placeholder="numero 1">
        <br>

        <label for="num2">numero 2</label>
        <input type="number" name="num2" placeholder="numero 2">
        <br> 
        <button type="submit">sumar</button>
    </form>
    <br>
    @isset($suma)
        <h3>la suma es: {{ $suma }}</h3>
    <br>
    @endisset
    
</body>
</html>