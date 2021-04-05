<?php
function loadRegistrations($filename)
{
  if (file_exists($filename)) {
    $jsondata = file_get_contents($filename);
    $arrData = json_decode($jsondata, true);
  } else {
    file_put_contents($filename, '');
    $arrData = [];
  }
  return $arrData;
}

function saveDataJSON($filename, $name, $email, $phone)
{
  try {
    $contact = [
      'name' => $name,
      'email' => $email,
      'phone' => $phone
    ];
    $arrData = loadRegistrations($filename);
    array_push($arrData, $contact);
    $jsondata = json_encode($arrData, JSON_PRETTY_PRINT);
    file_put_contents($filename, $jsondata);
    echo "Đăng ký thành công";
  } catch (Exception $exception) {
    echo "Lỗi: " . $exception->getMessage() . "<br>";
  }
}

$errors = [];
$registrations = loadRegistrations('data.json') ?? [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $name = $_POST['name'] ?? null;
  $email = $_POST['email'] ?? null;
  $phone = $_POST['phone'] ?? null;

  if (empty($name)) {
    $errors[] = "Tên đăng nhập không được để trống!";
  }
  if (empty($email)) {
    $errors[] = "Email không được để trống!";
  } else {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors[] = "Định dạng email sai (xxx@xxx.xxx.xxx)!";
    }
  }
  if (empty($phone)) {
    $errors[] = " Số điện thoại không được để trống!";
  }

  if (empty($errors)) {
    saveDataJSON("data.json", $name, $email, $phone);
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>[Bài tập] Trang đăng ký người dùng</title>
  <style>
    form>div {
      margin: 10px 0;
    }

    ul {
      padding: 0px;
      list-style-type: none;
    }

    table {
      text-align: center;
    }
  </style>
</head>

<body>
  <?php if (!empty($errors)) : ?>
    <ul>
      <?php foreach ($errors as $error) : ?>
        <li><?= $error ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <h2>Form đăng ký</h2>
  <form action="" method="post">
    <div>
      <input type="name" name="name" id="name">
      <label for="name">Tên người dùng</label>
    </div>
    <div>
      <input type="email" name="email" id="email">
      <label for="email">Email</label>
    </div>
    <div>
      <input type="number" name="phone" id="phone">
      <label for="phone">Số điện thoại</label>
    </div>
    <div>
      <input type="submit" value="Submit">
    </div>
  </form>

  <h2>Registation List</h2>
  <table>
    <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($registrations)) : ?>
        <?php foreach ($registrations as $registration) : ?>
          <tr>
            <td><?= $registration['name']; ?></td>
            <td><?= $registration['email']; ?></td>
            <td><?= $registration['phone']; ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</body>

</html>
