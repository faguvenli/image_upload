# Endigitals Co.
## Laravel PHP developer test

<p>Schools have a gallery of images, we want you to create the schools table and the polymorphic
images table. You are supposed to create an API app with images uploader endpoint that will
accept at most 5 images at a time, with only jpeg and png extensions, and the single image
acceptable size should not be more than 10 MB.</p>
<p>Every saved image should be added to the storage with a suitable file structure. Also, every
image should have 3 sizes (thumbs) as small, medium and large, alongside with the original
image, so as a total every image will have 4 versions.</p>

_<p>Endpoints can be found in the /postman_collection folder.</p>_

### DESCRIPTION
<p>Because the test description wants a separate endpoint for image uploads, images can be uploaded using the upload_image endpoint.</p>
<p>As a result of the upload process, the endpoint returns a list of uploaded
image urls and ids.</p>
<p>The ids returned from the response must be used in the school update and create requests in order 
to bind the relationship between schools and images.</p>

### Bonus

- ``php artisan sizes:add {size}`` command can be used to create new thumbnails from all the existing images with queue.
- this process needs a worker running at the background. (php artisan work).

