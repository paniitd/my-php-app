<?php
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $enrollment = preg_replace('/[^A-Za-z0-9]/', '', $_POST['enrollment']);

    if (empty($enrollment)) {
        $message = "<div class='error'>Please enter Enrollment Number.</div>";
    } elseif (!isset($_FILES['pdf'])) {
        $message = "<div class='error'>Please select a PDF file.</div>";
    } else {

        $uploadDir = "upload/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileType = strtolower(pathinfo($_FILES["pdf"]["name"], PATHINFO_EXTENSION));

        if ($fileType != "pdf") {
            $message = "<div class='error'>Only PDF files are allowed.</div>";
        } elseif ($_FILES["pdf"]["error"] != 0) {
            $message = "<div class='error'>File upload failed.</div>";
        } else {

            $destination = $uploadDir . $enrollment . ".pdf";

            if (move_uploaded_file($_FILES["pdf"]["tmp_name"], $destination)) {
                $message = "<div class='success'>Practical Uploaded Successfully.</div>";
            } else {
                $message = "<div class='error'>Unable to upload file.</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Exam PDF Upload</title>

<style>

body{
    font-family:Arial,Helvetica,sans-serif;
    background:#f2f5f9;
}

.container{
    width:500px;
    margin:40px auto;
    background:#fff;
    padding:30px;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,.15);
}

h2{
    text-align:center;
    color:#333;
}

label{
    font-weight:bold;
}

input[type=text]{
    width:100%;
    padding:12px;
    margin-top:8px;
    margin-bottom:20px;
    border:1px solid #ccc;
    border-radius:5px;
    font-size:16px;
}

.drop-zone{
    border:3px dashed #3b82f6;
    border-radius:10px;
    padding:40px;
    text-align:center;
    cursor:pointer;
    color:#555;
    transition:.3s;
}

.drop-zone.dragover{
    background:#eaf3ff;
}

.drop-zone p{
    margin:0;
    font-size:18px;
}

input[type=file]{
    display:none;
}

button{
    width:100%;
    margin-top:20px;
    padding:14px;
    background:#2563eb;
    color:white;
    border:none;
    border-radius:5px;
    font-size:18px;
    cursor:pointer;
}

button:hover{
    background:#1d4ed8;
}

.success{
    background:#d4edda;
    color:#155724;
    padding:12px;
    border-radius:5px;
    margin-bottom:15px;
}

.error{
    background:#f8d7da;
    color:#721c24;
    padding:12px;
    border-radius:5px;
    margin-bottom:15px;
}

#fileName{
    margin-top:15px;
    font-weight:bold;
    color:#2563eb;
}

</style>

</head>
<body>

<div class="container">


<div style="text-align:center; margin-bottom:20px;">
    <a href="Practicals.pdf" target="_blank"
       style="display:inline-block;padding:10px 20px;background:#28a745;color:#fff;
              text-decoration:none;border-radius:5px;font-weight:bold;">
        📄 Download Practical List
    </a>
</div>


<h2>Exam PDF Upload</h2>

<?php echo $message; ?>

<form method="post" enctype="multipart/form-data">

<label>Enrollment Number</label>

<input type="text" name="enrollment" required>

<label>Upload PDF</label>

<div class="drop-zone" id="dropZone">
    <p>📄 Drag & Drop PDF Here</p>
    <p>or Click to Browse</p>

    <input type="file" id="pdf" name="pdf" accept=".pdf" required>
</div>

<div id="fileName"></div>

<button type="submit">Upload PDF</button>

</form>

</div>

<script>

const dropZone=document.getElementById("dropZone");
const fileInput=document.getElementById("pdf");
const fileName=document.getElementById("fileName");

dropZone.addEventListener("click",()=>{
    fileInput.click();
});

fileInput.addEventListener("change",()=>{
    if(fileInput.files.length>0){
        fileName.innerHTML=fileInput.files[0].name;
    }
});

dropZone.addEventListener("dragover",(e)=>{
    e.preventDefault();
    dropZone.classList.add("dragover");
});

dropZone.addEventListener("dragleave",()=>{
    dropZone.classList.remove("dragover");
});

dropZone.addEventListener("drop",(e)=>{
    e.preventDefault();
    dropZone.classList.remove("dragover");

    fileInput.files=e.dataTransfer.files;

    if(fileInput.files.length>0){
        fileName.innerHTML=fileInput.files[0].name;
    }
});
</script>
</body>
</html>