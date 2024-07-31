<?php
// install.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = $_POST['host'];
    $db = $_POST['db'];
    $user = $_POST['user'];
    $pass = $_POST['pass'];

    // Crear archivo db.php
    $dbConfigContent = "<?php\n// db.php\n\n";
    $dbConfigContent .= "\$host = '$host';\n";
    $dbConfigContent .= "\$db = '$db';\n";
    $dbConfigContent .= "\$user = '$user';\n";
    $dbConfigContent .= "\$pass = '$pass';\n";
    $dbConfigContent .= "\$charset = 'utf8mb4';\n\n";
    $dbConfigContent .= "\$dsn = \"mysql:host=\$host;dbname=\$db;charset=\$charset\";\n";
    $dbConfigContent .= "\$options = [\n";
    $dbConfigContent .= "    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,\n";
    $dbConfigContent .= "    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,\n";
    $dbConfigContent .= "    PDO::ATTR_EMULATE_PREPARES   => false,\n";
    $dbConfigContent .= "];\n\n";
    $dbConfigContent .= "try {\n";
    $dbConfigContent .= "    \$pdo = new PDO(\$dsn, \$user, \$pass, \$options);\n";
    $dbConfigContent .= "} catch (PDOException \$e) {\n";
    $dbConfigContent .= "    throw new PDOException(\$e->getMessage(), (int)\$e->getCode());\n";
    $dbConfigContent .= "}\n";

    file_put_contents('db.php', $dbConfigContent);
    
    require 'db.php';
    
    // Ejecutar SQL de esquema
    $sql = file_get_contents('schema.sql');
    $pdo->exec($sql);

    function generateRandomPassword($length = 12) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $password;
    }

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }

    $adminPassword = generateRandomPassword();
    $hashedPassword = password_hash($adminPassword, PASSWORD_BCRYPT);

    $stmt = $pdo->prepare('INSERT INTO users (username, password, role) VALUES (?, ?, ?)');
    $stmt->execute(['admin', $hashedPassword, 'admin']);

    $adminMessage = "Admin user created with username 'admin' and password '$adminPassword'";

    // Mostrar el mensaje en la página y el botón de login
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Install</title>
        <style>
		@import url(https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap);

html body {
  font-family: Poppins
  
}
            body {
                font-family: Poppins;
                background-color: #f0f2f5;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .container {
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                padding: 20px;
                max-width: 400px;
                width: 100%;
            }
            h1 {
                text-align: center;
                color: #333;
            }
            .alert {
                background-color: #ffdddd;
                border-left: 6px solid #f44336;
                margin-bottom: 15px;
                padding: 10px;
                text-align: center;
                color: #a94442;
                border-radius: 4px;
            }
            label {
                display: block;
                margin-bottom: 8px;
                color: #555;
            }
            input[type='text'], input[type='password'] {
                width: calc(100% - 20px);
                padding: 10px;
                margin-bottom: 20px;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
            }
            button {
				font-family: Poppins;
                width: 100%;
                padding: 10px;
                border: none;
                border-radius: 4px;
                background-color: #007bff;
                color: white;
                font-size: 16px;
                cursor: pointer;
                text-decoration: none;
                display: inline-block;
                text-align: center;
            }
            button:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='alert'>$adminMessage</div>
            <a href='login.php'><button>Login</button></a>
        </div>
    </body>
    </html>";

    // Eliminar el archivo install.php y schema.sql
    unlink(__FILE__);
    unlink('schema.sql');
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Install</title>
        <style>
			@import url(https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap);

html body {
  font-family: Poppins
  
}

            body {
                font-family: Poppins;
                background-color: #f0f2f5;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .container {
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                padding: 20px;
                max-width: 400px;
                width: 100%;
            }
            h1 {
                text-align: center;
                color: #333;
            }
            label {
                display: block;
                margin-bottom: 8px;
                color: #555;
            }
            input[type="text"], input[type="password"] {
                width: calc(100% - 20px);
                padding: 10px;
                margin-bottom: 20px;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
            }
            button {
				font-family: Poppins;
                width: 100%;
                padding: 10px;
                border: none;
                border-radius: 4px;
                background-color: #007bff;
                color: white;
                font-size: 16px;
                cursor: pointer;
            }
            button:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Install</h1>
            <form method="post" action="">
                <label for="host">Host:</label>
                <input type="text" name="host" id="host" required>
                
                <label for="db">Database Name:</label>
                <input type="text" name="db" id="db" required>
                
                <label for="user">Database User:</label>
                <input type="text" name="user" id="user" required>
                
                <label for="pass">Database Password:</label>
                <input type="password" name="pass" id="pass" required>
                
                <button type="submit">Install</button>
            </form>
        </div>
    </body>
    </html>
    <?php
}
?>