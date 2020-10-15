<!-- bootstrap theme -->

<link rel="stylesheet" href="/css/bootstrap.min.css">
<script src="/js/jquery-3.2.1.slim.min.js"></script>
<script src="/js/popper.min.js"></script>
<script src="/js/bootstrap.min.js"></script>

<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        background-color: #ADABAB;
    }

    .header {
        overflow: hidden;
        background-color: #f1f1f1;
        padding: 20px 10px;
    }

    .header a {
        float: left;
        color: black;
        text-align: center;
        padding: 12px;
        text-decoration: none;
        font-size: 18px;
        line-height: 25px;
        border-radius: 4px;
    }

    .header a.logo {
        font-size: 25px;
        font-weight: bold;
    }

    .header a:hover {
        background-color: #ddd;
        color: black;
    }

    .header a.active {
        background-color: dodgerblue;
        color: white;
    }

    .header-right {
        float: right;
    }

    @media screen and (max-width: 500px) {
        .header a {
            float: none;
            display: block;
            text-align: left;
        }

        .header-right {
            float: none;
        }
    }
</style>
</head>
<body>

<div class="header">
    <a href="/" class="logo">AnApp</a>
    <div class="header-right">
        <?php
            if(isset($_SESSION['admin']))
                echo '<a href="/admin.php">Admin</a>';

            if(!isset($_SESSION['username'])){
                echo "<a href='/login.php'>Login</a>";
            }
            else{
                echo "<a href='/list_messages.php'>Message</a>";
                echo "<a href='/change_password.php'>Change password</a>";
                echo "<a href='/logout.php'>Logout</a>";
            }
        ?>
    </div>
</div>
