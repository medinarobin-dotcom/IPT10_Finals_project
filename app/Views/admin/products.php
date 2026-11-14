<h1>Products</h1>
<p><a class="btn" href="/admin/product-form">+ Add product</a></p>

<table class="table">
    <tr><th></th><th>Name</th><th>Color</th><th>Price</th><th>Sizes</th><th>Stock</th><th></th></tr>
    <?php foreach ($products as $p): ?>
        <tr>
            <td><img class="thumb" src="/images/<?= e($p['image']) ?>" alt=""></td>
            <td><?= e($p['name']) ?></td>
            <td><?= e($p['color']) ?></td>
            <td><?= peso($p['price']) ?></td>
            <td><?= e($p['sizes']) ?></td>
            <td><?= $p['stock'] ?></td>
            <td class="actions">
                <a href="/admin/product-form?id=<?= $p['id'] ?>">Edit</a>
                <form method="post" action="/admin/product-delete" class="inline" onsubmit="return confirm('Delete this product?')">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                    <button class="link danger">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
