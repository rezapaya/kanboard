<section id="main">
    <div class="page-header">
        <h2><?= t('Remove an automatic action') ?></h2>
    </div>

    <div class="confirm">
        <p class="alert alert-info">
            <?= t('Do you really want to remove this action: "%s"?', $action['event_name'].'/'.$action['action_name']) ?>
        </p>

        <div class="form-actions">
            <a href="?controller=action&amp;action=remove&amp;action_id=<?= $action['id'] ?>" class="btn btn-red"><?= t('Yes') ?></a>
            <?= t('or') ?> <a href="?controller=action&amp;action=index&amp;project_id=<?= $action['project_id'] ?>"><?= t('cancel') ?></a>
        </div>
    </div>
</section>