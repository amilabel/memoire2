<?php
$conn = mysqli_connect("localhost", "root", "", "cbir");

if (isset($_FILES["fileImg"]["name"])) {
  $totalFiles = count($_FILES['fileImg']['name']);
  $filesArray = array();

  for ($i = 0; $i < $totalFiles; $i++) {
    $imageName = $_FILES["fileImg"]["name"][$i];
    $tmpName = $_FILES["fileImg"]["tmp_name"][$i];

    $imageExtension = explode('.', $imageName);

    $name = $imageExtension[0];
    $imageExtension = strtolower(end($imageExtension));

    $newImageName = $name . " - " . uniqid(); // Generate new image name
    $newImageName .= '.' . $imageExtension;

    if (!in_array($newImageName, $filesArray)) {
      move_uploaded_file($tmpName, 'upload/' . $newImageName);
      $filesArray[] = $newImageName;
    }
  }

  $filesArray = json_encode($filesArray);
  $query = "INSERT INTO  image VALUES(NULL, '$filesArray',4)";
  echo $query;

  if (!mysqli_query($conn, $query)) {
    printf("Error: %s\n", mysqli_error($conn));
  } else {
    echo "Success";
  }
} else {
  echo "Please Fill Out The Form!";
}
