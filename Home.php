<?php session_start();
if (empty($_SESSION))
    $_SESSION["id"] = 0;
else {
    $id = $_SESSION["id"];

    $host = "localhost";
    $user = "root";
    $pass = "";
    $nomedb = "aziendaviaggi";

    $conn = mysqli_connect($host, $user, $pass, $nomedb);
    if (!$conn)
        echo "errore";
    else {
        $istr = "SELECT nome, cognome, email FROM utenti WHERE id = '$id'";
        $ris = mysqli_query($conn, $istr);
        if (mysqli_num_rows($ris) == 0) {
            //$_SESSION["id"] = 0;
        } else {
            $riga = mysqli_fetch_assoc($ris);
        }
    }
}
if (isset($_POST["logout"]))
    $_SESSION["id"] = 0;

?>
<!DOCTYPE html>
<html>

<head>
    <title>Home</title>
    <link rel="stylesheet" href="Home.css">
    <link rel="stylesheet" href="RadioButton.css">
    <link rel="stylesheet" href="Select.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div class="header">
        <a class="header-h1" href="Home.php">
            <img src="Immagini/logo3.png">
        </a>
        <div class="header-right">
            <?php
            if ($_SESSION["id"] == 0) {
            ?>
                <a href="Register.php">Registrati</a>
                <a href="login.php">Login</a>
            <?php
            } else {
            ?>
                <form action="Home.php" method="POST">
                    <a href="GestioneAccount.php">Account</a>
                    <input class="header-button" type="submit" name="logout" value="Log out">
                </form>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="body">
        <div class="benvenuto">
            <h1 id="benv">
                <?php
                if ($_SESSION["id"] == 0) {
                    echo "Benvenuto! Accedi o registrati!";
                } else {
                    echo "Benvenuto " . $riga["nome"] . " " . $riga["cognome"] . "!";
                }
                ?>
            </h1>
        </div>
        <div>
            <form class="body-content" method="POST" action="">
                <div class="filters">
                    <div class="form-filters">
                        <div class="select">
                            <p>Filtra per regione</p>
                            <select onchange="reg(this)" name="regioni">
                                <option value="all" selected='selected'>--Seleziona--</option>
                                <?php
                                $istr = "SELECT nome FROM regioni";
                                $ris = mysqli_query($conn, $istr);
                                if (isset($_GET["regione"]))
                                    $regione = $_GET["regione"];
                                else
                                    $regione = "null";
                                while ($row = mysqli_fetch_array($ris)) {
                                    if ($row['nome'] == $regione)
                                        echo "<option selected='selected' value=\"" . $row['nome'] . "\">" . $row['nome'] . "</option> \n";
                                    else
                                        echo "<option value=\"" . $row['nome'] . "\">" . $row['nome'] . "</option> \n";
                                }
                                ?>
                            </select>
                            <div class="select-icon">
                                <svg focusable="false" viewBox="0 0 104 128" width="25" height="35" class="icon">
                                    <path d="m2e1 95a9 9 0 0 1 -9 9 9 9 0 0 1 -9 -9 9 9 0 0 1 9 -9 9 9 0 0 1 9 9zm0-3e1a9 9 0 0 1 -9 9 9 9 0 0 1 -9 -9 9 9 0 0 1 9 -9 9 9 0 0 1 9 9zm0-3e1a9 9 0 0 1 -9 9 9 9 0 0 1 -9 -9 9 9 0 0 1 9 -9 9 9 0 0 1 9 9zm14 55h68v1e1h-68zm0-3e1h68v1e1h-68zm0-3e1h68v1e1h-68z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="select">
                            <p>Filtra per provincia</p>
                            <select onchange="prov(this)" name="province">
                                <?php
                                if (isset($_GET["regione"]) && $_GET["regione"] != "all") {
                                    echo "<option value='all'>--Seleziona--</option>";
                                    $regione = $_GET["regione"];

                                    $istr = "SELECT p.nome as prov, r.nome as reg FROM province as p INNER JOIN regioni as r ON p.id_regione = r.id WHERE r.nome = \"$regione\"";
                                    $ris = mysqli_query($conn, $istr);

                                    if (isset($_COOKIE["provincia"]))
                                        $prov = $_COOKIE["provincia"];
                                    else
                                        $prov = "null";
                                    while ($row = mysqli_fetch_array($ris)) {
                                        if ($row['prov'] == $prov)
                                            echo "<option selected='selected' data-option=\"" . $row['reg'] . "value=\"" . $row['prov'] . "\">" . $row['prov'] . "</option> \n";
                                        else
                                            echo "<option data-option=\"" . $row['reg'] . "value=\"" . $row['prov'] . "\">" . $row['prov'] . "</option> \n";
                                    }
                                } else {
                                    echo "<option disabled selected='selected'>" . "--Seleziona una regione--" . "</option>";
                                }
                                ?>
                            </select>
                            <div class="select-icon">
                                <svg focusable="false" viewBox="0 0 104 128" width="25" height="35" class="icon">
                                    <path d="m2e1 95a9 9 0 0 1 -9 9 9 9 0 0 1 -9 -9 9 9 0 0 1 9 -9 9 9 0 0 1 9 9zm0-3e1a9 9 0 0 1 -9 9 9 9 0 0 1 -9 -9 9 9 0 0 1 9 -9 9 9 0 0 1 9 9zm0-3e1a9 9 0 0 1 -9 9 9 9 0 0 1 -9 -9 9 9 0 0 1 9 -9 9 9 0 0 1 9 9zm14 55h68v1e1h-68zm0-3e1h68v1e1h-68zm0-3e1h68v1e1h-68z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="stelle">
                            <p>Stelle</p>
                            <label class="container">Tutti
                                <input type="radio" <?php if (!isset($_POST['radio']) || (isset($_POST['radio']) && $_POST['radio'] == 'all')) echo ' checked="checked"' ?> name="radio" id="all" value="all">
                                <span class="checkmark"></span>
                            </label>
                            <label class="container">5 stelle
                                <input type="radio" <?php if ((isset($_POST['radio']) && $_POST['radio'] == '5')) echo ' checked="checked"' ?> name="radio" id="5" value="5">
                                <span class="checkmark"></span>
                            </label>
                            <label class="container">4 stelle
                                <input type="radio" <?php if ((isset($_POST['radio']) && $_POST['radio'] == '4')) echo ' checked="checked"' ?> name="radio" id="4" value="4">
                                <span class="checkmark"></span>
                            </label>
                            <label class="container">3 stelle
                                <input type="radio" <?php if ((isset($_POST['radio']) && $_POST['radio'] == '3')) echo ' checked="checked"' ?> name="radio" id="3" value="3">
                                <span class="checkmark"></span>
                            </label>
                            <label class="container">2 stelle
                                <input type="radio" <?php if ((isset($_POST['radio']) && $_POST['radio'] == '2')) echo ' checked="checked"' ?> name="radio" id="2" value="2">
                                <span class="checkmark"></span>
                            </label>
                            <label class="container">1 stella
                                <input type="radio" <?php if ((isset($_POST['radio']) && $_POST['radio'] == '1')) echo ' checked="checked"' ?> name="radio" id="1" value="1">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="search-content">
                    <div class="search">
                        <div class="search-bar">
                            <input type="text" name="text" placeholder="Search...">
                            <button type="submit" name="search">
                                <svg viewBox="0 0 1024 1024">
                                    <path class="path1" d="M848.471 928l-263.059-263.059c-48.941 36.706-110.118 55.059-177.412 55.059-171.294 0-312-140.706-312-312s140.706-312 312-312c171.294 0 312 140.706 312 312 0 67.294-24.471 128.471-55.059 177.412l263.059 263.059-79.529 79.529zM189.623 408.078c0 121.364 97.091 218.455 218.455 218.455s218.455-97.091 218.455-218.455c0-121.364-103.159-218.455-218.455-218.455-121.364 0-218.455 97.091-218.455 218.455z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="content">
                        <?php
                        $istr = "SELECT a.Nome FROM Albergo AS a INNER JOIN province AS p ON a.Provincia = p.id INNER JOIN regioni AS r ON p.id_regione = r.id";
                        if (isset($_POST["search"])) {
                            echo "if 1";
                            $filters = array();
                            $i = 0;
                            if (!empty($_POST["regioni"]) && $_POST["regioni"] != "all") {
                                $regione = $_POST["regioni"];
                                $filters[$i] = " r.nome = \"$regione\" ";
                                $i++;
                            }

                            if (!empty($_POST["province"]) && $_POST["province"] != "all") {
                                $provincia = $_POST["province"];
                                $filters[$i] = " p.nome = \"$provincia\" ";
                                $i++;
                            }

                            if (!empty($_POST["radio"]) && $_POST["radio"] != "all") {
                                $stelle = $_POST["radio"];
                                $filters[$i] = " a.Stelle = \"$stelle\" ";
                                $i++;
                            }

                            if (!empty($_POST["text"])) {
                                $testo = $_POST["text"];
                                $filters[$i] = " a.Nome LIKE \"%$testo%\" ";
                                $i++;
                            }

                            for ($n = 0; $n < $i; $n++) {
                                if ($n == 0)
                                    $istr .= " WHERE ";
                                else
                                    $istr .= " AND ";
                                $istr .= $filters[$n];
                            }
                            $_SESSION["istr"] = $istr;
                        }
                        if (!isset($_GET["regione"])) {
                            echo "if 2";
                            unset($_SESSION["istr"]);
                        }
                        if (!empty($_SESSION["istr"])) {
                            echo "if 3";
                            $istr = $_SESSION["istr"];
                        }

                        echo $istr . "<br>";
                        $ris = mysqli_query($conn, $istr);
                        if (mysqli_num_rows($ris) == 0)
                            echo "Nessun hotel";
                        else
                            while ($row = mysqli_fetch_array($ris)) {
                                echo $row["Nome"] . "<br>";
                            }

                        ?>
                    </div>
                </div>
            </form>
        </div>
        <?php

        var_dump($_COOKIE);
        var_dump($_GET);
        ?>
</body>
<script>
    function reg(selected) {
        var regione = selected.value;
        if (regione == "all")
            window.location.href = "Home.php";
        else
            window.location.href = "Home.php?regione=" + regione;

    }

    function prov(selected) {
        var provincia = selected.value;
        document.cookie = "provincia =" + provincia;
    }
</script>

</html>