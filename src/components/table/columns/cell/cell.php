<!-- HTML Document -->
<div>
  <div class="bbn-w-100 bbn-nowrap">
    <a class="bbn-b bbn-m"
       :href="link"
       bbn-text="source.name"/>
    &nbsp;|&nbsp;
    <span bbn-if="source.is_real"
          class="bbn-success-text">
      <?= _("Table") ?>
    </span>
    <span bbn-else
          class="bbn-error-text">
      <?= _("Doesn't exist") ?>
    </span>
    &nbsp;|&nbsp;
    <span bbn-if="source.is_virtual"
          class="bbn-success-text">
      <?= _("Option ok") ?>
    </span>
    <span bbn-else
          class="bbn-error-text">
      <?= _("No option") ?>
    </span>
  </div>
</div>