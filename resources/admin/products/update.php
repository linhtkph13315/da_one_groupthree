<?php layout('layouts.admin.master') ?>

<?php section('title', 'Update {{ $product["title"] }}') ?>

<?php section('content') ?>
    <p>Update {{ $product['title'] }}</p>
    <form action="" method="post" enctype="multipart/form-data">
        <p>
            <label for="image">Image</label>
            <input type="file" name="image" id="image" accept="image/*"/>
            <span class="errors">{{ $errors['image'][0] ?? '' }}</span>
        </p>
        <p>
            <label for="title">Title</label>
            <input type="text" name="title" id="title" value="{{ $product['title'] }}"/>
            <span class="errors">{{ $errors['title'][0] ?? '' }}</span>
        </p>
        <p>
            <button type="submit">Update</button>
        </p>
    </form>
<?php endsection() ?>