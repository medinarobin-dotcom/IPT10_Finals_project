<h1>Admin Dashboard</h1>
<p><a href="/admin/products">Manage products &rarr;</a></p>

<div class="stats">
    <div class="box"><span class="muted">Sales</span><strong><?= peso($sales) ?></strong></div>
    <div class="box"><span class="muted">Orders</span><strong><?= count($orders) ?></strong></div>
    <div class="box"><span class="muted">Products</span><strong><?= $products ?></strong></div>
    <div class="box"><span class="muted">Customers</span><strong><?= $users ?></strong></div>
</div>

<h2>Orders</h2>
<table class="table">
    <tr><th>#</th><th>Customer</th><th>Date</th><th>Total</th><th>Status</th></tr>
    <?php foreach ($orders as $o): ?>
        <tr>
            <td><a href="/order?id=<?= $o['id'] ?>"><?= $o['id'] ?></a></td>
            <td><?= e($o['customer']) ?></td>
            <td><?= e($o['created_at']) ?></td>
            <td><?= peso($o['total']) ?></td>
            <td>
                <form method="post" action="/admin/order-status" class="inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $o['id'] ?>">
                    <select name="status" onchange="this.form.submit()">
                        <?php foreach (Order::STATUSES as $s): ?>
                            <option <?= $s === $o['status'] ? 'selected' : '' ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>Exchange rates (Frankfurter API)</h2>
<table class="table small-table">
    <tr><th>Currency</th><th>1 PHP =</th></tr>
    <?php foreach ($rates as $code => $rate): ?>
        <tr><td><?= e($code) ?></td><td><?= e($rate) ?></td></tr>
    <?php endforeach; ?>
</table>
