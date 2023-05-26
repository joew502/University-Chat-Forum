<html>
<head>
    <title>Testing</title>
    <style>
        body{
            background: #242424;
            color: white;
        }
        table{
            width: 100%;
        }
        thead>tr>th:nth-child(2){
            width: 100px;
        }
        thead>tr>th:nth-child(1){
            width: 25px;
        }
        .testing_lvl-1{
            color: #21bc21;
        }
        .testing_lvl0{
            color: #bababa;
        }
        .testing_lvl1{
            color: #ffffff;
        }
        .testing_lvl2{
            color: #ffdf00;
        }
        .testing_lvl3{
            color: #ff0000;
        }
        .testing_lvl4{
            color: #ff0000;
            font-weight: 700;
        }
        .testing_lvl5{
            color: #000000;
            font-weight: 900;
            text-shadow:  1px -1px 0px red,  1px 0px 0px red,  1px 1px 0px red,
                          0px -1px 0px red,  0px 0px 0px red,  0px 1px 0px red,
                         -1px -1px 0px red, -1px 0px 0px red, -1px 1px 0px red;
        }
    </style>
</head>
<body>
<table>
    <thead>
        <tr>
            <th></th><th>Level</th><th>Message</th>
        </tr>
    </thead>
    <tbody>
        <?php echo $content; ?>
    </tbody>
</table>
</body>
</html>