<?php $__env->startSection('content'); ?>
<h1>Success</h1>
<table class="table table-striped">
    <tbody>
        <tr>
            <td>Token TTL</td>
            <td><?php echo htmlspecialchars($session->client_session_token_ttl); ?></td>
        </tr>
        <tr>
            <td>Session ID</td>
            <td><?php echo htmlspecialchars($session->session_id); ?></td>
        </tr>
        <tr>
            <td>State</td>
            <td><?php echo htmlspecialchars($session->state); ?></td>
        </tr>
        <tr>
            <td>Client Session Token</td>
            <td><?php echo htmlspecialchars($session->client_session_token); ?></td>
        </tr>
        <tr>
            <td>Resources</td>
            <td>
            <?php if (!empty($session->resources->id_documents)) : ?>
                <?php foreach ($session->resources->id_documents as $id_document) : ?>
                    <h3>Tasks</h3>
                    <?php foreach ($id_document->tasks as $task) :?>
                        <hr />
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td>Type</td>
                                    <td><?php echo htmlspecialchars($task->type); ?></td>
                                </tr>
                                <tr>
                                    <td>ID</td>
                                    <td><?php echo htmlspecialchars($task->id); ?></td>
                                </tr>
                                <tr>
                                    <td>State</td>
                                    <td><?php echo htmlspecialchars($task->state); ?></td>
                                </tr>
                                <tr>
                                    <td>Created</td>
                                    <td><?php echo htmlspecialchars($task->created); ?></td>
                                </tr>
                                <tr>
                                    <td>Last Updated</td>
                                    <td><?php echo htmlspecialchars($task->last_updated); ?></td>
                                </tr>
                                <tr>
                                    <td>Generated Checks</td>
                                    <td>
                                        <?php foreach ($task->generated_checks as $check) :?>
                                        <h5><?php echo htmlspecialchars($check->type); ?></h5>
                                        <table class="table table-striped">
                                            <tbody>
                                                <tr>
                                                    <td>ID</td>
                                                    <td><?php echo htmlspecialchars($task->id); ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <?php endforeach; ?>
                                    </td>
                                </tr>
                            <tbody>
                        </table>
                    <?php endforeach; ?>
                    <h3>Pages</h3>
                    <?php foreach ($id_document->pages as $page) :?>
                        <hr />
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td>Capture Method</td>
                                    <td><?php echo htmlspecialchars($page->capture_method); ?></td>
                                </tr>
                                <tr>
                                    <td>Media</td>
                                    <td>
                                        <table class="table table-striped">
                                            <tbody>
                                                <tr>
                                                    <td>Preview</td>
                                                    <td><img width="100%" src="/media/<?php echo htmlspecialchars($page->media->id); ?>" /></td>
                                                </tr>
                                                <tr>
                                                    <td>ID</td>
                                                    <td><?php echo htmlspecialchars($page->media->id); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Type</td>
                                                    <td><?php echo htmlspecialchars($page->media->type); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Created</td>
                                                    <td><?php echo htmlspecialchars($page->media->created); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Last Updated</td>
                                                    <td><?php echo htmlspecialchars($page->media->last_updated); ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            <tbody>
                        </table>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            </td>
        </tr>
    <tbody>
</table>
<h2>Checks</h2>
<table class="table table-striped">
    <tbody>
        <tr>
            <th>ID</th>
            <th>Type</th>
            <th>State</th>
            <th>Report</th>
            <th>Created</th>
            <th>Last Updated</th>
        </tr>
        <?php if (!empty($session->checks)) : ?>
            <?php foreach ($session->checks as $check) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($check->id); ?></td>
                    <td><?php echo htmlspecialchars($check->type); ?></td>
                    <td><?php echo htmlspecialchars($check->state); ?></td>
                    <td><pre><?php echo htmlspecialchars(json_encode($check->report, JSON_PRETTY_PRINT)); ?></pre></td>
                    <td><?php echo htmlspecialchars($check->created); ?></td>
                    <td><?php echo htmlspecialchars($check->last_updated); ?></td>s
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
<h2>Response Data</h2>
<pre><?php echo htmlspecialchars(json_encode($session, JSON_PRETTY_PRINT)); ?></pre>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/share/nginx/html/resources/views/success.blade.php ENDPATH**/ ?>