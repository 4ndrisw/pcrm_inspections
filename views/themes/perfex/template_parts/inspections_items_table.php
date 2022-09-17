<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<table class="table table-inspection-items" data-order-col="1" data-order-type="desc">
    <thead>
        <tr>
            <th><?php echo 'No'; ?> #</th>
            <th><?php echo 'Nama pesawat'; ?></th>
            <th><?php echo 'Tags'; ?></th>

        </tr>
    </thead>
    <tbody>
        <?php $i=1;?>
        <?php foreach($inspection_items as $item){ ?>
            <tr>
                <td ><?php echo $i; ?></td>
                <td><?php echo $item['name']; ?></td>
                <td><?php echo $item['tag_name']; ?></td>
            </tr>
            <?php $i++; ?>
        <?php } ?>
    </tbody>
</table>
