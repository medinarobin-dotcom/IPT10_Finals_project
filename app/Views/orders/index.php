<h1>My Orders</h1>

<?php if (!$orders): ?>
    <p class="box">You have no orders yet.</p>
<?php else: ?>
    <table class="table">
        <tr><th>Order #</th><th>Date</th><th>Total</th><th>Status</th><th></th></tr>
        <?php foreach ($orders as $o): ?>
            <tr>
                <td><?= $o['id'] ?></td>
                <td><?= e($o['created_at']) ?></td>
                <td><?= peso($o['total']) ?></td>
                <td><span class="status <?= e($o['status']) ?>"><?= e($o['status']) ?></span></td>
                <td><a href="/order?id=<?= $o['id'] ?>">View</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
