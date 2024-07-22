// Get the input element
const imageUpload = document.getElementById('file-input');
const closeIcon = document.getElementById('close-icon');
const sendComment = document.getElementById('send-comment');

// Add an event listener to detect file upload
imageUpload.addEventListener('change', function() {
    // Get the selected file
    const file = imageUpload.files[0];
    // Create a FileReader object
    const reader = new FileReader();
    // Set up the reader's onload event handler
    reader.onload = function(e) {
        // Get the image data URL
        const ImageDataUrl = e.target.result;
        // Display the uploaded image
        const imagePreview = document.getElementById('imagePreview');
        imagePreview.src = ImageDataUrl;
        imageUpload.style.display = "none";
        closeIcon.style.display = "flex";
    };
    // Read the selected file as Data URL
    reader.readAsDataURL(file);
});

closeIcon.addEventListener('click', function () {
    imagePreview.src = "";
    this.style.display = "none";
    imageUpload.value = "";
});

sendComment.addEventListener('click', function () {
    setTimeout(function () {
        imagePreview.src = "";
        imageUpload.value = "";
        closeIcon.style.display = "none";
    }, 100);
});