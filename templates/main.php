<?php
style('file_selector', 'style');
script('file_selector', 'script');
?>

<div id="app">
    <h1>Select a File from CONTENT</h1>
    <form action="<?php p(\OCP\Util::linkToRoute('file_selector.page.addFile')); ?>" method="post">
        <select name="file">
            <?php foreach ($_['contentDirs'] as $dir): ?>
                <optgroup label="<?php p($dir); ?>">
                    <?php $files = scandir('/path/to/nextcloud/data/your-username/files/' . $dir); ?>
                    <?php foreach ($files as $file): ?>
                        <?php if ($file !== '.' && $file !== '..'): ?>
                            <option value="<?php p($dir . '/' . $file); ?>"><?php p($file); ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </optgroup>
            <?php endforeach; ?>
        </select>
        <button type="submit">Add to JSON</button>
    </form>

    <h2>JSON List</h2>
    <?php foreach ($_['jsonData'] as $dir => $items): ?>
        <h3><?php p($dir); ?></h3>
        <ul>
            <?php foreach ($items as $item): ?>
                <li><?php p($item); ?> <form action="<?php p(\OCP\Util::linkToRoute('file_selector.page.deleteFile')); ?>" method="post" style="display:inline;">
                    <input type="hidden" name="file" value="<?php p($dir . '/' . $item); ?>">
                    <button type="submit">Delete</button>
                </form></li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>
</div>
