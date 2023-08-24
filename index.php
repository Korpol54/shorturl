<?php
    $servername = "localhost";
    $username = "";
    $password = ""; 
    $dbname = "shorturl2";
// Connect Database
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

// Generation Short URL
    function generateShortUrl($length = 5) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $shortCode = '';

        for ($i = 0; $i < $length; $i++) {
            $randomIndex = rand(0, strlen($characters) - 1);
            $shortCode .= $characters[$randomIndex];
        }

        return $shortCode;
    }
// 
    if (isset($_POST['longUrl'])) {
        $longUrl = $_POST['longUrl'];
        $shortUrl = generateShortUrl();

        $sql = "INSERT INTO urls (long_url, short_url) VALUES ('$longUrl', '$shortUrl')";
        if ($conn->query($sql) === TRUE) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error creating short URL: " . $conn->error;
        }
    }   
// 
if (isset($_GET['code'])) {
    $shortCode = $_GET['code'];

    $sql = "SELECT id FROM click_history WHERE short_url = '$shortCode'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $sql = "UPDATE click_history SET click_count = click_count + 1 WHERE short_url = '$shortCode'";
        $conn->query($sql);
    } else {
        $sql = "INSERT INTO click_history (short_url, click_count) VALUES ('$shortCode', 1)";
        $conn->query($sql);
    }

    $sql = "SELECT long_url FROM urls WHERE short_url = '$shortCode'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $longUrl = $row['long_url'];
        echo "<script>window.location.href = '$longUrl';</script>";
        exit();
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Short URL</title>
    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mitr&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/ee7af74b6d.js" crossorigin="anonymous"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <style>
        * {
            font-family: 'Mitr', sans-serif;
        }
    </style>
    <!-- Header -->
    <div class="container-fluid pt-2 border-bottom">
        <h3 class="text-center"><i class="fa-solid fa-link" style="color: #31425e;"></i> ระบบ ShortURL</h3>
    </div>
    <!-- End -->

    <!-- Form Control -->
    <div class="container pt-5">
            <h4 class="text-center pb-3">ป้อน URL เพื่อทำการย่อ</h4>
            <div class="d-flex justify-content-center">
                <form class="row" method="POST">
                    <div class="col-10">
                      <label for="longUrl" class="visually-hidden">Original Url</label>
                      <input type="url" class="form-control" id="longUrl" name="longUrl" placeholder="www.example.com" required>
                    </div>
                    <div class="col-2">
                      <button type="submit" class="btn btn-primary mb-3">ย่อ</button>
                    </div>
                  </form>
            </div>
        <!-- Generation QR Code -->
    <div class="d-flex justify-content-center pt-3">
        <div class="text-center">
            <button class="btn btn-success" onclick="generateQRCode()">สร้าง QR Code</button>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        <div class="pt-4" id="qrcode"></div>
    </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>

        <script>
            let qrcode;
                function generateQRCode() {
                    let longUrl = prompt("Enter the URL for the QR Code:");
                        if (longUrl) {
                            if (qrcode) {
                                qrcode.clear(); 
                                qrcode = null; 
                                document.getElementById("qrcode").innerHTML = "";
                            }

                            qrcode = new QRCode(document.getElementById("qrcode"), {
                                text: longUrl,
                                width: 250,
                                height: 250,
                            });
                        }
                }
        </script>

    <!-- End -->

    
    <!-- History table -->
    <div class="container pt-5">
        <h3 class="text-center pb-3">ประวัติ</h3>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">URL</th>
                    <th scope="col">Short URL</th>
                    <th scope="col">Clicks</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                $perPage = 5;
                $offset = ($page - 1) * $perPage;
            
                $sql = "SELECT u.id, u.long_url, u.short_url, COALESCE(c.click_count, 0) AS click_count
                FROM urls u
                LEFT JOIN click_history c ON u.short_url = c.short_url
                ORDER BY u.id ASC
                LIMIT $offset, $perPage";
                $result = $conn->query($sql);
                if ($result) {
                    while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <th scope="row"><?= $row['id'] ?></th>
                <td><a href="<?= $row['long_url'] ?>" target="_blank"><?= $row['long_url'] ?></a></td>
                <td><a href="index.php?code=<?= $row['short_url'] ?>" target="_blank">https://shorturl/<?= $row['short_url'] ?>.com</a></td>
                <td><?= $row['click_count'] ?></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<!-- Pagination -->
    <div class="d-flex justify-content-center pt-3">
        <nav aria-label="Page navigation">
            <ul class="pagination">
            <?php
                $totalPages = ceil($conn->query("SELECT COUNT(*) FROM urls")->fetch_row()[0] / $perPage);
            
                if ($page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="?page=' . ($page - 1) . '" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
                } else {
                    echo '<li class="page-item disabled"><a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
                }
            
                for ($i = 1; $i <= $totalPages; $i++) {
                    echo '<li class="page-item ' . ($page === $i ? 'active' : '') . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                }
            
                if ($page < $totalPages) {
                    echo '<li class="page-item"><a class="page-link" href="?page=' . ($page + 1) . '" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
                } else {
                    echo '<li class="page-item disabled"><a class="page-link" href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
                }
            ?>
            </ul>
        </nav>
    </div>
    <!-- End -->

</body>
</html>
<?php
} else {
    echo "Error fetching URL history: " . $conn->error;
}
?>
