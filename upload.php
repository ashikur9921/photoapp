<?php
include 'includes/header.php';

$errors = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $title = $_POST['title'];
  $description = $_POST['description'];
  $image = $_FILES['image'];

  if (empty($title) || empty($description) || empty($image['name'])) {
      $errors = "Please fill all the fields.";
  } 
  elseif ($image['size'] > (5 * 1024 * 1024)) { // 5MB = 5*1024*1024 bytes
      $errors = "Image size must be less than 5MB.";
  } 
  else {
      $image_name = time() . '_' . basename($image['name']);
      $tmp_name = $image['tmp_name'];

      $upload_folder = 'uploads/';
      
      if (!is_dir($upload_folder)) {
          mkdir($upload_folder, 0777, true);
      }

      $target_file = $upload_folder . $image_name;

      if (move_uploaded_file($tmp_name, $target_file)) {
          $stmt = $pdo->prepare("INSERT INTO images (title, description, filename) VALUES (:title, :description, :image)");
          $stmt->execute([
              ':title' => $title,
              ':description' => $description,
              ':image' => $target_file
          ]);

          $success = "Image uploaded and data inserted successfully!";
      } else {
          $errors = "Failed to upload image.";
      }
  }
}


 ?>

<div class="container">
 
<div class="my-5">
    <h1>Upload</h1>
</div>

<?php if (!empty($success)) : ?>
    <div class="alert alert-success" role="alert">
        <?php echo htmlspecialchars($success); ?>
    </div>
<?php endif; ?>

<?php if (!empty($errors)) : ?>
    <div class="alert alert-danger" role="alert">
        <?php echo htmlspecialchars($errors); ?>
    </div>
<?php endif; ?>


<div class="row">

<form  method="post" enctype="multipart/form-data">
  <div class="mb-3">
    <label for="exampleInputEmail1">Title</label>
    <input type="text" name="title" class="form-control">
 
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1">Description</label>
    <input type="text" name="description" class="form-control" >
  </div>
<div>
  <div class="form-group">
    <input type="file" name="image" id="imageInput" onchange="previewImage(event)" /> <br>
    <img id="preview" src="#" style="display:none; max-width:100px; max-height: 100px; margin-top:10px;" alt="Image Preview"><br>
  </div>
</div>
  <button type="submit" class="bg-primary border-0 text-white p-2 mt-1 rounded" >Upload photo</button>
</form>

</div>
<?php
include 'includes/footer.php';

 ?>


</div>

<!-- JavaScript part -->
<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    const file = event.target.files[0];

    if(file){
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = "block";
        }
        reader.readAsDataURL(file);
    }
}

function clearPreview() {
    document.getElementById('preview').style.display = 'none';
}
</script>