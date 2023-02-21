<form action="" method="post" enctype="multipart/form-data">
  <input type="file" name="imageToCompare" onchange="previewImage(event)">
  <img id="preview" src="#" alt="Preview" style="max-width:200px; max-height:200px;">
  <input type="submit" value="search">
</form>

<script>
function previewImage(event) {
  var preview = document.getElementById('preview');
  preview.src = URL.createObjectURL(event.target.files[0]);
  preview.style.display = "block";
}
</script>
<?php
// Define the histogramme function
function histogramme($image)
{
    $hist_r = array_fill(0, 256, 0);
    $hist_g = array_fill(0, 256, 0);
    $hist_b = array_fill(0, 256, 0);
    $largeurimage = imagesx($image);
    $hauteurimage = imagesy($image);
    for ($i = 0; $i < $largeurimage; $i++)
        for ($j = 0; $j < $hauteurimage; $j++) {
            $rgb = imagecolorat($image, $i, $j);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;
            $hist_r[$r]++;
            $hist_g[$g]++;
            $hist_b[$b]++;
        }
    $hist = array_merge($hist_r, $hist_g, $hist_b);
    return $hist;
}
?>

<?php
// Define the folder path where the images are stored
$folderPath = "upload/";

// Check if a file was uploaded
if(isset($_FILES['imageToCompare'])) {

    // Get the file name of the image to compare
    $imageToCompare = $_FILES['imageToCompare']['tmp_name'];

    // Check if the file was successfully uploaded and is an image file
    if(is_uploaded_file($imageToCompare) && getimagesize($imageToCompare)) {

        // Move the uploaded file to a permanent location
        $targetFile = $folderPath . basename($_FILES['imageToCompare']['name']);
        if(move_uploaded_file($_FILES['imageToCompare']['tmp_name'], $targetFile)) {

            // Call the histogramme function to get the histogram of the image to compare
            $imageToCompareHist = histogramme(imagecreatefromjpeg($targetFile));

            // Get the list of files in the folder
            $fileList = glob($folderPath . '*');

            // Initialize variables for finding the closest match
            $closestMatchFile = "";
            $closestMatchDistance = PHP_INT_MAX;

            // Loop through each file in the folder
            foreach($fileList as $file) {
                
                // Check if the file is an image file
                if(!getimagesize($file)) continue;

                // Call the histogramme function to get the histogram of the current file
                $currentFileHist = histogramme(imagecreatefromjpeg($file));

                // Calculate the distance between the histograms of the two images
                $distance = 0;
                for($i = 0; $i < count($imageToCompareHist); $i++) {
                    $distance += abs($imageToCompareHist[$i] - $currentFileHist[$i]);
                }

                // Check if this is the closest match so far
                if ($distance < $closestMatchDistance) {
                    $closestMatchFile = $file;
                    $closestMatchDistance = $distance;
                }
            }

            // Display the closest match image
            if ($closestMatchFile != "") {
                echo '<img src="'.$closestMatchFile.'">';
            } else {
                echo "No image found";
            }

        } else {
            echo "Error moving uploaded file";
        }

    } else {
        echo "Error uploading file or invalid image file";
    }
}
?>