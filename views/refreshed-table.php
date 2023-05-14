<table>
    <thead>
        <tr>
        <th>ID</th><th>Title</th><th>Description</th><th>Color Code</th>
        </tr>
    </thead>
    
    <tbody>
        <?php foreach ($tasks as $task): ?>
        <tr>
            <td><?= $task->task ?></td>
            <td><?= $task->title ?></td>
            <td><?= $task->description ?></td>
            <td><?= $task->colorCode ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>